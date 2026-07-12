@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Data Kelas</h4>
        <small class="text-muted">Kelola kelas & jumlah siswa</small>
    </div>

    <button type="button"
        onclick="openCreate()"
        class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kelas
    </button>
</div>


    {{-- FILTER --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="d-flex gap-3 flex-wrap">

            {{-- CARI NAMA KELAS --}}
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control"
                   placeholder="Cari nama kelas..."
                   style="max-width:300px">

            {{-- FILTER TINGKAT --}}
            <select name="tingkat"
                    class="form-select"
                    style="max-width:200px"
                    onchange="this.form.submit()">
                <option value="">Semua Tingkat</option>
                <option value="10" {{ request('tingkat')=='10'?'selected':'' }}>Kelas 10</option>
                <option value="11" {{ request('tingkat')=='11'?'selected':'' }}>Kelas 11</option>
                <option value="12" {{ request('tingkat')=='12'?'selected':'' }}>Kelas 12</option>
            </select>

            <button class="btn btn-primary">
                <i class="bi bi-search"></i>
                Filter
            </button>

            @if(request()->hasAny(['search','tingkat']))
                <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            @endif

        </form>
    </div>
</div>


    {{-- DATA KELAS --}}
    <div class="row g-4">
        @foreach($kelas as $k)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $k->nama_kelas }}</h6>
                            <small class="text-muted">{{ $k->tingkat }}</small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $k->siswa_count }} siswa
                        </span>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('siswa.index', ['kelas_id' => $k->id]) }}"
                        class="btn btn-outline-primary btn-sm w-100">
                            Detail
                        </a>

                       <button type="button"
                        onclick="openEdit(
                            {{ $k->id }},
                            '{{ $k->nama_kelas }}',
                            '{{ $k->tingkat }}'
                        )"
                        class="btn btn-outline-secondary btn-sm w-100">
                        Edit
                    </button>

                        <form action="{{ route('kelas.destroy',$k->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>


{{-- MODAL TAMBAH / EDIT KELAS --}}
<div class="modal fade" id="kelasModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form id="kelasForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="methodField" value="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="kelasModalLabel">Tambah Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tingkat</label>
            <select name="tingkat" class="form-select" required>
              <option value="10">Kelas 10</option>
              <option value="11">Kelas 11</option>
              <option value="12">Kelas 12</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>



@endsection

@push('scripts')
<script>
let kelasModal;
const kelasModalEl = document.getElementById('kelasModal');

// init modal sekali aja
if (kelasModalEl) {
  kelasModal = new bootstrap.Modal(kelasModalEl);

  kelasModalEl.addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('kelasForm');
    form.reset();

    form.action = '/kelas';
    document.getElementById('methodField').value = 'POST';
    document.getElementById('kelasModalLabel').innerText = 'Tambah Kelas';
  });
}

function openCreate() {
  const form = document.getElementById('kelasForm');

  form.action = '/kelas';
  document.getElementById('methodField').value = 'POST';
  document.getElementById('kelasModalLabel').innerText = 'Tambah Kelas';

  document.querySelector('[name=nama_kelas]').value = '';
  document.querySelector('[name=tingkat]').value = '10';

  kelasModal.show();
}

function openEdit(id, nama, tingkat) {
  const form = document.getElementById('kelasForm');

  form.action = '/kelas/' + id;
  document.getElementById('methodField').value = 'PUT';
  document.getElementById('kelasModalLabel').innerText = 'Edit Kelas';

  document.querySelector('[name=nama_kelas]').value = nama;
  document.querySelector('[name=tingkat]').value = tingkat;

  kelasModal.show();
}
</script>
@endpush
