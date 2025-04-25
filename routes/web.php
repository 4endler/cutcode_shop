<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/images/{dir}/{method}/{size}/{ext}/{file}.{toext}', ThumbnailController::class)
    ->where('method', 'resize|crop|cover|coverDown')
    ->where('size', '\d+x\d+')
    ->where('ext', '(png|jpg|jpeg|gif|webp|svg)$')
    ->where('toext', '(png|jpg|jpeg|gif|webp|svg)$')
    // ->where('file', '.+\.(png|jpg|jpeg|gif|webp)$')
    ->name('thumbnail');