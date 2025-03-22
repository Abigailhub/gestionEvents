<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-createur', function () {
        return view('createur.dashboard');
    })->name('dashboard.createur')->middleware('role:createur');

    
    Route::get('/dashboard-participant', function () {
        return view('participant.dashboard');
    })->name('dashboard.participant')->middleware('role:participant');
});

require __DIR__.'/auth.php';
