<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::with([
                'buyer',
                'seller',
                'product.images',
                'messages' => fn($q) => $q->latest()->limit(1)
            ])
            ->where('buyer_id', $request->user()->id)
            ->orWhere('seller_id', $request->user()->id)
            ->orderBy('last_message_at', 'desc')
            ->get();

        return response()->json($conversations);
    }

    public function show(Request $request, $id)
    {
        $conversation = Conversation::with([
                'buyer',
                'seller',
                'product',
                'messages.sender'
            ])
            ->where(function($q) use ($request) {
                $q->where('buyer_id', $request->user()->id)
                  ->orWhere('seller_id', $request->user()->id);
            })
            ->findOrFail($id);

        return response()->json($conversation);
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_id'  => 'required|exists:users,id',
            'product_id' => 'sometimes|exists:products,id',
        ]);

        $existing = Conversation::where('buyer_id', $request->user()->id)
            ->where('seller_id', $request->seller_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json($existing->load(['buyer', 'seller', 'product']));
        }

        $conversation = Conversation::create([
            'buyer_id'   => $request->user()->id,
            'seller_id'  => $request->seller_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json($conversation->load(['buyer', 'seller', 'product']), 201);
    }
}