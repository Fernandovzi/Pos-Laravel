<?php

namespace Database\Seeders;

use App\Models\SatUsoCfdi;
use Illuminate\Database\Seeder;

class SatUsoCfdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SatUsoCfdi::upsert([
            ['clave' => 'G01', 'descripcion' => 'Adquisición de mercancías', 'estado' => 1],
            ['clave' => 'G03', 'descripcion' => 'Gastos en general', 'estado' => 1],
            ['clave' => 'I01', 'descripcion' => 'Construcciones', 'estado' => 1],
            ['clave' => 'P01', 'descripcion' => 'Por definir', 'estado' => 1],
            ['clave' => 'S01', 'descripcion' => 'Sin efectos fiscales', 'estado' => 1],
        ], ['clave'], ['descripcion', 'estado']);
    }
}
