<h6 class="fw-bold">{{ $kelas->nama_kelas }}</h6>
<p class="text-muted">Kelas {{ $kelas->tingkat }}</p>

<hr>

<ul class="list-group list-group-flush">
@forelse($kelas->siswa as $s)
  <li class="list-group-item px-0">{{ $s->nama }}</li>
@empty
  <li class="list-group-item text-muted px-0">Belum ada siswa</li>
@endforelse
</ul>
