<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['user', 'category', 'images'])
            ->where('status', 'active');

        if ($request->has('category')) {
            $categoryId = (int) $request->category;
            $subIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
            $allIds = array_merge([$categoryId], $subIds);
            $query->whereIn('category_id', $allIds);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        match($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            'popular'    => $query->orderBy('views', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate($request->per_page ?? 12);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->increment('views');
        $product->load(['user', 'category', 'images', 'reviews.user']);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'sometimes|nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'sku'            => 'sometimes|string|unique:products',
            'status'         => 'sometimes|in:active,paused,draft',
            'tags'           => 'sometimes|string',
        ]);

        $product = Product::create([
            'user_id'        => $request->user()->id,
            'category_id'    => $request->category_id,
            'name'           => $request->name,
            'slug'           => Str::slug($request->name).'-'.Str::random(5),
            'description'    => $request->description,
            'price'          => $request->price,
            'discount_price' => $request->discount_price,
            'stock'          => $request->stock,
            'sku'            => $request->sku ?? Str::upper(Str::random(8)),
            'status'         => $request->status ?? 'active',
            'tags'           => $request->tags,
        ]);

        return response()->json($product->load(['category', 'images']), 201);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'sometimes|string|max:255',
            'description'    => 'sometimes|string',
            'price'          => 'sometimes|numeric|min:0',
            'discount_price' => 'sometimes|nullable|numeric|min:0',
            'stock'          => 'sometimes|integer|min:0',
            'status'         => 'sometimes|in:active,paused,draft',
            'tags'           => 'sometimes|string',
            'is_featured'    => 'sometimes|boolean',
        ]);

        if ($request->has('name')) {
            $request->merge(['slug' => Str::slug($request->name).'-'.Str::random(5)]);
        }

        $product->update($request->all());

        return response()->json($product->load(['category', 'images']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Producto eliminado.']);
    }

    public function popular()
    {
        $products = Product::with(['user', 'category', 'images'])
            ->where('status', 'active')
            ->orderBy('views', 'desc')
            ->limit(12)
            ->get();

        return response()->json(['data' => $products]);
    }

    public function featured()
    {
        $products = Product::with(['user', 'category', 'images'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->limit(8)
            ->get();

        return response()->json(['data' => $products]);
    }
}