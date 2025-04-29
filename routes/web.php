<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Middleware\CatalogViewMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::middleware(CatalogViewMiddleware::class)->get('/catalog/{category:slug?}', CatalogController::class)->name('catalog');
Route::get('/product/{product:slug}', ProductController::class)->name('product');

Route::get('/images/{dir}/{method}/{size}/{ext}/{file}.{toext}', ThumbnailController::class)
    ->where('method', 'resize|crop|cover|coverDown')
    ->where('size', '\d+x\d+')
    ->where('ext', '(png|jpg|jpeg|gif|webp|svg)$')
    ->where('toext', '(png|jpg|jpeg|gif|webp|svg)$')
    // ->where('file', '.+\.(png|jpg|jpeg|gif|webp)$')
    ->name('thumbnail');