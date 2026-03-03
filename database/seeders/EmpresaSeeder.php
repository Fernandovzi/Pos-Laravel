<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::insert([
            'nombre' => 'Prueba',
            'propietario' => 'Fernandovzi',
            'ruc' => 'XAXX010101000',
            'porcentaje_impuesto' => '0',
            'abreviatura_impuesto' => '%',
            'direccion' => 'Av. Siempre Viva 742 Col. Jardines del Sol C.P. 45000 Guadalajara, Jalisco México',
            'moneda_id' => 1
        ]);
    }
}
