<?php

namespace Tests\Feature;

use App\Enums\MetodoPagoEnum;
use App\Enums\TipoMovimientoEnum;
use App\Enums\TipoTransaccionEnum;
use App\Models\Caja;
use App\Models\Caracteristica;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\Presentacione;
use App\Models\Producto;
use App\Models\Ubicacione;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaPago;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class VentaControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $permisos = [
            'ver-venta',
            'crear-venta',
            'mostrar-venta',
            'eliminar-venta',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        $this->user = User::factory()->create();
        $this->user->givePermissionTo($permisos);
        $this->actingAs($this->user);
    }

    public function test_index_shows_a_visible_cancel_sale_button_for_active_sales(): void
    {
        $venta = $this->crearVentaActiva();

        $response = $this->get(route('ventas.index'));

        $response->assertOk();
        $response->assertSee('Cancelar venta');
        $response->assertSee($venta->numero_comprobante);
    }

    public function test_destroy_cancels_the_sale_and_restores_cash_inventory_and_kardex(): void
    {
        $venta = $this->crearVentaActiva();
        $producto = $venta->productos()->firstOrFail();

        $response = $this->delete(route('ventas.destroy', $venta));

        $response->assertRedirect(route('ventas.show', $venta));
        $response->assertSessionHas('success', 'Venta cancelada, caja e inventario actualizados.');

        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'estado' => 'CANCELADA',
        ]);

        $this->assertDatabaseHas('inventario', [
            'producto_id' => $producto->id,
            'cantidad' => 10,
        ]);

        $this->assertDatabaseHas('movimientos', [
            'caja_id' => $venta->caja_id,
            'tipo' => TipoMovimientoEnum::Retiro->value,
            'descripcion' => 'Cancelación de venta n° ' . $venta->numero_comprobante,
            'monto' => 40,
            'metodo_pago' => MetodoPagoEnum::EFECTIVO->value,
        ]);

        $this->assertDatabaseHas('kardex', [
            'producto_id' => $producto->id,
            'tipo_transaccion' => TipoTransaccionEnum::CancelacionVenta->value,
            'entrada' => 2,
            'salida' => null,
            'saldo' => 10,
        ]);
    }

    private function crearVentaActiva(): Venta
    {
        $caracteristica = Caracteristica::create([
            'nombre' => 'Unidad',
            'descripcion' => 'Presentación de prueba',
            'estado' => 1,
        ]);

        $presentacion = Presentacione::create([
            'caracteristica_id' => $caracteristica->id,
            'sigla' => 'pz',
        ]);

        $producto = Producto::create([
            'codigo' => 'P001',
            'nombre' => 'Producto de prueba',
            'estado' => 1,
            'precio' => 20,
            'costo' => 10,
            'presentacione_id' => $presentacion->id,
        ]);

        $ubicacion = Ubicacione::create([
            'nombre' => 'Principal',
        ]);

        Inventario::create([
            'producto_id' => $producto->id,
            'ubicacione_id' => $ubicacion->id,
            'cantidad' => 8,
        ]);

        Kardex::create([
            'producto_id' => $producto->id,
            'tipo_transaccion' => TipoTransaccionEnum::Venta,
            'descripcion_transaccion' => 'Salida de producto por la venta n°T00001',
            'entrada' => null,
            'salida' => 2,
            'saldo' => 8,
            'costo_unitario' => 10,
        ]);

        $persona = Persona::create([
            'razon_social' => 'Cliente demo',
            'tipo' => 'FISICA',
            'email' => 'cliente@example.com',
            'estado' => 1,
            'rfc' => 'XAXX010101000',
            'regimen_fiscal' => '601',
            'codigo_postal_fiscal' => '01000',
            'uso_cfdi' => 'G03',
        ]);

        $cliente = Cliente::create([
            'persona_id' => $persona->id,
        ]);

        $comprobante = Comprobante::create([
            'nombre' => 'Ticket',
        ]);

        $caja = Caja::create([
            'nombre' => 'Caja principal',
            'fecha_hora_apertura' => now(),
            'saldo_inicial' => 100,
            'estado' => 1,
            'user_id' => $this->user->id,
        ]);

        $venta = Venta::create([
            'cliente_id' => $cliente->id,
            'user_id' => $this->user->id,
            'caja_id' => $caja->id,
            'comprobante_id' => $comprobante->id,
            'numero_comprobante' => 'T00001',
            'metodo_pago' => MetodoPagoEnum::EFECTIVO,
            'fecha_hora' => now(),
            'subtotal' => 40,
            'impuesto' => 0,
            'total' => 40,
            'monto_recibido' => 40,
            'vuelto_entregado' => 0,
            'estado' => 'ACTIVA',
        ]);

        $venta->productos()->attach($producto->id, [
            'cantidad' => 2,
            'precio_venta' => 20,
        ]);

        VentaPago::create([
            'venta_id' => $venta->id,
            'metodo_pago' => MetodoPagoEnum::EFECTIVO,
            'monto' => 40,
        ]);

        Movimiento::create([
            'caja_id' => $caja->id,
            'tipo' => TipoMovimientoEnum::Venta,
            'descripcion' => 'Venta n° ' . $venta->numero_comprobante,
            'monto' => 40,
            'metodo_pago' => MetodoPagoEnum::EFECTIVO,
        ]);

        return $venta->load(['productos', 'pagos']);
    }
}
