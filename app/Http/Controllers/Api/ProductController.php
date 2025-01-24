<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;
use Illuminate\Support\Facades\Storage;

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
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'cat_id' => 'required', // Tidak wajib, tetapi harus valid jika diisi
            'name'   => 'required',
            'slug'   => 'required|unique:products,slug,' . $id, // Unik jika diisi, kecuali untuk ID yang sama
            'price'  => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find product by ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found!'], 404);
        }

        // Check if 'front' image is provided
        if ($request->hasFile('front')) {
            // Upload new 'front' image
            $front = $request->file('front');
            $front->storeAs('public/product/front', $front->hashName());

            // Delete old 'front' image if exists
            if ($product->front) {
                Storage::delete('public/product/front/' . basename($product->front));
            }

            // Update product with new 'front' image
            $product->front = 'product/front/' . $front->hashName();
        }

        // Check if 'back' image is provided
        if ($request->hasFile('back')) {
            // Upload new 'back' image
            $back = $request->file('back');
            $back->storeAs('public/product/back', $back->hashName());

            // Delete old 'back' image if exists
            if ($product->back) {
                Storage::delete('public/product/back/' . basename($product->back));
            }

            // Update product with new 'back' image
            $product->back = 'product/back/' . $back->hashName();
        }

        // Update other fields
        $product->cat_id = $request->cat_id ?? $product->cat_id;
        $product->name   = $request->name ?? $product->name;
        $product->slug   = $request->slug ?? $product->slug;
        $product->price  = $request->price ?? $product->price;

        // Save the updated product
        $product->save();

        // Return response
        return response()->json([
            'status'  => 'success',
            'message' => 'Product updated successfully!',
            'data'    => $product,
        ], 200);
    }



    // 10. DELETE product by id
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
