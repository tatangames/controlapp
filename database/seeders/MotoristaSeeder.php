<?php

namespace Database\Seeders;

use App\Models\Motoristas;
use Illuminate\Database\Seeder;

class MotoristaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Motoristas::create([
            'usuario' => 'tatan',
            'password' => bcrypt('1234'),
            'nombre' => 'jonathan moran'
        ]);
    }
}
