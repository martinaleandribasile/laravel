<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserInventoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard admin
    Route::get('/admin/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    // CRUD articoli (solo admin) e richieste
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('items', ItemController::class);
        Route::resource('categories', CategoryController::class);
        Route::post('items/{item}/details', [\App\Http\Controllers\ItemDetailController::class, 'store'])->name('items.details.store');

    // Statistiche avanzate
    Route::get('statistiche', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistiche');

    // Storico utilizzi pezzo
    Route::get('items/details/{id}/storico', [\App\Http\Controllers\ItemDetailController::class, 'storico']);

        // Gestione richieste
        Route::get('requests', [\App\Http\Controllers\AdminRequestController::class, 'index'])->name('requests.index');
    Route::post('requests/{user_request}/confirm', [\App\Http\Controllers\AdminRequestController::class, 'confirm'])->name('requests.confirm');
    Route::post('requests/{user_request}/cancel', [\App\Http\Controllers\AdminRequestController::class, 'cancel'])->name('requests.cancel');
    });

    // Dashboard user
    Route::get('/user/dashboard', function () {
        return Inertia::render('User/Dashboard');
    })->name('user.dashboard');

    Route::get('/user/inventory', [UserInventoryController::class, 'index'])
        ->name('user.inventory');
    Route::get('/user/items/{item}', [UserInventoryController::class, 'show'])->name('user.items.show');
    Route::get('/user/requests', [\App\Http\Controllers\UserRequestController::class, 'index'])->name('user.requests.index');
    Route::post('/user/requests', [\App\Http\Controllers\UserRequestController::class, 'store'])->name('user.requests.store');
    Route::post('/user/requests/acquisto', [\App\Http\Controllers\UserRequestController::class, 'storeAcquisto'])->name('user.requests.storeAcquisto');
});

require __DIR__.'/auth.php';
