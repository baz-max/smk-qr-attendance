@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="mb-4">
       <h4 class="fw-bold text-capitalize">
    Data Absensi {{ ucfirst($tipe) }}
    @if(request('kelas'))
        – Kelas {{ request('kelas') }}
    @endif
</h4>

        <p class="text-muted mb-0">
            Form absensi siswa
        </p>
    </div>

    {{-- FORM ABSEN --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="row g-4">

            {{-- FORM INPUT --}}
            <form action="{{ route('absen.simpan') }}" method="POST" onsubmit="return validateForm()" class="col-md-5">
                @csrf
                <input type="hidden" name="tipe" value="{{ request('tipe') }}">

                <input type="hidden" id="siswa_id" name="siswa_id">

                <div class="mb-3">
                    <label class="form-label">NISN</label>
                    <input type="text" id="nisn" name="nisn" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" id="kelas" name="kelas" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" id="jurusan" name="jurusan" class="form-control" value="RPL" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" readonly>
                </div>

                <div class="mb-4">
                <label class="form-label">Status</label>

                <!-- Badge tampilan -->
                <div id="statusBadge" class="inline-block px-3 py-1 rounded text-white font-semibold text-sm mb-2">
                    -
                </div>

                <!-- Input dikirim ke database -->
                <input type="hidden" id="status" name="status">
                 </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="startScan()">Scan</button>
                </div>
            </form>

            {{-- AREA SCAN --}}
            <div class="scanner-wrapper" id="scannerWrapper">
            <div id="reader"></div>

            <div class="scanner-overlay" id="scannerOverlay">
                <div class="scanner-box">
                    <span class="corner tl"></span>
                    <span class="corner tr"></span>
                    <span class="corner bl"></span>
                    <span class="corner br"></span>
                </div>
            </div>
        </div>

            </div>

        </div> <!-- END ROW -->
    </div> <!-- END CARD BODY -->
</div> <!-- END CARD -->


    {{-- DATA ABSEN --}}
   <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
    <h6 class="fw-bold mb-0">Data Absen Siswa</h6>

    <div class="d-flex align-items-center gap-3">
        <form method="GET"
      action="{{ route('absen.index', request('tipe')) }}">

            <input type="hidden" name="tipe" value="{{ request('tipe') }}">
            <select name="kelas" onchange="this.form.submit()" class="form-select">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>
        </form>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#manualModal">
            + Absen Manual
        </button>
    </div>
</div>


            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->siswa->nisn }}</td>
                            <td>{{ $row->siswa->nama }}</td>
                            <td>{{ $row->siswa->kelas }}</td>
                            <td>{{ $row->siswa->jurusan }}</td>
                            <td>{{ $row->tanggal }}</td>

                            <td>
                            @php
                                $badgeClass = match($row->status) {
                                    'masuk'  => 'success',
                                    'keluar' => 'primary',
                                    'izin'   => 'warning',
                                    'sakit'  => 'info',
                                    'bolos'  => 'dark',
                                    'alpha', 'alfa' => 'danger',
                                    default  => 'secondary',
                                };
                            @endphp


                            <span class="badge bg-{{ $badgeClass }} text-capitalize">
                                {{ $row->status }}
                            </span>
                        </td>

                            <td>{{ $row->jam }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data absensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- SCRIPT SCAN --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode;
let scanLocked = false;

function startScan() {

    // ⬇️ TAMPILKAN AREA SCANNER
    document.getElementById('scannerWrapper').style.display = 'block';

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 200,
            aspectRatio: 1.0
        },

        (decodedText) => {

            if (scanLocked) return;
            scanLocked = true;

            let token = decodedText.startsWith('ABSEN:')
                ? decodedText.replace(/^ABSEN:/, '').trim()
                : decodedText.trim();

            fetch(`{{ url('/api/scan') }}/${token}`)
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then(data => {

                    let tipe = "{{ $tipe }}"; // masuk / keluar

                    // ❌ QR tidak valid
                    if (!data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'QR tidak valid'
                        });
                    }
                    // 🚫 SUDAH ABSEN
                    else if (
                        (tipe === 'masuk' && data.sudahMasuk) ||
                        (tipe === 'keluar' && data.sudahKeluar)
                    ) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sudah Absen',
                            text: `Siswa sudah absen ${tipe} hari ini`
                        });
                    }
                    // ✅ BOLEH ABSEN
                    else {

                     // 🔊 MAINkan BEEP
                        const beep = document.getElementById("beep-sound");
                        beep.currentTime = 0;
                        beep.play();
                        
                        document.getElementById('nisn').value = data.siswa.nisn;
                        document.getElementById('siswa_id').value = data.siswa.id;
                        document.getElementById('nama').value = data.siswa.nama;
                        document.getElementById('kelas').value = data.siswa.kelas;
                        document.getElementById('jurusan').value = "RPL";
                        document.getElementById('tanggal').value =
                            new Date().toISOString().slice(0, 10);

                        setelahScan();
                    }

                    // 🔓 BUKA LAGI BIAR BISA SCAN ULANG (TANPA KEDIP)
                    setTimeout(() => {
                        scanLocked = false;
                    }, 1500);
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'QR tidak valid'
                    });
                    scanLocked = false;
                });
        }
    );
}
</script>


