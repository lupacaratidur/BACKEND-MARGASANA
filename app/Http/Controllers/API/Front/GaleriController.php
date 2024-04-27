<?php

namespace App\Http\Controllers\API\Front;


use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

use Inertia\Inertia;

class GaleriController extends Controller
{
    public function index()
    {
        return Inertia::render('Galeri');
    }
    public function list()
    {
        $galeri = Galeri::orderBy('created_at', 'desc')->get();
        return $galeri;
    }


    public function detail($slug)
    {
        $galeri = Galeri::where('slug', $slug)->first();

        // Jika galeri tidak ditemukan, berikan respons yang sesuai
        if (!$galeri) {
            return response()->json(['error' => 'Berita tidak ditemukan'], 404);
        }
        return Inertia::render('DetailGaleri', [
            'galeri' => $galeri
        ]);
    }
}