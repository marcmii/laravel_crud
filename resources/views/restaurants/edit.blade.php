<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-slate-900">Editar restaurant</h1>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('restaurants.update', $restaurant) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('restaurants._form')

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Actualitzar restaurant
                </button>
                <a href="{{ route('restaurants.show', $restaurant) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Cancel·lar</a>
            </div>
        </form>
    </div>
</x-app-layout>
