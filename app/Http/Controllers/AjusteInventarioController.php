<?php

namespace App\Http\Controllers;

use App\Enums\TipoTransaccionEnum;
use App\Http\Requests\StoreAjusteInventarioRequest;
use App\Models\AjusteInventario;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AjusteInventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-ajuste-inventario|crear-ajuste-inventario', ['only' => ['index']]);
        $this->middleware('permission:crear-ajuste-inventario', ['only' => ['create', 'store']]);
    }

    public function index(Request $request): View|StreamedResponse
    {
        $query = AjusteInventario::with(['producto', 'user'])->latest();

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->string('fecha_inicio'));
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->string('fecha_fin'));
        }

        if ($request->filled('producto_id')) {
            $query->where('producto_id', $request->integer('producto_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->boolean('exportar')) {
            return $this->exportarCsv(clone $query);
        }

        $ajustes = $query->get();
        $productos = Producto::orderBy('nombre')->get();
        $usuarios = DB::table('users')->select('id', 'name')->orderBy('name')->get();

        return view('ajusteInventario.index', compact('ajustes', 'productos', 'usuarios'));
    }

    public function create(): View
    {
        $productos = Producto::with('inventario')->orderBy('nombre')->get();

        return view('ajusteInventario.create', compact('productos'));
    }

    public function store(StoreAjusteInventarioRequest $request, Kardex $kardex): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $inventario = Inventario::where('producto_id', $data['producto_id'])->lockForUpdate()->firstOrFail();

            $stockActual = (int) $inventario->cantidad;
            $cantidadFisica = (int) $data['cantidad_fisica'];
            $diferencia = $cantidadFisica - $stockActual;

            if ($diferencia === 0) {
                return redirect()->back()->with('error', 'No hay diferencia entre stock del sistema y conteo físico.');
            }

            $tipoMovimiento = $diferencia > 0 ? 'ENTRADA' : 'SALIDA';
            $cantidadMovimiento = abs($diferencia);

            if ($tipoMovimiento === 'SALIDA' && $cantidadMovimiento > $stockActual) {
                return redirect()->back()->with('error', 'No se puede dejar el inventario en negativo.');
            }

            $inventario->update(['cantidad' => $cantidadFisica]);

            $producto = Producto::findOrFail($data['producto_id']);

            $kardex->crearRegistro([
                'producto_id' => $data['producto_id'],
                'cantidad' => $cantidadMovimiento,
                'tipo_movimiento' => $tipoMovimiento,
                'descripcion_ajuste' => $data['motivo'] ?? null,
                'costo_unitario' => $producto->costo,
            ], TipoTransaccionEnum::Ajuste);

            $ajuste = AjusteInventario::create([
                'producto_id' => $data['producto_id'],
                'user_id' => auth()->id(),
                'stock_actual' => $stockActual,
                'cantidad_fisica' => $cantidadFisica,
                'diferencia' => $diferencia,
                'tipo_movimiento' => $tipoMovimiento,
                'motivo' => $data['motivo'] ?? null,
            ]);

            DB::commit();

            ActivityLogService::log('Ajuste de inventario', 'Ajustes inventario', $ajuste->toArray());

            return redirect()->route('ajustes-inventario.index')->with('success', 'Ajuste aplicado correctamente.');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear ajuste de inventario', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Ups, algo falló al aplicar el ajuste.');
        }
    }

    private function exportarCsv($query): StreamedResponse
    {
        $nombreArchivo = 'historial-ajustes-inventario-' . now()->format('YmdHis') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Fecha', 'Producto', 'Usuario', 'Stock sistema', 'Cantidad física', 'Diferencia', 'Movimiento', 'Motivo']);

            $query->get()->each(function ($item) use ($file) {
                fputcsv($file, [
                    $item->created_at?->format('d/m/Y H:i'),
                    $item->producto?->nombre_completo,
                    $item->user?->name,
                    $item->stock_actual,
                    $item->cantidad_fisica,
                    $item->diferencia,
                    $item->tipo_movimiento,
                    $item->motivo,
                ]);
            });

            fclose($file);
        }, $nombreArchivo, ['Content-Type' => 'text/csv']);
    }
}
