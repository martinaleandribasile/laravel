<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserInventoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Pagina di benvenuto (pubblica)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard generica (solo autenticati e verificati)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Tutte le route protette da autenticazione
Route::middleware('auth')->group(function () {
    // Gestione profilo utente (modifica, update, cancellazione)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard amministratore
    Route::get('/admin/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    // Sezione amministratore: CRUD articoli, categorie, dettagli, statistiche, richieste
    Route::prefix('admin')->name('admin.')->group(function () {
        // CRUD articoli
        Route::resource('items', ItemController::class);
        // CRUD categorie
        Route::resource('categories', CategoryController::class);
        // Aggiunta dettaglio pezzo a un articolo
        Route::post('items/{item}/details', [\App\Http\Controllers\ItemDetailController::class, 'store'])->name('items.details.store');

        // Statistiche avanzate magazzino
        Route::get('statistiche', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistiche');

        // Storico utilizzi di un singolo pezzo
        Route::get('items/details/{id}/storico', [\App\Http\Controllers\ItemDetailController::class, 'storico']);

        // Gestione richieste utenti (visualizza, conferma, annulla)
        Route::get('requests', [\App\Http\Controllers\AdminRequestController::class, 'index'])->name('requests.index');
        Route::post('requests/{user_request}/confirm', [\App\Http\Controllers\AdminRequestController::class, 'confirm'])->name('requests.confirm');
        Route::post('requests/{user_request}/cancel', [\App\Http\Controllers\AdminRequestController::class, 'cancel'])->name('requests.cancel');
    });

    // Dashboard utente
    Route::get('/user/dashboard', function () {
        return Inertia::render('User/Dashboard');
    })->name('user.dashboard');

    // Inventario utente: lista articoli disponibili
    Route::get('/user/inventory', [UserInventoryController::class, 'index'])->name('user.inventory');
    // Dettaglio articolo per utente
    Route::get('/user/items/{item}', [UserInventoryController::class, 'show'])->name('user.items.show');
    // Lista richieste utente
    Route::get('/user/requests', [\App\Http\Controllers\UserRequestController::class, 'index'])->name('user.requests.index');
    // Invia richiesta per un pezzo
    Route::post('/user/requests', [\App\Http\Controllers\UserRequestController::class, 'store'])->name('user.requests.store');
    // Invia richiesta di acquisto articolo non presente
    Route::post('/user/requests/acquisto', [\App\Http\Controllers\UserRequestController::class, 'storeAcquisto'])->name('user.requests.storeAcquisto');
});

// Route di autenticazione (login, register, ecc.)
require __DIR__.'/auth.php';
