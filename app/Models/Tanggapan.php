<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    protected $table = 'tanggapan';
    protected $guarded = ['id'];
    public function pengaduan() {
        return $this->belongsTo(Pengaduan::class);
    }
    public function petugas() {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
}
