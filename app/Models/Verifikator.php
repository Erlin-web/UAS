<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Verifikator extends Model
{
    use HasFactory;

    protected $table = 'verifikator'; // Set nama tabel
    protected $primaryKey = 'id_verifikator'; // Set primary key sebagai NIM
    public $incrementing = true;  // Non-increment karena NIM adalah string
    protected $keyType = 'int'; // Tipe data primary key
    protected $fillable = ['id_user', 'tahapan', 'jabatan', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function approvail()
    {
        return $this->hasMany(Approvail::class, 'id_verifikator', 'id_verifikator');
    }
}
