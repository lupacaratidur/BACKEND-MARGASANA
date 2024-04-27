<?php

namespace App\Http\Controllers\API\Front;


use App\Http\Controllers\Controller;
use App\Models\PemerintahDesa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PemerintahDesaController extends Controller
{
    public function index()
    {

        return Inertia::render('PemerintahDesa');
    }

    public function list()
    {
        return PemerintahDesa::all();
    }
}