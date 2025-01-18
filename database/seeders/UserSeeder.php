<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Fikri Admin',
                'email' => 'fikri@admin',
                'password' => Hash::make('fikriadmin'),
            ],
            [
                'name' => 'Fahrezy Admin',
                'email' => 'fahrezy@admin',
                'password' => Hash::make('fahrezyadmin'),
            ],
            [
                'name' => 'Akhdan Admin',
                'email' => 'akhdan@admin',
                'password' => Hash::make('akhdanadmin'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
