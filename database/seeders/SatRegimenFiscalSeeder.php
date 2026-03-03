<?php

namespace Database\Seeders;

use App\Models\SatRegimenFiscal;
use Illuminate\Database\Seeder;

class SatRegimenFiscalSeeder extends Seeder
{
    public function run(): void
    {
        SatRegimenFiscal::upsert([
            ['clave'=>'601','descripcion'=>'General de Ley Personas Morales','aplica_fisica'=>false,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'603','descripcion'=>'Personas Morales con Fines no Lucrativos','aplica_fisica'=>false,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'605','descripcion'=>'Sueldos y Salarios e Ingresos Asimilados a Salarios','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'606','descripcion'=>'Arrendamiento','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'607','descripcion'=>'Régimen de Enajenación o Adquisición de Bienes','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'608','descripcion'=>'Demás ingresos','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'610','descripcion'=>'Residentes en el Extranjero sin Establecimiento Permanente en México','aplica_fisica'=>true,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'611','descripcion'=>'Ingresos por Dividendos (socios y accionistas)','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'612','descripcion'=>'Personas Físicas con Actividades Empresariales y Profesionales','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'614','descripcion'=>'Ingresos por intereses','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'615','descripcion'=>'Régimen de los ingresos por obtención de premios','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'616','descripcion'=>'Sin obligaciones fiscales','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'620','descripcion'=>'Sociedades Cooperativas de Producción que optan por diferir sus ingresos','aplica_fisica'=>false,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'621','descripcion'=>'Incorporación Fiscal','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'622','descripcion'=>'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras','aplica_fisica'=>true,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'623','descripcion'=>'Opcional para Grupos de Sociedades','aplica_fisica'=>false,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'624','descripcion'=>'Coordinados','aplica_fisica'=>false,'aplica_moral'=>true,'estado'=>1],
            ['clave'=>'625','descripcion'=>'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas','aplica_fisica'=>true,'aplica_moral'=>false,'estado'=>1],
            ['clave'=>'626','descripcion'=>'Régimen Simplificado de Confianza','aplica_fisica'=>true,'aplica_moral'=>true,'estado'=>1],
        ], ['clave'], ['descripcion','aplica_fisica','aplica_moral','estado']);
    }
}
