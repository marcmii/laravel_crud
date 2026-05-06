<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Categories</h1>
                <p class="mt-1 text-sm text-slate-500">Organitza els restaurants per tipus de cuina.</p>
            </div>

            @can('create', \App\Models\Category::class)
                <a href="{{ route('categories.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Nova categoria
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($categories as $category)
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900">{{ $category->name }}</h2>
                <p class="mt-3 text-sm text-slate-600">{{ $category->description ?: 'No hi ha descripció disponible.' }}</p>
                <p class="mt-4 text-sm font-medium text-slate-500">{{ $category->restaurants_count }} restaurants</p>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('categories.show', $category) }}" class="text-sm font-semibold text-slate-900 hover:text-slate-600">Veure detall</a>

                    @can('update', $category)
                        <a href="{{ route('categories.edit', $category) }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Editar</a>
                    @endcan
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                Encara no hi ha categories disponibles.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $categories->links() }}
    </div>
</x-app-layout>
