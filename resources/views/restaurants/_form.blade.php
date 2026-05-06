<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nom</label>
        <input id="name" name="name" type="text" value="{{ old('name', $restaurant->name) }}" required class="w-full rounded-lg border-slate-300">
    </div>

    <div>
        <label for="category_id" class="mb-2 block text-sm font-medium text-slate-700">Categoria</label>
        <select id="category_id" name="category_id" required class="w-full rounded-lg border-slate-300">
            <option value="">Selecciona una categoria</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $restaurant->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    @if (auth()->user()->isAdmin())
        <div>
            <label for="owner_id" class="mb-2 block text-sm font-medium text-slate-700">Propietari</label>
            <select id="owner_id" name="owner_id" required class="w-full rounded-lg border-slate-300">
                <option value="">Selecciona un propietari</option>
                @foreach ($owners as $owner)
                    <option value="{{ $owner->id }}" @selected(old('owner_id', $restaurant->owner_id) == $owner->id)>
                        {{ $owner->name }} ({{ $owner->email }})
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <label for="capacity" class="mb-2 block text-sm font-medium text-slate-700">Capacitat</label>
        <input id="capacity" name="capacity" type="number" min="1" value="{{ old('capacity', $restaurant->capacity ?: 1) }}" required class="w-full rounded-lg border-slate-300">
    </div>

    <div class="md:col-span-2">
        <label for="address" class="mb-2 block text-sm font-medium text-slate-700">Adreça</label>
        <input id="address" name="address" type="text" value="{{ old('address', $restaurant->address) }}" required class="w-full rounded-lg border-slate-300">
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">Telefon</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $restaurant->phone) }}" class="w-full rounded-lg border-slate-300">
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Correu de contacte</label>
        <input id="email" name="email" type="email" value="{{ old('email', $restaurant->email) }}" class="w-full rounded-lg border-slate-300">
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Descripcio</label>
        <textarea id="description" name="description" rows="4" class="w-full rounded-lg border-slate-300">{{ old('description', $restaurant->description) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $restaurant->is_active ?? true)) class="rounded border-slate-300 text-slate-900">
            Restaurant actiu
        </label>
    </div>
</div>
