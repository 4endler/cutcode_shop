<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __invoke(?Category $category)
    {
        $categories = Category::query()->select('id', 'title','slug')->has('products')->get();
        $products = Product::query()
            ->select('id', 'title', 'thumbnail', 'price','slug')
            ->filtered()
            ->sorted()
            ->when($category->exists, function (Builder $query) use ($category) {
                $query->whereRelation('categories', 'categories.id', $category->id);
            })
            ->paginate(6);
        // logger()->channel('telegram')->info('homepage');
        return view('catalog.index', compact('categories','products', 'category'));
    }
}
