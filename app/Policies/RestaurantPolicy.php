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
        if ($restaurant->is_active) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return Gate::forUser($user)->allows('is_admin') || Gate::forUser($user)->allows('is_owner', $restaurant);
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
        return Gate::forUser($user)->allows('is_admin');
    }
}
