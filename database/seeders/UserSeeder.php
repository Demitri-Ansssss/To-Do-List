<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'Admin@email.com',
                'password' => Hash::make('123'),
            ],
            [
                'name' => 'Creator',
                'email' => 'Creator@email.com',
                'password' => Hash::make('123'),
            ],
            [
                'name' => 'Editor',
                'email' => 'Editor@email.com',
                'password' => Hash::make('123'),
            ],
        ];
        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
