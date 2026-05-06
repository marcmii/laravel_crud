<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $category->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $category->description ?: 'No hi ha descripció disponible.' }}</p>
            </div>

            @can('update', $category)
                <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Editar categoria
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Restaurants d'aquesta categoria</h2>

            <div class="mt-4 space-y-4">
                @forelse ($category->restaurants as $restaurant)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $restaurant->name }}</h3>
                                <p class="mt-1 text-sm text-slate-500">Propietari: {{ $restaurant->owner->name }}</p>
                            </div>
                            <a href="{{ route('restaurants.show', $restaurant) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                                Obrir
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Encara no hi ha restaurants assignats a aquesta categoria.</p>
                @endforelse
            </div>
        </section>

        @can('delete', $category)
            <aside class="rounded-2xl border border-rose-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-rose-700">Zona de perill</h2>
                <p class="mt-2 text-sm text-slate-600">Només es pot eliminar una categoria si no té restaurants assignats.</p>

                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">
                        Eliminar categoria
                    </button>
                </form>
            </aside>
        @endcan
    </div>
</x-app-layout>
