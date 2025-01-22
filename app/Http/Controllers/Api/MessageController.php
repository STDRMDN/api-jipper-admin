<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Http\Resources\DyoResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        $messages = Messages::latest()->paginate(10);  // Data yang dipaginate
        $totalMessages = Messages::count();  // Mendapatkan total semua data Messages

        return (new DyoResource("success", 'List Data Messages', $messages))->additional([
            'total' => $totalMessages
        ]);
    }


    public function show($id)
    {
        $message = Messages::find($id);

        if (!$message) {
            return response()->json(['error' => 'Message not found!'], 404);
        }

        return new DyoResource("success", 'message berhasil ditemukan', $message);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255',
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $message = Messages::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->message,
        ]);

        return new DyoResource("success", 'message berhasil disimpan', $message);
    }
}
