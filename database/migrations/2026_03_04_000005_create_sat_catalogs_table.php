<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sat_regimenes_fiscales', function (Blueprint $table): void {
            $table->string('clave', 3)->primary()->comment('Clave SAT del régimen fiscal');
            $table->string('descripcion')->comment('Descripción del régimen fiscal SAT');
            $table->boolean('aplica_fisica')->default(true)->comment('Indica si aplica para persona física');
            $table->boolean('aplica_moral')->default(true)->comment('Indica si aplica para persona moral');
            $table->tinyInteger('estado')->default(1)->comment('1 activo, 0 inactivo');
            $table->timestamps();
        });

        Schema::create('sat_usos_cfdi', function (Blueprint $table): void {
            $table->string('clave', 4)->primary()->comment('Clave SAT de uso CFDI');
            $table->string('descripcion')->comment('Descripción del uso CFDI SAT');
            $table->tinyInteger('estado')->default(1)->comment('1 activo, 0 inactivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sat_usos_cfdi');
        Schema::dropIfExists('sat_regimenes_fiscales');
    }
};
