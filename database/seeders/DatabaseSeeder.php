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
        // \App\Models\User::factory(10)->create();
        /*$this->call(RolesSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(TiposServiciosSeeder::class);
        $this->call(HorarioSeeder::class);*/
        $this->call(MotoristaSeeder::class);
    }
}
