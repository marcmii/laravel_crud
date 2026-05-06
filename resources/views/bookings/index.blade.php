<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Reserves</h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ auth()->user()->isAdmin() ? 'Gestiona totes les reserves de la plataforma.' : 'Gestiona les teves reserves.' }}
                </p>
            </div>

            <a href="{{ route('bookings.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Nova reserva
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Restaurant</th>
                        <th class="px-6 py-4 font-medium">Client</th>
                        <th class="px-6 py-4 font-medium">Data</th>
                        <th class="px-6 py-4 font-medium">Comensals</th>
                        <th class="px-6 py-4 font-medium">Estat</th>
                        <th class="px-6 py-4 font-medium">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $booking->restaurant->name }}</div>
                                <div class="text-slate-500">{{ $booking->restaurant->category->name }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4">{{ $booking->booking_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $booking->guests_count }}</td>
                            <td class="px-6 py-4 capitalize">{{ $booking->status }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-3">
                                    <a href="{{ route('bookings.show', $booking) }}" class="font-medium text-slate-600 hover:text-slate-900">Veure</a>
                                    <a href="{{ route('bookings.edit', $booking) }}" class="font-medium text-slate-600 hover:text-slate-900">Editar</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">No hi ha reserves disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
</x-app-layout>
