<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cat_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'front' => 'required|image',
            'back' => 'required|image',
            'price' => 'required|numeric',
        ]);

        $category = Category::find($request->cat_id);
        $categorySlug = $category->slug;

        $productCount = Product::where('cat_id', $request->cat_id)->count();

        $productSlug = $categorySlug . '-' . ($productCount + 1);

        // Store the front and back images in their respective directories
        $frontPath = $request->file('front')->store('product/front', 'public');
        $backPath = $request->file('back')->store('product/back', 'public');

        $product = Product::create([
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'slug' => $productSlug,
            'front' => $frontPath, // Save the path to the database
            'back' => $backPath,   // Save the path to the database
            'price' => $request->price,
        ]);

        return response()->json($product, 201);
    }



    // 6. GET Product all
    public function index()
    {
        $products = Product::with('category')->paginate(10);

        return response()->json([
            'message' => 'Products retrieved successfully',
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'data' => $products->items()
        ], 200);
    }



    // 7. GET product by category
    public function getByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $products = $category->products;

        return response()->json(['data' => $products], 200);
    }

    // 8. GET product by id
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json(['data' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'cat_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'front' => 'nullable|image',
            'back' => 'nullable|image',
            'price' => 'required|numeric',
        ]);

        // Update product data
        $product->update($request->all());

        // Handle image uploads if present
        if ($request->hasFile('front')) {
            $frontPath = $request->file('front')->store('product/front', 'public');
            $product->front = $frontPath;
        }

        if ($request->hasFile('back')) {
            $backPath = $request->file('back')->store('product/back', 'public');
            $product->back = $backPath;
        }

        $product->save();

        return response()->json(['message' => 'Product updated successfully', 'data' => $product], 200);
    }


    // 10. DELETE product by id
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
