<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::controller(CartController::class)
        ->prefix('cart')
        ->group(function () {
            Route::get('/', 'index')->name('cart');
            Route::post('/{product}/add', 'add')->name('cart.add');
            Route::post('/{item}/quantity', 'quantity')->name('cart.quantity');
            Route::delete('/{item}/delete', 'delete')->name('cart.delete');
            Route::delete('/truncate', 'truncate')->name('cart.truncate');
        });
});
