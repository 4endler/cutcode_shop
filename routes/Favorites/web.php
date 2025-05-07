<?php

use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::controller(FavoriteController::class)
        ->prefix('favorites')
        ->group(function () {
            Route::get('/', 'index')->name('favorites');
            Route::post('/{product}/add', 'add')->name('favorites.add');
            Route::delete('/{item}/delete', 'delete')->name('favorites.delete');
            Route::delete('/truncate', 'truncate')->name('favorites.truncate');
        });
});
