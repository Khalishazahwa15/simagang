# RENCANA MIGRASI KE SUPABASE

Supabase adalah PostgreSQL. Seluruh kode SIMAGANG saat ini ditulis untuk MySQL,
jadi migrasinya bukan sekadar mengganti alamat basis data.

Dokumen ini disusun 19 Agustus 2026 dari pemindaian kode, bukan perkiraan.
Angka pada tiap baris adalah jumlah kejadian nyata di dalam repositori.

---

## Ringkasan

- **9 tabel**, 97 titik sentuh yang perlu disesuaikan
- **88 pemanggilan kueri** tersebar di `app/`
- Perkiraan: **3–5 hari** bila hanya memindahkan basis data (autentikasi dan
  penyimpanan dokumen tetap di aplikasi sendiri). Lebih lama bila ikut memakai
  Supabase Auth dan Supabase Storage.

---

## Prasyarat lingkungan

| Kebutuhan | Status saat ini |
|---|---|
| Ekstensi PHP `pdo_pgsql` | **Belum aktif.** Hanya `pdo_mysql` yang menyala |
| Koneksi SSL | Wajib oleh Supabase; DSN perlu `sslmode=require` |
| Hosting produksi | Harus menyediakan `pdo_pgsql` juga |

Aktifkan lebih dulu di `php.ini` sebelum apa pun dikerjakan:

```ini
extension=pdo_pgsql
extension=pgsql
```

---

## Penghalang di lapisan skema

Seluruhnya di `database/schema.sql` dan `database/UPGRADE.sql`.

| Hal | Jumlah | Perlu jadi |
|---|---|---|
| `AUTO_INCREMENT` | 10 | `GENERATED ALWAYS AS IDENTITY` |
| `ENGINE=InnoDB` | 10 | dihapus |
| `INDEX` di dalam `CREATE TABLE` | 11 | `CREATE INDEX` terpisah setelah tabel dibuat |
| `ENUM(...)` | 4 | `CREATE TYPE ... AS ENUM` atau `CHECK` constraint |
| `ON UPDATE CURRENT_TIMESTAMP` | 3 | trigger `BEFORE UPDATE` buatan sendiri |
| `utf8mb4` / collation | 3 | dihapus; PostgreSQL memakai UTF-8 secara bawaan |

### Catatan tentang ENUM

Kolom `pengajuan.status` memuat 12 nilai dan **wajib tetap cocok** dengan peta
transisi di `StatusService::$allowedTransitions`. Pengujian sudah memeriksa
kecocokan ini, jadi jalankan `tests/TestJalurRusak.php` setelah konversi.

`CHECK` constraint lebih mudah diubah daripada `CREATE TYPE` bila kelak ada
status baru — `ALTER TYPE ... ADD VALUE` di PostgreSQL tidak bisa dibatalkan
dalam transaksi.

---

## Penghalang di lapisan kode

| Hal | Jumlah | Berkas utama | Perlu jadi |
|---|---|---|---|
| `LIKE ?` pada pencarian | 14 | `AdminController`, `SekretariatController` | `ILIKE` |
| `lastInsertId()` | 12 | 8 berkas | `lastInsertId('namatabel_id_seq')` atau `RETURNING id` |
| `TRUNCATE` + `FOREIGN_KEY_CHECKS` | 9 | `database/seeder.php` | `TRUNCATE ... RESTART IDENTITY CASCADE` |
| `DATE_ADD` / `DATE_SUB` | 4 | `LoginThrottleService`, `AuthController` | `NOW() + INTERVAL '15 minutes'` |
| `TIMESTAMPDIFF()` | 1 | `LoginThrottleService` | `EXTRACT(EPOCH FROM ...)` |
| `IFNULL()` | 1 | `AdminController` | `COALESCE()` |
| DSN `mysql:` | 2 | `app/Core/Database.php`, `tests/bootstrap.php` | `pgsql:` |
| Kueri `information_schema` | 14 | `UPGRADE.sql` | nama kolom berbeda di PostgreSQL |

### Yang paling berbahaya: `LIKE`

MySQL membandingkan `LIKE` **tanpa** peduli huruf besar-kecil. PostgreSQL
peduli. Tanpa diubah ke `ILIKE`, mencari `najwa` tidak akan menemukan `Najwa`.

Tidak ada galat, tidak ada peringatan — hasil pencariannya hanya kosong. Ini
kegagalan senyap, jadi periksa manual seluruh halaman berpenyaring setelah
migrasi: Kelola Pengajuan, Peserta Magang, Arsip Dokumen, Kelola Pengguna,
Audit Log.

### Catatan `lastInsertId()`

Di PostgreSQL, `lastInsertId()` tanpa argumen tidak mengembalikan id baris yang
baru dibuat. Nama sequence-nya harus disebutkan, atau lebih baik lagi kueri
`INSERT`-nya diubah memakai `RETURNING id`.

Perhatikan `PengajuanService::createPengajuan()`: nomor pengajuan dibentuk dari
id baris setelah baris itu dibuat, jadi bagian ini akan langsung rusak bila
`lastInsertId()` tidak disesuaikan.

---

## Tiga keputusan yang perlu diambil lebih dulu

Ketiganya menentukan besar pekerjaannya, jadi putuskan sebelum menulis kode.

**1. Autentikasi — Supabase Auth atau tabel `users` sendiri?**

Memakai Supabase Auth berarti menulis ulang `AuthService`, `LoginThrottleService`,
seluruh alur reset kata sandi, dan pemetaan peran. Tetap memakai tabel sendiri
berarti Supabase hanya berfungsi sebagai basis data biasa — jauh lebih ringan,
dan seluruh pengujian yang ada tetap berlaku.

**2. Dokumen — Supabase Storage atau disk server?**

Memakai Supabase Storage berarti menulis ulang `DokumenService`, termasuk
pemeriksaan kepemilikan berkas yang sekarang sudah teruji anti-IDOR. Tetap di
disk berarti tidak ada yang berubah.

**3. Row Level Security**

Supabase menyalakan RLS secara bawaan. Bila tabel dibuat tanpa policy, seluruh
kueri mengembalikan hasil kosong — bukan galat. Ini mudah disalahartikan sebagai
data hilang. Tentukan sejak awal: matikan RLS (aman bila akses hanya lewat
aplikasi dengan service key), atau tulis policy per tabel.

---

## Urutan pengerjaan yang disarankan

1. Aktifkan `pdo_pgsql`, buat proyek Supabase, catat kredensialnya
2. Kerjakan di **cabang terpisah**, jangan di `main`
3. Terjemahkan `schema.sql` ke PostgreSQL, jalankan di Supabase
4. Sesuaikan `app/Core/Database.php` agar DSN-nya dapat memilih penggerak
5. Perbaiki titik sentuh di lapisan kode, mulai dari `lastInsertId()` dan `LIKE`
6. Jalankan `tests/TestSystemFlow.php` dan `tests/TestJalurRusak.php` — keduanya
   memakai basis data terpisah, jadi aman dijalankan berulang
7. Telusuri manual seluruh halaman berpenyaring untuk memastikan pencarian
   masih menemukan hasil
8. Pindahkan data lama bila diperlukan

---

## Yang tidak perlu diubah

- `NOW()`, `COUNT()`, `GROUP BY`, `LIMIT`/`OFFSET`, `JOIN` — sama di kedua sisi
- Seluruh kueri sudah memakai prepared statement dengan parameter, jadi tidak
  ada perakitan string yang perlu ditulis ulang
- Foreign key, indeks sekunder, dan struktur relasinya sudah sesuai; hanya
  sintaksis pembuatannya yang berbeda
