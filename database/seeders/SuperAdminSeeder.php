<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création du Super Admin
        $superAdmin = User::create([
            'firstname' => 'G3L3INFO',
            'lastname' => 'AMSB',
            'email' => 'g3l3info@gmail.com',
            'password' => Hash::make('G3L3INFO1234')
        ]);

        $superAdmin->assignRole('Super Admin');

        $superAdminTwo = User::create([
            'firstname' => 'Kylian',
            'lastname' => 'GRANDY',
            'email' => 'kylian.grandy@gmail.com',
            'description' => 'c\'est la description de kylian',
            'emergency' => '0782824600',
            'password' => Hash::make('29062003$Kykygrd')
        ]);

        $superAdminTwo->assignRole('Super Admin');
    }
}
