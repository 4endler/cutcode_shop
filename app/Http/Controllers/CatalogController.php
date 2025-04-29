<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category)
    {
        $categories = Category::query()->select('id', 'title','slug')->has('products')->get();
        $products = Product::query()
            ->select('id', 'title', 'thumbnail', 'price','slug', 'json_properties')
            ->filtered()
            ->sorted()
            ->withCategory($category)
            ->search()
            ->paginate(6);
        // logger()->channel('telegram')->info('homepage');
        return view('catalog.index', compact('categories','products', 'category'));
    }
}
