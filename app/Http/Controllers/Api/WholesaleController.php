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
            'status' => 1,
        ]);

        // Return data yang berhasil disimpan dengan resource
        return new DyoResource('success', 'Data Wholesale Berhasil Ditambahkan!', $wholesale);
    }

    public function updateStatus($id, Request $request)
    {
        // Validasi input status
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer|in:0,1,2', // Misalnya status bisa 0, 1, atau 2
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Cari wholesale berdasarkan ID
        $wholesale = Wholesale::find($id);

        // Jika data wholesale tidak ditemukan
        if (!$wholesale) {
            return response()->json(['error' => 'Wholesale not found!'], 404);
        }

        // Update status wholesale
        $wholesale->status = $request->status;
        $wholesale->save(); // Simpan perubahan

        return response()->json([
            'message' => 'Wholesale status updated successfully!',
            'wholesale' => $wholesale
        ]);
    }
}
