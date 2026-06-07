@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Kembali -->
    <a href="/data-siswa" class="btn btn-secondary mb-3">
        ← Kembali ke Data Siswa
    </a>

    <div class="card p-4 mb-4">
        <h4 class="mb-3">📥 Import Data Siswa (CSV)</h4>

        <form method="POST" action="{{ url('/siswa/import') }}" enctype="multipart/form-data">
    @csrf

    <div class="border rounded p-4 text-center mb-3">
        <p class="fw-semibold">Pilih File CSV Siswa</p>

       <input type="file" name="file" class="form-control mb-3" required>

        <button type="submit" class="btn btn-primary">
            🚀 Proses Import Data
        </button>
    </div>
</form>

    </div>

    <div class="card p-4">
        <h5>📌 Instruksi & Hasil Import</h5>
        <ol class="mt-3">
            <li>Download template CSV.</li>
            <li>Isi data sesuai kolom:
                <b>NISN, NAMA_LENGKAP, KELAS, Jurusan</b>
            </li>
            <li>Upload file CSV di atas.</li>
        </ol>
    </div>

</div>
@endsection
