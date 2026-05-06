<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Restaurants</h1>
                <p class="mt-1 text-sm text-slate-500">Consulta els restaurants i gestiona els teus.</p>
            </div>

            @auth
                <a href="{{ route('restaurants.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Nou restaurant
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($restaurants as $restaurant)
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $restaurant->category->name }}</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900">{{ $restaurant->name }}</h2>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $restaurant->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $restaurant->is_active ? 'Actiu' : 'Privat' }}
                    </span>
                </div>

                <p class="mt-4 text-sm text-slate-600">{{ $restaurant->description ?: 'Encara no hi ha descripció.' }}</p>

                <dl class="mt-6 space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between gap-4">
                        <dt>Propietari</dt>
                        <dd class="font-medium text-slate-800">{{ $restaurant->owner->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Capacitat</dt>
                        <dd class="font-medium text-slate-800">{{ $restaurant->capacity }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Adreça</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $restaurant->address }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('restaurants.show', $restaurant) }}" class="text-sm font-semibold text-slate-900 hover:text-slate-600">
                        Veure detall
                    </a>

                    @can('update', $restaurant)
                        <a href="{{ route('restaurants.edit', $restaurant) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                            Editar
                        </a>
                    @endcan
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                Encara no hi ha restaurants disponibles.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $restaurants->links() }}
    </div>
</x-app-layout>
