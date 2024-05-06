<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\BPD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BPDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bpd = BPD::all();
        return response()->json([
            'status' => 'success',
            'data' => $bpd,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $bpd = BPD::create([
            'nama' => $request->input('nama'),
            'jabatan' => $request->input('jabatan'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data BPD berhasil ditambahkan',
            'data' => $bpd,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bpd = BPD::find($id);

        if (!$bpd) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data BPD tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $bpd,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jabatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $bpd = BPD::find($id);

        if (!$bpd) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data BPD tidak ditemukan',
            ], 404);
        }


        $bpd->nama = $request->input('nama');
        $bpd->jabatan = $request->input('jabatan');
        $bpd->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data BPD berhasil diperbarui',
            'data' => $bpd,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bpd = BPD::find($id);

        if (!$bpd) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data BPD tidak ditemukan',
            ], 404);
        }

        $bpd->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data BPD berhasil dihapus',
        ]);
    }
}
