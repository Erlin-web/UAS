<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ListUniversitas extends Model
{
    use HasFactory;

    protected $table = 'list_universitas'; // Set nama tabel
    protected $primaryKey = 'kode'; // Set primary key sebagai NIM
    public $incrementing = false;  // Non-increment karena NIM adalah string
    protected $keyType = 'string'; // Tipe data primary key
    protected $fillable = ['kode', 'nama_universitas', 'alamat_universitas'];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'kode', 'kode');
    }
}
