<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $methods = PaymentMethod::where('user_id', $request->user()->id)->get();
        return response()->json($methods);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return response()->json($paymentMethod);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:card,paypal,cash,transfer',
            'provider'    => 'sometimes|string',
            'last_four'   => 'sometimes|string|size:4',
            'holder_name' => 'sometimes|string',
            'is_default'  => 'sometimes|boolean',
        ]);

        if ($request->is_default) {
            PaymentMethod::where('user_id', $request->user()->id)
                ->update(['is_default' => false]);
        }

        $method = PaymentMethod::create([
            'user_id'     => $request->user()->id,
            'type'        => $request->type,
            'provider'    => $request->provider,
            'last_four'   => $request->last_four,
            'holder_name' => $request->holder_name,
            'is_default'  => $request->is_default ?? false,
        ]);

        return response()->json($method, 201);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'is_default'  => 'sometimes|boolean',
            'holder_name' => 'sometimes|string',
        ]);

        if ($request->is_default) {
            PaymentMethod::where('user_id', $request->user()->id)
                ->update(['is_default' => false]);
        }

        $paymentMethod->update($request->all());

        return response()->json($paymentMethod);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return response()->json(['message' => 'Método de pago eliminado.']);
    }
}