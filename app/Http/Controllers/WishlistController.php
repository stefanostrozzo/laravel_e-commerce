<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function add_to_wishlist(Request $request)
    {
        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back()->with('success', 'Item added to wishlist successfully!');
    }

    public function remove_from_wishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back()->with('success', 'Item removed from wishlist successfully!');
    }

    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();
        return redirect()->back()->with('success', 'Wishlist cleared successfully!');
    }

    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('cart')->add($item->id, $item->name, $item->qty, $item->price)->associate('App\Models\Product');
        return redirect()->back()->with('success', 'Item moved to cart successfully!');
    }

    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist', compact('items'));
    }	
}
