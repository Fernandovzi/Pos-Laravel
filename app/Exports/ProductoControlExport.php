<?php

namespace App\Exports;

use App\Exports\Sheets\ProductosControlSheet;
use App\Exports\Sheets\ResumenInventarioSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductoControlExport implements WithMultipleSheets
{
    public function __construct(
        protected Collection $productos,
        protected Collection $pedidosNoCancelados,
    ) {
    }

    public function sheets(): array
    {
        $inventarioConExistencia = $this->productos
            ->filter(fn ($producto) => (int) ($producto->inventario?->cantidad ?? 0) > 0)
            ->values();

        return [
            new ProductosControlSheet(
                title: 'Productos',
                encabezado: 'Catálogo general de productos',
                logoPath: public_path('img/maleri.png'),
                headings: ['Código', 'Producto', 'Categoría', 'Proveedor', 'Costo', 'Precio', 'Estado', 'Existencia'],
                rows: $this->productos->map(fn ($producto) => [
                    $producto->codigo,
                    $producto->nombre,
                    $producto->categoria?->caracteristica?->nombre ?? 'Sin categoría',
                    $producto->proveedore?->persona?->razon_social ?? 'Sin proveedor',
                    (float) $producto->costo,
                    (float) $producto->precio,
                    $producto->estado ? 'Activo' : 'Inactivo',
                    (int) ($producto->inventario?->cantidad ?? 0),
                ])->toArray(),
            ),
            new ProductosControlSheet(
                title: 'Inventario',
                encabezado: 'Productos con existencia disponible',
                logoPath: public_path('img/maleri.png'),
                headings: ['Código', 'Producto', 'Categoría', 'Proveedor', 'Existencia', 'Costo', 'Precio'],
                rows: $inventarioConExistencia->map(fn ($producto) => [
                    $producto->codigo,
                    $producto->nombre,
                    $producto->categoria?->caracteristica?->nombre ?? 'Sin categoría',
                    $producto->proveedore?->persona?->razon_social ?? 'Sin proveedor',
                    (int) ($producto->inventario?->cantidad ?? 0),
                    (float) $producto->costo,
                    (float) $producto->precio,
                ])->toArray(),
            ),
            new ProductosControlSheet(
                title: 'Pedidos a proveedor',
                encabezado: 'Pedidos vigentes (no cancelados)',
                logoPath: public_path('img/maleri.png'),
                headings: ['Folio', 'Fecha', 'Proveedor', 'Producto', 'Cantidad', 'Precio', 'Subtotal', 'Estatus'],
                rows: $this->pedidosNoCancelados->flatMap(function ($pedido) {
                    return $pedido->productos->map(fn ($producto) => [
                        $pedido->folio,
                        optional($pedido->fecha_apartado)->format('d/m/Y H:i'),
                        $pedido->proveedore?->persona?->razon_social ?? 'Sin proveedor',
                        $producto->nombre,
                        (int) ($producto->pivot->cantidad ?? 0),
                        (float) ($producto->pivot->precio ?? 0),
                        (float) (($producto->pivot->cantidad ?? 0) * ($producto->pivot->precio ?? 0)),
                        $pedido->estado->value,
                    ]);
                })->values()->toArray(),
                dataValidationColumn: 'H',
                dataValidationOptions: ['APARTADO', 'ENTREGADO', 'CANCELADO'],
            ),
            new ResumenInventarioSheet(
                productos: $this->productos,
                pedidosNoCancelados: $this->pedidosNoCancelados,
                logoPath: public_path('img/maleri.png'),
            ),
        ];
    }
}
