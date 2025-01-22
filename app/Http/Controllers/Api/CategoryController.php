<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    // 1. POST category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
        ]);

        $category = Category::create($request->all());

        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    // 2. PUT category
    public function update(Request $request, $id)
    {
        // Cari kategori berdasarkan ID, jika tidak ditemukan akan return 404
        $category = Category::findOrFail($id);

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id, // Memastikan slug unik, kecuali untuk ID ini
        ]);

        // Update kategori dengan data yang sudah divalidasi
        $category->update($validatedData);

        // Return response sukses
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }


    // 3. DELETE category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

    // 4. GET category
    public function index()
    {
        $categories = Category::all();
        $totalCategories = $categories->count();

        return response()->json([
            'message' => 'categories retrivied successfully',
            'total' => $totalCategories,
            'data' => $categories
        ], 200);
    }
}
