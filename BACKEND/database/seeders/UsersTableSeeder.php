<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
        [
            'name'=> 'Admin',
            'email'=> 'admin@example.com',
            'password' => Hash::make('adminpass'),
            'role' => 'ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name'=> 'user1',
            'email'=> 'user1@example.com',
            'password' => Hash::make('user1pass'),
            'role' => 'user1',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name'=> 'user2',
            'email'=> 'user2@example.com',
            'password' => Hash::make('user2pass'),
            'role' => 'user2',
            'created_at' => now(),
            'updated_at' => now(),
        ]

    ]);
    }
}
