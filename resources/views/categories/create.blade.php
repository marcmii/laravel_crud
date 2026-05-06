<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-slate-900">Crear categoria</h1>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf
            @include('categories._form')

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Desar categoria
                </button>
                <a href="{{ route('categories.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Cancel·lar</a>
            </div>
        </form>
    </div>
</x-app-layout>
