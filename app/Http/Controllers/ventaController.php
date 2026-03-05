<?php

namespace App\Http\Controllers;

use App\Enums\MetodoPagoEnum;
use App\Events\CreateVentaDetalleEvent;
use App\Events\CreateVentaEvent;
use App\Http\Requests\StoreVentaRequest;
use App\Models\Cliente;
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

    /**
     * Registra la venta, su detalle y los pagos múltiples asociados.
     */
    public function store(StoreVentaRequest $request): RedirectResponse
    {
        try {
            $venta = DB::transaction(function () use ($request) {
                $venta = Venta::create($request->safe()->except('pagos', 'arrayidproducto', 'arraycantidad', 'arrayprecioventa'));
                $detalleVenta = $this->obtenerDetalleVenta($request);

                $pivotData = $detalleVenta
                    ->mapWithKeys(function (array $detalle): array {
                        return [
                            $detalle['producto_id'] => [
                                'cantidad' => $detalle['cantidad'],
                                'precio_venta' => $detalle['precio_venta'],
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

                // Guardar el desglose de pagos para soportar pago mixto y referencias por método.
                $venta->pagos()->createMany($request->validated('pagos'));

                CreateVentaEvent::dispatch($venta);

                return $venta;
            });

            ActivityLogService::log('Creación de una venta', 'Ventas', $request->validated());

            return redirect()->route('movimientos.index', ['caja_id' => $venta->caja_id])
                ->with('success', 'Venta registrada');
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
        $venta->load('pagos');

        return view('venta.show', compact('venta', 'empresa'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
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

        return collect($idsProductos)
            ->values()
            ->map(function ($productoId, int $index) use ($cantidades, $preciosVenta): array {
                return [
                    'producto_id' => (int) $productoId,
                    'cantidad' => $cantidades[$index],
                    'precio_venta' => $preciosVenta[$index],
                ];
            });
    }
}
