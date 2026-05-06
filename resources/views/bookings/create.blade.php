<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-slate-900">Crear reserva</h1>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($restaurants->isEmpty())
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                No hi ha restaurants actius disponibles per reservar.
            </div>
        @else
            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
                @csrf
                @include('bookings._form')

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                        Desar reserva
                    </button>
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Cancel·lar</a>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