<script>
// status KOSONG saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('status').value = ""; 
});

// fungsi dipanggil SETELAH scan berhasil

function setelahScan() {
    let tipe = "{{ $tipe }}"; // masuk / keluar

    // isi input hidden untuk dikirim ke database
    document.getElementById('status').value = tipe;

    // ambil badge
    const badge = document.getElementById('statusBadge');

    // reset class dulu
    badge.classList.remove('badge-masuk', 'badge-keluar');

    // set text + warna
    if (tipe === 'masuk') {
        badge.innerText = "masuk";
        badge.classList.add('badge-masuk');
    } else {
        badge.innerText = "keluar";
        badge.classList.add('badge-keluar');
    }
}
function validateForm() {
    if (!document.getElementById('siswa_id').value) {
        alert('Silakan scan QR terlebih dahulu');
        return false;
    }
    return true;
}

</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let tipe = "{{ request('tipe') }}";

    if (tipe === "masuk") {
        console.log("Mode Absen Masuk");
    }

    if (tipe === "keluar") {
        console.log("Mode Absen Keluar");
    }
});

</script>


<style>
.scanner-wrapper {
    width: 320px;
    height: 320px;
    margin: auto;
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #000;
}


#reader video {
    width: 110% !important;
    height: 110% !important;
    object-fit: cover;
    transform: translate(-5%, -5%);
    display: none;
}


#reader {
    width: 100%;
    height: 100%;
}

/* overlay gelap */
.scanner-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    pointer-events: none;
}


/* kotak scan */
.scanner-box {
    width: 250px;
    height: 250px;
    position: relative;
}


/* sudut-sudut putih */
.corner {
    position: absolute;
    width: 28px;
    height: 28px;
    border: 4px solid #fff;
}

.corner.tl {
    top: 0; left: 0;
    border-right: none;
    border-bottom: none;
}

.corner.tr {
    top: 0; right: 0;
    border-left: none;
    border-bottom: none;
}

.corner.bl {
    bottom: 0; left: 0;
    border-right: none;
    border-top: none;
}

.corner.br {
    bottom: 0; right: 0;
    border-left: none;
    border-top: none;
}

 .badge-masuk {
        background: #16a34a; /* hijau */
    }
    .badge-keluar {
        background: #dc2626; /* merah */
    }
    
</style>
<audio id="beep-sound" src="{{ asset('sound/beep.mp3') }}"></audio>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 1500,
        showConfirmButton: false
    });
    
    function filterKelas() {
    let kelas = document.getElementById('filterKelas').value;
    window.location.href = `?kelas=${kelas}`;
}

</script>
@endif

<!-- MODAL ABSEN MANUAL -->
<div class="modal fade" id="manualModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Absen Manual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('absen.simpan') }}" method="POST">
        @csrf
        <input type="hidden" name="tipe" value="{{ request('tipe') }}">
        <input type="hidden" name="jam" value="{{ date('H:i:s') }}">

        
        <div class="modal-body">

            <div class="mb-3">
                @php
                $filter = request('kelas');
                $siswas = $filter 
                    ? \App\Models\Siswa::where('kelas', $filter)->get()
                    : \App\Models\Siswa::all();
                @endphp

                <select name="siswa_id" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->nisn }} - {{ $s->nama }} ({{ $s->kelas }})
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" 
                       value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="masuk">Masuk</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="bolos">Bolos</option>
                    <option value="alfa">Alfa</option>
                </select>

            </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
