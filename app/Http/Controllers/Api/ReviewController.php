<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])
            ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reviews);
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return response()->json($review);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'sometimes|string|max:1000',
        ]);

        $existing = Review::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Ya dejaste una reseña para este producto.'
            ], 422);
        }

        $review = Review::create([
            'user_id'    => $request->user()->id,
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return response()->json($review->load('user'), 201);
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'rating'  => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|max:1000',
        ]);

        $review->update($request->only(['rating', 'comment']));

        return response()->json($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Reseña eliminada.']);
    }
}