<?php

namespace App\Http\Controllers;

use App\Enums\EstadoPedidoEnum;
use App\Exports\ProductoControlExport;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\Presentacione;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Services\ActivityLogService;
use App\Services\ProductoService;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProductoController extends Controller
{
    protected $productoService;

    function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
        $this->middleware('permission:ver-producto|crear-producto|editar-producto|eliminar-producto', ['only' => ['index']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $productos = Producto::with([
            'categoria.caracteristica',
            'proveedore.persona',
            'presentacione.caracteristica',
            'inventario'
        ])
            ->latest()
            ->get();

        return view('producto.index', compact('productos'));
    }

    public function exportControlExcel(): BinaryFileResponse
    {
        $productos = Producto::with([
            'categoria.caracteristica',
            'proveedore.persona',
            'inventario',
        ])->orderBy('nombre')->get();

        $pedidosNoCancelados = Pedido::with(['proveedore.persona', 'productos'])
            ->where('estado', '!=', EstadoPedidoEnum::Cancelado)
            ->latest()
            ->get();

        $filename = 'control-inventario-maleri-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ProductoControlExport($productos, $pedidosNoCancelados), $filename);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $proveedores = Proveedore::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();


        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('producto.create', compact('proveedores', 'presentaciones', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request): RedirectResponse
    {
        try {

            // AQUÍ MISMO (antes del service)
            $data = $request->validated();

            if ($request->hasFile('img_path')) {
                $data['img_path'] = $request->file('img_path');
            }

            // Luego llamas al service
            $producto = $this->productoService->crearProducto($data);

            ActivityLogService::log(
                'Creación de producto',
                'Productos',
                [
                    ...$data,
                    'producto_id' => $producto->id,
                ]
            );

            return redirect()
                ->route('inventario.create', ['producto_id' => $producto->id])
                ->with('success', 'Producto registrado. Ahora registra la primera existencia.');
        } catch (Throwable $e) {
            Log::error('Error al crear el producto', [
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('productos.index')
                ->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto): View
    {
        $proveedores = Proveedore::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('producto.edit', compact('producto', 'proveedores', 'presentaciones', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        try {
            $this->productoService->editarProducto($request->validated(), $producto);
            ActivityLogService::log('Edición de producto', 'Productos', $request->validated());
            return redirect()->route('productos.index')->with('success', 'Producto editado');
        } catch (Throwable $e) {
            Log::error('Error al editar el producto', ['error' => $e->getMessage()]);
            return redirect()->route('productos.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /*
        $message = '';
        $producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Producto eliminado';
        } else {
            Producto::where('id', $producto->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Producto restaurado';
        }

        return redirect()->route('productos.index')->with('success', $message);*/
    }
}
