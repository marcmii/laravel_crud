<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('restaurants.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('restaurants', RestaurantController::class)->only(['index', 'show']);
Route::resource('categories', CategoryController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('restaurants', RestaurantController::class)->except(['index', 'show']);
    Route::resource('bookings', BookingController::class);
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
});

require __DIR__.'/auth.php';
