<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Tanggapan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function index()
    {
        return view('tanggapan/index', [
            'title' => 'Semua Tanggapan',
            'tanggapan' => Tanggapan::all()
        ]);
    }

    public function destroy($id)
    {
        $tanggapan = Tanggapan::find($id);
        if (!$tanggapan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tanggapan tidak ditemukan',
            ], 404);
        }

        $tanggapan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data tanggapan berhasil dihapus',
        ]);
    }
}
