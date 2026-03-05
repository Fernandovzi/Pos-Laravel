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
        DB::beginTransaction();
        try {
            $arrayProductoId = $request->array('arrayidproducto');
            $arrayCantidad = $request->array('arraycantidad');
            $arrayPrecio = $request->array('arraypreciocompra');
            $arrayFechaVencimiento = $request->array('arrayfechavencimiento');

            $subtotal = 0;
            foreach ($arrayCantidad as $index => $cantidad) {
                $subtotal += ((int) $cantidad) * ((float) $arrayPrecio[$index]);
            }

            $compra = Compra::create([
                'user_id' => Auth::id(),
                'fecha_hora' => $request->string('fecha_hora'),
                'subtotal' => $subtotal,
                'impuesto' => 0,
                'total' => $subtotal,
            ]);

            foreach ($arrayProductoId as $index => $productoId) {
                $compra->productos()->syncWithoutDetaching([
                    $productoId => [
                        'cantidad' => $arrayCantidad[$index],
                        'precio_compra' => $arrayPrecio[$index],
                        'fecha_vencimiento' => $arrayFechaVencimiento[$index] ?? null,
                    ],
                ]);

                CreateCompraDetalleEvent::dispatch(
                    $compra,
                    $productoId,
                    $arrayCantidad[$index],
                    $arrayPrecio[$index],
                    $arrayFechaVencimiento[$index] ?? null
                );
            }

            DB::commit();
            ActivityLogService::log('Registro de entrada por producción interna', 'Producción Interna', $request->all());

            return redirect()->route('compras.index')->with('success', 'Entrada por producción interna registrada.');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al registrar producción interna', ['error' => $e->getMessage()]);
            return redirect()->route('compras.index')->with('error', 'Ups, algo falló');
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
}
