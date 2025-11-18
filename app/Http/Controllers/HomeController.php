<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products.
     */
    public function index(): Response
    {
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('products')->get();

        return Inertia::render('Home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
        ]);
    }
}
