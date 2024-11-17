<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductListController extends Controller
{
    public function index() {

        $products = Product::with('category', 'location', 'product_images');
        $filterProducts = $products->filtered()->paginate(12)->withQueryString();

        $categories = Category::get();
        $locations = Location::get();
        return Inertia::render(
            'User/ProductList',
            [
                'categories'=>$categories,
                'locations'=>$locations,
                'products'=>ProductResource::collection($filterProducts)
            ]
        );
    }
}
