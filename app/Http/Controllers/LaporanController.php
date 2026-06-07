<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Exports\LaporanHarianExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * ============================
     * HALAMAN LAPORAN (AKUMULASI)
     * ============================
     */
    public function index(Request $request)
{
    $mode    = $request->mode ?? 'harian';
    $kelas   = $request->kelas;
    $tanggal = $request->tanggal ?? now()->toDateString();
    $bulan   = $request->bulan ?? now()->format('Y-m');
    $tahun   = $request->tahun ?? now()->year;

    // =========================
    // FILTER ABSENSI
    // =========================
    $absensiQuery = Absensi::query();

    if ($mode === 'harian') {
        $absensiQuery->whereDate('tanggal', $tanggal);
    } 
    elseif ($mode === 'mingguan') {
        $start = \Carbon\Carbon::parse($tanggal)->startOfWeek();
        $end   = \Carbon\Carbon::parse($tanggal)->endOfWeek();
        $absensiQuery->whereBetween('tanggal', [$start, $end]);
    } 
    elseif ($mode === 'bulanan') {
        $carbon = \Carbon\Carbon::createFromFormat('Y-m', $bulan);
        $absensiQuery->whereMonth('tanggal', $carbon->month)
                     ->whereYear('tanggal', $carbon->year);
    } 
    elseif ($mode === 'tahunan') {
        $absensiQuery->whereYear('tanggal', $tahun);
    }

    $absensi = $absensiQuery->get();

    // =========================
    // AMBIL SISWA
    // =========================
    $siswaQuery = Siswa::query();

    if ($kelas) {
        $siswaQuery->where('kelas', $kelas);
    }

    $siswas = $siswaQuery->get();

    // =========================
    // BUAT LAPORAN PER SISWA
    // =========================
    $laporan = $siswas->map(function ($siswa) use ($absensi, $mode, $tanggal) {

    $dataSiswa = $absensi->where('siswa_id', $siswa->id);

    $hadir = 0;
    $izin  = 0;
    $sakit = 0;
    $bolos = 0;
    $alpha = 0;

    if ($mode === 'harian') {

        if ($dataSiswa->isEmpty()) {
            $alpha = 1; // 🔥 tidak absen sama sekali
        } else {

            $jamMasuk  = optional($dataSiswa->where('status', 'masuk')->first())->jam;
            $jamPulang = optional($dataSiswa->where('status', 'keluar')->first())->jam;

            if ($dataSiswa->where('status', 'sakit')->isNotEmpty()) {
                $sakit = 1;
            } 
            elseif ($dataSiswa->where('status', 'izin')->isNotEmpty()) {
                $izin = 1;
            }
            elseif ($jamMasuk && $jamPulang) {
                $hadir = 1;
            }
            elseif ($jamMasuk && !$jamPulang) {
                $bolos = 1;
            }
            else {
                $alpha = 1;
            }
        }
    }

    return [
        'siswa'  => $siswa,
        'hadir'  => $hadir,
        'izin'   => $izin,
        'sakit'  => $sakit,
        'bolos'  => $bolos,
        'alpha'  => $alpha,
        'detail' => collect(), // sementara kosong untuk harian
    ];
});


    // =========================
    // LIST KELAS
    // =========================
    $kelasList = Siswa::select('kelas')
        ->distinct()
        ->orderBy('kelas')
        ->pluck('kelas');



    // =========================
// REKAP TOTAL SEMUA SISWA
// =========================
$rekap = [
    'hadir' => $laporan->sum('hadir'),
    'izin'  => $laporan->sum('izin'),
    'sakit' => $laporan->sum('sakit'),
    'bolos' => $laporan->sum('bolos'),
    'alpha' => $laporan->sum('alpha'),
];

    return view('laporan.index', compact(
    'laporan',
    'rekap',        // 🔥 tambahkan ini
    'kelasList',
    'tanggal',
    'kelas'
));

}


    /*
    =========================================
    EXPORT EXCEL
    =========================================
    */

    public function exportExcel(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();
        $kelas   = $request->kelas;

        return Excel::download(
            new LaporanHarianExport($tanggal, $kelas),
            'laporan-absensi-' . $tanggal . '.xlsx'
        );
    }

    /*
    =========================================
    EXPORT PDF
    =========================================
    */

    public function exportPdf(Request $request)
{
    $tanggal = $request->tanggal ?? now()->toDateString();
    $kelas   = $request->kelas;

    // =========================
    // AMBIL SISWA (FILTER KELAS)
    // =========================
    $siswaQuery = Siswa::query();

    if ($kelas) {
        $siswaQuery->where('kelas', $kelas);
    }

    $siswas = $siswaQuery->get();

    // =========================
    // AMBIL ABSENSI SESUAI TANGGAL
    // =========================
    $absensi = Absensi::whereDate('tanggal', $tanggal)->get();

    // =========================
    // BUAT LAPORAN PER SISWA
    // =========================
    $laporan = $siswas->map(function ($siswa) use ($absensi) {

        $dataSiswa = $absensi->where('siswa_id', $siswa->id);

        $hadir = 0;
        $izin  = 0;
        $sakit = 0;
        $bolos = 0;
        $alpha = 0;

        if ($dataSiswa->isNotEmpty()) {

            $jamMasuk  = optional($dataSiswa->where('status', 'masuk')->first())->jam;
            $jamPulang = optional($dataSiswa->where('status', 'keluar')->first())->jam;

            if ($dataSiswa->where('status', 'sakit')->isNotEmpty()) {
                $sakit = 1;
            } 
            elseif ($dataSiswa->where('status', 'izin')->isNotEmpty()) {
                $izin = 1;
            }
            elseif ($jamMasuk && $jamPulang) {
                $hadir = 1;
            }
            elseif ($jamMasuk && !$jamPulang) {
                $bolos = 1;   // 🔥 masuk tapi belum keluar
            }
            else {
                $alpha = 1;   // 🔥 default semua siswa
            }


        } else {
            // Tidak ada absensi sama sekali
            $alpha = 1;
        }

        return [
            'siswa'  => $siswa,
            'hadir'  => $hadir,
            'izin'   => $izin,
            'sakit'  => $sakit,
            'bolos'  => $bolos,
            'alpha'  => $alpha,
        ];
    });

    // =========================
    // GENERATE PDF
    // =========================
    $pdf = Pdf::loadView('laporan.harian_pdf', [
        'laporan' => $laporan,
        'tanggal' => $tanggal,
        'kelas'   => $kelas,
    ])->setPaper('A4', 'portrait');

    return $pdf->download('laporan-absensi-' . $tanggal . '.pdf');
}


}
