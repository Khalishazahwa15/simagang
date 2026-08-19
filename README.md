# SIMAGANG Bappeda Provinsi Lampung

Sistem Informasi Magang (SIMAGANG) Bappeda Provinsi Lampung adalah platform manajemen magang terpadu untuk memfasilitasi administrasi dan proses bisnis magang, mulai dari tahap pengajuan hingga penerbitan sertifikat selesai magang.

Proyek ini dibangun menggunakan **PHP Native MVC**, **MySQL**, tanpa bantuan framework atau Composer untuk sisi backend-nya. Desain UI disesuaikan agar rapi dan profesional.

---

## Prasyarat

- **PHP** versi 7.4 atau lebih baru (disarankan 8.x)
- **MySQL** versi 5.7 atau lebih baru
- Web Server lokal: Laragon, XAMPP, MAMP, dsb.

---

## Cara Instalasi di Lingkungan Lokal (Development)

1. **Clone Repository (atau ekstrak folder)**
   Pindahkan folder utama (misal: `simagang`) ke direktori root dari web server Anda.
   - Laragon: `C:\laragon\www\simagang`
   - XAMPP: `C:\xampp\htdocs\simagang`

2. **Setup Konfigurasi (.env)**
   Salin file `.env.example` menjadi `.env`.
   Buka file `.env` dan atur parameter sesuai server lokal Anda. Contoh:
   ```ini
   APP_ENV=development
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=simagang
   DB_USER=root
   DB_PASS=
   ```

3. **Impor Skema Database**
   - Buat database baru bernama `simagang` di phpMyAdmin / MySQL CLI.
   - Impor file `database/schema.sql` ke dalam database tersebut. File ini memuat struktur tabel standar.

4. **Isi Data Dummy (Seeder)**
   Untuk keperluan tes, Anda dapat menjalankan seeder dengan memanggil skrip dari terminal / browser:
   - Via Terminal: `php database/seeder.php`
   - Via Browser: Akses `http://localhost/simagang/database/seeder.php`

5. **Jalankan Aplikasi**
   Akses `http://localhost/simagang` di web browser. Anda bisa langsung mencoba login.

---

## Akun Login Tes

Jika Anda mengeksekusi `seeder.php`, beberapa akun yang bisa dipakai untuk menguji aplikasi:

- **Admin**:
  - Email: `admin@bappeda.lampungprov.go.id`
  - Pass : `password123`
- **Sekretariat**:
  - Email: `sekretariat@bappeda.lampungprov.go.id`
  - Pass : `password123`
- **Mahasiswa**:
  - Email: `najwa@students.unila.ac.id`
  - Pass : `password123`

---

## Struktur Folder Utama

- `app/` - Inti aplikasi MVC (Models, Views, Controllers, Services).
- `app/Core/` - Router, Session, Middleware, Env loader, dan kelas utama lainnya.
- `config/` - Konfigurasi routing (routes.php) dan database (database.php).
- `database/` - Skema asli (`schema.sql`) dan skrip populasi data (`seeder.php`).
- `public/` - Direktori web-facing (CSS, JavaScript, dan Front Controller `index.php`).
- `storage/` - Penyimpanan file hasil upload dan catatan log.
  - `storage/uploads/` - Lokasi asli dokumen mahasiswa.
  - `storage/logs/` - File error saat berjalan pada *production mode*.

---

## Manajemen Lingkungan (Environment)

- **Development**: Set `APP_ENV=development` di `.env` untuk menampilkan semua pesan error secara langsung di browser demi kemudahan debugging.
- **Production**: Set `APP_ENV=production` untuk menyembunyikan error demi keamanan dan menuliskannya di file `storage/logs/error.log`.
