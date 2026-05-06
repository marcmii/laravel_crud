<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Restaurant::class);

        $user = Auth::user();
        $restaurants = Restaurant::query()
            ->with(['category', 'owner'])
            ->when(! $user || ! $user->isAdmin(), function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->where('is_active', true);

                    if ($user) {
                        $subQuery->orWhere('owner_id', $user->id);
                    }
                });
            })
            ->latest()
            ->paginate(9);

        return view('restaurants.index', compact('restaurants'));
    }

    public function create(): View
    {
        $this->authorize('create', Restaurant::class);

        $categories = Category::query()->orderBy('name')->get();
        $owners = User::query()->orderBy('name')->get();

        return view('restaurants.create', [
            'restaurant' => new Restaurant(),
            'categories' => $categories,
            'owners' => $owners,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Restaurant::class);

        $validated = $this->validateRestaurant($request);
        $validated['owner_id'] = $request->user()->isAdmin()
            ? (int) $validated['owner_id']
            : $request->user()->id;
        $validated['slug'] = $this->uniqueSlug($validated['name']);

        $restaurant = Restaurant::create($validated);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('status', 'Restaurant creat correctament.');
    }

    public function show(Restaurant $restaurant): View
    {
        $this->authorize('view', $restaurant);

        $restaurant->load([
            'category',
            'owner',
            'bookings' => fn ($query) => $query->with('user')->latest(),
        ]);

        $visibleBookings = collect();
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::id() === $restaurant->owner_id)) {
            $visibleBookings = $restaurant->bookings;
        }

        return view('restaurants.show', compact('restaurant', 'visibleBookings'));
    }

    public function edit(Restaurant $restaurant): View
    {
        $this->authorize('update', $restaurant);

        $categories = Category::query()->orderBy('name')->get();
        $owners = User::query()->orderBy('name')->get();

        return view('restaurants.edit', compact('restaurant', 'categories', 'owners'));
    }

    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('update', $restaurant);

        $validated = $this->validateRestaurant($request, $restaurant);
        $validated['owner_id'] = $request->user()->isAdmin()
            ? (int) $validated['owner_id']
            : $restaurant->owner_id;
        $validated['slug'] = $this->uniqueSlug($validated['name'], $restaurant);

        $restaurant->update($validated);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('status', 'Restaurant actualitzat correctament.');
    }

    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('delete', $restaurant);

        $restaurant->delete();

        return redirect()->route('restaurants.index')
            ->with('status', 'Restaurant eliminat correctament.');
    }

    private function validateRestaurant(Request $request, ?Restaurant $restaurant = null): array
    {
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($request->user()->isAdmin()) {
            $rules['owner_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function uniqueSlug(string $name, ?Restaurant $restaurant = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Restaurant::query()
                ->when($restaurant, fn ($query) => $query->whereKeyNot($restaurant->id))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
