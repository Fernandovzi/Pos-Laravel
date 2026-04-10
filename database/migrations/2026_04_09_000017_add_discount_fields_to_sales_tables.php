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
        Schema::table('ventas', function (Blueprint $table): void {
            $table->decimal('descuento_total_porcentaje', 8, 2)->default(0)->after('subtotal');
            $table->decimal('descuento_total_monto', 10, 2)->default(0)->after('descuento_total_porcentaje');
        });

        Schema::table('producto_venta', function (Blueprint $table): void {
            $table->decimal('precio_original', 10, 2)->nullable()->after('cantidad');
            $table->decimal('descuento_porcentaje', 8, 2)->default(0)->after('precio_venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_venta', function (Blueprint $table): void {
            $table->dropColumn(['precio_original', 'descuento_porcentaje']);
        });

        Schema::table('ventas', function (Blueprint $table): void {
            $table->dropColumn(['descuento_total_porcentaje', 'descuento_total_monto']);
        });
    }
};
