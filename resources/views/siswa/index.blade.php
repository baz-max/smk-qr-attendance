@extends('layouts.app')

@section('content')

<style>
/* ===== MOBILE OPTIMIZATION ===== */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }

    h5, h6 {
        font-size: 1rem;
    }

    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }

    .list-group-item {
        font-size: 0.9rem;
    }
}
</style>

<div class="container-fluid py-3">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- MULAI ABSENSI (PRIORITAS MOBILE) --}}
        <div class="col-12 col-md-6 col-xl-5 order-0 order-md-2">
            <div class="card shadow-sm border-0 h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="bi bi-qr-code-scan fs-1 text-primary mb-2"></i>
                    <h5 class="fw-bold">Mulai Absensi</h5>
                    <p class="text-muted mb-3">Pilih jenis absensi siswa</p>

                    <button class="btn btn-primary btn-lg w-100 fw-semibold"
                            onclick="bukaModalAbsen()">
                        Mulai
                    </button>
                </div>
            </div>
        </div>

        {{-- PROFIL GURU --}}
        <div class="col-12 col-md-6 col-xl-3 order-1">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-person-badge fs-4 text-primary me-2"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Profil Guru</h6>
                            <small class="text-muted">Informasi Pengajar</small>
                        </div>
                    </div>

                    <hr class="my-2">

                    <small class="text-muted">Nama</small>
                    <div class="fw-semibold">Hari Topan</div>

                    <small class="text-muted mt-2 d-block">NIP</small>
                    <div class="fw-semibold">19871231202201</div>
                </div>
            </div>
        </div>

        {{-- STATUS HARI INI --}}
        <div class="col-12 col-xl-4 order-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">📊 Status Hari Ini</h6>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <strong>Tanggal:</strong>
                            {{ now()->format('d M Y') }}
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Absensi:</strong>
                            <span class="badge bg-success">Aktif</span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Catatan:</strong><br>
                            Pastikan siswa melakukan <b>absen pulang</b>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- TIPS (RINGKAS & MOBILE FRIENDLY) --}}
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-body text-muted small text-center">
            💡 Absen pulang disarankan sebelum jam <b>16.00</b> agar data tercatat sempurna
        </div>
    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalPilihAbsen" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Jenis Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <div class="d-grid gap-3">
                    <a href="/absen?tipe=masuk" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Absen Masuk
                    </a>

                    <a href="/absen?tipe=keluar" class="btn btn-warning btn-lg text-white w-100">
                        <i class="bi bi-box-arrow-left me-2"></i> Absen Keluar
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function bukaModalAbsen() {
    new bootstrap.Modal(
        document.getElementById('modalPilihAbsen')
    ).show();
}
</script>

@endsection
