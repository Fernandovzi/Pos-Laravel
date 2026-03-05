<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->foreignId('comprobante_id')->nullable()->change();
            $table->foreignId('proveedore_id')->nullable()->change();
            $table->enum('metodo_pago', ['EFECTIVO', 'TARJETA'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->foreignId('comprobante_id')->nullable(false)->change();
            $table->foreignId('proveedore_id')->nullable(false)->change();
            $table->enum('metodo_pago', ['EFECTIVO', 'TARJETA'])->nullable(false)->change();
        });
    }
};
