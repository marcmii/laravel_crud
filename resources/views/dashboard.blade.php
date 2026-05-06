<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-slate-900">Tauler</h1>
    </x-slot>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Rol</p>
            <p class="mt-2 text-xl font-semibold text-slate-900">{{ auth()->user()->isAdmin() ? 'Administrador' : 'Usuari' }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Els meus restaurants</p>
            <p class="mt-2 text-xl font-semibold text-slate-900">{{ auth()->user()->restaurants()->count() }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Les meves reserves</p>
            <p class="mt-2 text-xl font-semibold text-slate-900">{{ auth()->user()->bookings()->count() }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Accions rapides</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('restaurants.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Veure restaurants</a>
            <a href="{{ route('bookings.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Veure reserves</a>
            @can('create', \App\Models\Restaurant::class)
                <a href="{{ route('restaurants.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Crear restaurant</a>
            @endcan
            @can('create', \App\Models\Category::class)
                <a href="{{ route('categories.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Crear categoria</a>
            @endcan
        </div>
    </div>
</x-app-layout>
