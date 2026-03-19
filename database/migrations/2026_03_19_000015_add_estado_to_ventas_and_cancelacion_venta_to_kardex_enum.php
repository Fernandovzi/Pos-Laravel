<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->enum('estado', ['ACTIVA', 'CANCELADA'])->default('ACTIVA')->after('vuelto_entregado');
        });

        DB::statement("ALTER TABLE kardex MODIFY tipo_transaccion ENUM('COMPRA','VENTA','AJUSTE','APERTURA','PEDIDO','CANCELACION_PEDIDO','PRODUCCION_INTERNA','CANCELACION_VENTA')");
    }

    public function down(): void
    {
        DB::table('kardex')->where('tipo_transaccion', 'CANCELACION_VENTA')->delete();

        DB::statement("ALTER TABLE kardex MODIFY tipo_transaccion ENUM('COMPRA','VENTA','AJUSTE','APERTURA','PEDIDO','CANCELACION_PEDIDO','PRODUCCION_INTERNA')");

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
