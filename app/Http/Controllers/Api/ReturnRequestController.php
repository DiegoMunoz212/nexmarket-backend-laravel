<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\ReturnItem;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function index(Request $request)
    {
        $returns = ReturnRequest::with(['order', 'items.product'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($returns);
    }

    public function show(ReturnRequest $return)
    {
        $return->load(['order.items.product', 'items.product']);
        return response()->json($return);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'reason'         => 'required|string|max:1000',
            'refund_method'  => 'required|in:original,store_credit,transfer',
            'items'          => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.condition'     => 'required|string',
        ]);

        $return = ReturnRequest::create([
            'order_id'      => $request->order_id,
            'user_id'       => $request->user()->id,
            'reason'        => $request->reason,
            'refund_method' => $request->refund_method,
            'status'        => 'requested',
        ]);

        foreach ($request->items as $item) {
            ReturnItem::create([
                'return_id'     => $return->id,
                'product_id'    => $item['product_id'],
                'order_item_id' => $item['order_item_id'],
                'quantity'      => $item['quantity'],
                'condition'     => $item['condition'],
            ]);
        }

        return response()->json($return->load('items'), 201);
    }

    public function update(Request $request, ReturnRequest $return)
    {
        $request->validate([
            'status'        => 'required|in:requested,approved,rejected,completed',
            'refund_amount' => 'sometimes|numeric|min:0',
        ]);

        $data = $request->only(['status', 'refund_amount']);

        if ($request->status === 'completed') {
            $data['resolved_at'] = now();
        }

        $return->update($data);

        return response()->json($return);
    }

    public function destroy(ReturnRequest $return)
    {
        $return->delete();
        return response()->json(['message' => 'Solicitud de devolución eliminada.']);
    }
}