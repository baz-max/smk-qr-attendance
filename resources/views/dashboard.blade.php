@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background:#f8fafc;
}

/* ================= HEADER STATUS ================= */
.status-card {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 15px 40px rgba(37,99,235,.35);
}

/* Sticky header (mobile only) */
@media (max-width: 768px) {
    .status-wrapper {
        position: sticky;
        top: 0;
        z-index: 1000;
        background:#f8fafc;
        padding-bottom: 12px;
    }
}

.status-item h4 {
    font-weight:700;
    margin-bottom:0;
    font-size:16px;
}

.status-item small {
    font-size:12px;
    opacity:.85;
}

/* ================= CARD BASE ================= */
.card-base {
    background:#fff;
    border-radius:20px;
    padding:22px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    height:100%;
}

.section-title {
    font-weight:700;
    color:#0f172a;
}

.sub-text {
    font-size:14px;
    color:#64748b;
}

/* ================= MENU ================= */
.menu-tile {
    border-radius:18px;
    padding:20px 14px;
    background:#fff;
    box-shadow:0 8px 22px rgba(0,0,0,.05);
    transition:.25s;
    height:100%;
}

.menu-tile:active {
    transform:scale(.97);
}

.menu-icon {
    width:52px;
    height:52px;
    border-radius:16px;
    background:rgba(37,99,235,.1);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    color:#2563eb;
    margin:auto;
}

/* ================= FLOATING BUTTON ================= */
.fab {
    position:fixed;
    bottom:80px;
    right:18px;
    width:60px;
    height:60px;
    background:#2563eb;
    color:#fff;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
    box-shadow:0 12px 30px rgba(37,99,235,.5);
    z-index:1200;
}

@media (min-width: 992px) {
    .fab {
        display:none;
    }
}

/* ================= BOTTOM NAV ================= */
.bottom-nav {
    position:fixed;
    bottom:0;
    left:0;
    right:0;
    background:#fff;
    box-shadow:0 -6px 20px rgba(0,0,0,.08);
    display:flex;
    justify-content:space-around;
    padding:8px 0;
    z-index:1100;
}

.bottom-nav a {
    text-decoration:none;
    color:#64748b;
    font-size:12px;
    text-align:center;
}

.bottom-nav i {
    font-size:20px;
    display:block;
}

.bottom-nav a.active {
    color:#2563eb;
    font-weight:600;
}

@media (min-width: 992px) {
    .bottom-nav {
        display:none;
    }
}

/* spacing supaya tidak ketutup bottom nav */
@media (max-width: 768px) {
    .page-content {
        padding-bottom:110px;
    }
}

/* ================= FOOTER ================= */
.main-footer {
    margin-top: 60px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    padding: 40px 0 20px 0;
}

.footer-title {
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 10px;
}

.footer-text {
    font-size: 14px;
    color: #64748b;
}

.footer-link {
    text-decoration: none;
    color: #64748b;
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    transition: .2s;
}

.footer-link:hover {
    color: #2563eb;
}

.footer-bottom {
    border-top: 1px solid #e2e8f0;
    margin-top: 30px;
    padding-top: 15px;
    font-size: 13px;
    color: #94a3b8;
}

.footer-social a {
    color: #64748b;
    font-size: 18px;
    margin-right: 12px;
    transition: .2s;
}

.footer-social a:hover {
    color: #2563eb;
}

/* Supaya aman dari bottom nav mobile */
@media (max-width: 768px) {
    .main-footer {
        margin-bottom: 100px;
    }
}

.status-wrapper {
    margin-top: 5px;
    margin-bottom: 30px; /* jarak ke card bawah */
}

.page-content {
    margin-bottom: 5px; /* jarak ke footer */
}

.menu-tile{
    background:#ffffff;
    border-radius:18px;
    padding:28px 10px;
    transition:all .35s cubic-bezier(.4,0,.2,1);
    box-shadow:0 8px 18px rgba(0,0,0,.06);
    position:relative;
    overflow:hidden;
}

.menu-tile::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,rgba(37,99,235,.12),transparent);
    opacity:0;
    transition:.35s;
}

.menu-icon{
    width:64px;
    height:64px;
    margin:auto;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: #e3e8f7; /* abu soft */
    color:#2563eb; /* icon biru */
    font-size:26px;
    transition:.35s;
}


.menu-tile p{
    transition:.3s;
}

/* ✨ HOVER EFFECT */
.menu-tile:hover{
    transform:translateY(-8px) scale(1.02);
    box-shadow:0 20px 35px rgba(0,0,0,.12);
}

.menu-tile:hover::before{
    opacity:1;
}

.menu-tile:hover .menu-icon{
    background:#2f5fd0;
    color:#fff;
    transform:scale(1.12) rotate(3deg);
    box-shadow:0 10px 20px rgba(47,95,208,.25);
}

.menu-tile:hover p{
    color:#2563eb;
}
.menu-tile:active{
    transform:scale(.96);
    box-shadow:0 6px 12px rgba(0,0,0,.15);
}


</style>

