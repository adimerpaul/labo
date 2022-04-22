<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos')->insert([
            ["nombre"=>"HEMOGRAMA COMPLETO",],
            ["nombre"=>"QUIMICA SANGUINIA",],
            ["nombre"=>"EXAMEN GENERAL DE ORINA",],
            ["nombre"=>"ANALISIS DE SECRECION URETRAL",],
            ["nombre"=>"ANALISIS DE SECRECION VAGINAL",],
            ["nombre"=>"ANALISIS DE HECES",],
            ["nombre"=>"COPRAPARASITOLOGICO SIMPLE",],
            ["nombre"=>"COPRAPARASITOLOGICO SERIADO",],
            ["nombre"=>"SEROLOGIA",],
            ["nombre"=>"LABORATORIO SEROLOGIA",],
            ["nombre"=>"RESULTADO SEROLOGIA",],
            ["nombre"=>"INMUNOENSAYO DE FLUORESCENCIA",],
            ["nombre"=>"TEST EMBARAZO",],
        ]);
    }
}