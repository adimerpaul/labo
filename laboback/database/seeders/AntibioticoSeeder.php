<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AntibioticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('antibioticos')->insert([
        ['nombre'=>"AC. NALIDIXICO",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMOXICILINA/AC. CLAVULANICO",'referencia'=>"21/10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMPICILINA",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMPICILINA/SULBACTAM",'referencia'=>"10/10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMIKACINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AZITROMICINA",'referencia'=>"15ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFEPIME",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFOTAXIMA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFTRIAXONA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFTAZIDIMA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFUROXIMA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFALOTINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFAZOLINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CLORANFENICOL",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CIPROFLOXACINA",'referencia'=>"5ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"DOXICICLINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"ERTAPENEM",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"ERITROMICINA",'referencia'=>"15ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"ESTREPTOMICINA",'referencia'=>"300ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"GENTAMICINA",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"GENTAMICINA",'referencia'=>"120ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"IMIPENEM",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"LEVOFLOXACINA",'referencia'=>"5ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"LINEZOLID",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"MEROPENEM",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"MINOCICLINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"NITROFURANTOINA",'referencia'=>"300ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"NORFLOXACINA",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"OXACILINA",'referencia'=>"5ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"PENICILINA",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"PIPERACILINA",'referencia'=>"100ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"PIPERACILINA/TAZOBACTAM",'referencia'=>"100/10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"TETRACICLINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"TOBRAMICINA",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"TRIMETHOPRIM/SULFAMETHOXAZOL",'referencia'=>"125/23/75ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"VANCOMICINA",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CLINDAMICINA",'referencia'=>"2ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CLARITROMICINA",'referencia'=>"15ug",'tipo'=>"CULTIVO"],

    ]);
    }
}
