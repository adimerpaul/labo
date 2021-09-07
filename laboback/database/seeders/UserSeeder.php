<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'=>'admin',
//                'tipo'=>'admin',
                'celular'=>'69603027',
                'email'=>'admin@test.com',
                'fechalimite'=>'9999/01/01',
                'password'=>Hash::make('admin'),
            ]
        ]);
    }
}
