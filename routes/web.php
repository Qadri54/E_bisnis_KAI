<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::prefix('profile')->group(function(){
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/home', function () {
        return view('KeretaApi.home');
    })->name('KeretaApi.home');

    Route::get('/buyticket', function () {
        return view('KeretaApi.buyticket');
    })->name('KeretaApi.buyticket');

    Route::get('/reservasi', function () {
        return view('KeretaApi.reservasi');
    })->name('KeretaApi.reservasi');

});

require __DIR__ . '/auth.php';
