<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::factory()->create([
            'name' => 'user',
            'display_name' => 'Usuario',
            'description' => 'Acceso a registros y editar en el sistema'
        ]);

        Role::factory()->create([
            'name' => 'specialist',
            'display_name' => 'Especialista',
            'description' => 'Atencion de usuarios, mantenimiento correctivo y preventivo'
        ]);
    }
}