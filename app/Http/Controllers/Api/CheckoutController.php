<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;
use App\Models\Order;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// untuk email 
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;

class CheckoutController extends Controller
{
    public function index()
    {
        $data = Order::with('productOrders')->latest()->simplePaginate(10);
        return new DyoResource("success", "get all orders", $data);
    }

    public function show($id)
    {
        $order = Order::with('productOrders')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return new DyoResource("success", "get order by id", $order);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'address_detail' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
            'shipping_method' => 'required',
            'subtotal' => 'required',
            'shipping_fee' => 'required',
            'tax' => 'required',
            'total' => 'required',
            'is_product' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $order = Order::create([
            'status' => 1,
            'email' => $request->email,
            'country' => $request->country,
            'name' => $request->name,
            'address' => $request->address,
            'address_detail' => $request->address_detail,
            "city" => $request->city,
            "province" => $request->province,
            "postal_code" => $request->postal_code,
            "phone" => $request->phone,
            "shipping_method" => $request->shipping_method,
            "subtotal" => $request->subtotal,
            "shipping_fee" => $request->shipping_fee,
            "tax" => $request->tax,
            "total" => $request->total,
            "is_product" => $request->is_product,
            "id_dyo" => $request->id_dyo
        ]);

        if ($request->is_product) {
            foreach ($request->product_orders as $product) {
                ProductOrder::create([
                    'id_order' => $order->id,
                    'id_product' => $product["id"],
                    'name_back' => $product["name_back"] ?? null,  // opsional
                    'number_back' => $product["number_back"] ?? null,  // opsional
                    'size' => $product["size"],
                    'material' => $product["material"],
                    'price' => $product["price"],
                    'quantity' => $product["quantity"]
                ]);
            }
        }


        return new DyoResource("success", "order created", Order::with('productOrders')->find($order->id));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Cari order berdasarkan ID
        $order = Order::find($id);

        // Jika tidak ditemukan, kembalikan respons 404
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Update status
        $order->update(['status' => $request->status]);

        // Kembalikan respons dengan data terbaru
        return new DyoResource("success", "Order status updated", $order);
    }
}
