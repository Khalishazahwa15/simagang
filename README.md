# SIMAGANG Bappeda Provinsi Lampung

Sistem Informasi Magang (SIMAGANG) Bappeda Provinsi Lampung adalah platform manajemen magang terpadu, mulai dari pengajuan mahasiswa sampai penerbitan dokumen akhir.

Dibangun dengan **PHP Native MVC**, tanpa framework dan tanpa Composer. Berjalan
di atas **MySQL** maupun **PostgreSQL/Supabase** — penggeraknya dipilih lewat satu
baris di `.env`, tanpa mengubah kode.

---

## Daftar Isi

- [Prasyarat](#prasyarat)
- [Instalasi Baru](#instalasi-baru)
- [Menarik Perubahan ke Instalasi Lama](#menarik-perubahan-ke-instalasi-lama)
- [Akun Login Tes](#akun-login-tes)
- [Konfigurasi Email (SMTP)](#konfigurasi-email-smtp)
- [Pengujian](#pengujian)
- [Struktur Folder](#struktur-folder)
- [Perkakas](#perkakas)
- [Dokumentasi](#dokumentasi)
- [Alur Pengajuan](#alur-pengajuan)
- [Pemecahan Masalah](#pemecahan-masalah)

---

## Prasyarat

| Kebutuhan | Versi | Catatan |
|---|---|---|
| PHP | 7.4+ (diuji pada 8.3) | Ekstensi `fileinfo`, `mbstring`, `openssl`, dan salah satu penggerak basis data di bawah |
| Basis data | MySQL 5.7+ (diuji pada 5.7.39) | Perlu ekstensi `pdo_mysql`. MariaDB 10.4+ juga berjalan |
| | atau PostgreSQL 13+ (diuji pada 17.6) | Perlu ekstensi `pdo_pgsql`. Supabase termasuk di sini |
| Web server | Apache atau Nginx | Laragon, XAMPP, atau MAMP |

**Tidak ada dependensi yang perlu di-install.** Tidak ada `composer install`, tidak ada `npm install`. Satu-satunya pustaka pihak ketiga, PHPMailer, sudah disertakan di `lib/PHPMailer/`.

Ekstensi di atas aktif secara bawaan pada Laragon dan XAMPP, **kecuali
`pdo_pgsql`** yang harus dinyalakan sendiri bila memakai PostgreSQL. Untuk
memastikan:

```bash
php -r "echo implode(', ', PDO::getAvailableDrivers());"
```

Keluarannya harus memuat penggerak yang akan Anda pakai. Bila belum, hapus tanda
`;` pada baris `extension=...` yang sesuai di `php.ini`, lalu mulai ulang web
server.

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
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=simagang_db
DB_USER=root
DB_PASS=
```

Untuk PostgreSQL atau Supabase, isi `DB_DRIVER=pgsql` beserta `DB_SSLMODE` dan
`DB_SCHEMA`. Seluruh kuncinya dijelaskan di `.env.example`, dan langkah
lengkapnya ada di [docs/MIGRASI_SUPABASE.md](docs/MIGRASI_SUPABASE.md).

`APP_ENV=development` menampilkan pesan galat di layar. Untuk server sungguhan gunakan `production`, yang menyembunyikan galat dari pengguna dan menuliskannya ke `storage/logs/error.log`.

`.env` tidak ikut masuk Git, jadi setiap orang membuatnya sendiri.

### 3. Siapkan basis data

```bash
php database/pasang.php
```

Satu perintah untuk semuanya: membuat basis data bila belum ada, memasang
seluruh tabel, lalu menerapkan perubahan struktur untuk instalasi lama. Aman
dijalankan berulang; tabel yang sudah ada tidak disentuh. Nama basis datanya
mengikuti `DB_NAME` di `.env`, dan MySQL maupun PostgreSQL ditangani perintah
yang sama.

### 4. Isi data contoh

```bash
php database/seeder.php
```

Opsional, untuk mendapatkan akun uji di bawah.

> **Perhatian:** seeder mengosongkan seluruh tabel sebelum mengisi ulang. Jangan
> menjalankannya pada data sungguhan, dan jangan mengaksesnya lewat browser.

### 5. Jalankan

Direktori yang dilayani web server adalah `public/`, **bukan** folder root proyek.

- **Laragon** (disarankan): virtual host otomatis aktif di `http://simagang.test`
- **Tanpa virtual host**: `http://localhost/simagang/public`

Buka alamat tersebut, lalu masuk dengan salah satu akun di bawah.

---

## Menarik Perubahan ke Instalasi Lama

```bash
git pull
php database/pasang.php
```

Perintahnya sama dengan pemasangan baru. Struktur yang belum ada akan
ditambahkan, yang sudah ada dibiarkan, dan datanya tidak disentuh.

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
php tests/TestSystemFlow.php    # alur sistem dan otomasi berbasis tanggal
php tests/TestJalurRusak.php    # perbuatan yang harus ditolak sistem
php tests/TestTawaranFlow.php   # tawaran divisi, IDOR, dan hak akses
```

Ketiganya memuat `tests/bootstrap.php`, yang **membangun ulang lingkungan uji
dari nol** setiap kali dijalankan, sehingga basis data pengembangan tidak
tersentuh:

- MySQL memakai basis data terpisah, `simagang_test` (ganti lewat `SIMAGANG_TEST_DB`)
- PostgreSQL memakai schema terpisah, `simagang_test` (ganti lewat `SIMAGANG_TEST_SCHEMA`)

Untuk menjalankannya di atas basis data lain tanpa menyentuh `.env` sehari-hari,
tunjuk berkas `.env` lain lewat `SIMAGANG_ENV`:

```bash
SIMAGANG_ENV=.env.supabase php tests/TestJalurRusak.php
```

Setiap perubahan yang menyentuh kueri sebaiknya diuji di **kedua** penggerak.
Angka lulusnya harus sama; bila salah satu berbeda, ada kueri yang tidak setara.

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
database/        pasang.php (pemasang), seeder.php (data contoh),
                 schema.sql, schema.pgsql.sql, UPGRADE.sql
docs/            Dokumentasi. Mulai dari docs/README.md
lib/PHPMailer/   PHPMailer 7.1.1 (LGPL-2.1), dipasang manual tanpa Composer
public/          Direktori web-facing: index.php, CSS, dan gambar
storage/         Dibuat otomatis saat dipakai
  uploads/         Dokumen mahasiswa
  logs/            error.log dan mail.log
tests/           Berkas pengujian
tools/           Perkakas baris perintah
```

## Perkakas

```bash
php tools/kirim-email-uji.php alamat@contoh.com   # uji konfigurasi SMTP
php tools/banding-skema.php                        # bandingkan skema MySQL vs PostgreSQL
```

`banding-skema.php` membandingkan struktur kedua basis data yang benar-benar
berjalan, lalu keluar dengan kode 1 bila berbeda. Skema ditulis di dua berkas,
dan perubahan yang hanya masuk ke salah satunya tidak tertangkap pengujian —
pengujian hanya menjalankan satu penggerak dalam satu waktu, sehingga keduanya
tetap lulus di lingkungannya sendiri.

### Berkas CSS

`public/assets/css/tailwind.css` adalah **hasil build**, bukan berkas untuk
disunting tangan. Warna dan hurufnya diturunkan dari `tokens.css`, jadi
perubahan tampilan cukup dilakukan di sana. Cara membangunnya ulang ada di
[docs/PANDUAN_DESAIN.md](docs/PANDUAN_DESAIN.md) bagian 5. Menjalankan sistem
tidak memerlukan langkah ini.

## Dokumentasi

Seluruhnya ada di [docs/](docs/README.md):

| Berkas | Isi |
|---|---|
| [ATURAN_BISNIS.md](docs/ATURAN_BISNIS.md) | Aturan yang ditegakkan sistem, ringkas |
| [PRD.md](docs/PRD.md) | Dokumen kebutuhan resmi beserta riwayat revisinya |
| [KETERLACAKAN_PRD.md](docs/KETERLACAKAN_PRD.md) | Status pengerjaan tiap butir PRD |
| [RANCANGAN_BASIS_DATA.md](docs/RANCANGAN_BASIS_DATA.md) | Tabel, relasi, dan alasan rancangannya |
| [PANDUAN_DESAIN.md](docs/PANDUAN_DESAIN.md) | Arah visual antarmuka |
| [MIGRASI_SUPABASE.md](docs/MIGRASI_SUPABASE.md) | Cara menjalankannya di atas PostgreSQL/Supabase |
| [VERIFIKASI_MIGRASI.html](docs/VERIFIKASI_MIGRASI.html) | Potret hasil pengujian migrasi per 20 Agustus 2026 |

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

Rincian tabel dan relasinya ada di [docs/RANCANGAN_BASIS_DATA.md](docs/RANCANGAN_BASIS_DATA.md).

---

## Pemecahan Masalah

| Gejala | Sebab dan solusi |
|---|---|
| Galat tabel atau kolom tidak ditemukan setelah `git pull` | Struktur basis data belum dimutakhirkan. Jalankan `php database/pasang.php`. |
| Tampilan berantakan setelah `git pull` | CSS lama ter-cache. Tekan `Ctrl+F5`. |
| `Unknown database ...` | Basis datanya belum dibuat. Jalankan `php database/pasang.php`. |
| `could not find driver` | Ekstensi penggerak belum aktif. Periksa dengan `php -r "echo implode(', ', PDO::getAvailableDrivers());"`. |
| Pencarian selalu kosong di PostgreSQL | Ada kueri `LIKE` yang belum lewat `App\Core\Sql::searchText()`. Gagal tanpa pesan galat. |
| Kueri gagal acak saat banyak pengguna | Koneksi Supabase memakai Transaction pooler (port 6543). Pindah ke Session pooler (port 5432). |
| Halaman berjalan lambat di Supabase | Wajar bila aplikasi dan basis datanya berjauhan: tiap kueri menempuh jaringan. Tempatkan keduanya berdekatan. |
| Tautan reset kata sandi tidak masuk email | Baca `storage/logs/error.log`. Bila justru muncul di `storage/logs/mail.log`, berarti `SMTP_HOST` masih kosong. |
| Halaman kosong tanpa pesan apa pun | Set `APP_ENV=development` di `.env`, atau baca `storage/logs/error.log`. |
| Akun tidak bisa login padahal sandi benar | Terkunci 15 menit karena percobaan gagal berulang, atau statusnya `nonaktif`. |

---

## Manajemen Lingkungan (Environment)

- **Development**: `APP_ENV=development` menampilkan seluruh pesan galat langsung di browser.
- **Production**: `APP_ENV=production` menyembunyikan galat dari pengguna dan menuliskannya ke `storage/logs/error.log`.
