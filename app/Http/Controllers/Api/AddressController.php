<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)->get();
        return response()->json($addresses);
    }

    public function show(Address $address)
    {
        return response()->json($address);
    }

    public function store(Request $request)
    {
        $request->validate([
            'street'     => 'required|string',
            'city'       => 'required|string',
            'state'      => 'required|string',
            'country'    => 'required|string',
            'zip_code'   => 'required|string',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($request->is_default) {
            Address::where('user_id', $request->user()->id)
                ->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id'    => $request->user()->id,
            'street'     => $request->street,
            'city'       => $request->city,
            'state'      => $request->state,
            'country'    => $request->country,
            'zip_code'   => $request->zip_code,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json($address, 201);
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'street'     => 'sometimes|string',
            'city'       => 'sometimes|string',
            'state'      => 'sometimes|string',
            'country'    => 'sometimes|string',
            'zip_code'   => 'sometimes|string',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($request->is_default) {
            Address::where('user_id', $request->user()->id)
                ->update(['is_default' => false]);
        }

        $address->update($request->all());

        return response()->json($address);
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return response()->json(['message' => 'Dirección eliminada.']);
    }
}