<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSiswa extends Model
{
    protected $table = 'siswas'; // WAJIB karena nama model beda

    protected $fillable = [
        'nisn',
        'nama',
        'jenis_kelamin',
        'kelas_id',
        'qr_token'
    ];

    public function kelasRelasi()
{
    return $this->belongsTo(Kelas::class, 'kelas_id');
}

}
