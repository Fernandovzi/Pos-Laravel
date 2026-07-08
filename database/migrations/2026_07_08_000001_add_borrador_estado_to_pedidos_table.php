<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY estado ENUM('BORRADOR', 'APARTADO', 'ENTREGADO', 'CANCELADO') NOT NULL DEFAULT 'BORRADOR'");
        DB::statement("ALTER TABLE pedidos MODIFY fecha_apartado TIMESTAMP NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY fecha_apartado TIMESTAMP NOT NULL");
        DB::statement("ALTER TABLE pedidos MODIFY estado ENUM('APARTADO', 'ENTREGADO', 'CANCELADO') NOT NULL DEFAULT 'APARTADO'");
    }
};
