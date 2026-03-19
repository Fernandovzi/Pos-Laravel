<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('permissions')
            ->where('name', 'eliminar-venta')
            ->exists();

        if (!$exists) {
            DB::table('permissions')->insert([
                'name' => 'eliminar-venta',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('permissions')
            ->where('name', 'eliminar-venta')
            ->delete();
    }
};
