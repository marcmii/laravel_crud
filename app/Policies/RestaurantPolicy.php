<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class RestaurantPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Restaurant $restaurant): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Restaurant $restaurant): bool
    {
        return Gate::forUser($user)->allows('is_admin') || Gate::forUser($user)->allows('is_owner', $restaurant);
    }

    public function delete(User $user, Restaurant $restaurant): bool
    {
        return Gate::forUser($user)->allows('is_admin') || Gate::forUser($user)->allows('is_owner', $restaurant);
    }

    public function restore(User $user, Restaurant $restaurant): bool
    {
        return Gate::forUser($user)->allows('is_admin');
    }

    public function forceDelete(User $user, Restaurant $restaurant): bool
    {
        return Gate::forUser($user)->allows('is_admin');
    }
}
