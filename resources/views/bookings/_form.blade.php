<div class="grid gap-6 md:grid-cols-2">
    @if (auth()->user()->isAdmin())
        <div class="md:col-span-2">
            <label for="user_id" class="mb-2 block text-sm font-medium text-slate-700">Client</label>
            <select id="user_id" name="user_id" required class="w-full rounded-lg border-slate-300">
                <option value="">Selecciona un client</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $booking->user_id) == $user->id)>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <label for="restaurant_id" class="mb-2 block text-sm font-medium text-slate-700">Restaurant</label>
        <select id="restaurant_id" name="restaurant_id" required class="w-full rounded-lg border-slate-300">
            <option value="">Selecciona un restaurant</option>
            @foreach ($restaurants as $restaurant)
                <option value="{{ $restaurant->id }}" @selected(old('restaurant_id', $booking->restaurant_id) == $restaurant->id)>
                    {{ $restaurant->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="booking_at" class="mb-2 block text-sm font-medium text-slate-700">Data i hora</label>
        <input id="booking_at" name="booking_at" type="datetime-local" value="{{ old('booking_at', optional($booking->booking_at)->format('Y-m-d\\TH:i')) }}" required class="w-full rounded-lg border-slate-300">
    </div>

    <div>
        <label for="guests_count" class="mb-2 block text-sm font-medium text-slate-700">Comensals</label>
        <input id="guests_count" name="guests_count" type="number" min="1" value="{{ old('guests_count', $booking->guests_count ?: 1) }}" required class="w-full rounded-lg border-slate-300">
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Estat</label>
        <select id="status" name="status" class="w-full rounded-lg border-slate-300" @disabled(! auth()->user()->isAdmin())>
            @foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                <option value="{{ $status }}" @selected(old('status', $booking->status ?: 'pending') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        @if (! auth()->user()->isAdmin())
            <p class="mt-2 text-xs text-slate-500">Només l'administrador pot canviar l'estat de la reserva.</p>
        @endif
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">Observacions</label>
        <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border-slate-300">{{ old('notes', $booking->notes) }}</textarea>
    </div>
</div>
