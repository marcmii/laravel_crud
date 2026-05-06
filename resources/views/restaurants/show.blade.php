<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ $restaurant->category->name }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $restaurant->name }}</h1>
            </div>

            <div class="flex gap-3">
                @auth
                    <a href="{{ route('bookings.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                        Crear reserva
                    </a>
                @endauth

                @can('update', $restaurant)
                    <a href="{{ route('restaurants.edit', $restaurant) }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Editar restaurant
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
        <section class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Resum</h2>
                <p class="mt-4 text-sm leading-6 text-slate-600">{{ $restaurant->description ?: 'Encara no hi ha descripció.' }}</p>
            </div>

            @if ($visibleBookings->isNotEmpty())
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Reserves d'aquest restaurant</h2>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-slate-500">
                                <tr>
                                    <th class="pb-3 font-medium">Client</th>
                                    <th class="pb-3 font-medium">Data</th>
                                    <th class="pb-3 font-medium">Comensals</th>
                                    <th class="pb-3 font-medium">Estat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($visibleBookings as $booking)
                                    <tr>
                                        <td class="py-3">{{ $booking->user->name }}</td>
                                        <td class="py-3">{{ $booking->booking_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-3">{{ $booking->guests_count }}</td>
                                        <td class="py-3 capitalize">{{ $booking->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Detalls</h2>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div>
                        <dt class="font-medium text-slate-500">Propietari</dt>
                        <dd class="mt-1 text-slate-900">{{ $restaurant->owner->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Adreça</dt>
                        <dd class="mt-1 text-slate-900">{{ $restaurant->address }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Telefon</dt>
                        <dd class="mt-1 text-slate-900">{{ $restaurant->phone ?: 'No informat' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Correu</dt>
                        <dd class="mt-1 text-slate-900">{{ $restaurant->email ?: 'No informat' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Capacitat</dt>
                        <dd class="mt-1 text-slate-900">{{ $restaurant->capacity }} comensals</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-slate-500">Visibilitat</dt>
                        <dd class="mt-1 text-slate-900">{{ $restaurant->is_active ? 'Públic' : 'Privat per al propietari i l\'administrador' }}</dd>
                    </div>
                </dl>
            </div>

            @can('delete', $restaurant)
                <div class="rounded-2xl border border-rose-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-rose-700">Zona de perill</h2>
                    <p class="mt-2 text-sm text-slate-600">Si elimines el restaurant, també s'eliminaran les seves reserves.</p>

                    <form method="POST" action="{{ route('restaurants.destroy', $restaurant) }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                            Eliminar restaurant
                        </button>
                    </form>
                </div>
            @endcan
        </aside>
    </div>
</x-app-layout>
