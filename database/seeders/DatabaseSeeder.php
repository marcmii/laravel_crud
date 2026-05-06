<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Admin User',
                'email' => 'admin@crud-laravel.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user = User::query()->updateOrCreate(
            ['email' => 'user@crud-laravel.test'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $owner = User::query()->updateOrCreate(
            ['email' => 'owner@crud-laravel.test'],
            [
                'name' => 'Restaurant Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $categories = collect([
            ['name' => 'Mediterranean', 'description' => 'Fresh Mediterranean cuisine and tapas.'],
            ['name' => 'Italian', 'description' => 'Pasta, pizza and classic Italian dishes.'],
            ['name' => 'Asian Fusion', 'description' => 'Asian-inspired cuisine with modern touches.'],
        ])->mapWithKeys(function (array $categoryData) {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                ]
            );

            return [$category->slug => $category];
        });

        $restaurants = collect([
            [
                'name' => 'Sea Breeze',
                'category' => 'mediterranean',
                'owner_id' => $owner->id,
                'address' => 'Passeig Maritim 12, Castelldefels',
                'phone' => '+34 931 000 111',
                'email' => 'seabreeze@example.com',
                'capacity' => 48,
                'is_active' => true,
                'description' => 'Restaurant specializing in rice dishes, grilled fish and local tapas.',
            ],
            [
                'name' => 'Pasta Corner',
                'category' => 'italian',
                'owner_id' => $user->id,
                'address' => 'Carrer Major 8, Gava',
                'phone' => '+34 931 000 222',
                'email' => 'pastacorner@example.com',
                'capacity' => 32,
                'is_active' => true,
                'description' => 'Cozy Italian restaurant with fresh pasta and wood-fired pizza.',
            ],
            [
                'name' => 'Wok Garden',
                'category' => 'asian-fusion',
                'owner_id' => $admin->id,
                'address' => 'Avinguda Europa 22, Barcelona',
                'phone' => '+34 931 000 333',
                'email' => 'wokgarden@example.com',
                'capacity' => 60,
                'is_active' => false,
                'description' => 'A modern fusion proposal with tasting menus and signature cocktails.',
            ],
        ])->mapWithKeys(function (array $restaurantData) use ($categories) {
            $restaurant = Restaurant::query()->updateOrCreate(
                ['slug' => Str::slug($restaurantData['name'])],
                [
                    'category_id' => $categories[$restaurantData['category']]->id,
                    'owner_id' => $restaurantData['owner_id'],
                    'name' => $restaurantData['name'],
                    'description' => $restaurantData['description'],
                    'address' => $restaurantData['address'],
                    'phone' => $restaurantData['phone'],
                    'email' => $restaurantData['email'],
                    'capacity' => $restaurantData['capacity'],
                    'is_active' => $restaurantData['is_active'],
                ]
            );

            return [$restaurant->slug => $restaurant];
        });

        Booking::query()->updateOrCreate(
            ['user_id' => $user->id, 'restaurant_id' => $restaurants['sea-breeze']->id],
            [
                'booking_at' => now()->addDays(2)->setTime(21, 0),
                'guests_count' => 4,
                'status' => 'pending',
                'notes' => 'Table near the window if possible.',
            ]
        );

        Booking::query()->updateOrCreate(
            ['user_id' => $admin->id, 'restaurant_id' => $restaurants['pasta-corner']->id],
            [
                'booking_at' => now()->addDays(5)->setTime(20, 30),
                'guests_count' => 2,
                'status' => 'confirmed',
                'notes' => 'Anniversary dinner.',
            ]
        );
    }
}
