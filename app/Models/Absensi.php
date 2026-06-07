<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['siswa_id', 'tanggal', 'jam', 'status', 'jurusan']; // <-- tambahkan jurusan

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
