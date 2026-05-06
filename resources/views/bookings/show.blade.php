<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Reserva #{{ $booking->id }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $booking->restaurant->name }}</h1>
            </div>

            <a href="{{ route('bookings.edit', $booking) }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Editar reserva
            </a>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Detalls de la reserva</h2>

            <dl class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Client</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $booking->user->name }} ({{ $booking->user->email }})</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Estat</dt>
                    <dd class="mt-1 text-sm capitalize text-slate-900">{{ $booking->status }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Data i hora</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $booking->booking_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Comensals</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $booking->guests_count }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-slate-500">Observacions</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $booking->notes ?: 'No hi ha observacions.' }}</dd>
                </div>
            </dl>
        </section>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Restaurant</h2>
                <p class="mt-2 text-sm text-slate-900">{{ $booking->restaurant->name }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $booking->restaurant->address }}</p>
                <p class="mt-1 text-sm text-slate-500">Propietari: {{ $booking->restaurant->owner->name }}</p>
                <a href="{{ route('restaurants.show', $booking->restaurant) }}" class="mt-4 inline-flex text-sm font-medium text-slate-600 hover:text-slate-900">
                    Obrir restaurant
                </a>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-rose-700">Zona de perill</h2>
                <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                        Eliminar reserva
                    </button>
                </form>
            </div>
        </aside>
    </div>
</x-app-layout>
