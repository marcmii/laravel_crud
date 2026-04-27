<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Restaurant::class);

        return view('restaurants.index');
    }

    public function create(): View
    {
        $this->authorize('create', Restaurant::class);

        return view('restaurants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Restaurant::class);

        return redirect()->route('restaurants.index')
            ->with('status', 'Restaurant structure ready for implementation.');
    }

    public function show(Restaurant $restaurant): View
    {
        $this->authorize('view', $restaurant);

        return view('restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant): View
    {
        $this->authorize('update', $restaurant);

        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('update', $restaurant);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('status', 'Restaurant structure ready for implementation.');
    }

    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('delete', $restaurant);

        return redirect()->route('restaurants.index')
            ->with('status', 'Restaurant structure ready for implementation.');
    }
}
