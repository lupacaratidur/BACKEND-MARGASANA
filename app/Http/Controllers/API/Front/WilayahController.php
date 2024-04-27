<?php

namespace App\Http\Controllers\API\Front;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Inertia\Inertia;

class WilayahController extends Controller
{
    public function index()
    {
        return Inertia::render('Wilayah');
    }
}