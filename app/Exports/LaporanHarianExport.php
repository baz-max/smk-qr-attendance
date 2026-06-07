<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanHarianExport implements FromCollection, WithHeadings
{
    protected $tanggal;
    protected $kelas;

    public function __construct($tanggal, $kelas = null)
    {
        $this->tanggal = $tanggal;
        $this->kelas = $kelas;
    }

    public function collection()
{
    $query = Absensi::with('siswa')
        ->whereDate('tanggal', $this->tanggal);

    if ($this->kelas) {
        $query->whereHas('siswa', function ($q) {
            $q->where('kelas', $this->kelas);
        });
    }

    $data = $query->get()->groupBy('siswa_id');

    return $data->map(function ($items) {

        $masuk  = $items->firstWhere('status', 'masuk');
        $keluar = $items->firstWhere('status', 'keluar');

        // 🔑 LOGIKA STATUS (SAMA DENGAN PDF)
        if ($items->where('status', 'izin')->isNotEmpty()) {
            $status = 'IZIN';
        } elseif ($masuk && $keluar) {
            $status = 'HADIR';
        } else {
            $status = 'ALPHA';
        }

        return [
            'nama'       => $items->first()->siswa->nama ?? '-',
            'kelas'      => $items->first()->siswa->kelas ?? '-',
            'jam_masuk'  => $masuk?->jam ?? '-',
            'jam_keluar' => $keluar?->jam ?? '-',
            'status'     => $status, // ⬅️ TAMBAHAN
        ];
    })->values();
}



    public function headings(): array
{
    return ['Nama', 'Kelas', 'Jam Masuk', 'Jam Keluar', 'Status'];
}

}
