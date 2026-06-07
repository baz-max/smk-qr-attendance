<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 0; }
        p { text-align: center; margin-top: 4px; font-size: 12px; }
    </style>
</head>
<body>

<h3>LAPORAN REKAP ABSENSI</h3>
<p>
    @if(isset($tanggal))
        Tanggal: {{ $tanggal }}
    @endif

    @if(isset($kelas) && $kelas)
        | Kelas: {{ $kelas }}
    @endif
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>H</th>
            <th>I</th>
            <th>A</th>
        </tr>
    </thead>
    <tbody>

        @forelse($laporan as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row['siswa']->nisn }}</td>
            <td style="text-align:left;">{{ $row['siswa']->nama }}</td>
            <td>{{ $row['siswa']->kelas }}</td>
            <td>{{ $row['hadir'] ?? 0 }}</td>
            <td>{{ $row['izin'] ?? 0 }}</td>
            <td>{{ $row['alpha'] ?? 0 }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada data</td>
        </tr>
        @endforelse

    </tbody>
</table>

</body>
</html>
