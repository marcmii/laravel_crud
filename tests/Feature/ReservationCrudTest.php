<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_public_restaurants_but_not_private_restaurants(): void
    {
        $category = Category::create([
            'name' => 'Italian',
            'slug' => 'italian',
        ]);

        $owner = User::factory()->create();
        $publicRestaurant = Restaurant::create([
            'category_id' => $category->id,
            'owner_id' => $owner->id,
            'name' => 'Open Place',
            'slug' => 'open-place',
            'address' => 'Main street',
            'capacity' => 20,
            'is_active' => true,
        ]);

        $privateRestaurant = Restaurant::create([
            'category_id' => $category->id,
            'owner_id' => $owner->id,
            'name' => 'Private Place',
            'slug' => 'private-place',
            'address' => 'Hidden street',
            'capacity' => 10,
            'is_active' => false,
        ]);

        $this->get(route('restaurants.index'))
            ->assertOk()
            ->assertSee('Open Place')
            ->assertDontSee('Private Place');

        $this->get(route('restaurants.show', $publicRestaurant))->assertOk();
        $this->get(route('restaurants.show', $privateRestaurant))->assertForbidden();
    }

    public function test_regular_user_can_create_and_update_only_their_own_restaurants_but_cannot_delete_them(): void
    {
        User::factory()->create(['id' => 1]);

        $category = Category::create([
            'name' => 'Mediterranean',
            'slug' => 'mediterranean',
        ]);

        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $ownRestaurant = Restaurant::create([
            'category_id' => $category->id,
            'owner_id' => $user->id,
            'name' => 'My Place',
            'slug' => 'my-place',
            'address' => 'A street',
            'capacity' => 40,
            'is_active' => true,
        ]);
        $otherRestaurant = Restaurant::create([
            'category_id' => $category->id,
            'owner_id' => $otherUser->id,
            'name' => 'Other Place',
            'slug' => 'other-place',
            'address' => 'B street',
            'capacity' => 30,
            'is_active' => true,
        ]);

        $this->actingAs($user)->post(route('restaurants.store'), [
            'category_id' => $category->id,
            'name' => 'Created Place',
            'description' => 'Test',
            'address' => 'Created street',
            'capacity' => 25,
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('restaurants', [
            'name' => 'Created Place',
            'owner_id' => $user->id,
        ]);

        $this->actingAs($user)->put(route('restaurants.update', $ownRestaurant), [
            'category_id' => $category->id,
            'name' => 'Updated Place',
            'description' => 'Updated',
            'address' => 'Updated street',
            'capacity' => 50,
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('restaurants', [
            'id' => $ownRestaurant->id,
            'name' => 'Updated Place',
        ]);

        $this->actingAs($user)->get(route('restaurants.edit', $otherRestaurant))->assertForbidden();
        $this->actingAs($user)->delete(route('restaurants.destroy', $ownRestaurant))->assertForbidden();
        $this->actingAs($user)->delete(route('restaurants.destroy', $otherRestaurant))->assertForbidden();
    }

    public function test_admin_can_choose_restaurant_visibility_and_delete_restaurants(): void
    {
        $admin = User::factory()->create(['id' => 1]);
        $owner = User::factory()->create();
        $category = Category::create([
            'name' => 'Fusion',
            'slug' => 'fusion',
        ]);

        $this->actingAs($admin)->post(route('restaurants.store'), [
            'category_id' => $category->id,
            'owner_id' => $owner->id,
            'name' => 'Admin Private Place',
            'description' => 'Hidden restaurant',
            'address' => 'Secret street',
            'capacity' => 20,
            'is_active' => '0',
        ])->assertRedirect();

        $restaurant = Restaurant::where('name', 'Admin Private Place')->firstOrFail();

        $this->assertDatabaseHas('restaurants', [
            'id' => $restaurant->id,
            'owner_id' => $owner->id,
            'is_active' => false,
        ]);

        $this->actingAs($admin)->delete(route('restaurants.destroy', $restaurant))->assertRedirect();

        $this->assertDatabaseMissing('restaurants', [
            'id' => $restaurant->id,
        ]);
    }

    public function test_admin_can_manage_categories_but_regular_user_cannot(): void
    {
        $admin = User::factory()->create(['id' => 1]);
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Asian',
            'slug' => 'asian',
        ]);

        $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Desserts',
            'description' => 'Sweet options',
        ])->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'Desserts']);

        $this->actingAs($admin)->put(route('categories.update', $category), [
            'name' => 'Asian Fusion',
            'description' => 'Fusion dishes',
        ])->assertRedirect();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Asian Fusion']);

        $this->actingAs($user)->get(route('categories.create'))->assertForbidden();
        $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'Forbidden',
        ])->assertForbidden();
    }

    public function test_regular_users_only_see_and_manage_their_own_bookings(): void
    {
        $category = Category::create([
            'name' => 'Grill',
            'slug' => 'grill',
        ]);

        $owner = User::factory()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $restaurant = Restaurant::create([
            'category_id' => $category->id,
            'owner_id' => $owner->id,
            'name' => 'Fire House',
            'slug' => 'fire-house',
            'address' => 'Central avenue',
            'capacity' => 70,
            'is_active' => true,
        ]);

        $ownBooking = Booking::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'booking_at' => now()->addDay(),
            'guests_count' => 2,
            'status' => 'pending',
        ]);

        $otherBooking = Booking::create([
            'user_id' => $otherUser->id,
            'restaurant_id' => $restaurant->id,
            'booking_at' => now()->addDays(2),
            'guests_count' => 4,
            'status' => 'confirmed',
        ]);

        $this->actingAs($user)->get(route('bookings.index'))
            ->assertOk()
            ->assertSee('Fire House')
            ->assertSee($user->name)
            ->assertDontSee($otherUser->name);

        $this->actingAs($user)->get(route('bookings.show', $ownBooking))->assertOk();
        $this->actingAs($user)->get(route('bookings.show', $otherBooking))->assertForbidden();

        $this->actingAs($user)->post(route('bookings.store'), [
            'restaurant_id' => $restaurant->id,
            'booking_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'guests_count' => 3,
            'notes' => 'Birthday',
        ])->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'guests_count' => 3,
        ]);

        $this->actingAs($user)->delete(route('bookings.destroy', $otherBooking))->assertForbidden();
    }
}
