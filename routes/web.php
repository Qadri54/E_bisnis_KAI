<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutingPageController;
use App\Http\Controllers\BusinessProcessController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoutingPageController::class, 'index'])->name('home');

Route::get('/list-ticket', [RoutingPageController::class, 'show_list_ticket'])->name('list_ticket');
Route::get('/detail-Ticket/{id_tiket}', [BusinessProcessController::class, 'booking_process'])->name('detail_ticket');

Route::middleware('auth')->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    Route::get('/detail-Ticket/{id_tiket}', [BusinessProcessController::class, 'booking_process'])->name('detail_ticket');
    Route::post('/checkout-process', [BusinessProcessController::class, 'checkout_process'])->name('checkout_process');
});

require __DIR__ . '/auth.php';
