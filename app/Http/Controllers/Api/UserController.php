<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'sometimes|string|max:255',
            'email'  => 'sometimes|email|unique:users,email,'.$id,
            'phone'  => 'sometimes|string',
            'avatar' => 'sometimes|string',
        ]);

        $user->update($request->only([
            'name', 'email', 'phone', 'avatar'
        ]));

        return response()->json($user);
    }

    public function sellers()
    {
        $sellers = User::where('role', 'seller')
            ->withCount('products')
            ->with('sellerAnalytics')
            ->get();

        return response()->json($sellers);
    }

    public function seller($id)
    {
        $seller = User::where('role', 'seller')
            ->withCount('products')
            ->with(['products', 'sellerAnalytics'])
            ->findOrFail($id);

        return response()->json($seller);
    }
}