<?php

namespace Database\Seeders;

use App\Models\Clientes;
use App\Models\Usuarios;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuarios::create([
            'nombre' => 'Administrador',
            'usuario' => 'admin',
            'password' => bcrypt('1234'),
            'activo' => '1'
        ])->assignRole('Super-Admin');


    }
}
