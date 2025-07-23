<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    use  HasFactory;

    protected $table = 'role'; // Nama tabel
    protected $primaryKey = 'id_role'; // Primary key
    public $incrementing = true; // Primary key auto-increment
    protected $keyType = 'int'; // Tipe data primary key

    protected $fillable = ['nama_role']; // Kolom yang bisa diisi

    public $timestamps = true; // Aktifkan created_at dan updated_at

    public function user()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}


