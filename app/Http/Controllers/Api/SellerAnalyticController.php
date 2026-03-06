<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SellerAnalytic;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerAnalyticController extends Controller
{
    public function index(Request $request)
    {
        $analytics = SellerAnalytic::where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($analytics);
    }

    public function summary(Request $request)
    {
        $userId = $request->user()->id;

        $totalRevenue = Order::whereHas('items.product', fn($q) => $q->where('user_id', $userId))
            ->where('payment_status', 'completed')
            ->sum('total');

        $totalOrders = Order::whereHas('items.product', fn($q) => $q->where('user_id', $userId))
            ->count();

        $totalProducts = Product::where('user_id', $userId)->count();

        $totalViews = Product::where('user_id', $userId)->sum('views');

        $avgRating = \App\Models\Review::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->avg('rating');

        return response()->json([
            'total_revenue'   => $totalRevenue,
            'total_orders'    => $totalOrders,
            'total_products'  => $totalProducts,
            'total_views'     => $totalViews,
            'avg_rating'      => round($avgRating, 2),
        ]);
    }
}