<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->hasAttached(
                Role::factory()->create([
                    'name' => 'admin',
                    'display_name' => 'Administrador',
                    'description' => 'Acceso a todas las areas y administracion del sistema',
                ])
            )
            ->create([
                'name' => 'Daniel',
                'email' => 'daniel@gmail.com',
                'password' => 'admin_23',
            ]);
    }
}
