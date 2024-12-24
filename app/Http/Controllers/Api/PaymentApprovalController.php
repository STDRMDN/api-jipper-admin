<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;
use App\Models\PaymentApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentApprovalController extends Controller
{
    public function index()
    {
        return DyoResource::collection(
            PaymentApproval::query()->paginate(10)
        );
    }

    public function show($id)
    {
        $paymentApproval = PaymentApproval::find($id);
        if (!$paymentApproval) {
            return response()->json(['error' => 'PaymentApproval not found!'], 404);
        }

        return new DyoResource("success", "get payment approval by id", $paymentApproval);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            "bukti" => 'required',
            'id_order' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $filename = time() . '.' . $request->bukti->getClientOriginalName();
        $request->bukti->move(public_path('bukti'), $filename);

        $paymentApproval = PaymentApproval::create([
            'name' => $request->name,
            'email' => $request->email,
            'proof_payment' => $filename,
            'status' => 0,
            'id_order' => $request->id_order
        ]);

        return new DyoResource("success", "payment approval created", $paymentApproval);
    }
}
