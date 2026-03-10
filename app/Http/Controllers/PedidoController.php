<?php

namespace App\Http\Controllers;

use App\Enums\EstadoPedidoEnum;
use App\Enums\TipoTransaccionEnum;
use App\Http\Requests\StorePedidoRequest;
use App\Models\Empresa;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Services\ActivityLogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Lista de pedidos apartados y su estado.
     */
    public function index(): View
    {
        $pedidos = Pedido::with(['proveedore.persona', 'cliente.persona', 'user'])->latest()->get();

        return view('pedido.index', compact('pedidos'));
    }

    /**
     * Formulario para apartar productos.
     */
    public function create(): View
    {
        $productos = Producto::query()
            ->join('inventario as i', 'i.producto_id', '=', 'productos.id')
            ->join('presentaciones as p', 'p.id', '=', 'productos.presentacione_id')
            ->select('productos.id', 'productos.codigo', 'productos.nombre', 'productos.precio', 'i.cantidad', 'p.sigla')
            ->where('productos.estado', 1)
            ->where('i.cantidad', '>', 0)
            ->orderBy('productos.nombre')
            ->get();

        $proveedores = Proveedore::whereHas('persona', fn ($query) => $query->where('estado', 1))->get();
        $empresa = Empresa::first();

        return view('pedido.create', compact('productos', 'proveedores', 'empresa'));
    }

    /**
     * Crea el pedido, aparta stock y registra salida temporal en kardex.
     */
    public function store(StorePedidoRequest $request): RedirectResponse
    {
        $pedido = null;

        DB::transaction(function () use ($request, &$pedido) {
            $pedido = Pedido::create($request->validated());

            $productoIds = $request->arrayidproducto;
            $cantidades = $request->arraycantidad;
            $precios = $request->arrayprecio;

            foreach ($productoIds as $index => $productoId) {
                $cantidadSolicitada = (int) $cantidades[$index];
                $precio = (float) $precios[$index];

                $inventario = Inventario::where('producto_id', $productoId)->lockForUpdate()->firstOrFail();
                abort_if($inventario->cantidad < $cantidadSolicitada, 422, 'Stock insuficiente para completar el pedido.');

                $pedido->productos()->attach($productoId, [
                    'cantidad' => $cantidadSolicitada,
                    'precio' => $precio,
                ]);

                $inventario->decrement('cantidad', $cantidadSolicitada);

                $ultimoKardex = Kardex::where('producto_id', $productoId)->latest('id')->firstOrFail();
                (new Kardex())->crearRegistro([
                    'folio' => $pedido->folio,
                    'producto_id' => $productoId,
                    'cantidad' => $cantidadSolicitada,
                    'costo_unitario' => $ultimoKardex->costo_unitario,
                ], TipoTransaccionEnum::Pedido);
            }

            ActivityLogService::log('Creación de pedido', 'Pedidos', ['pedido_id' => $pedido->id, 'folio' => $pedido->folio]);
        });

        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido registrado y stock apartado.');
    }

    /**
     * Muestra el detalle del pedido.
     */
    public function show(Pedido $pedido): View
    {
        $pedido->load(['proveedore.persona', 'cliente.persona', 'productos', 'user']);
        $empresa = Empresa::first();

        return view('pedido.show', compact('pedido', 'empresa'));
    }

    /**
     * Cancela un pedido para devolver stock al inventario.
     */
    public function destroy(Pedido $pedido): RedirectResponse
    {
        if ($pedido->estado !== EstadoPedidoEnum::Apartado) {
            return back()->with('error', 'Solo se pueden cancelar pedidos apartados.');
        }

        DB::transaction(function () use ($pedido) {
            $pedido->load('productos');

            foreach ($pedido->productos as $producto) {
                $cantidad = (int) $producto->pivot->cantidad;

                $inventario = Inventario::where('producto_id', $producto->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $inventario->increment('cantidad', $cantidad);

                $ultimoKardex = Kardex::where('producto_id', $producto->id)->latest('id')->first();
                (new Kardex())->crearRegistro([
                    'folio' => $pedido->folio,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'tipo_movimiento' => 'ENTRADA',
                    'costo_unitario' => $ultimoKardex?->costo_unitario ?? $producto->costo,
                ], TipoTransaccionEnum::CancelacionPedido);
            }

            $pedido->update(['estado' => EstadoPedidoEnum::Cancelado]);

            ActivityLogService::log('Cancelación de pedido', 'Pedidos', ['pedido_id' => $pedido->id, 'folio' => $pedido->folio]);
        });

        return redirect()->route('pedidos.index')->with('success', 'Pedido cancelado y stock liberado.');
    }

    /**
     * Genera el PDF del pedido.
     */
    public function exportPdf(Pedido $pedido): Response
    {
        $pedido->load(['proveedore.persona', 'cliente.persona', 'productos', 'user']);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('pdf.pedido', compact('pedido', 'empresa'));

        return $pdf->stream('pedido-' . $pedido->folio . '.pdf');
    }
}
