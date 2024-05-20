<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $galeri = Galeri::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($galeri, 200);
    }

    public function show(Galeri $galeri, $id)
    {
        $galeri = Galeri::findOrFail($id);
        return response()->json($galeri);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'gambar1' => 'required|image',
            'gambar2' => 'required|image',
            'gambar3' => 'required|image',
            'gambar4' => 'required|image',
            'gambar5' => 'required|image',
            'gambar6' => 'required|image',
        ], [
            'gambar1.required' => 'Gambar wajib diisi',
            'gambar2.required' => 'Gambar wajib diisi',
            'gambar3.required' => 'Gambar wajib diisi',
            'gambar4.required' => 'Gambar wajib diisi',
            'gambar5.required' => 'Gambar wajib diisi',
            'gambar6.required' => 'Gambar wajib diisi',
            'gambar1.image' => 'Gambar wajib diisi',
            'judul.required' => 'Judul wajib diisi',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->judul);
        $data['gambar1'] = $request->file('gambar1')->store('galeri', 'public');
        $data['gambar2'] = $request->file('gambar2')->store('galeri', 'public');
        $data['gambar3'] = $request->file('gambar3')->store('galeri', 'public');
        $data['gambar4'] = $request->file('gambar4')->store('galeri', 'public');
        $data['gambar5'] = $request->file('gambar5')->store('galeri', 'public');
        $data['gambar6'] = $request->file('gambar6')->store('galeri', 'public');

        Galeri::create($data);

        return response()->json(['message' => 'Berhasil menambahkan foto'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'gambar1' => 'nullable|image',
            'gambar2' => 'nullable|image',
            'gambar3' => 'nullable|image',
            'gambar4' => 'nullable|image',
            'gambar5' => 'nullable|image',
            'gambar6' => 'nullable|image',
        ], [
            'gambar1.image' => 'File harus berupa gambar.',
            'judul.required' => 'Judul wajib diisi.',
        ]);

        $galeri = Galeri::findOrFail($id);
        $oldGambar1 = $galeri->gambar1;
        $oldGambar2 = $galeri->gambar2;
        $oldGambar3 = $galeri->gambar3;
        $oldGambar4 = $galeri->gambar4;
        $oldGambar5 = $galeri->gambar5;
        $oldGambar6 = $galeri->gambar6;

        $galeri->judul = $request->judul;
        $galeri['slug'] = Str::slug($request->judul);

        if ($request->hasFile('gambar1')) {
            if ($oldGambar1) {
                Storage::delete('public/' . $oldGambar1);
            }
            $galeri->gambar1 = $request->file('gambar1')->store('galeri', 'public');
        }
        if ($request->hasFile('gambar2')) {
            if ($oldGambar2) {
                Storage::delete('public/' . $oldGambar2);
            }
            $galeri->gambar2 = $request->file('gambar2')->store('galeri', 'public');
        }
        if ($request->hasFile('gambar3')) {
            if ($oldGambar3) {
                Storage::delete('public/' . $oldGambar3);
            }
            $galeri->gambar3 = $request->file('gambar3')->store('galeri', 'public');
        }
        if ($request->hasFile('gambar4')) {
            if ($oldGambar4) {
                Storage::delete('public/' . $oldGambar4);
            }
            $galeri->gambar4 = $request->file('gambar4')->store('galeri', 'public');
        }
        if ($request->hasFile('gambar5')) {
            if ($oldGambar5) {
                Storage::delete('public/' . $oldGambar5);
            }
            $galeri->gambar5 = $request->file('gambar5')->store('galeri', 'public');
        }
        if ($request->hasFile('gambar6')) {
            if ($oldGambar6) {
                Storage::delete('public/' . $oldGambar6);
            }
            $galeri->gambar6 = $request->file('gambar6')->store('galeri', 'public');
        }

        $galeri->save();

        return response()->json([
            'message' => 'Berhasil mengubah data',
            'galeri' => $galeri,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galeri $galeri)
    {
        Storage::delete('public/' . $galeri->gambar1);
        Storage::delete('public/' . $galeri->gambar2);
        Storage::delete('public/' . $galeri->gambar3);
        Storage::delete('public/' . $galeri->gambar4);
        Storage::delete('public/' . $galeri->gambar5);
        Storage::delete('public/' . $galeri->gambar6);
        $galeri->delete();

        return response()->json(['message' => 'Berhasil hapus galeri'], 200);
    }
}
