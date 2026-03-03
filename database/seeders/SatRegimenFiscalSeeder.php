<?php

namespace Database\Seeders;

use App\Models\SatRegimenFiscal;
use Illuminate\Database\Seeder;

class SatRegimenFiscalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SatRegimenFiscal::upsert([
            ['clave' => '601', 'descripcion' => 'General de Ley Personas Morales', 'aplica_fisica' => false, 'aplica_moral' => true, 'estado' => 1],
            ['clave' => '603', 'descripcion' => 'Personas Morales con Fines no Lucrativos', 'aplica_fisica' => false, 'aplica_moral' => true, 'estado' => 1],
            ['clave' => '605', 'descripcion' => 'Sueldos y Salarios e Ingresos Asimilados a Salarios', 'aplica_fisica' => true, 'aplica_moral' => false, 'estado' => 1],
            ['clave' => '612', 'descripcion' => 'Personas Físicas con Actividades Empresariales y Profesionales', 'aplica_fisica' => true, 'aplica_moral' => false, 'estado' => 1],
            ['clave' => '616', 'descripcion' => 'Sin obligaciones fiscales', 'aplica_fisica' => true, 'aplica_moral' => false, 'estado' => 1],
            ['clave' => '626', 'descripcion' => 'Régimen Simplificado de Confianza', 'aplica_fisica' => true, 'aplica_moral' => true, 'estado' => 1],
        ], ['clave'], ['descripcion', 'aplica_fisica', 'aplica_moral', 'estado']);
    }
}
