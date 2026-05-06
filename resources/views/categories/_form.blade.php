<div class="space-y-6">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nom</label>
        <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required class="w-full rounded-lg border-slate-300">
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Descripcio</label>
        <textarea id="description" name="description" rows="4" class="w-full rounded-lg border-slate-300">{{ old('description', $category->description) }}</textarea>
    </div>
</div>
