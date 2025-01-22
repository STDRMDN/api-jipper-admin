<?php

namespace App\Http\Controllers\Api;

use App\Models\Forders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FordersController extends Controller
{
    public function index()
    {
        $forders = Forders::latest()->paginate(10);
        $totalForders = Forders::count();  // Mendapatkan total semua data Forders

        return (new DyoResource("success", 'List Data Orders', $forders))->additional([
            'total' => $totalForders
        ]);
    }


    public function show($id)
    {
        $forder = Forders::find($id);

        if (!$forder) {
            return response()->json(['error' => 'Order not found!'], 404);
        }

        return new DyoResource("success", 'data berhasil ditemukan', $forder);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'your_name' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'shipping_address' => 'required|string',
            'zip_code' => 'required|string|max:10',
            'material' => 'required|string|max:255',
            'attachments.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'order_list' => 'required|file|mimes:xlsx,xls|max:2048',
            'jersey_material' => 'required|string|max:255',
            'jersey_size_chart' => 'required|string|max:255',
            'custom_jersey_size' => 'nullable|file|mimes:xlsx,xls|max:2048', // Changed to nullable
            'rush_shipping' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $forders = Forders::create([
            'team_name' => $request->team_name,
            'city' => $request->city,
            'email' => $request->email,
            'your_name' => $request->your_name,
            'state' => $request->state,
            'phone_number' => $request->phone_number,
            'shipping_address' => $request->shipping_address,
            'zip_code' => $request->zip_code,
            'material' => $request->material,
            'jersey_material' => $request->jersey_material,
            'jersey_size_chart' => $request->jersey_size_chart,
            'rush_shipping' => $request->rush_shipping,
            'status' => 1,
        ]);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment->isValid()) {
                    $path = $attachment->storeAs('attachments', time() . '_' . $attachment->getClientOriginalName(), 'public');
                    $attachments[] = $path;
                }
            }
        }

        // Handle order_list file
        $orderListPath = $request->file('order_list')->storeAs('order_list', time() . '_' . $request->file('order_list')->getClientOriginalName(), 'public');

        // Handle custom_jersey_size file if exists
        if ($request->hasFile('custom_jersey_size')) {
            $customJerseySizePath = $request->file('custom_jersey_size')->storeAs('custom_jersey', time() . '_' . $request->file('custom_jersey_size')->getClientOriginalName(), 'public');
            $forders->custom_jersey_size = $customJerseySizePath;
        }

        $forders->attachments = json_encode($attachments);
        $forders->order_list = $orderListPath;
        $forders->save();

        return new DyoResource(true, 'Order berhasil dibuat!', $forders);
    }

    public function update(Request $request, $id)
    {
        // Validasi input status
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Cari order berdasarkan ID
        $forder = Forders::find($id);

        // Jika order tidak ditemukan, return error
        if (!$forder) {
            return response()->json(['error' => 'Order not found!'], 404);
        }

        // Update status order
        $forder->update(['status' => $request->status]);
        return new DyoResource("success", "Order status updated", $forder);
    }
}
