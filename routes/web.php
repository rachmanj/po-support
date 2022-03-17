<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemHistoryController;
use App\Http\Controllers\ItemServiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PoServiceController;
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
        Route::get('/export_excel', [ItemHistoryController::class, 'export_excel'])->name('export_excel');
        Route::get('/data', [ItemHistoryController::class, 'index_data'])->name('index_data');
        Route::get('/test', [ItemHistoryController::class, 'test']);
    });

    Route::get('po_service/data', [PoServiceController::class, 'data'])->name('po_service.data');
    Route::resource('po_service', PoServiceController::class);
    Route::get('/po_service/{id}/add_items', [PoServiceController::class, 'add_items'])->name('po_service.add_items');
    Route::get('/po_service/{id}/print', [PoServiceController::class, 'print_pdf'])->name('po_service.print_pdf');

    Route::get('/item_service/{po_id}/data', [ItemServiceController::class, 'data'])->name('item_service.data');
    ROute::post('/item_service/{po_id}', [ItemServiceController::class, 'store'])->name('item_service.store');
    ROute::get('/item_service/{item_id}', [ItemServiceController::class, 'edit'])->name('item_service.edit');
    ROute::put('/item_service/{item_id}', [ItemServiceController::class, 'update'])->name('item_service.update');
    ROute::delete('/item_service/{item_id}', [ItemServiceController::class, 'destroy'])->name('item_service.destroy');

    Route::post('/item_service/{po_id}/import_item', [ItemServiceController::class, 'import_item'])->name('item_service.import_item');
});


