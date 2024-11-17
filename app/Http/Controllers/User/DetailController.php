<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DetailController extends Controller
{
        // In DetailController.php
    public function productDetail($id)
    {
        // Fetch the specific product by ID, including its relationships
        $product = Product::with('category', 'location', 'product_images')->findOrFail($id);

        // Fetch locations and categories if needed elsewhere in the Vue component
        $locations = Location::all();
        $categories = Category::all();

        return Inertia::render('User/Detail', [
            'product' => $product,
            'locations' => $locations,
            'categories' => $categories
        ]);
    }

}
