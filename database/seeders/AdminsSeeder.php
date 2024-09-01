<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminsSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Azab',
                'email' => 'azab@arena.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
            [
                'name' => 'Shuaib',
                'email' => 'shuaib@arena.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
            [
                'name' => 'Nour',
                'email' => 'nour@arena.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
            [
                'name' => 'Nagib',
                'email' => 'nagib@arena.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
