<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class BeritaController extends Controller
{
    // Existing methods...

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $berita = Berita::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($berita);
    }

    public function create()
    {
        return view('berita.create', [
            'title' => 'Tambah Berita',
        ]);
    }

    public function edit($id)
    {
        $berita = Berita::find($id);
        return view('berita.edit', [
            'berita' => $berita,
            'title' => 'Edit Berita',


        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $berita, $id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json($berita);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Pastikan pengguna telah terautentikasi sebelum mengakses properti 'nama'
        if (auth()->check()) {
            $validator = Validator::make($request->all(), [
                'gambar' => 'required|image',
                'judul' => 'required',
                'deskripsi' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $data = $request->all();
            $data['slug'] = Str::slug($request->judul);
            // Periksa apakah pengguna telah terautentikasi sebelum mengakses properti 'nama'
            $data['user_name'] = auth()->user() ? auth()->user()->nama : 'Guest';
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');

            $berita = Berita::create($data);

            return response()->json($berita, 201);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $berita)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'nullable|image',
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'slug' => Str::slug($request->judul),
            'user_name' => auth()->user()->nama,
        ];

        // Update gambar hanya jika ada file yang diunggah
        if ($request->File('gambar')) {
            // Hapus gambar lama dari penyimpanan
            Storage::delete('public/' . $berita->gambar);

            // Simpan gambar baru dan simpan path-nya ke dalam data
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        // Lakukan pembaruan data
        $berita->update($data);

        return response()->json($berita, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        Storage::delete('public/' . $berita->gambar);
        $berita->delete();

        return response()->json(['message' => 'Berhasil menghapus berita'], 200);
    }
}
