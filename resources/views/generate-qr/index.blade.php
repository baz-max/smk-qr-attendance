@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/dashboard" class="btn btn-secondary mb-3">← Kembali</a>

    {{-- Card Upload --}}
    <div class="card p-4 mb-4">
        <h4 class="mb-3">🔑 Generate QR Siswa (CSV)</h4>

        {{-- Button Download Template --}}
        <div class="mb-3">
    <a href="{{ asset('template/Template.csv') }}"
       class="btn btn-success btn-sm"
       style="
           display: inline-block;
           width: auto;
           padding: 8px 16px;
           font-weight: 600;
       "
       download>
        📥 Download Template CSV
    </a>
</div>

        <form action="{{ route('qr.generate.csv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file" class="form-control mb-3" required>

            <button class="btn btn-primary w-100">
                🚀 Upload & Download 
            </button>
        </form>
    </div>

    {{-- Card Instruksi --}}
    <div class="card p-4">
        <h5>📌 Instruksi & Hasil Import</h5>
        <ol class="mt-3">
            <li>Download template CSV.</li>
            <li>
                Isi data sesuai kolom:
                <b>NISN, NAMA_LENGKAP, KELAS, JURUSAN</b>
            </li>
            <li>Upload file CSV di atas.</li>
            <li>Setelah upload, QR siswa otomatis digenerate dalam bentuk PDF.</li>
        </ol>
    </div>
</div>
@endsection
