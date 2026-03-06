<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->get();

        return response()->json($categories);
    }

    public function show(Category $category)
    {
        $category->load(['children', 'products']);
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'sometimes|string',
            'icon'        => 'sometimes|string',
            'parent_id'   => 'sometimes|exists:categories,id',
        ]);

        $category = Category::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'icon'        => $request->icon,
            'parent_id'   => $request->parent_id,
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'icon'        => 'sometimes|string',
            'is_active'   => 'sometimes|boolean',
            'parent_id'   => 'sometimes|exists:categories,id',
        ]);

        if ($request->has('name')) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Categoría eliminada.']);
    }
}