<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['order', 'paymentMethod'])
            ->whereHas('order', fn($q) => $q->where('user_id', $request->user()->id))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($payments);
    }

    public function show(Payment $payment)
    {
        $payment->load(['order', 'paymentMethod']);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'          => 'required|exists:orders,id',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
            'amount'            => 'required|numeric|min:0',
            'currency'          => 'sometimes|string|size:3',
            'transaction_id'    => 'sometimes|string',
        ]);

        $payment = Payment::create([
            'order_id'          => $request->order_id,
            'payment_method_id' => $request->payment_method_id,
            'amount'            => $request->amount,
            'currency'          => $request->currency ?? 'USD',
            'status'            => 'completed',
            'transaction_id'    => $request->transaction_id,
            'paid_at'           => now(),
        ]);

        Order::find($request->order_id)->update([
            'payment_status' => 'completed',
            'status'         => 'confirmed',
        ]);

        return response()->json($payment, 201);
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $payment->update($request->only(['status']));

        return response()->json($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Pago eliminado.']);
    }
}