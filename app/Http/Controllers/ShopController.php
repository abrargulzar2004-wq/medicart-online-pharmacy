<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::visibleInStorefront()
            ->with(['category', 'brand', 'images']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->has('brands')) {
            $brandSlugs = array_filter((array) $request->brands);
            if ($brandSlugs) {
                $query->whereHas('brand', fn ($brandQuery) => $brandQuery->whereIn('slug', $brandSlugs));
            }
        }

        $products = $query->latest('id')->paginate(30)->withQueryString();
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::visibleInStorefront()
            ->with(['category', 'brand', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('shop.show', compact('product'));
    }
}
