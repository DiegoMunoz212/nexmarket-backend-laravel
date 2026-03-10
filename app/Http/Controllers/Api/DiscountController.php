<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        return response()->json(Discount::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|unique:discounts',
            'type'         => 'required|in:percent,fixed,shipping',
            'value'        => 'required|numeric|min:0',
            'min_purchase' => 'sometimes|numeric|min:0',
            'max_uses'     => 'sometimes|integer|min:1',
            'starts_at'    => 'sometimes|date',
            'ends_at'      => 'sometimes|date',
            'applies_to'   => 'sometimes|string',
        ]);

        $discount = Discount::create($request->all());
        return response()->json($discount, 201);
    }

    public function show(Discount $discount)
    {
        return response()->json($discount);
    }

    public function update(Request $request, Discount $discount)
    {
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
            'code'  => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $discount = Discount::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$discount) {
            return response()->json([
                'valid'   => false,
                'message' => 'Código de descuento inválido.'
            ], 422);
        }

        if ($discount->ends_at && now()->isAfter($discount->ends_at)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este código ha expirado.'
            ], 422);
        }

        if ($discount->max_uses && $discount->used_count >= $discount->max_uses) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este código ya alcanzó el límite de usos.'
            ], 422);
        }

        if ($discount->min_purchase && $request->total < $discount->min_purchase) {
            return response()->json([
                'valid'   => false,
                'message' => "Compra mínima de \${$discount->min_purchase} requerida."
            ], 422);
        }

        $discountAmount = match($discount->type) {
            'percent'  => round($request->total * ($discount->value / 100), 2),
            'fixed'    => min($discount->value, $request->total),
            'shipping' => 0,
            default    => 0,
        };

        return response()->json([
            'valid'           => true,
            'message'         => '¡Código aplicado exitosamente!',
            'discount_amount' => $discountAmount,
            'discount_type'   => $discount->type,
            'discount_value'  => $discount->value,
            'code'            => $discount->code,
        ]);
    }
}