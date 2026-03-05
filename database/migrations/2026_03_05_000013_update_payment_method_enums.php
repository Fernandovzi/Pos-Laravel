<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ventas')->where('metodo_pago', 'TARJETA')->update(['metodo_pago' => 'TARJETA_DEBITO']);
        DB::table('movimientos')->where('metodo_pago', 'TARJETA')->update(['metodo_pago' => 'TARJETA_DEBITO']);

        DB::statement("ALTER TABLE ventas MODIFY metodo_pago ENUM('EFECTIVO', 'TARJETA_DEBITO', 'TARJETA_CREDITO', 'TRANSFERENCIA_SPEI', 'PAGO_MIXTO')");
        DB::statement("ALTER TABLE movimientos MODIFY metodo_pago ENUM('EFECTIVO', 'TARJETA_DEBITO', 'TARJETA_CREDITO', 'TRANSFERENCIA_SPEI')");
    }

    public function down(): void
    {
        DB::table('ventas')->where('metodo_pago', 'TARJETA_CREDITO')->update(['metodo_pago' => 'TARJETA_DEBITO']);
        DB::table('ventas')->where('metodo_pago', 'TRANSFERENCIA_SPEI')->update(['metodo_pago' => 'EFECTIVO']);
        DB::table('ventas')->where('metodo_pago', 'PAGO_MIXTO')->update(['metodo_pago' => 'EFECTIVO']);
        DB::table('ventas')->where('metodo_pago', 'TARJETA_DEBITO')->update(['metodo_pago' => 'TARJETA']);

        DB::table('movimientos')->where('metodo_pago', 'TARJETA_CREDITO')->update(['metodo_pago' => 'TARJETA_DEBITO']);
        DB::table('movimientos')->where('metodo_pago', 'TRANSFERENCIA_SPEI')->update(['metodo_pago' => 'EFECTIVO']);
        DB::table('movimientos')->where('metodo_pago', 'TARJETA_DEBITO')->update(['metodo_pago' => 'TARJETA']);

        DB::statement("ALTER TABLE ventas MODIFY metodo_pago ENUM('EFECTIVO', 'TARJETA')");
        DB::statement("ALTER TABLE movimientos MODIFY metodo_pago ENUM('EFECTIVO', 'TARJETA')");
    }
};
