<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalSiswa = Siswa::count();

        $hadir = Absensi::whereDate('tanggal', $today)
            ->where('status', 'masuk')
            ->distinct('siswa_id')
            ->count('siswa_id');

        $izin = Absensi::whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->distinct('siswa_id')
            ->count('siswa_id');

        $alpha = max($totalSiswa - ($hadir + $izin), 0);

        $persentase = $totalSiswa > 0
            ? round(($hadir / $totalSiswa) * 100)
            : 0;

        return view('dashboard', compact(
            'totalSiswa',
            'hadir',
            'izin',
            'alpha',
            'persentase'
        ));
    }
}