{{-- ================= HEADER ================= --}}
<div class="status-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <div class="status-card">
                <div class="row text-center gy-2">
                    <div class="col-12 col-md-4 status-item">
                        <h4>{{ now()->format('d M Y') }}</h4>
                        <small>Tanggal</small>
                    </div>
                    <div class="col-6 col-md-4 status-item">
                        <h4>Aktif</h4>
                        <small>Status</small>
                    </div>
                    <div class="col-6 col-md-4 status-item">
                        <h4>{{ now()->format('H:i') }}</h4>
                        <small>Jam Server</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">

{{-- ================= CONTENT ================= --}}
<div class="row g-4">

    {{-- RINGKASAN --}}
    <div class="col-lg-4">
        <div class="card-base">
            <h5 class="section-title mb-1">
                <i class="bi bi-clipboard-check me-1"></i>
                Ringkasan Absensi
            </h5>
            <span class="sub-text">Hari ini</span>

            <div class="row text-center mt-4">
                <div class="col-6">
                    <h2 class="fw-bold text-success mb-0">{{ $hadir }}</h2>
                    <small class="text-muted">Hadir</small>
                </div>
                <div class="col-6">
                    <h2 class="fw-bold text-danger mb-0">{{ $alpha }}</h2>
                    <small class="text-muted">Belum Absen</small>
                </div>
            </div>

            <div class="mt-4">
                <small class="text-muted">Persentase</small>
                <div class="progress mt-1" style="height:8px">
                    <div class="progress-bar bg-primary" style="width:{{ $persentase }}%"></div>
                </div>
                <small class="text-muted">{{ $persentase }}%</small>
            </div>

            <a href="/laporan" class="btn btn-outline-primary w-100 mt-4 rounded-pill">
                <i class="bi bi-eye me-1"></i>
                Detail
            </a>
        </div>
    </div>

    {{-- MENU --}}
    <div class="col-lg-8">
        <div class="card-base">
            <h5 class="section-title mb-1">
                <i class="bi bi-grid-fill me-1"></i>
                Menu Utama
            </h5>
            <span class="sub-text">Navigasi sistem</span>

            @php
            $menus = [
                ['label'=>'Data Siswa','icon'=>'bi-people','url'=>'/siswa-data'],
                ['label'=>'Generate QR','icon'=>'bi-upc-scan','url'=>'/generate-qr'],
                ['label'=>'Absen','icon'=>'bi-qr-code-scan','url'=>'/data-siswa'],
                ['label'=>'Data Kelas','icon'=>'bi-building','url'=>'/kelas'],
                ['label'=>'User','icon'=>'bi-person-gear','url'=>'/user'],
                ['label'=>'Laporan','icon'=>'bi-file-earmark-text','url'=>'/laporan'],
            ];
            @endphp

            <div class="row g-3 mt-3">
                @foreach($menus as $m)
                <div class="col-6 col-md-4">
                    <a href="{{ $m['url'] }}" class="text-decoration-none text-dark">
                        <div class="menu-tile text-center">
                            <div class="menu-icon">
                                <i class="bi {{ $m['icon'] }}"></i>
                            </div>
                            <p class="mt-2 fw-semibold mb-0">{{ $m['label'] }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
</div>

{{-- ================= FOOTER ================= --}}
<footer class="main-footer">
    <div class="container">
        <div class="row">

            {{-- Tentang --}}
            <div class="col-md-4 mb-4">
                <h6 class="footer-title">Sistem Absensi</h6>
                <p class="footer-text">
                    Sistem absensi berbasis QR Code untuk mendukung digitalisasi 
                    dan efisiensi kehadiran siswa secara real-time.
                </p>
            </div>

            {{-- Navigasi --}}
            <div class="col-md-4 mb-4">
                <h6 class="footer-title">Navigasi</h6>
                <a href="/dashboard" class="footer-link">Dashboard</a>
                <a href="/data-siswa" class="footer-link">Absen</a>
                <a href="/laporan" class="footer-link">Laporan</a>
                <a href="/user" class="footer-link">User</a>
            </div>

            {{-- Kontak --}}
            <div class="col-md-4 mb-4">
                <h6 class="footer-title">Kontak</h6>
                <p class="footer-text mb-1">
                    <i class="bi bi-geo-alt me-1"></i> Pandeglang, Banten
                </p>
                <p class="footer-text mb-1">
                    <i class="bi bi-envelope me-1"></i> smkmupan@sekolah.sch.id
                </p>

                <div class="footer-social mt-2">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-globe"></i></a>
                </div>
            </div>

        </div>

        <div class="footer-bottom text-center">
            © {{ date('Y') }} Sistem Absensi QR. All rights reserved.
        </div>
    </div>
</footer>

{{-- FLOATING ABSEN --}}
<a href="/data-siswa" class="fab">
    <i class="bi bi-qr-code-scan"></i>
</a>

{{-- BOTTOM NAV --}}
<div class="bottom-nav">
    <a href="/dashboard" class="active">
        <i class="bi bi-house"></i>Home
    </a>
    <a href="/data-siswa">
        <i class="bi bi-qr-code-scan"></i>Absen
    </a>
    <a href="/laporan">
        <i class="bi bi-file-earmark-text"></i>Laporan
    </a>
    <a href="/user">
        <i class="bi bi-person"></i>User
    </a>
</div>

@endsection
