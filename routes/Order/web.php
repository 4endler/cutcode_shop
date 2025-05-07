<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::controller(OrderController::class)
        ->prefix('order')
        ->group(function () {
            Route::get('/', 'index')->name('order');
            Route::post('/', 'handle')->name('order.handle');
        });
});
