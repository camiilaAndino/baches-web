<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['nombre' => Role::SUPER_ADMINISTRADOR],
            ['descripcion' => 'Puede hacer todo dentro del sistema, incluyendo borrar y desactivar usuarios.']
        );

        Role::firstOrCreate(
            ['nombre' => Role::ADMINISTRADOR],
            ['descripcion' => 'Puede hacer todo dentro del sistema, excepto borrar o desactivar.']
        );

        Role::firstOrCreate(
            ['nombre' => Role::USUARIO_APP],
            ['descripcion' => 'Usuario final de la app móvil. No se asigna desde el panel de usuarios.']
        );
    }
}
