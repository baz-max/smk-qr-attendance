<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
    'nisn',
    'nama',
    'jenis_kelamin',
    'kelas',
    'jurusan',
    'qr_token'
];


    public function absensis()
{
    return $this->hasMany(\App\Models\Absensi::class);
}
}
