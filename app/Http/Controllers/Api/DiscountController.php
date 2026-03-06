<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'desc')->get();
        return response()->json($discounts);
    }

    public function show(Discount $discount)
    {
        return response()->json($discount);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|unique:discounts|uppercase',
            'type'         => 'required|in:percent,fixed,shipping',
            'value'        => 'required|numeric|min:0',
            'min_purchase' => 'sometimes|numeric|min:0',
            'max_uses'     => 'sometimes|integer|min:1',
            'starts_at'    => 'sometimes|date',
            'ends_at'      => 'sometimes|date|after:starts_at',
            'applies_to'   => 'sometimes|in:all,category,product',
        ]);

        $discount = Discount::create($request->all());

        return response()->json($discount, 201);
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'type'         => 'sometimes|in:percent,fixed,shipping',
            'value'        => 'sometimes|numeric|min:0',
            'min_purchase' => 'sometimes|numeric|min:0',
            'max_uses'     => 'sometimes|integer|min:1',
            'starts_at'    => 'sometimes|date',
            'ends_at'      => 'sometimes|date',
            'is_active'    => 'sometimes|boolean',
            'applies_to'   => 'sometimes|in:all,category,product',
        ]);

        $discount->update($request->all());

        return response()->json($discount);
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return response()->json(['message' => 'Descuento eliminado.']);
    }

    public function validate(Request $request)
    {
        $request->validate([
            'code'    => 'required|string',
            'subtotal'=> 'required|numeric',
        ]);

        $discount = Discount::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$discount) {
            return response()->json(['message' => 'Cupón inválido.'], 404);
        }

        if ($discount->ends_at && $discount->ends_at->isPast()) {
            return response()->json(['message' => 'Cupón expirado.'], 422);
        }

        if ($discount->max_uses && $discount->used_count >= $discount->max_uses) {
            return response()->json(['message' => 'Cupón agotado.'], 422);
        }

        if ($request->subtotal < $discount->min_purchase) {
            return response()->json([
                'message' => "Compra mínima requerida: $".$discount->min_purchase
            ], 422);
        }

        $discountAmount = match($discount->type) {
            'percent'  => $request->subtotal * ($discount->value / 100),
            'fixed'    => $discount->value,
            'shipping' => 0,
            default    => 0,
        };

        return response()->json([
            'discount'        => $discount,
            'discount_amount' => $discountAmount,
        ]);
    }
}