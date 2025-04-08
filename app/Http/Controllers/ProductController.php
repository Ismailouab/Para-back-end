<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Check if the user is admin
    private function isAdmin($id)
    {
        return Auth::id() === 1 && (int)$id === 1;
    }

    // Get all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Show a specific product by ID
    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json($product);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Store a new product item
    public function store(Request $request, $id)
    {
        if (!$this->isAdmin($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'required|string',
            'taille' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = 'storage/' . $path; // final path: public/storage/products/filename.jpg
        }
        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    // Update a product item
    public function update(Request $request, $id, Product $product)
    {
        if (!$this->isAdmin($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'required|string',
            'taille' => 'required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = 'storage/' . $path;
        }
        $product->update($validated);
        return response()->json($product, 200);
    }

    // Delete a product item
    public function destroy($id, Product $product)
    {
        if (!$this->isAdmin($id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
