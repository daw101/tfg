<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::with('category')->get();

    foreach ($products as $product) {
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
    }

    return response()->json($products);
}


    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Borrar imagen anterior si existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->fill($request->only([
            'name', 'description', 'price', 'stock', 'category_id'
        ]));

        $product->save();

        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado']);
    }
}
