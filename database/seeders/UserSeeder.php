<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user exists and create if not.
        User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => RoleEnum::ADMIN,
        ]);

        User::firstOrCreate([
            'email' => 'admin2@admin.com',
        ], [
            'name' => 'Admin User2',
            'password' => Hash::make('password'),
            'role' => RoleEnum::ADMIN,
        ]);

        User::firstOrCreate([
            'email' => 'john@doe.com',
        ], [
            'name' => 'John Doe',
            'password' => Hash::make('password'),
            'role' => RoleEnum::AUTHOR,
        ]);

        User::firstOrCreate([
            'email' => 'jane@smith.com',
        ], [
            'name' => 'Jane Smith',
            'password' => Hash::make('password'),
            'role' => RoleEnum::AUTHOR,
        ]);
    }
}
