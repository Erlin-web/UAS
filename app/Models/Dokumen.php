<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen'; // Set nama tabel
    protected $primaryKey = 'id_dokumen'; // Set primary key sebagai NIM
    public $incrementing = true;  // Non-increment karena NIM adalah string
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = ['id_pendaftaran', 'nama_file'];



    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

}
