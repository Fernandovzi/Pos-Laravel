<?php

namespace App\Http\Controllers;

use App\Enums\MetodoPagoEnum;
use App\Enums\TipoMovimientoEnum;
use App\Enums\TipoTransaccionEnum;
use App\Events\CreateVentaDetalleEvent;
use App\Events\CreateVentaEvent;
use App\Http\Requests\StoreVentaRequest;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\ActivityLogService;
use App\Services\ComprobanteService;
use App\Services\EmpresaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ventaController extends Controller
{
    protected EmpresaService $empresaService;

    public function __construct(EmpresaService $empresaService)
    {
        $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta', ['only' => ['index']]);
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-venta', ['only' => ['show']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
        $this->middleware('check-caja-aperturada-user', ['only' => ['create', 'store']]);
        $this->middleware('check-show-venta-user', ['only' => ['show']]);
        $this->empresaService = $empresaService;
    }

    public function index(): View
    {
        $ventas = Venta::with(['comprobante', 'cliente.persona', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('venta.index', compact('ventas'));
    }

    public function create(ComprobanteService $comprobanteService): View
    {
        $productos = $this->obtenerProductosDisponibles();
        $clientes = $this->obtenerClientesActivos();
        $comprobantes = $comprobanteService->obtenerComprobantes();
        $optionsMetodoPago = MetodoPagoEnum::salesMethods();
        $empresa = $this->empresaService->obtenerEmpresa();

        return view('venta.create', compact(
            'productos',
            'clientes',
            'comprobantes',
            'optionsMetodoPago',
            'empresa'
        ));
    }

    public function store(StoreVentaRequest $request): RedirectResponse
    {
        try {
            $venta = DB::transaction(function () use ($request) {
                $venta = Venta::create($request->safe()->except(
                    'pagos',
                    'arrayidproducto',
                    'arraycantidad',
                    'arrayprecioventa',
                    'arraydescuentoproducto'
                ));

                $detalleVenta = $this->obtenerDetalleVenta($request);

                $subtotalBruto = $detalleVenta->sum(fn(array $detalle): float => (float) $detalle['cantidad'] * (float) $detalle['precio_original']);
                $subtotalNeto = (float) $request->validated('subtotal');
                $descuentoTotalMonto = round(max($subtotalBruto - $subtotalNeto, 0), 2);
                $descuentoTotalPorcentaje = $subtotalBruto > 0
                    ? round(($descuentoTotalMonto / $subtotalBruto) * 100, 2)
                    : 0;

                $venta->update([
                    'descuento_total_porcentaje' => $descuentoTotalPorcentaje,
                    'descuento_total_monto' => $descuentoTotalMonto,
                ]);

                $pivotData = $detalleVenta
                    ->mapWithKeys(function (array $detalle): array {
                        return [
                            $detalle['producto_id'] => [
                                'cantidad' => $detalle['cantidad'],
                                'precio_original' => $detalle['precio_original'],
                                'precio_venta' => $detalle['precio_venta'],
                                'descuento_porcentaje' => $detalle['descuento_porcentaje'],
                            ],
                        ];
                    })
                    ->all();

                $venta->productos()->syncWithoutDetaching($pivotData);

                $detalleVenta->each(function (array $detalle) use ($venta): void {
                    CreateVentaDetalleEvent::dispatch(
                        $venta,
                        $detalle['producto_id'],
                        $detalle['cantidad'],
                        $detalle['precio_venta']
                    );
                });

                $venta->pagos()->createMany($request->validated('pagos'));

                CreateVentaEvent::dispatch($venta);

                return $venta;
            });

            ActivityLogService::log('Creación de una venta', 'Ventas', $request->validated());

            return redirect()->route('ventas.show', $venta)->with('success', 'Venta cobrada correctamente.');
        } catch (Throwable $e) {
            Log::error('Error al crear la venta', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('ventas.index')->with('error', 'Ups, algo falló');
        }
    }

    public function show(Venta $venta): View
    {
        $empresa = $this->empresaService->obtenerEmpresa();
        $venta->load(['pagos', 'productos.presentacione', 'cliente.persona', 'user', 'comprobante']);
        $tieneCajaAbierta = Caja::where('user_id', Auth::id())
            ->where('estado', 1)
            ->exists();


        return view('venta.show', compact('venta', 'empresa', 'tieneCajaAbierta'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Venta $venta): RedirectResponse
    {
        if ($venta->estado === 'CANCELADA') {
            return redirect()->route('ventas.show', $venta)->with('error', 'La venta ya fue cancelada.');
        }

        $cajaAbierta = Caja::where('user_id', Auth::id())
            ->where('estado', 1)
            ->latest('id')
            ->first();

        if (!$cajaAbierta) {
            return redirect()->route('ventas.show', $venta)->with('error', 'Debe tener una caja abierta para cancelar la venta.');
        }

        try {
            DB::transaction(function () use ($venta, $cajaAbierta): void {
                $venta->load(['productos', 'pagos']);

                foreach ($venta->productos as $producto) {
                    $cantidad = (int) $producto->pivot->cantidad;

                    $inventario = Inventario::where('producto_id', $producto->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $inventario->increment('cantidad', $cantidad);

                    $ultimoKardex = Kardex::where('producto_id', $producto->id)->latest('id')->first();

                    (new Kardex())->crearRegistro([
                        'venta_id' => $venta->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'tipo_movimiento' => 'ENTRADA',
                        'costo_unitario' => $ultimoKardex?->costo_unitario ?? $producto->costo,
                    ], TipoTransaccionEnum::CancelacionVenta);
                }

                foreach ($venta->pagos as $pago) {
                    Movimiento::create([
                        'tipo' => TipoMovimientoEnum::Retiro,
                        'descripcion' => 'Cancelación de venta n° ' . $venta->numero_comprobante,
                        'monto' => $pago->monto,
                        'metodo_pago' => $pago->metodo_pago,
                        'caja_id' => $cajaAbierta->id,
                    ]);
                }

                $venta->update(['estado' => 'CANCELADA']);
            });

            ActivityLogService::log('Cancelación de venta', 'Ventas', ['venta_id' => $venta->id, 'numero_comprobante' => $venta->numero_comprobante]);

            return redirect()->route('ventas.show', $venta)->with('success', 'Venta cancelada, caja e inventario actualizados.');
        } catch (Throwable $e) {
            Log::error('Error al cancelar la venta', [
                'venta_id' => $venta->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('ventas.show', $venta)->with('error', 'No fue posible cancelar la venta.');
        }
    }

    private function obtenerProductosDisponibles(): Collection
    {
        return Producto::join('inventario as i', function ($join) {
            $join->on('i.producto_id', '=', 'productos.id');
        })
            ->join('presentaciones as p', function ($join) {
                $join->on('p.id', '=', 'productos.presentacione_id');
            })
            ->select(
                'p.sigla',
                'productos.nombre',
                'productos.codigo',
                'productos.id',
                'i.cantidad',
                'productos.precio'
            )
            ->where('productos.estado', 1)
            ->where('i.cantidad', '>', 0)
            ->get();
    }

    private function obtenerClientesActivos(): Collection
    {
        return Cliente::with('persona')
            ->whereHas('persona', function ($query) {
                $query->where('estado', 1);
            })
            ->get();
    }

    private function obtenerDetalleVenta(StoreVentaRequest $request): Collection
    {
        $idsProductos = $request->validated('arrayidproducto');
        $cantidades = $request->validated('arraycantidad');
        $preciosVenta = $request->validated('arrayprecioventa');
        $descuentosProducto = $request->validated('arraydescuentoproducto');
        $preciosBasePorProducto = Producto::query()
            ->whereIn('id', $idsProductos)
            ->pluck('precio', 'id');

        return collect($idsProductos)
            ->values()
            ->map(function ($productoId, int $index) use ($cantidades, $preciosVenta, $descuentosProducto, $preciosBasePorProducto): array {
                $precioOriginal = (float) ($preciosBasePorProducto[(int) $productoId] ?? $preciosVenta[$index]);

                return [
                    'producto_id' => (int) $productoId,
                    'cantidad' => $cantidades[$index],
                    'precio_original' => $precioOriginal,
                    'precio_venta' => $preciosVenta[$index],
                    'descuento_porcentaje' => $descuentosProducto[$index] ?? 0,
                ];
            });
    }
}
