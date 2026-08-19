# SIMAGANG Bappeda Provinsi Lampung

Sistem Informasi Magang (SIMAGANG) Bappeda Provinsi Lampung adalah platform manajemen magang terpadu, mulai dari pengajuan mahasiswa sampai penerbitan dokumen akhir.

Dibangun dengan **PHP Native MVC** dan **MySQL**, tanpa framework dan tanpa Composer.

---

## Daftar Isi

- [Prasyarat](#prasyarat)
- [Instalasi Baru](#instalasi-baru)
- [Menarik Perubahan ke Instalasi Lama](#menarik-perubahan-ke-instalasi-lama)
- [Akun Login Tes](#akun-login-tes)
- [Konfigurasi Email (SMTP)](#konfigurasi-email-smtp)
- [Pengujian](#pengujian)
- [Struktur Folder](#struktur-folder)
- [Alur Pengajuan](#alur-pengajuan)
- [Pemecahan Masalah](#pemecahan-masalah)

---

## Prasyarat

| Kebutuhan | Versi | Catatan |
|---|---|---|
| PHP | 7.4+ (diuji pada 8.3) | Ekstensi `pdo_mysql`, `fileinfo`, `mbstring`, `openssl` harus aktif |
| MySQL | 5.7+ (diuji pada 5.7.39) | MariaDB 10.4+ juga berjalan |
| Web server | Apache atau Nginx | Laragon, XAMPP, atau MAMP |

**Tidak ada dependensi yang perlu di-install.** Tidak ada `composer install`, tidak ada `npm install`. Satu-satunya pustaka pihak ketiga, PHPMailer, sudah disertakan di `lib/PHPMailer/`.

Keempat ekstensi PHP di atas aktif secara bawaan pada Laragon dan XAMPP. Untuk memastikan:

```bash
php -m
```

Bila `pdo_mysql`, `fileinfo`, `mbstring`, atau `openssl` tidak muncul di daftar, hapus tanda `;` pada baris `extension=...` yang sesuai di `php.ini`, lalu mulai ulang web server.

---

## Instalasi Baru

### 1. Ambil kode

Tempatkan folder proyek di direktori web server:

```bash
cd C:\laragon\www
git clone https://github.com/Khalishazahwa15/simagang.git
```

- Laragon: `C:\laragon\www\simagang`
- XAMPP: `C:\xampp\htdocs\simagang`

### 2. Buat berkas konfigurasi

```bash
cd simagang
copy .env.example .env
```

Buka `.env` dan sesuaikan bila perlu:

```ini
APP_ENV=development
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=simagang_db
DB_USER=root
DB_PASS=
```

`APP_ENV=development` menampilkan pesan galat di layar. Untuk server sungguhan gunakan `production`, yang menyembunyikan galat dari pengguna dan menuliskannya ke `storage/logs/error.log`.

`.env` tidak ikut masuk Git, jadi setiap orang membuatnya sendiri.

### 3. Impor skema basis data

```bash
mysql -u root < database/schema.sql
```

Atau lewat phpMyAdmin: tab **Import** → pilih `database/schema.sql` → Go.

Berkas itu sudah memuat perintah `CREATE DATABASE simagang_db`, jadi databasenya tidak perlu dibuat manual. Namanya harus sama dengan `DB_NAME` di `.env`.

### 4. Isi data contoh

```bash
php database/seeder.php
```

> **Perhatian:** seeder meng-`TRUNCATE` seluruh tabel sebelum mengisi ulang. Jangan pernah menjalankannya di lingkungan berisi data nyata, dan jangan mengaksesnya lewat browser.

### 5. Jalankan

Direktori yang dilayani web server adalah `public/`, **bukan** folder root proyek.

- **Laragon** (disarankan): virtual host otomatis aktif di `http://simagang.test`
- **Tanpa virtual host**: `http://localhost/simagang/public`

Buka alamat tersebut, lalu masuk dengan salah satu akun di bawah.

---

## Menarik Perubahan ke Instalasi Lama

`schema.sql` memakai `CREATE TABLE IF NOT EXISTS`, sehingga basis data yang sudah ada **tidak ikut berubah** saat Anda `git pull`. Jalankan berkas pemutakhiran satu kali:

```bash
git pull
mysql -u root simagang_db < database/UPGRADE.sql
```

Berkas itu aman dijalankan berulang. Setiap perubahan diperiksa dulu ke `information_schema`, jadi bagian yang sudah pernah dieksekusi dilewati begitu saja tanpa galat. Bila berhasil, baris terakhir keluarannya berbunyi `UPGRADE.sql selesai dijalankan.` — kalau baris itu tidak muncul, berarti ada yang gagal dan perlu diperiksa.

Bisa juga lewat phpMyAdmin: pilih database `simagang_db` → tab **Import** → pilih berkasnya.

Melewatkan langkah ini membuat halaman login galat, karena tabel `login_attempts` belum ada.

Setelah itu tekan `Ctrl+F5` di browser untuk memuat ulang CSS yang ter-cache.

---

## Akun Login Tes

Tersedia setelah `php database/seeder.php` dijalankan. Kata sandi ketiganya `password123`.

| Peran | Email |
|---|---|
| Admin | `admin@bappeda.lampung.go.id` |
| Sekretariat | `sekretariat@bappeda.lampung.go.id` |
| Mahasiswa | `najwa@student.unila.ac.id` |

> Setelah lima kali gagal login berturut-turut dari satu perangkat, akun terkunci 15 menit. Login yang berhasil langsung mengosongkan hitungannya.

---

## Konfigurasi Email (SMTP)

Fitur Lupa Password mengirim tautan pengaturan ulang lewat email. Isi kredensial di `.env`:

```ini
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=akun@gmail.com
SMTP_PASS=app-password-16-karakter
SMTP_ENCRYPTION=tls
MAIL_FROM_ADDRESS=akun@gmail.com
MAIL_FROM_NAME=SIMAGANG Bappeda Lampung
```

- `SMTP_ENCRYPTION` menerima `tls` (port 587), `ssl` (port 465), atau `none`.
- Untuk Gmail, `SMTP_PASS` harus **App Password**, bukan kata sandi akun. App Password baru muncul setelah verifikasi 2 langkah aktif, dan dibuat di `myaccount.google.com/apppasswords`.
- `MAIL_FROM_ADDRESS` sebaiknya sama dengan `SMTP_USER`; Gmail menolak alamat pengirim yang bukan miliknya.
- **Bila `SMTP_HOST` dibiarkan kosong**, email tidak dikirim melainkan ditulis ke `storage/logs/mail.log`. Berguna saat pengembangan lokal: tautan resetnya tetap bisa diambil dari berkas itu.

### Email otomatis perubahan status

Selain tautan reset kata sandi, sistem mengirim email ke mahasiswa **setiap kali
status pengajuannya berubah** — dari "Diajukan" sampai "Selesai", termasuk
transisi otomatis berbasis tanggal. Isinya sama dengan notifikasi dalam aplikasi,
ditambah tautan ke halaman status.

Matikan sementara saat pengembangan agar kotak masuk tidak penuh:

```ini
MAIL_NOTIFIKASI=false
```

Pengujian otomatis selalu mematikannya sendiri, jadi menjalankan tes tidak akan
mengirim email ke siapa pun.

Kegagalan pengiriman dicatat di `storage/logs/error.log` dan **tidak pernah**
membatalkan perubahan status yang sudah tersimpan.

### Menguji konfigurasi

Uji konfigurasi tanpa lewat halaman web:

```bash
php tools/kirim-email-uji.php alamat-tujuan@contoh.com
```

Perintah tersebut menampilkan konfigurasi yang terbaca beserta percakapan SMTP-nya, sehingga penyebab kegagalan langsung terlihat. Kata sandi disamarkan di layar.

Memeriksa konfigurasi lewat halaman web tidak disarankan: halaman Lupa Password sengaja selalu menampilkan pesan sukses yang sama agar alamat terdaftar tidak bisa ditebak, jadi kegagalan pengiriman tidak terlihat di layar. Jejaknya hanya ada di `storage/logs/error.log`.

---

## Pengujian

```bash
php tests/TestSystemFlow.php   # alur normal: tawaran, IDOR, hak akses, otomasi tanggal
php tests/TestJalurRusak.php   # perbuatan yang harus ditolak sistem
```

Kedua berkas memuat `tests/bootstrap.php`, yang **membangun ulang basis data `simagang_test` dari nol** setiap kali dijalankan. Basis data pengembangan tidak tersentuh. Ganti namanya lewat variabel lingkungan `SIMAGANG_TEST_DB` bila perlu.

---

## Struktur Folder

```
app/
  Controllers/   Penerima permintaan HTTP, satu berkas per peran
  Core/          Router, Session, Auth, Database, Env, Logger, ErrorHandler
  Models/        Akses tabel
  Services/      Aturan bisnis: pengajuan, status, dokumen, email, notifikasi
  Views/         Berkas tampilan, dikelompokkan per peran
config/          routes.php, database.php, app.php
database/        schema.sql, UPGRADE.sql, seeder.php
docs/            Rancangan basis data, matriks keterlacakan PRD, rencana migrasi Supabase
lib/PHPMailer/   PHPMailer 7.1.1 (LGPL-2.1), dipasang manual tanpa Composer
public/          Direktori web-facing: index.php dan aset
storage/         Dibuat otomatis saat dipakai
  uploads/         Dokumen mahasiswa
  logs/            error.log dan mail.log
tests/           Berkas pengujian
tools/           Perkakas baris perintah
```

---

## Alur Pengajuan

```
Mahasiswa daftar -> lengkapi profil -> ajukan magang (pilih divisi preferensi)
        |
        v
Sekretariat buka berkas -> tekan "Mulai Verifikasi"      [dalam_verifikasi]
        |
   +----+----------------+------------------+----------------+
   v                     v                  v                v
Terima            Tawarkan divisi        Revisi            Tolak
(penempatan       alternatif
 mengunci ke           |
 preferensi)           v
   |            Mahasiswa terima / tolak
   |                   |
   v                   v
diterima      Sekretariat finalisasi -> diterima
```

Beberapa aturan ditegakkan sistem, bukan sekadar konvensi:

- Mahasiswa tidak dapat mengajukan magang selama data profilnya belum lengkap.
- Keputusan hanya dapat ditetapkan saat status `dalam_verifikasi`, dan tidak dapat diubah setelahnya.
- Menerima pengajuan selalu menempatkan mahasiswa pada divisi preferensinya. Menempatkan di divisi lain wajib lewat tawaran yang disetujui mahasiswa.

Rincian tabel dan relasinya ada di `docs/DATABASE_DESIGN.md`.

---

## Pemecahan Masalah

| Gejala | Sebab dan solusi |
|---|---|
| Halaman login galat setelah `git pull` | Tabel `login_attempts` belum ada. Jalankan `database/UPGRADE.sql`. |
| Profil mahasiswa gagal disimpan | Kolom profil belum lengkap. Jalankan `database/UPGRADE.sql`. |
| Tampilan berantakan setelah `git pull` | CSS lama ter-cache. Tekan `Ctrl+F5`. |
| `Unknown database 'simagang_db'` | `schema.sql` belum diimpor, atau `DB_NAME` di `.env` berbeda. |
| Tautan reset kata sandi tidak masuk email | Baca `storage/logs/error.log`. Bila justru muncul di `storage/logs/mail.log`, berarti `SMTP_HOST` masih kosong. |
| Halaman kosong tanpa pesan apa pun | Set `APP_ENV=development` di `.env`, atau baca `storage/logs/error.log`. |
| Akun tidak bisa login padahal sandi benar | Terkunci 15 menit karena percobaan gagal berulang, atau statusnya `nonaktif`. |

---

## Manajemen Lingkungan (Environment)

- **Development**: `APP_ENV=development` menampilkan seluruh pesan galat langsung di browser.
- **Production**: `APP_ENV=production` menyembunyikan galat dari pengguna dan menuliskannya ke `storage/logs/error.log`.
