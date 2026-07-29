<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::visibleInStorefront()
            ->where('is_featured', 1)
            ->with(['category', 'brand', 'images'])
            ->take(4)
            ->get();

        $newArrivals = Product::visibleInStorefront()
            ->where('is_new_arrival', 1)
            ->with(['category', 'brand', 'images'])
            ->take(4)
            ->get();

        $categories = Category::where('status', 1)->take(4)->get();

        return view('home', compact('featuredProducts', 'newArrivals', 'categories'));
    }
}
