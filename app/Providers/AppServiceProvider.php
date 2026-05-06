<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('is_admin', fn (User $user): bool => $user->isAdmin());

        Gate::define('is_owner', function (User $user, mixed $resource): bool {
            return match (true) {
                $resource instanceof Restaurant => $resource->owner_id === $user->id,
                $resource instanceof Booking => $resource->user_id === $user->id,
                default => false,
            };
        });
    }
}
