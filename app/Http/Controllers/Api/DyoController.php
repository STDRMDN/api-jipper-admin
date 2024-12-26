<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DyoResource;
use App\Models\Attachment;
use App\Models\AttachmentRef;
use App\Models\Dyo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class DyoController extends Controller
{


    public function index()
    {
        $dyo = Dyo::with('attachments', 'attachmentRefs')->latest()->simplePaginate(10);
        return new DyoResource("success", "get all dyo", $dyo);
    }



    public function show($id)
    {
        $dyo = Dyo::with('attachments', 'attachmentRefs')->find($id);

        if (!$dyo) {
            return response()->json(['error' => 'Dyo not found!'], 404);
        }

        return new DyoResource("success", "get dyo by id", $dyo);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'team' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'id_ref' => 'required|integer|max:255',
            'description' => 'required|string|max:255',
            'attachments' => 'required',
            'attachments.*' => 'mimes:doc,docx,pdf,jpg,jpeg,png|max:2048',
            'attachmentRefs' => 'required',
            'attachmentRefs.*' => 'mimes:doc,docx,pdf,jpg,jpeg,png|max:2048',
        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if (!$request->hasFile('attachments') && !$request->hasFile('attachmentRefs')) {
            return response(['errors' => 'attachments and attachmentRefs are required'], 422);
        }

        $dyo = Dyo::create([
            'name' => $request->name,
            'email' => $request->email,
            'team' => $request->team,
            'phone' => $request->phone,
            'id_ref' => $request->id_ref,
            'description' => $request->description,
            'status' => 1,
        ]);

        // tambah attachmet
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments'), $filename);
                Attachment::create([
                    'pathname' => $filename,
                    'id_dyo' => $dyo->id
                ]);
            }
        }

        $attachment = Attachment::where('id_dyo', $dyo->id)->get();

        // tambah attachmentRef
        if ($request->hasFile('attachmentRefs')) {
            foreach ($request->file('attachmentRefs') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachmentRefs'), $filename);
                AttachmentRef::create([
                    'pathname' => $filename,
                    'id_dyo' => $dyo->id
                ]);
            }
        }

        $attachmentRef = AttachmentRef::where('id_dyo', $dyo->id)->get();

        // hasil akhir
        $data = [
            "dyo" => $dyo,
            "attachment" => $attachment,
            "attachmentRef" => $attachmentRef
        ];

        return new DyoResource('success', 'Dyo created successfully', $data);
    }

    public function update(Request $request, $id)
    {
        // Validasi input status
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Cari Dyo berdasarkan ID
        $dyo = Dyo::find($id);

        // Jika Dyo tidak ditemukan, return error
        if (!$dyo) {
            return response()->json(['error' => 'Dyo not found!'], 404);
        }

        // Update status Dyo
        $dyo->update(['status' => $request->status]);
        return new DyoResource("success", "Dyo status updated", $dyo);
    }
}
