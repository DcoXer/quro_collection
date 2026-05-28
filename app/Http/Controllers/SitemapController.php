<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $products   = Product::where('is_active', true)->select('slug', 'updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();

        return response()->view('sitemap', compact('products', 'categories'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\n"
            . "Disallow: /cart\n"
            . "Disallow: /checkout\n"
            . "Disallow: /orders\n"
            . "Disallow: /profile\n"
            . "Disallow: /notifications\n"
            . "Disallow: /wishlist\n"
            . "Disallow: /admin\n"
            . "Disallow: /login\n"
            . "Disallow: /register\n"
            . "\n"
            . "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
