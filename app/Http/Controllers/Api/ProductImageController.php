<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    // Store a new product image
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|max:2048', // Validasi gambar dengan ukuran maksimal 2MB
        ]);

        // Upload image to public/storage/product_image
        $imagePath = $request->file('image')->store('product_image', 'public');

        // Buat record untuk menyimpan path gambar
        $productImage = ProductImage::create([
            'product_id' => $request->product_id,
            'path' => $imagePath,
        ]);

        return response()->json(['message' => 'Image uploaded successfully', 'data' => $productImage], 201);
    }


    // Get all images by product ID
    public function getByProduct($productId)
    {
        $productImages = ProductImage::where('product_id', $productId)->get();

        if ($productImages->isEmpty()) {
            return response()->json(['message' => 'No images found for this product'], 404);
        }

        return response()->json(['data' => $productImages], 200);
    }



    // Delete a product image by ID
    public function destroy($id)
    {
        $productImage = ProductImage::findOrFail($id);

        // Delete image from storage
        Storage::disk('public')->delete($productImage->path);

        // Delete the product image record
        $productImage->delete();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}
