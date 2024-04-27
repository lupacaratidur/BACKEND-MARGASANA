<?php

namespace App\Http\Controllers\API\Front;


use App\Http\Controllers\Controller;
use App\Models\BPD;
use Illuminate\Http\Request;

use Inertia\Inertia;

class BPDController extends Controller
{
    public function index()
    {
        return Inertia::render('BPD');
    }

    public function list()
    {
        return BPD::all();
    }
}