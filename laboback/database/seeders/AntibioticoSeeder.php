<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        DB::table('tipos')->insert([
        ['nombre'=>"",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"",'tipo'=>"CULTIVO"],
        ['nombre'=>"AC. NALIDIXICO",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMOXICILINA/AC. CLAVULANICO",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"21/10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMPICILINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMPICILINA/SULBACTAM",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10/10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AMIKACINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"AZITROMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"15ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFEPIME",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFOTAXIMA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFTRIAXONA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFTAZIDIMA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFUROXIMA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFALOTINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CEFAZOLINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CLORANFENICOL",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CIPROFLOXACINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"5ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"DOXICICLINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"ERTAPENEM",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"ERITROMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"15ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"ESTREPTOMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"300ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"GENTAMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"GENTAMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"120ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"IMIPENEM",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"LEVOFLOXACINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"5ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"LINEZOLID",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"MEROPENEM",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"MINOCICLINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"NITROFURANTOINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"300ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"NORFLOXACINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"OXACILINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"5ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"PENICILINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"PIPERACILINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"100ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"PIPERACILINA/TAZOBACTAM",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"100/10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"TETRACICLINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"TOBRAMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"10ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"TRIMETHOPRIM/SULFAMETHOXAZOL",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"125/23/75ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"VANCOMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"30ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CLINDAMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"2ug",'tipo'=>"CULTIVO"],
        ['nombre'=>"CLARITROMICINA",'unidad'=>"",'rangoMin'=>"",'rangoMax'=>"",'referencia'=>"15ug",'tipo'=>"CULTIVO"],

    ]);
    }
}
