<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\GenerateQrController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\AuthController;
use App\Exports\LaporanHarianExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| HALAMAN UMUM
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});
/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showAuth'])->name('login');
Route::get('/register', [AuthController::class, 'showAuth'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| HALAMAN VIEW (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/scan', fn () => view('scan'));
    Route::get('/token', fn () => view('token'));
    Route::get('/siswa', fn () => view('siswa'));
    Route::get('/kelas', fn () => view('kelas'));
    Route::get('/user', fn () => view('user'));

});

/*
|--------------------------------------------------------------------------
| DATA SISWA
|--------------------------------------------------------------------------
*/
Route::get('/data-siswa', [SiswaController::class, 'index']);
Route::get('/data-siswa/{kelas}', [SiswaController::class, 'kelas']);

Route::post('/siswa', [SiswaController::class, 'store'])
    ->name('siswa.store');

Route::get('/siswa/{id}/generate-token', [SiswaController::class, 'generateToken']);

Route::get('/siswa/{id}/qr', function ($id) {
    $siswa = Siswa::findOrFail($id);
    return view('siswa.qr', compact('siswa'));
});

/*
|--------------------------------------------------------------------------
| IMPORT SISWA (CSV)
|--------------------------------------------------------------------------
*/
Route::get('/siswa/import', [SiswaController::class, 'importForm'])
    ->name('siswa.import.form');

Route::post('/siswa/import', [SiswaController::class, 'import'])
    ->name('siswa.import');

/*
|--------------------------------------------------------------------------
| ABSENSI & SCAN
|--------------------------------------------------------------------------
*/
Route::get('/api/siswa/token/{token}', function ($token) {
    $siswa = Siswa::where('qr_token', $token)->first();

    if (!$siswa) {
        return response()->json(['message' => 'QR tidak valid'], 404);
    }

    return response()->json([
        'nis'  => $siswa->nis,
        'nama' => $siswa->nama,
    ]);
});

Route::get('/api/scan/{token}', [AbsensiController::class, 'apiScan']);

Route::post('/absen/simpan', [AbsensiController::class, 'simpan'])
    ->name('absen.simpan');

Route::get('/absen/{tipe}', [AbsensiController::class, 'index'])
    ->whereIn('tipe', ['masuk', 'keluar'])
    ->name('absen.index');

Route::get('/absen', [AbsensiController::class, 'index']);

Route::get('/cek-status/{token}', [AbsensiController::class, 'cekStatus']);

/*
|--------------------------------------------------------------------------
| GENERATE QR
|--------------------------------------------------------------------------
*/
Route::get('/generate-qr', [GenerateQrController::class, 'index']);
Route::post('/generate-qr', [GenerateQrController::class, 'downloadPdf']);
Route::post('/generate-qr/csv', [GenerateQrController::class, 'generateFromCsv'])
    ->name('qr.generate.csv');

/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/
Route::get('/laporan', [LaporanController::class, 'index'])
    ->name('laporan.index');

Route::get('/laporan/pdf/{kelas}', [LaporanController::class, 'pdf']);

Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])
    ->name('laporan.export.excel');

Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])
    ->name('laporan.export.pdf');

Route::get('/laporan/export-excel', function () {
    return Excel::download(
        new LaporanHarianExport(
            request('tanggal') ?? now()->toDateString(),
            request('kelas')
        ),
        'laporan-harian.xlsx'
    );
});

/*
|--------------------------------------------------------------------------
| KELAS (RESOURCE)
|--------------------------------------------------------------------------
*/
Route::resource('kelas', KelasController::class);

/*
|--------------------------------------------------------------------------
| DATA SISWA MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::get('/siswa-data', [DataSiswaController::class, 'index'])
    ->name('siswa.index');

Route::put('/siswa-data/{id}', [DataSiswaController::class, 'update'])
    ->name('siswa.update');

Route::delete('/siswa-data/{id}', [DataSiswaController::class, 'destroy'])
    ->name('siswa.destroy');

Route::post('/siswa/bulk-update', [DataSiswaController::class, 'bulkUpdate'])
    ->name('siswa.bulkUpdate');

/*
|--------------------------------------------------------------------------
| PROFIL
|--------------------------------------------------------------------------
*/
Route::get('/profil', [AuthController::class, 'profile']);

Route::post('/profil/update', [AuthController::class, 'updateProfile'])
    ->name('profil.update');

/*
|--------------------------------------------------------------------------
| TEST ROLE ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/tes-admin', function () {
    return 'Khusus Admin';
})->middleware(['auth', 'role:admin']);

use App\Http\Controllers\UserController;

Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

