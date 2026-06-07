<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa.index', [
            'kelasAktif' => null,
            'data' => collect()
        ]);
    }

    public function kelas($kelas)
    {
        $today = Carbon::today();

        $data = Siswa::where('kelas', $kelas)
            ->with(['absensis' => function ($q) use ($today) {
                $q->whereDate('tanggal', $today);
            }])
            ->get();

        return view('siswa.index', [
            'kelasAktif' => $kelas,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nisn' => 'required|unique:siswas,nisn',
            'kelas' => 'required',
        ]);

        Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'token_masuk' => Str::uuid(),
            'token_keluar' => Str::uuid(),
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan');
    }

    // =========================
    // IMPORT CSV
    // =========================

    public function importForm()
    {
        return view('siswa.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file));

        // hapus header
        unset($rows[0]);

        foreach ($rows as $row) {

            // skip kalau NISN sudah ada
            if (Siswa::where('nisn', $row[0])->exists()) {
                continue;
            }

            Siswa::create([
                'nisn' => $row[0],
                'nama' => $row[1],
                'kelas' => $row[2],
                'qr_token' => Str::uuid(),
                'token_masuk' => Str::uuid(),
                'token_keluar' => Str::uuid(),
            ]);
        }

        return redirect('/data-siswa')
            ->with('success', 'Import CSV berhasil 🎉');
    }
    public function generatePage()
    {
        return view('siswa.generate-qr');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv_file'), 'r');

        $importedIds = [];

        while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {

            // skip header
            if ($row[0] === 'nisn') continue;

            if (Siswa::where('nisn', $row[0])->exists()) {
                continue;
            }

            $siswa = Siswa::create([
                'nisn' => $row[0],
                'nama' => $row[1],
                'kelas' => $row[2],
                'token_masuk' => Str::uuid(),
                'token_keluar' => Str::uuid(),
            ]);

            $importedIds[] = $siswa->id;
        }

        fclose($file);

        return redirect('/generate-qr')
            ->with('success', 'Import CSV berhasil 🎉')
            ->with('imported_ids', $importedIds);
    }

}
