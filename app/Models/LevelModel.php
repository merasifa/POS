<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Nama tabel di database
    protected $primaryKey = 'level_id'; // Ubah primary key ke 'level_id'
    public $timestamps = true; // Jika tabel tidak memiliki created_at dan updated_at

    protected $fillable = [
        'level_kode',
        'level_nama'
    ];
}