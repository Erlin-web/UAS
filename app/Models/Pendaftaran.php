<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran'; // Set nama tabel
    protected $primaryKey = 'id_pendaftaran'; // Set primary key sebagai NIM
    public $incrementing = true;  // Non-increment karena NIM adalah string
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = ['id_pendaftaran', 'id_user', 'id_beasiswa', 'kode', 'telp', 'alamat'];



    public function persetujuan()
    {
        return $this->hasMany(Persetujuan::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function beasiswa() {
        return $this->belongsTo(Beasiswa::class, 'id_beasiswa');
    }

    public function list_universitas() {
        return $this->belongsTo(ListUniversitas::class, 'kode', 'kode');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function approvail()
    {
        return $this->hasMany(Approvail::class, 'id_pendaftaran', 'id_pendaftaran');
    }

}
