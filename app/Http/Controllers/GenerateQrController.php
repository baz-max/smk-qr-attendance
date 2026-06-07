<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Str;
use PDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class GenerateQrController extends Controller
{
    public function index()
    {
        return view('generate-qr.index');
    }

    public function generateFromCsv(Request $request)
    {
        // VALIDASI FILE
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        // BACA CSV
        $rows = array_map('str_getcsv', file($request->file('csv_file')));
        unset($rows[0]); // hapus header

        $nisList = [];

        foreach ($rows as $row) {
            /*
             | CSV FORMAT:
             | row[0] = NIS
             | row[1] = Nama
             | row[2] = Kelas
             | row[3] = Jurusan
             */

            $siswa = Siswa::updateOrCreate(
                ['nisn' => trim($row[0])],
                [
                    'nama'               => trim($row[1]),
                    'jenis_kelamin'      => trim($row[2]),
                    'kelas'              => trim($row[3]),
                    'jurusan'            => trim($row[4]),
                    'qr_token'           => Str::uuid(), // regenerate token
                ]
            );

            $nisList[] = $siswa->nisn;
        }

        $siswas = Siswa::whereIn('nisn', $nisList)->get();

        // ============================
        // GENERATE QR BASE64
        // ============================
        $writer = new PngWriter();

        foreach ($siswas as $siswa) {
            $qr = QrCode::create('ABSEN:' . $siswa->qr_token)
                ->setSize(200)
                ->setMargin(10);

            $result = $writer->write($qr);
            $siswa->qr_base64 = $result->getDataUri();
        }

        // ============================
        // GENERATE PDF
        // ============================
        $pdf = PDF::loadView('generate-qr.pdf', compact('siswas'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('qr-siswa.pdf');
    }
}
