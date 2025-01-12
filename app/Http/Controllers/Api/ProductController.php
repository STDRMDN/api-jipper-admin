<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // 5. POST product
    public function store(Request $request)
    {
        // Validasi input produk
        $request->validate([
            'cat_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'front' => 'required|image',
            'back' => 'required|image',
            'price' => 'required|numeric',
        ]);

        // Ambil slug dari kategori yang dipilih
        $category = Category::find($request->cat_id);
        $categorySlug = $category->slug;

        // Hitung jumlah produk yang sudah ada dalam kategori tersebut
        $productCount = Product::where('cat_id', $request->cat_id)->count();

        // Buat slug produk dengan format "{slug category}-{increment}"
        $productSlug = $categorySlug . '-' . ($productCount + 1);

        // Simpan produk baru
        $product = Product::create([
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'slug' => $productSlug,
            'front' => $request->file('front')->store('products/front'), // Upload image front
            'back' => $request->file('back')->store('products/back'), // Upload image back
            'price' => $request->price,
        ]);

        return response()->json($product, 201);
    }


    // 6. GET Product all
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json(['data' => $products], 200);
    }

    // 7. GET product by category
    public function getByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $products = $category->products; // Relasi dengan produk

        return response()->json(['data' => $products], 200);
    }

    // 8. GET product by id
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json(['data' => $product], 200);
    }

    // 9. PUT product by id
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

        $product->update($request->all());

        // Handle image uploads
        if ($request->hasFile('front')) {
            $product->front = $request->file('front')->store('products');
        }

        if ($request->hasFile('back')) {
            $product->back = $request->file('back')->store('products');
        }

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
