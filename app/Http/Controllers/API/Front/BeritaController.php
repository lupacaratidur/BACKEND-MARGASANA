<?php

namespace App\Http\Controllers\API\Front;


use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

use Inertia\Inertia;

class BeritaController extends Controller
{
    public function index()
    {
        return Inertia::render('Berita');
    }



    public function list()
    {
        $berita = Berita::orderBy('created_at', 'desc')->get();
        return $berita;
    }


    public function detail($slug)
    {
        $berita = Berita::where('slug', $slug)->first();

        // Jika berita tidak ditemukan, berikan respons yang sesuai
        if (!$berita) {
            return response()->json(['error' => 'Berita tidak ditemukan'], 404);
        }
        return Inertia::render('DetailBerita', [
            'berita' => $berita
        ]);
    }
}