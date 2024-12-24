<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;
use App\Models\Wholesale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WholesaleResource;

class WholesaleController extends Controller
{

    public function index()
    {
        $wholesales = Wholesale::latest()->paginate(10);
        return new DyoResource("success", 'List Data Wholesales', $wholesales);
    }


    public function show($id)
    {
        $wholesale = Wholesale::find($id);

        if (is_null($wholesale)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return new DyoResource("success", "Data Wholesale dengan id $id", $wholesale);
    }

    public function store(Request $request)
    {
        // Lakukan validasi data
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'country'      => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email'        => 'required|string|email|max:255',
        ]);

        // Jika validasi gagal, kembalikan pesan error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Simpan data ke dalam model Wholesale
        $wholesale = Wholesale::create([
            'name'         => $request->name,
            'country'      => $request->country,
            'phone_number' => $request->phone_number,
            'email'        => $request->email,
        ]);

        // Return data yang berhasil disimpan dengan resource
        return new DyoResource('success', 'Data Wholesale Berhasil Ditambahkan!', $wholesale);
    }
}
