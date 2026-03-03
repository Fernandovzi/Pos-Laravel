<?php

namespace Database\Seeders;

use App\Models\SatUsoCfdi;
use Illuminate\Database\Seeder;

class SatUsoCfdiSeeder extends Seeder
{
    public function run(): void
    {
        SatUsoCfdi::upsert([

            ['clave'=>'G01','descripcion'=>'Adquisición de mercancías','estado'=>1],
            ['clave'=>'G02','descripcion'=>'Devoluciones, descuentos o bonificaciones','estado'=>1],
            ['clave'=>'G03','descripcion'=>'Gastos en general','estado'=>1],
            ['clave'=>'I01','descripcion'=>'Construcciones','estado'=>1],
            ['clave'=>'I02','descripcion'=>'Mobiliario y equipo de oficina por inversiones','estado'=>1],
            ['clave'=>'I03','descripcion'=>'Equipo de transporte','estado'=>1],
            ['clave'=>'I04','descripcion'=>'Equipo de cómputo y accesorios','estado'=>1],
            ['clave'=>'I05','descripcion'=>'Dados, troqueles, moldes, matrices y herramental','estado'=>1],
            ['clave'=>'I06','descripcion'=>'Comunicaciones telefónicas','estado'=>1],
            ['clave'=>'I07','descripcion'=>'Comunicaciones satelitales','estado'=>1],
            ['clave'=>'I08','descripcion'=>'Otra maquinaria y equipo','estado'=>1],
            ['clave'=>'D01','descripcion'=>'Honorarios médicos, dentales y gastos hospitalarios','estado'=>1],
            ['clave'=>'D02','descripcion'=>'Gastos médicos por incapacidad o discapacidad','estado'=>1],
            ['clave'=>'D03','descripcion'=>'Gastos funerales','estado'=>1],
            ['clave'=>'D04','descripcion'=>'Donativos','estado'=>1],
            ['clave'=>'D05','descripcion'=>'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)','estado'=>1],
            ['clave'=>'D06','descripcion'=>'Aportaciones voluntarias al SAR','estado'=>1],
            ['clave'=>'D07','descripcion'=>'Primas por seguros de gastos médicos','estado'=>1],
            ['clave'=>'D08','descripcion'=>'Gastos de transportación escolar obligatoria','estado'=>1],
            ['clave'=>'D09','descripcion'=>'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones','estado'=>1],
            ['clave'=>'D10','descripcion'=>'Pagos por servicios educativos (colegiaturas)','estado'=>1],
            ['clave'=>'S01','descripcion'=>'Sin efectos fiscales','estado'=>1],
        ], ['clave'], ['descripcion','estado']);
    }
}