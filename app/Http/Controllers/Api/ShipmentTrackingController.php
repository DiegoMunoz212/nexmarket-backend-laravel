<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShipmentTracking;
use Illuminate\Http\Request;

class ShipmentTrackingController extends Controller
{
    public function index(Request $request)
    {
        $trackings = ShipmentTracking::with('order')
            ->whereHas('order', fn($q) => $q->where('user_id', $request->user()->id))
            ->get();

        return response()->json($trackings);
    }

    public function show(ShipmentTracking $shipmentTracking)
    {
        $shipmentTracking->load('order');
        return response()->json($shipmentTracking);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'        => 'required|exists:orders,id',
            'carrier'         => 'sometimes|string',
            'tracking_number' => 'sometimes|string',
            'status'          => 'sometimes|in:pending,picked_up,in_transit,out_for_delivery,delivered,failed',
            'location'        => 'sometimes|string',
            'estimated_at'    => 'sometimes|date',
        ]);

        $tracking = ShipmentTracking::create($request->all());

        return response()->json($tracking, 201);
    }

    public function update(Request $request, ShipmentTracking $shipmentTracking)
    {
        $request->validate([
            'carrier'         => 'sometimes|string',
            'tracking_number' => 'sometimes|string',
            'status'          => 'sometimes|in:pending,picked_up,in_transit,out_for_delivery,delivered,failed',
            'location'        => 'sometimes|string',
            'estimated_at'    => 'sometimes|date',
            'delivered_at'    => 'sometimes|date',
        ]);

        $shipmentTracking->update($request->all());

        return response()->json($shipmentTracking);
    }

    public function destroy(ShipmentTracking $shipmentTracking)
    {
        $shipmentTracking->delete();
        return response()->json(['message' => 'Tracking eliminado.']);
    }
}