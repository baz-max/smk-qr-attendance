# SMK QR Attendance

Sistem Absensi Siswa Berbasis QR Code menggunakan Laravel.

## Features

- Login Admin
- Manajemen Data Siswa
- Manajemen Kelas
- Generate QR Code Siswa
- Scan QR Absensi
- Laporan Absensi Harian
- Export Excel
- Dashboard Monitoring

## Tech Stack

- Laravel
- MySQL
- Tailwind CSS
- Laravel Excel
- QR Code Generator

## Installation

```bash
git clone https://github.com/baz-max/smk-qr-attendance.git
cd smk-qr-attendance

composer install
npm install

cp .env.example .env

php artisan key:generate
```

Buat database lalu sesuaikan konfigurasi pada file `.env`.

```env
DB_DATABASE=smk_qr_attendance
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi:

```bash
php artisan migrate --seed
```

Menjalankan aplikasi:

```bash
npm run dev
php artisan serve
```

Akses aplikasi:

```text
http://127.0.0.1:8000
```

## Author

Athif Basyar Mussafa
