<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Booking::class);

        $user = Auth::user();
        $bookings = Booking::query()
            ->with(['restaurant.category', 'user'])
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->latest('booking_at')
            ->paginate(12);

        return view('bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        $this->authorize('create', Booking::class);

        return view('bookings.create', [
            'booking' => new Booking(),
            'restaurants' => Restaurant::query()->where('is_active', true)->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Booking::class);

        $validated = $this->validateBooking($request);
        $restaurant = Restaurant::query()->findOrFail($validated['restaurant_id']);

        if (! $request->user()->isAdmin() && ! $restaurant->is_active) {
            abort(403);
        }

        $validated['user_id'] = $request->user()->isAdmin()
            ? (int) $validated['user_id']
            : $request->user()->id;
        $validated['status'] = $request->user()->isAdmin()
            ? $validated['status']
            : 'pending';

        $booking = Booking::create($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('status', 'Reserva creada correctament.');
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['restaurant.category', 'restaurant.owner', 'user']);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $this->authorize('update', $booking);

        return view('bookings.edit', [
            'booking' => $booking,
            'restaurants' => Restaurant::query()
                ->where('is_active', true)
                ->orWhere('id', $booking->restaurant_id)
                ->orderBy('name')
                ->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $validated = $this->validateBooking($request);
        $restaurant = Restaurant::query()->findOrFail($validated['restaurant_id']);

        if (! $request->user()->isAdmin() && ! $restaurant->is_active) {
            abort(403);
        }

        $validated['user_id'] = $request->user()->isAdmin()
            ? (int) $validated['user_id']
            : $booking->user_id;
        $validated['status'] = $request->user()->isAdmin()
            ? $validated['status']
            : $booking->status;

        $booking->update($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('status', 'Reserva actualitzada correctament.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('status', 'Reserva eliminada correctament.');
    }

    private function validateBooking(Request $request): array
    {
        $rules = [
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'booking_at' => ['required', 'date'],
            'guests_count' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,confirmed,cancelled,completed'],
        ];

        if ($request->user()->isAdmin()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);
        $validated['status'] = $validated['status'] ?? 'pending';

        return $validated;
    }
}
