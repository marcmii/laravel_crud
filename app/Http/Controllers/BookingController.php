<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Booking::class);

        return view('bookings.index');
    }

    public function create(): View
    {
        $this->authorize('create', Booking::class);

        return view('bookings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Booking::class);

        return redirect()->route('bookings.index')
            ->with('status', 'Booking structure ready for implementation.');
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $this->authorize('update', $booking);

        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        return redirect()->route('bookings.show', $booking)
            ->with('status', 'Booking structure ready for implementation.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        return redirect()->route('bookings.index')
            ->with('status', 'Booking structure ready for implementation.');
    }
}
