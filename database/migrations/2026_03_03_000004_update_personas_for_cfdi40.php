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
        if (Schema::hasTable('personas')) {
            DB::statement("UPDATE personas SET tipo = 'FISICA' WHERE tipo = 'NATURAL'");
            DB::statement("UPDATE personas SET tipo = 'MORAL' WHERE tipo = 'JURIDICA'");
            DB::statement("ALTER TABLE personas MODIFY tipo ENUM('FISICA', 'MORAL') NOT NULL COMMENT 'Tipo de persona para facturación: FISICA o MORAL'");

            Schema::table('personas', function (Blueprint $table): void {
                if (Schema::hasColumn('personas', 'documento_id')) {
                    $table->dropForeign(['documento_id']);
                    $table->dropColumn('documento_id');
                }

                if (Schema::hasColumn('personas', 'numero_documento')) {
                    $table->dropColumn('numero_documento');
                }

                if (!Schema::hasColumn('personas', 'rfc')) {
                    $table->string('rfc', 13)
                        ->nullable()
                        ->comment('RFC del receptor para CFDI 4.0')
                        ->after('email');
                }

                if (!Schema::hasColumn('personas', 'regimen_fiscal')) {
                    $table->string('regimen_fiscal', 3)
                        ->nullable()
                        ->comment('Clave SAT del régimen fiscal del receptor')
                        ->after('rfc');
                }

                if (!Schema::hasColumn('personas', 'codigo_postal_fiscal')) {
                    $table->string('codigo_postal_fiscal', 5)
                        ->nullable()
                        ->comment('Código postal del domicilio fiscal del receptor')
                        ->after('regimen_fiscal');
                }

                if (!Schema::hasColumn('personas', 'uso_cfdi')) {
                    $table->string('uso_cfdi', 4)
                        ->nullable()
                        ->comment('Clave SAT de uso de CFDI')
                        ->after('codigo_postal_fiscal');
                }
            });

            Schema::table('personas', function (Blueprint $table): void {
                $table->unique('rfc', 'personas_rfc_unique');
            });
        }

        Schema::dropIfExists('documentos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('documentos')) {
            Schema::create('documentos', function (Blueprint $table): void {
                $table->id();
                $table->string('nombre', 30);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('personas')) {
            Schema::table('personas', function (Blueprint $table): void {
                if (Schema::hasColumn('personas', 'rfc')) {
                    $table->dropUnique('personas_rfc_unique');
                    $table->dropColumn('rfc');
                }

                if (Schema::hasColumn('personas', 'regimen_fiscal')) {
                    $table->dropColumn('regimen_fiscal');
                }

                if (Schema::hasColumn('personas', 'codigo_postal_fiscal')) {
                    $table->dropColumn('codigo_postal_fiscal');
                }

                if (Schema::hasColumn('personas', 'uso_cfdi')) {
                    $table->dropColumn('uso_cfdi');
                }

                if (!Schema::hasColumn('personas', 'documento_id')) {
                    $table->foreignId('documento_id')
                        ->nullable()
                        ->comment('Tipo de documento de identificación')
                        ->after('estado')
                        ->constrained()
                        ->cascadeOnDelete();
                }

                if (!Schema::hasColumn('personas', 'numero_documento')) {
                    $table->string('numero_documento', 20)
                        ->nullable()
                        ->comment('Número de documento de identificación')
                        ->after('documento_id');
                }
            });

            DB::statement("UPDATE personas SET tipo = 'NATURAL' WHERE tipo = 'FISICA'");
            DB::statement("UPDATE personas SET tipo = 'JURIDICA' WHERE tipo = 'MORAL'");
            DB::statement("ALTER TABLE personas MODIFY tipo ENUM('NATURAL', 'JURIDICA') NOT NULL COMMENT 'Tipo de persona original'");
        }
    }
};
