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
        Route::prefix('customers')->group(function () {
            Volt::route('/', 'pages.customer.index')->name('customers');
            Volt::route('/{customer}', 'pages.customer.detail')->name('customers.detail');
        });

        Route::prefix('fields')->group(function () {
            Volt::route('/', 'pages.field.index')->name('fields');
            Volt::route('/{field}', 'pages.field.detail')->name('fields.detail');
        });

        Route::prefix('packages')->group(function () {
            Volt::route('/', 'pages.package.index')->name('packages');
            Volt::route('/{package}', 'pages.package.detail')->name('packages.detail');
        });

        Route::prefix('/admin-bookings')->group(function () {
            Volt::route('/', 'pages.admin-booking.index')->name('admin-bookings');
            Volt::route('/{allotment}', 'pages.admin-booking.detail')->name('admin-bookings.detail');
        });
    });

    Route::middleware(['iscustomer'])->group(function () {
        Route::prefix('/customer-bookings')->group(function () {
            Volt::route('/', 'pages.customer-booking.index')->name('customer-bookings');
            Volt::route('/{field}', 'pages.customer-booking.field-detail')->name('customer-bookings.field-detail');
        });

        Route::prefix('/customer-upcoming-bookings')->group(function () {
            Volt::route('/', 'pages.customer-upcoming-booking.index')->name('customer-upcoming-bookings');
        });

        Route::prefix('/customer-history-bookings')->group(function () {
            Volt::route('/', 'pages.customer-history-booking.index')->name('customer-history-bookings');
        });

        Route::prefix('/payments')->group(function () {
            Volt::route('/', 'pages.customer-payment.index')->name('customer-payments');
        });
    });
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
