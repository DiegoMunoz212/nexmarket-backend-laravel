<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'discount', 'shipmentTracking'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        $order->load(['items.product.images', 'discount', 'shipmentTracking', 'payment']);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:card,paypal,cash,transfer',
            'discount_code'    => 'sometimes|string',
        ]);

        // Calcular subtotal
        $subtotal = 0;
        $items = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Stock insuficiente para {$product->name}"
                ], 422);
            }

            $unitPrice = $product->discount_price ?? $product->price;
            $itemSubtotal = $unitPrice * $item['quantity'];
            $subtotal += $itemSubtotal;

            $items[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'unit_price' => $unitPrice,
                'subtotal'   => $itemSubtotal,
            ];
        }

        // Aplicar descuento
        $discountAmount = 0;
        $discountId = null;

        if ($request->discount_code) {
            $discount = Discount::where('code', $request->discount_code)
                ->where('is_active', true)
                ->first();

            if ($discount) {
                $discountId = $discount->id;
                if ($discount->type === 'percent') {
                    $discountAmount = $subtotal * ($discount->value / 100);
                } elseif ($discount->type === 'fixed') {
                    $discountAmount = $discount->value;
                }
                $discount->increment('used_count');
            }
        }

        $total = $subtotal - $discountAmount;

        // Crear orden
        $order = Order::create([
            'user_id'          => $request->user()->id,
            'discount_id'      => $discountId,
            'subtotal'         => $subtotal,
            'discount_amount'  => $discountAmount,
            'shipping_cost'    => 0,
            'total'            => $total,
            'shipping_address' => $request->shipping_address,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'pending',
            'status'           => 'pending',
        ]);

        // Crear items y reducir stock
        foreach ($items as $item) {
            OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            Product::find($item['product_id'])->decrement('stock', $item['quantity']);
        }

        return response()->json($order->load(['items.product', 'discount']), 201);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'sometimes|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|in:pending,completed,failed,refunded',
        ]);

        $order->update($request->only(['status', 'payment_status']));

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Orden eliminada.']);
    }
}