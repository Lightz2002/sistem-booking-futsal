<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Route::middleware(['isadmin'])->group(function () {
        Route::prefix('fields')->group(function () {
            Volt::route('/', 'pages.field.index')->name('fields');
            Volt::route('/{field}', 'pages.field.detail')->name('fields.detail');
        });

        Route::prefix('packages')->group(function () {
            Volt::route('/', 'pages.package.index')->name('packages');
            Volt::route('/{package}', 'pages.package.detail')->name('packages.detail');
        });
    });
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
