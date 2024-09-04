<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminsSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
            ]
        ];
        $users = [
            [
                'name' => 'Azab',
                'email' => 'azab@arena.com',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
