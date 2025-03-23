<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private function isAdmin($id)
    {
        return Auth::check() && Auth::id() === (int)$id && (int)$id === 1;
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request, $id)
    {
        if (!$this->isAdmin($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate(['name' => 'required|string|max:255']);
        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category);
        }
        return response()->json(['message' => 'Category not found'], 404);
    }

    public function update(Request $request, $id, Category $category)
    {
        if (!$this->isAdmin($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate(['name' => 'required|string|max:255']);
        $category->update($validated);
        return response()->json($category);
    }

    public function destroy($id, Category $category)
    {
        if (!$this->isAdmin($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
