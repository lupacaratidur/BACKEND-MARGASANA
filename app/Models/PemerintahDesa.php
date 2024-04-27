<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemerintahDesa extends Model
{
    use HasFactory;
    protected $table = 'pemerintah_desa';
    protected $fillable = ['nama', 'foto', 'jabatan'];

    protected $hidden = [];
}