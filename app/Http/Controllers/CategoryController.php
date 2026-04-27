<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        return view('categories.index');
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

        return redirect()->route('categories.index')
            ->with('status', 'Category structure ready for implementation.');
    }

    public function show(Category $category): View
    {
        $this->authorize('view', $category);

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

        return redirect()->route('categories.show', $category)
            ->with('status', 'Category structure ready for implementation.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        return redirect()->route('categories.index')
            ->with('status', 'Category structure ready for implementation.');
    }
}
