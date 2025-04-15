<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')
        ->middleware('guest')
        ->name('login');
    Route::post('/login', 'signIn')->middleware('guest')->name('signIn');
    Route::get('/sign-up', 'signUp')->middleware('guest')->name('signUp');
    Route::post('/sign-up', 'store')->middleware('guest')->name('store');
    Route::delete('/logout', 'logout')->name('logout');
    Route::get('/forgot-password', 'forgot')
        ->middleware('guest')
        ->name('password.requets');
    Route::post('/forgot-password', 'sendResetLinkEmail')
        ->middleware('guest')
        ->name('password.email');
    Route::get('/reset-password/{token}', 'resetPassword')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'updatePassword')->middleware('guest')->name('password.update');
});
Route::get('/', HomeController::class)->name('home');