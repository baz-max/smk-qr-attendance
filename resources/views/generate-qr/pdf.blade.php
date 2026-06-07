<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px }
        .card {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .qr { 
            text-align: center; 
            margin-top: 10px; 
        }
        img.qr {
            width: 120px;
            height: 120px;
        }
    </style>
</head>
<body>

<h3 style="text-align:center">QR Code Absensi Siswa</h3>

@foreach($siswas as $siswa)
<div class="card">
    <strong>{{ $siswa->nama }}</strong><br>
    NIS: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas }}

    <div class="qr">
        <p>QR ABSENSI</p>
        <img class="qr" src="{{ $siswa->qr_base64 }}">
    </div>
</div>
@endforeach

</body>
</html>
