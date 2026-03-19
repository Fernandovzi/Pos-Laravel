<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Fernandovzi',
                'password' => bcrypt('12345678'),
            ]
        );

        $rol = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        $permisos = Permission::pluck('id', 'id')->all();

        $rol->syncPermissions($permisos);

        if (!$user->hasRole($rol->name)) {
            $user->assignRole($rol->name);
        }
    }
}
