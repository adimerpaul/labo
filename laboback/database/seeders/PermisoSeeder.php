<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('permisos')->insert([
            ['nombre'=>'Usuarios'],
            ['nombre'=>'Doctores'],
            ['nombre'=>'Pacientes'],
            ['nombre'=>'Historial'],
            ['nombre'=>'Reactivos'],
            ['nombre'=>'Por caducar'],
        ]);
    }
}
