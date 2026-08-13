<?php

namespace Tests\Unit;

use App\Enums\EstadoPedidoEnum;
use App\Exports\ProductoControlExport;
use App\Exports\Sheets\AnalisisPedidosSheet;
use App\Exports\Sheets\ResumenInventarioSheet;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Proveedore;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ProductoControlExportTest extends TestCase
{
    public function test_includes_product_codes_in_supplier_orders_and_inventory_summary(): void
    {
        $producto = $this->producto(10, 'P-010', 'Producto diez', 8);
        $pedido = $this->pedido('Vendedor Norte', [$this->linea($producto, 3)]);
        $sheets = (new ProductoControlExport(collect([$producto]), collect([$pedido])))->sheets();

        $pedidoRows = $sheets[2]->array();
        $this->assertSame('Código', $pedidoRows[0][3]);
        $this->assertSame('P-010', $pedidoRows[1][3]);

        $resumenRows = $sheets[3]->array();
        $this->assertSame(['Código', 'Producto', 'Existencia actual', 'Cantidad en pedidos pendientes'], $resumenRows[0]);
        $this->assertSame('P-010', $resumenRows[1][0]);
        $this->assertCount(4, $resumenRows[1]);
    }

    public function test_analysis_groups_quantities_by_seller_and_shows_remaining_inventory_in_maleri(): void
    {
        $pedidoProducto = $this->producto(10, 'P-010', 'Producto pedido', 12);
        $productoMaleri = $this->producto(20, 'P-020', 'Producto sin pedido', 9);
        $pedidos = collect([
            $this->pedido('Vendedor Sur', [$this->linea($pedidoProducto, 2)]),
            $this->pedido('Vendedor Norte', [$this->linea($pedidoProducto, 3)]),
            $this->pedido('Vendedor Sur', [$this->linea($pedidoProducto, 4)]),
        ]);

        $rows = (new AnalisisPedidosSheet(
            collect([$pedidoProducto, $productoMaleri]),
            $pedidos,
            '/logo-inexistente.png',
        ))->array();

        $this->assertSame(
            ['Código', 'Producto', 'Maleri', 'Vendedor Norte', 'Vendedor Sur', 'Total en pedidos'],
            $rows[0],
        );
        $this->assertSame(['P-010', 'Producto pedido', 12, 3, 6, 9], $rows[1]);
        $this->assertSame(['P-020', 'Producto sin pedido', 9, 0, 0, 0], $rows[2]);
    }

    public function test_export_adds_analysis_as_the_fifth_sheet(): void
    {
        $sheets = (new ProductoControlExport(collect(), collect()))->sheets();

        $this->assertCount(5, $sheets);
        $this->assertInstanceOf(ResumenInventarioSheet::class, $sheets[3]);
        $this->assertInstanceOf(AnalisisPedidosSheet::class, $sheets[4]);
        $this->assertSame('Análisis de pedidos', $sheets[4]->title());
    }

    private function producto(int $id, string $codigo, string $nombre, int $existencia): Producto
    {
        $producto = new Producto(['codigo' => $codigo, 'nombre' => $nombre]);
        $producto->id = $id;
        $producto->setRelation('inventario', new Inventario(['cantidad' => $existencia]));

        return $producto;
    }

    private function linea(Producto $producto, int $cantidad): Producto
    {
        $linea = clone $producto;
        $linea->setRelation('pivot', (object) ['cantidad' => $cantidad, 'precio' => 10]);

        return $linea;
    }

    private function pedido(string $vendedor, array $productos): Pedido
    {
        $persona = new Persona(['razon_social' => $vendedor]);
        $proveedor = new Proveedore();
        $proveedor->setRelation('persona', $persona);

        $pedido = new Pedido([
            'estado' => EstadoPedidoEnum::Apartado,
            'folio' => 'P00001',
            'fecha_apartado' => now(),
        ]);
        $pedido->setRelation('proveedore', $proveedor);
        $pedido->setRelation('productos', new Collection($productos));

        return $pedido;
    }
}
