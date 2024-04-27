<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    use HasFactory;
    protected $table = 'berita';
    protected $fillable = ['judul', 'slug', 'gambar', 'deskripsi', 'user_name'];

    protected $hidden = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_name', 'nama');
    }
}
