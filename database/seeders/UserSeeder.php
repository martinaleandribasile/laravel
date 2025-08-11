<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin Role',
            'email' => 'admin@example.com',
            'password' => Hash::make('admintest'),
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        // Normal user
        User::create([
            'name' => 'User Role',
            'email' => 'user@example.com',
            'password' => Hash::make('usertest'),
            'role' => 'user',
            'remember_token' => Str::random(10),
        ]);
    }
}
