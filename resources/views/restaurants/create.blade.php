<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-slate-900">Crear restaurant</h1>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($categories->isEmpty())
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Necessites almenys una categoria abans de crear un restaurant.
            </div>
        @else
            <form method="POST" action="{{ route('restaurants.store') }}" class="space-y-6">
                @csrf
                @include('restaurants._form')

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                        Desar restaurant
                    </button>
                    <a href="{{ route('restaurants.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Cancel·lar</a>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
