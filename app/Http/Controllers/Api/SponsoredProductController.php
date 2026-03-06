<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SponsoredProduct;
use Illuminate\Http\Request;

class SponsoredProductController extends Controller
{
    public function index(Request $request)
    {
        $sponsored = SponsoredProduct::with(['product.images', 'product.category'])
            ->where('is_active', true)
            ->where('ends_at', '>', now())
            ->orderBy('position')
            ->get();

        return response()->json($sponsored);
    }

    public function show(SponsoredProduct $sponsoredProduct)
    {
        $sponsoredProduct->load(['product', 'user']);
        return response()->json($sponsoredProduct);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
            'budget'     => 'required|numeric|min:0',
            'position'   => 'sometimes|integer|min:1',
        ]);

        $sponsored = SponsoredProduct::create([
            'product_id' => $request->product_id,
            'user_id'    => $request->user()->id,
            'starts_at'  => $request->starts_at,
            'ends_at'    => $request->ends_at,
            'budget'     => $request->budget,
            'position'   => $request->position ?? 1,
            'is_active'  => true,
        ]);

        return response()->json($sponsored->load('product'), 201);
    }

    public function update(Request $request, SponsoredProduct $sponsoredProduct)
    {
        $request->validate([
            'is_active' => 'sometimes|boolean',
            'budget'    => 'sometimes|numeric|min:0',
            'position'  => 'sometimes|integer|min:1',
            'ends_at'   => 'sometimes|date',
        ]);

        $sponsoredProduct->update($request->all());

        return response()->json($sponsoredProduct);
    }

    public function destroy(SponsoredProduct $sponsoredProduct)
    {
        $sponsoredProduct->delete();
        return response()->json(['message' => 'Producto patrocinado eliminado.']);
    }
}