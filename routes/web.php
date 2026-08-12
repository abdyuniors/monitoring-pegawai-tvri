<?php

use App\Livewire\Security\Dashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'security'])->group(function () {
    Route::get('/security', Dashboard::class)
        ->name('security.dashboard');
});

require __DIR__ . '/settings.php';