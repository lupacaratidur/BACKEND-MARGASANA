<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;
    protected $table = 'galeri';
    protected $fillable = ['judul', 'slug', 'gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5', 'gambar6',];

    protected $hidden = [];
}