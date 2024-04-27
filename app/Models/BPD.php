<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BPD extends Model
{
    use HasFactory;
    protected $table = 'bpd';
    protected $fillable = ['nama', 'jabatan'];

    protected $hidden = [];
}
