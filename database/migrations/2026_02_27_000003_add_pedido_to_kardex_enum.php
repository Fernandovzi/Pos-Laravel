<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE kardex MODIFY tipo_transaccion ENUM('COMPRA','VENTA','AJUSTE','APERTURA','PEDIDO')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE kardex MODIFY tipo_transaccion ENUM('COMPRA','VENTA','AJUSTE','APERTURA')");
    }
};
