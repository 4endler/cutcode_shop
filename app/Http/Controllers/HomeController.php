<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Category::query()->homePage()->get();
        $brands = Brand::query()->homePage()->get();
        $products = Product::query()->homePage()->get();
        // logger()->channel('telegram')->info('homepage');
        return view('homepage', compact('categories', 'brands', 'products'));
    }
}
