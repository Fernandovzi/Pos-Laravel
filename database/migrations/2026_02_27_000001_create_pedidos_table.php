<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('proveedore_id')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('folio')->unique();
            $table->string('persona_recojo');
            $table->text('observaciones')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['APARTADO', 'ENTREGADO', 'CANCELADO'])->default('APARTADO');
            $table->timestamp('fecha_apartado');
            $table->timestamp('fecha_entrega_estimada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
