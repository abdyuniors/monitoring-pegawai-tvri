<?php

use App\Livewire\Security\Dashboard as SecurityDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Login Redirect
    |--------------------------------------------------------------------------
    |
    | Mengarahkan user setelah login sesuai role.
    |
    */
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role->value) {
            'admin',
            'pimpinan' => redirect('/admin'),

            'security' => redirect('/security'),

            default => abort(403),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Security Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/security', SecurityDashboard::class)
        ->middleware('security')
        ->name('security.dashboard');
});

/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/

require __DIR__ . '/settings.php';