<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user'; // Set nama tabel
    protected $primaryKey = 'id_user'; // Set primary key sebagai NIM
    public $incrementing = true;  // Non-increment karena NIM adalah string
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = ['id_role', 'username', 'email', 'password', 'otp', 'otp_expires_at', 'email_verified_at'];

    protected $hidden = ['password']; // Sembunyikan password dalam response
    public $timestamps = true; // Mengaktifkan created_at & updated_at
    protected $casts = [
        'otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_user', 'id_user');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function persetujuan()
    {
        return $this->hasMany(Persetujuan::class, 'id_user', 'id_user');
    }

}
