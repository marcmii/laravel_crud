<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@crud-laravel.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'user@crud-laravel.test'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'owner@crud-laravel.test'],
            [
                'name' => 'Restaurant Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
