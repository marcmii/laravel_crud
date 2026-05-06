<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        return Gate::forUser($user)->allows('is_admin') || $booking->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Booking $booking): bool
    {
        return Gate::forUser($user)->allows('is_admin') || $booking->user_id === $user->id;
    }

    public function delete(User $user, Booking $booking): bool
    {
        return Gate::forUser($user)->allows('is_admin') || $booking->user_id === $user->id;
    }
}
