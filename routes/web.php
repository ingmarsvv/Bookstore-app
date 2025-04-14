<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;


Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('book.index');
    Route::post('/{book}/purchase', [PurchaseController::class, 'store'])->name('book.purchase');
});

require __DIR__.'/auth.php';
