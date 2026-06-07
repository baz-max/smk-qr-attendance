<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = ['10', '11', '12'];

        foreach ($kelasList as $kelas) {
            for ($i = 1; $i <= 30; $i++) {
                Siswa::create([
                    'nis' => $kelas . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama' => "Siswa $kelas-$i",
                    'kelas' => $kelas,
                    'qr_token' => Str::uuid(),
                ]);
            }
        }
    }
}
