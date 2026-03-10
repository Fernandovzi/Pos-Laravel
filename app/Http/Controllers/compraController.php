<?php

namespace App\Http\Controllers;

use App\Events\CreateCompraDetalleEvent;
use App\Http\Requests\StoreCompraRequest;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\EmpresaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class compraController extends Controller
{
    protected EmpresaService $empresaService;

    function __construct(EmpresaService $empresaService)
    {
        $this->middleware('permission:ver-compra|crear-compra|mostrar-compra|eliminar-compra', ['only' => ['index']]);
        $this->middleware('permission:crear-compra', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-compra', ['only' => ['show']]);
        $this->empresaService = $empresaService;
    }

    public function index(Request $request): View
    {
        $productoId = $request->integer('producto_id');
        $usuarioId = $request->integer('user_id');

        $compras = Compra::with('productos.presentacione', 'user')
            ->when($request->filled('fecha_inicio'), fn($q) => $q->whereDate('fecha_hora', '>=', $request->string('fecha_inicio')))
            ->when($request->filled('fecha_fin'), fn($q) => $q->whereDate('fecha_hora', '<=', $request->string('fecha_fin')))
            ->when($productoId, fn($q) => $q->whereHas('productos', fn($subQ) => $subQ->where('productos.id', $productoId)))
            ->when($usuarioId, fn($q) => $q->where('user_id', $usuarioId))
            ->latest()
            ->get();

        $productos = Producto::where('estado', 1)->orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('compra.index', compact('compras', 'productos', 'usuarios'));
    }

    public function create(): View
    {
        $productos = Producto::where('estado', 1)->get();
        $empresa = $this->empresaService->obtenerEmpresa();

        return view('compra.create', compact('productos', 'empresa'));
    }

    public function store(StoreCompraRequest $request): RedirectResponse
    {
        try {
            $compra = DB::transaction(function () use ($request): Compra {
                $detalleCompra = $this->buildDetalleCompra($request);
                $subtotal = $detalleCompra->sum(fn (array $detalle): float => $detalle['cantidad'] * $detalle['precio_compra']);

                $compra = Compra::create([
                    'user_id' => Auth::id(),
                    'fecha_hora' => $request->validated('fecha_hora'),
                    'subtotal' => $subtotal,
                    'impuesto' => 0,
                    'total' => $subtotal,
                ]);

                // Un único sync evita múltiples roundtrips al motor SQL.
                $compra->productos()->syncWithoutDetaching(
                    $detalleCompra
                        ->mapWithKeys(fn (array $detalle): array => [
                            $detalle['producto_id'] => [
                                'cantidad' => $detalle['cantidad'],
                                'precio_compra' => $detalle['precio_compra'],
                                'fecha_vencimiento' => $detalle['fecha_vencimiento'],
                            ],
                        ])
                        ->all()
                );

                $detalleCompra->each(function (array $detalle) use ($compra): void {
                    CreateCompraDetalleEvent::dispatch(
                        $compra,
                        $detalle['producto_id'],
                        $detalle['cantidad'],
                        $detalle['precio_compra'],
                        $detalle['fecha_vencimiento']
                    );
                });

                return $compra;
            });

            ActivityLogService::log('Registro de entrada por producción interna', 'Producción Interna', $request->validated());

            return redirect()->route('compras.index')->with('success', 'Entrada por producción interna registrada.');
        } catch (Throwable $e) {
            Log::error('Error al registrar producción interna', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->with('error', 'No fue posible registrar la entrada. Verifica los datos e inténtalo nuevamente.');
        }
    }

    public function show(Compra $compra): View
    {
        $empresa = $this->empresaService->obtenerEmpresa();
        return view('compra.show', compact('compra', 'empresa'));
    }

    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    /**
     * Construye el detalle de compra con precios resueltos desde catálogo.
     */
    private function buildDetalleCompra(StoreCompraRequest $request): Collection
    {
        $productoIds = $request->validated('arrayidproducto');
        $cantidades = $request->validated('arraycantidad');
        $fechasVencimiento = $request->validated('arrayfechavencimiento', []);

        $productos = Producto::query()
            ->whereIn('id', $productoIds)
            ->get(['id', 'costo', 'precio'])
            ->keyBy('id');

        return collect($productoIds)
            ->values()
            ->map(function ($productoId, int $index) use ($productos, $cantidades, $fechasVencimiento): array {
                $producto = $productos->get((int) $productoId);
                $precioCompra = (float) ($producto?->costo ?? $producto?->precio ?? 0);

                if ($precioCompra <= 0) {
                    throw new \DomainException('El producto seleccionado no tiene costo/precio configurado.');
                }

                return [
                    'producto_id' => (int) $productoId,
                    'cantidad' => (int) $cantidades[$index],
                    'precio_compra' => $precioCompra,
                    'fecha_vencimiento' => $fechasVencimiento[$index] ?? null,
                ];
            });
    }
}
