<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;

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

        return new DyoResource("success", "Category created successfully", $category);
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
        return new DyoResource("success", "Category updated successfully", $category);
    }

    // 3. DELETE category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return new DyoResource("success", "Category deleted successfully", null);
    }

    // 4. GET categories
    public function index()
    {
        // Mendapatkan semua data kategori dengan pagination
        $categories = Category::latest()->paginate(10);

        // Mendapatkan total seluruh data dalam tabel tanpa terpengaruh pagination
        $totalCategories = Category::count();

        return new DyoResource("success", "Categories retrieved successfully", [
            'total' => $totalCategories, // Total seluruh kategori
            'categories' => $categories  // Kategori yang dipaginate
        ]);
    }
}
