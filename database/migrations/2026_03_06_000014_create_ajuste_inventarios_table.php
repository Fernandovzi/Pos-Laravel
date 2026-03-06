<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajuste_inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('stock_actual');
            $table->integer('cantidad_fisica');
            $table->integer('diferencia');
            $table->enum('tipo_movimiento', ['ENTRADA', 'SALIDA']);
            $table->string('motivo', 500)->nullable();
            $table->timestamps();

            $table->index(['producto_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajuste_inventarios');
    }
};
