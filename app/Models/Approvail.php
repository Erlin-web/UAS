<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approvail extends Model
{
    protected $table = 'approvail'; // Set nama tabel
    protected $primaryKey = 'id_approvail'; // Set primary key sebagai NIM
    public $incrementing = true;  // Non-increment karena NIM adalah string
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = ['id_pendaftaran', 'id_verifikator', 'status', 'catatan'];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    // Relasi ke Verifikator
    public function verifikator()
    {
        return $this->belongsTo(Verifikator::class, 'id_verifikator', 'id_verifikator');
    }
}
