<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemHistoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemHistoryController::class, 'index'])->name('index');
        Route::post('/import_excel', [ItemHistoryController::class, 'import_excel'])->name('import_excel');
        Route::get('/data', [ItemHistoryController::class, 'index_data'])->name('index_data');
        Route::get('/test', [ItemHistoryController::class, 'test']);
    });
});


