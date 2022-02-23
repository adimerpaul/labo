<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('seguros')->insert([
            ['nombre'=>'CLINICO',],
            ['nombre'=>'EXTERNO',],
            ['nombre'=>'COSSMIL',],
            ['nombre'=>'CSBP',],
        ]);
    }
}
