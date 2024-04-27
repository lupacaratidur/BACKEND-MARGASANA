<?php

namespace App\Http\Controllers\API\Front;

use App\Http\Controllers\Controller;

use App\Models\Beranda;
use Illuminate\Http\Request;
use Inertia\Inertia;


class BerandaController extends Controller
{
  public function index()
  {
    return Inertia::render('Beranda');
  }
}