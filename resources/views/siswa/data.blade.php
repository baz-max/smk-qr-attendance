@extends('layouts.app')

@section('content')

{{-- CARD HEADER --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body d-flex flex-column flex-md-row
                justify-content-between align-items-md-center gap-3">

        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary-subtle text-primary rounded-3 p-3">
                <i class="bi bi-people fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Data Siswa</h5>
                <small class="text-muted">
                    Kelola data siswa berdasarkan kelas
                </small>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
<div class="d-flex align-items-center gap-3">

    {{-- FILTER --}}
    <form method="GET" class="d-flex align-items-center gap-2 mb-0">
        <i class="bi bi-funnel text-muted"></i>
        <select name="kelas_id"
                class="form-select form-select-sm"
                style="min-width: 200px"
                onchange="this.form.submit()">
            <option value="">Semua Kelas</option>

            <option value="belum"
                {{ request('kelas_id') == 'belum' ? 'selected' : '' }}>
                Belum Diset
            </option>

            @foreach($kelasList as $k)
                <option value="{{ $k->id }}"
                    {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- BUTTON --}}
    <button class="btn btn-sm btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalBulkEdit">
        <i class="bi bi-pencil-square me-1"></i>
        Edit Massal
    </button>

</div>
    

        

    </div>
</div>

{{-- TABLE CARD --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 px-4 pt-4">
        <h6 class="fw-semibold mb-0">
            <i class="bi bi-table me-1 text-primary"></i>
            Daftar Siswa
        </h6>
    
    </div>

    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th class="text-end" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ $s->nisn }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>
                                @if($s->jenis_kelamin == 'L')
                                    <span class="badge bg-info-subtle text-info">
                                        L
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        P
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($s->kelasRelasi)

                                    @php
                                        $nama = strtolower($s->kelasRelasi->nama_kelas ?? '');

                                        if (str_contains($nama, '10 a')) {
                                            $warna = 'bg-primary-subtle text-primary'; // Biru
                                        } elseif (str_contains($nama, '10 b')) {
                                            $warna = 'bg-info-subtle text-info'; // Cyan
                                        } elseif (str_contains($nama, '11')) {
                                            $warna = 'bg-success-subtle text-success'; // Hijau
                                        } elseif (str_contains($nama, '12')) {
                                            $warna = 'bg-warning-subtle text-warning'; // Kuning
                                        } else {
                                            $warna = 'bg-secondary-subtle text-secondary';
                                        }
                                    @endphp

                                    <span class="badge {{ $warna }}">
                                        {{ $s->kelasRelasi->nama_kelas }}
                                    </span>

                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        Belum diset
                                    </span>
                                @endif
                            </td>

                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $s->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('siswa.destroy', $s->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                Data siswa belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($siswas as $s)
<div class="modal fade" id="modalEdit{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('siswa.update', $s->id) }}">
            @csrf
            @method('PUT')

            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NISN</label>
                        <input type="text"
                               name="nisn"
                               value="{{ $s->nisn }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text"
                               name="nama"
                               value="{{ $s->nama }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ $s->jenis_kelamin == 'L' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="P" {{ $s->jenis_kelamin == 'P' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>


                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}"
                                    {{ $s->kelas_id == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->tingkat }} {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-warning">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
{{-- MODAL BULK EDIT --}}
<div class="modal fade" id="modalBulkEdit" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('siswa.bulkUpdate') }}">
            @csrf

            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kelas Semua Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning small">
                        Perubahan ini akan mengubah kelas semua siswa yang sedang ditampilkan.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas Baru</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">
                                     {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- kirim filter kelas jika ada --}}
                    <input type="hidden" name="filter_kelas_id" value="{{ request('kelas_id') }}">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-primary">
                        Update Semua
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
