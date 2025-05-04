<?php

namespace App\Models;

use App\Models\StokModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'level_id',
        'username',
        'nama',
        'password',
        'profile_picture',
    ];

    //Relasi dengan tabel level
    public function level():BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
    // Relasi dengan tabel stok
    public function stok()
    {
        return $this->hasMany(StokModel::class, 'user_id', 'user_id');
    }
    // Mendapatkan nama role
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }
    // Cek apakah user memiliki role tertentu
    public function hasRole(string $role): bool
    {
        return $this->level->level_kode === $role;
    }
    public function getRole() {
        return $this->level->level_kode;
    }
}