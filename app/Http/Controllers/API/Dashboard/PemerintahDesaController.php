<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\PemerintahDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PemerintahDesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemerintah_desa = PemerintahDesa::all();
        return response()->json($pemerintah_desa);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'foto' => 'required|image',
            'jabatan' => 'required',
        ]);

        $data = $request->all();
        $data['foto'] = $request->file('foto')->store('pemerintah_desa', 'public');

        $pemerintah_desa = PemerintahDesa::create($data);

        return response()->json($pemerintah_desa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PemerintahDesa $pemerintah_desa, $id)
    {
        $pemerintah_desa = PemerintahDesa::findOrFail($id);
        return response()->json($pemerintah_desa, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemerintahDesa $pemerintah_desa)
    {
        $request->validate([
            'foto' => 'nullable|image',
            'nama' => 'required',
            'jabatan' => 'required',
        ]);

        if ($request->hasFile('foto')) {
            // Menghapus foto lama jika ada
            if ($pemerintah_desa->foto) {
                Storage::delete('public/' . $pemerintah_desa->foto);
            }

            // Menyimpan foto baru
            $foto = $request->file('foto')->store('pemerintah_desa', 'public');
        } else {
            // Jika tidak ada foto yang diunggah, gunakan foto yang sudah ada
            $foto = $pemerintah_desa->foto;
        }

        $pemerintah_desa->update([
            'foto' => $foto,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
        ]);

        return response()->json($pemerintah_desa, 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PemerintahDesa $pemerintah_desa)
    {
        Storage::delete('public/' . $pemerintah_desa->foto);
        $pemerintah_desa->delete();

        return response()->json(['message' => 'Berhasil hapus pegawai'], 200);
    }
}
