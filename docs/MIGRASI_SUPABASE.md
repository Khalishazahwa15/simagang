# MENJALANKAN DI ATAS POSTGRESQL / SUPABASE

Sistem ini berjalan di MySQL maupun PostgreSQL. Penggeraknya dipilih lewat
`DB_DRIVER` di `.env`, tanpa mengubah kode. Dokumen ini berisi hal-hal yang
hanya berlaku untuk PostgreSQL/Supabase.

---

## Cara menjalankan

Simpan kredensial Supabase di `.env.supabase` (diabaikan Git), lalu pilih
berkas itu lewat `SIMAGANG_ENV` — jangan menimpa `.env`, agar bisa
bergantian tanpa menyunting apa pun:

```bash
SIMAGANG_ENV=.env.supabase php database/pasang.php     # pasang struktur
SIMAGANG_ENV=.env.supabase php tests/TestJalurRusak.php # uji di Supabase
php tests/TestJalurRusak.php                            # uji di MySQL
```

Isi `.env.supabase`:

```ini
DB_DRIVER=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_NAME=postgres
DB_USER=postgres.<ref-proyek>
DB_PASS=<kata sandi basis data>
DB_SSLMODE=require
DB_SCHEMA=public
```

---

## Wajib Session pooler, bukan Transaction pooler

Supabase menyediakan tiga bentuk koneksi. Yang boleh dipakai hanya
**Session pooler (port 5432)** atau Direct connection.

**Transaction pooler (port 6543) akan merusak aplikasi ini.** Mode transaksi
pgbouncer tidak mendukung prepared statement sungguhan, sedangkan
`app/Core/Database.php` menyetel `PDO::ATTR_EMULATE_PREPARES = false` demi
keamanan dari injeksi SQL.

Yang membuatnya berbahaya: koneksi awalnya tetap berhasil, jadi kesalahan ini
tidak kelihatan saat dicoba sekilas. Kegagalannya baru muncul acak ketika
beberapa permintaan berjalan bersamaan.

---

## Prasyarat

Ekstensi `pdo_pgsql` harus aktif. Ia tidak menyala secara bawaan di Laragon
maupun XAMPP:

```bash
php -r "echo implode(', ', PDO::getAvailableDrivers());"
# harus memuat: mysql, pgsql
```

Bila belum ada, hapus tanda `;` pada baris `extension=pdo_pgsql` di `php.ini`,
lalu mulai ulang web server. Hosting produksi juga harus menyediakannya.

---

## Aturan menulis kueri lintas penggerak

Seluruh perbedaan dialek dikumpulkan di `app/Core/Sql.php`. **Tambahkan
perbedaan baru di sana, jangan menulis percabangan penggerak di controller
atau model.**

| Metode | Menangani |
|---|---|
| `searchText()` | `LIKE` menjadi `ILIKE` |
| `searchNumber()` | pencarian teks pada kolom angka |
| `nowPlusHours()`, `plusMinutesParam()`, `nowMinusMinutesParam()` | aritmetika tanggal |
| `secondsFromNow()` | `TIMESTAMPDIFF` menjadi `EXTRACT(EPOCH FROM ...)` |
| `currentSchema()` | `DATABASE()` menjadi `current_schema()` |
| `lastInsertId()` | menambahkan nama sequence |
| `truncate()` | pengosongan tabel beserta reset urutan |

Dua hal berikut lolos dari pemeriksaan teks kueri karena sintaksisnya sah di
kedua penggerak, jadi harus diingat saat menulis kueri baru:

- **Bandingkan boolean dengan `TRUE`/`FALSE`, bukan `1`/`0`.** MySQL
  memperlakukan `BOOLEAN` sebagai `TINYINT(1)` sehingga `= 1` sah di sana,
  sementara PostgreSQL menolaknya.
- **Jangan pakai `LIKE` pada kolom integer.** PostgreSQL menolaknya; lewatkan
  melalui `Sql::searchNumber()`.

Gejala keduanya bisa senyap: pencarian yang selalu mengembalikan kosong di
PostgreSQL biasanya berasal dari salah satu hal di atas.

---

## Yang perlu diperhatikan

- **Skema ditulis di dua berkas.** `database/schema.sql` (MySQL) dan
  `database/schema.pgsql.sql` (PostgreSQL) harus diubah bersamaan. Perubahan
  yang hanya masuk ke salah satunya akan lolos pengujian di satu penggerak dan
  gagal di penggerak lain. Jalankan `php tools/banding-skema.php` untuk
  memeriksanya; perintah itu keluar dengan kode 1 bila keduanya berbeda.
- **Row Level Security tidak ikut campur** selama akses hanya lewat aplikasi,
  karena PDO menyambung langsung sebagai pengguna basis data, bukan lewat
  PostgREST. **Bila kelak ada klien yang mengakses lewat API Supabase,
  keputusan ini harus ditinjau ulang.**
- **Autentikasi dan penyimpanan dokumen tidak memakai layanan Supabase.**
  Akun tetap di tabel `users` sendiri dan berkas tetap di disk server, sehingga
  seluruh pemeriksaan kepemilikan berkas yang sudah teruji tetap berlaku.
- **Bersihkan data uji** dari schema `public` Supabase sebelum dipakai
  sungguhan.
