<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // ==============================
    // 1. HALAMAN ABSEN + FILTER KELAS
    // ==============================
    public function index(Request $request)
{
    $tipe = $request->route('tipe') ?? $request->query('tipe');
    $filterKelas = $request->kelas;

    $query = Absensi::with('siswa')
    ->orderBy('tanggal', 'desc')
    ->orderByRaw("
    CASE status
        WHEN 'masuk' THEN 1
        WHEN 'keluar' THEN 2
        WHEN 'izin' THEN 3
        WHEN 'sakit' THEN 4
        WHEN 'bolos' THEN 5
        WHEN 'alfa' THEN 6
    END
")

    ->orderBy('jam', 'desc');


   if ($tipe === 'masuk') {
    $query->whereIn('status', ['masuk', 'izin', 'sakit', 'bolos', 'alfa']);
    } elseif ($tipe === 'keluar') {
        $query->whereIn('status', ['keluar', 'izin', 'sakit', 'bolos', 'alfa']);
    }

    if ($filterKelas) {
        $query->whereHas('siswa', function ($q) use ($filterKelas) {
            $q->where('kelas', $filterKelas);
        });
    }

    $data = $query->get();

    return view('siswa.absen', [
        'data' => $data,
        'filterKelas' => $filterKelas,
        'tipe' => $tipe
    ]);
} // ✅ INI YANG TADI KURANG


    // ==============================
    // 2. SCAN QR
    // ==============================
    public function scan($token)
{
    $siswa = Siswa::where('qr_token', $token)->firstOrFail();
    $today = now()->toDateString();

    // ambil absensi hari ini (jika ada)
    $absen = Absensi::where('siswa_id', $siswa->id)
        ->where('tanggal', $today)
        ->first();

    // ======================
    // SCAN PERTAMA → MASUK
    // ======================
    if (!$absen) {
        Absensi::create([
            'siswa_id' => $siswa->id,
            'tanggal'  => $today,
            'jam'      => now()->format('H:i:s'),
            'status'   => 'masuk',
            'jurusan'  => 'RPL',
        ]);

        return view('scan.result', [
            'status' => 'masuk',
            'siswa'  => $siswa
        ]);
    }

    // ======================
    // SCAN KEDUA → KELUAR
    // ======================
    if ($absen->status === 'masuk') {
        $absen->update([
            'status' => 'keluar'
            // jam keluar BELUM disimpan karena tabel belum ada
        ]);

        return view('scan.result', [
            'status' => 'keluar',
            'siswa'  => $siswa
        ]);
    }

    // ======================
    // SUDAH MASUK & KELUAR
    // ======================
    return view('scan.result', [
        'status' => 'sudah',
        'siswa'  => $siswa
    ]);
}


    // ==============================
    // 3. API UNTUK SCAN
    // ==============================
    public function apiScan($token)
{
    $siswa = Siswa::where('qr_token', $token)->first();

    if (!$siswa) {
        return response()->json([
            'success' => false,
            'message' => 'QR tidak valid'
        ], 404);
    }

    $today = now()->toDateString();

    $sudahMasuk = Absensi::where('siswa_id', $siswa->id)
        ->where('tanggal', $today)
        ->where('status', 'masuk')
        ->exists();

    $sudahKeluar = Absensi::where('siswa_id', $siswa->id)
        ->where('tanggal', $today)
        ->where('status', 'keluar')
        ->exists();

    return response()->json([
        'success' => true,
        'siswa' => [
            'id'    => $siswa->id,
            'nisn'   => $siswa->nisn,
            'nama'  => $siswa->nama,
            'kelas' => $siswa->kelas,
        ],
        'sudahMasuk'  => $sudahMasuk,
        'sudahKeluar' => $sudahKeluar,
    ]);
}


    // ==============================
    // 4. SIMPAN ABSENSI MANUAL/FORM
    // ==============================
    public function simpan(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:siswas,id',
        'tanggal'  => 'required|date',
        'status'   => 'required',
    ]);

    Absensi::create([
        'siswa_id' => $request->siswa_id,
        'tanggal'  => $request->tanggal,
        'status'   => $request->status,
        'jam'      => $request->jam ?? now()->format('H:i:s'),
        'jurusan'  => 'RPL',
    ]);

    return redirect()
    ->route('absen.index', request('tipe'))
    ->with('success', 'Absensi berhasil disimpan!');


}

public function cekStatus($token)
{
    $siswa = Siswa::where('qr_token', $token)->first();

    if (!$siswa) {
        return response()->json([
            'status' => 'error'
        ], 404);
    }

    $today = now()->toDateString();

    $sudahMasuk = Absensi::where('siswa_id', $siswa->id)
        ->where('tanggal', $today)
        ->where('status', 'masuk')
        ->exists();

    $sudahKeluar = Absensi::where('siswa_id', $siswa->id)
        ->where('tanggal', $today)
        ->where('status', 'keluar')
        ->exists();

    return response()->json([
        'status' => 'ok',
        'siswa' => [
            'id'    => $siswa->id,
            'nisn'   => $siswa->nisn,
            'nama'  => $siswa->nama,
            'kelas' => $siswa->kelas,
        ],
        'sudahMasuk'  => $sudahMasuk,
        'sudahKeluar' => $sudahKeluar,
    ]);
}

}