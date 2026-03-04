<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('pedidos', 'proveedore_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->foreignId('proveedore_id')
                    ->nullable()
                    ->after('cliente_id')
                    ->constrained('proveedores')
                    ->nullOnDelete();
            });
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE pedidos MODIFY cliente_id BIGINT UNSIGNED NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pedidos ALTER COLUMN cliente_id DROP NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pedidos', 'proveedore_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropForeign(['proveedore_id']);
                $table->dropColumn('proveedore_id');
            });
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE pedidos MODIFY cliente_id BIGINT UNSIGNED NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pedidos ALTER COLUMN cliente_id SET NOT NULL');
        }
    }
};
