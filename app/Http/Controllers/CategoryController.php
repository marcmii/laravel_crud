<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->withCount('restaurants')
            ->orderBy('name')
            ->paginate(12);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('categories.create', ['category' => new Category()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $category = Category::create($validated);

        return redirect()->route('categories.show', $category)
            ->with('status', 'Categoria creada correctament.');
    }

    public function show(Category $category): View
    {
        $this->authorize('view', $category);

        $category->load(['restaurants' => fn ($query) => $query->with('owner')->latest()]);

        return view('categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $category);
        $category->update($validated);

        return redirect()->route('categories.show', $category)
            ->with('status', 'Categoria actualitzada correctament.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->restaurants()->exists()) {
            return redirect()->route('categories.show', $category)
                ->with('status', 'No pots eliminar una categoria que encara té restaurants assignats.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('status', 'Categoria eliminada correctament.');
    }

    private function uniqueSlug(string $name, ?Category $category = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::query()
                ->when($category, fn ($query) => $query->whereKeyNot($category->id))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
