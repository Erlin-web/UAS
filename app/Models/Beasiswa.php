<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Beasiswa extends Model
{
    use HasFactory;

    protected $table = 'beasiswa'; // Set nama tabel
    protected $primaryKey = 'id_beasiswa'; // Set primary key sebagai NIM
    public $incrementing = true;  // Non-increment karena NIM adalah string
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = ['nama_beasiswa', 'deskripsi', 'persyaratan'];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_beasiswa', 'id_beasiswa');
    }
}
