<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoutingPageController::class, 'index'])->name('home');

Route::get('/search-tickets', [RoutingPageController::class, 'show_list_ticket'])->name('list_ticket');

Route::middleware('auth')->group(function () {

    Route::prefix('profile')->group(function(){
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/detail-ticket/{id_tiket}', [RoutingPageController::class, 'show_detail_ticket'])->name('detail_ticket');
    Route::get('/tutor-pembayaran', [RoutingPageController::class, 'show_tutor_pembayaran'])->name('tutor_pembayaran');
});

require __DIR__ . '/auth.php';