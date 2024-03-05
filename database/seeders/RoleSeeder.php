<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des rôles
        Role::create(['name' => 'Super Admin']); // Pratiques courante d'avoir un Super Admin, possède tout les droits du site
        $admin = Role::create(['name' => 'Admin']);

        // Attribution des permissions
        $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user'
        ]);

        $coach = Role::create(['name' => 'coach']);

        $coach->givePermissionTo([
            'create-team',
            'edit-team',
            'delete-team',
            'create-user',
            'edit-user',
            'delete-user'
        ]);

        $parents = Role::create(['name' => 'parents']);

    }
}
