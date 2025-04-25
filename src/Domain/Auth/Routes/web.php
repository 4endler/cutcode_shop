<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SignInController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::controller(SignInController::class)->group(function () {
        Route::get('/login', 'page')->middleware('guest')->name('login');
        Route::post('/login', 'handle')->middleware(['guest','throttle:auth'])->name('login.handle');
        Route::delete('/logout', 'logout')->name('logout');
    });
    Route::controller(SignUpController::class)->middleware('guest')->group(function () {
        Route::get('/sign-up', 'page')->name('signUp');
        Route::post('/sign-up', 'handle')->middleware('throttle:auth')->name('signUp.handle');
    });
    Route::controller(ForgotPasswordController::class)->middleware('guest')->group(function () {
        Route::get('/forgot-password', 'page')->name('forgot');
        Route::post('/forgot-password', 'handle')->middleware('throttle:auth')->name('forgot.handle');
    });
    Route::controller(ResetPasswordController::class)->middleware('guest')->group(function () {
        Route::get('/reset-password/{token}', 'page')->name('password.reset');
        Route::post('/reset-password', 'handle')->middleware('throttle:auth')->name('reset.handle');
    });
});
