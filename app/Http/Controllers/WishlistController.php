<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product.category'])
            ->withCount('product')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Product $product)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
            ]);
            $inWishlist = true;
        }

        Cache::forget('wishlist_ids_' . auth()->id());

        if (request()->expectsJson()) {
            return response()->json(['in_wishlist' => $inWishlist]);
        }

        return back()->with('success', $inWishlist ? 'Ditambahkan ke wishlist.' : 'Dihapus dari wishlist.');
    }

    public function destroy(Wishlist $wishlist)
    {
        abort_unless($wishlist->user_id === auth()->id(), 403);
        $wishlist->delete();
        Cache::forget('wishlist_ids_' . auth()->id());

        return back()->with('success', 'Dihapus dari wishlist.');
    }
}
