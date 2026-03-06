<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = Wishlist::with(['product.images', 'product.category'])
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($wishlist);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Producto ya está en tu wishlist.'
            ], 422);
        }

        $wishlist = Wishlist::create([
            'user_id'    => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json($wishlist->load('product'), 201);
    }

    public function destroy(Request $request, $productId)
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'Producto eliminado de wishlist.']);
    }
}