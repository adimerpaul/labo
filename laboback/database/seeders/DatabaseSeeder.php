<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
           UserSeeder::class,
            PermisoSeeder::class,
            PermisoUserSeeder::class,
        ]);
//        \App\Models\User::factory(2)->create();
//        \App\Models\Doctor::factory(2)->create();
//        \App\Models\Paciente::factory(2)->create();
    }
}
