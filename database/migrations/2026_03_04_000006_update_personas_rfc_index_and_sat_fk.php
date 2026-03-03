<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table): void {
            $table->dropUnique('personas_rfc_unique');
            $table->index('rfc', 'personas_rfc_index');

            $table->foreign('regimen_fiscal', 'personas_regimen_fiscal_fk')
                ->references('clave')
                ->on('sat_regimenes_fiscales')
                ->nullOnDelete();

            $table->foreign('uso_cfdi', 'personas_uso_cfdi_fk')
                ->references('clave')
                ->on('sat_usos_cfdi')
                ->nullOnDelete();
        });

        DB::statement("ALTER TABLE personas MODIFY rfc VARCHAR(13) NULL COMMENT 'RFC del receptor para CFDI 4.0. XAXX010101000 permitido para público en general'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table): void {
            $table->dropForeign('personas_regimen_fiscal_fk');
            $table->dropForeign('personas_uso_cfdi_fk');
            $table->dropIndex('personas_rfc_index');
            $table->unique('rfc', 'personas_rfc_unique');
        });

        DB::statement("ALTER TABLE personas MODIFY rfc VARCHAR(13) NULL COMMENT 'RFC del receptor para CFDI 4.0'");
    }
};
