@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            ← Dashboard
        </a>
        <div class="text-end">
            <h3 class="mb-0 fw-bold">Laporan Kehadiran</h3>
            <small class="text-muted">Ringkasan kehadiran siswa</small>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">

                    {{-- MODE --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-muted">Mode Laporan</label>
                        <select name="mode" class="form-select rounded-3" onchange="this.form.submit()">
                            <option value="harian" {{ request('mode')=='harian'?'selected':'' }}>Harian</option>
                            <option value="bulanan" {{ request('mode')=='bulanan'?'selected':'' }}>Bulanan</option>
                            <option value="tahunan" {{ request('mode')=='tahunan'?'selected':'' }}>Tahunan</option>
                        </select>
                    </div>

                    {{-- INPUT DINAMIS --}}
                    @if(request('mode','harian')=='harian')
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Tanggal</label>
                            <input type="date" name="tanggal"
                                   value="{{ request('tanggal', now()->toDateString()) }}"
                                   class="form-control rounded-3">
                        </div>
                    @elseif(request('mode')=='bulanan')
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Bulan</label>
                            <input type="month" name="bulan"
                                   value="{{ request('bulan', now()->format('Y-m')) }}"
                                   class="form-control rounded-3">
                        </div>
                    @else
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted">Tahun</label>
                            <select name="tahun" class="form-select rounded-3">
                                @for($i = now()->year; $i >= now()->year-5; $i--)
                                    <option value="{{ $i }}" {{ request('tahun')==$i?'selected':'' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    @endif

                    {{-- KELAS --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-muted">Kelas</label>
                        <select name="kelas" class="form-select rounded-3">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas }}" {{ request('kelas')==$kelas?'selected':'' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100 rounded-pill">
                            🔍 Tampilkan
                        </button>
                        <button class="btn btn-outline-success rounded-pill"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#exportModal">
                            📤 Export
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- REKAP --}}
<div class="row row-cols-1 row-cols-md-5 g-4 mb-4">
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75">Hadir</small>
                        <h2 class="fw-bold mb-0">{{ $rekap['hadir'] }}</h2>
                    </div>
                    <i class="bi bi-person-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 bg-warning bg-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75">Izin</small>
                        <h2 class="fw-bold mb-0">{{ $rekap['izin'] }}</h2>
                    </div>
                    <i class="bi bi-envelope fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 bg-info bg-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75">Sakit</small>
                        <h2 class="fw-bold mb-0">{{ $rekap['sakit'] }}</h2>
                    </div>
                    <i class="bi bi-heart-pulse fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 bg-dark bg-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75">Bolos</small>
                        <h2 class="fw-bold mb-0">{{ $rekap['bolos'] }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75">Alpha</small>
                        <h2 class="fw-bold mb-0">{{ $rekap['alpha'] }}</h2>
                    </div>
                    <i class="bi bi-x-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>JK</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>H</th>
            <th>I</th>
            <th>S</th>
            <th>B</th>
            <th>A</th>
            <th>Keterangan</th>
        </tr>
    </thead>

    <tbody>
        @foreach($laporan as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['siswa']->nisn }}</td>
                <td>{{ $row['siswa']->nama }}</td>
                <td>{{ $row['siswa']->jenis_kelamin }}</td>
                <td>{{ $row['siswa']->kelas }}</td>
                <td>{{ $row['siswa']->jurusan }}</td>
                <td class="text-success fw-bold">{{ $row['hadir'] }}</td>
                <td class="text-warning fw-bold">{{ $row['izin'] }}</td>
                <td class="text-info fw-bold">{{ $row['sakit'] }}</td>
                <td class="text-dark fw-bold">{{ $row['bolos'] }}</td>
                <td class="text-danger fw-bold">{{ $row['alpha'] }}</td>
                <td>
                    <button class="btn btn-sm btn-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#detail{{ $row['siswa']->id }}">
                        Detail
                    </button>
                </td>
            </tr>

            {{-- ROW DETAIL --}}
            <tr class="collapse" id="detail{{ $row['siswa']->id }}">
                <td colspan="10">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($row['detail'] as $tanggal => $items)
                                @php
                                    $jamMasuk  = optional($items->where('status','masuk')->first())->jam;
                                    $jamPulang = optional($items->where('status','keluar')->first())->jam;

                                    if ($items->where('status','sakit')->isNotEmpty()) {
                                        $status = 'Sakit';
                                    } 
                                    elseif ($items->where('status','izin')->isNotEmpty()) {
                                        $status = 'Izin';
                                    } 
                                    elseif ($jamMasuk && $jamPulang) {
                                        $status = 'Hadir';
                                    }
                                    elseif ($jamMasuk && !$jamPulang) {
                                        $status = 'Bolos'; // 🔥 ini yang kurang
                                    }
                                    else {
                                        $status = 'Alpha';
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $tanggal }}</td>
                                    <td>{{ $status }}</td>
                                    <td>{{ $jamMasuk ?? '-' }}</td>
                                    <td>{{ $jamPulang ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


        </div>

        <div class="card-footer bg-light small text-muted rounded-bottom-4">
            ℹ️ Siswa yang tidak melakukan absensi otomatis tercatat sebagai <strong>Alpha</strong>.
        </div>
    </div>

</div>

{{-- MODAL EXPORT --}}
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Export Laporan</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p class="text-muted mb-4">Pilih format file</p>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url('/laporan/export-excel?tanggal='.$tanggal.'&kelas='.request('kelas')) }}"
                       class="btn btn-success rounded-pill px-4">
                        📊 Excel
                    </a>

                    <a href="{{ route('laporan.export.pdf', request()->query()) }}"
                       class="btn btn-danger rounded-pill px-4">
                        📄 PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
