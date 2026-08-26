# Dokumentasi SIMAGANG

Mulai dari `../README.md` bila Anda ingin memasang dan menjalankan sistemnya.
Berkas-berkas di folder ini menjelaskan **kenapa** sistemnya dibuat seperti
sekarang.

## Baca sesuai kebutuhan

| Bila Anda ingin | Baca |
|---|---|
| Tahu apa yang boleh dan tidak boleh dilakukan sistem | [ATURAN_BISNIS.md](ATURAN_BISNIS.md) |
| Menelusuri kebutuhan resmi beserta sejarah revisinya | [PRD.md](PRD.md) |
| Memastikan tiap butir PRD sudah dikerjakan atau belum | [KETERLACAKAN_PRD.md](KETERLACAKAN_PRD.md) |
| Memahami tabel, relasi, dan alasan rancangannya | [RANCANGAN_BASIS_DATA.md](RANCANGAN_BASIS_DATA.md) |
| Mengubah tampilan tanpa merusak arah visualnya | [PANDUAN_DESAIN.md](PANDUAN_DESAIN.md) |
| Menjalankan sistem di atas PostgreSQL atau Supabase | [MIGRASI_SUPABASE.md](MIGRASI_SUPABASE.md) |
| Melihat hasil pengujian migrasi beserta angkanya | [VERIFIKASI_MIGRASI.html](VERIFIKASI_MIGRASI.html) |

`VERIFIKASI_MIGRASI.html` dibuka langsung di peramban, tidak perlu server.
Berkas itu berdiri sendiri sehingga bisa dikirim apa adanya ke pihak lain.

## Urutan yang disarankan untuk anggota baru

1. `../README.md` — pasang dan jalankan sistemnya lebih dulu
2. `ATURAN_BISNIS.md` — pahami aturannya secara ringkas
3. `RANCANGAN_BASIS_DATA.md` — kenali datanya
4. `PRD.md` — baru masuk ke dokumen resmi yang panjang

## Bila dokumen bertentangan dengan kode

Yang berlaku bergantung jenis perbedaannya:

- **Aturan bisnis berbeda** — `PRD.md` yang berlaku, dan kodenya yang perlu
  diperbaiki. Kecuali bila tim sudah memutuskan sebaliknya; keputusan seperti
  itu dicatat di `PRD.md` sebagai revisi bernomor, bukan disimpan di kepala.
- **Struktur tabel berbeda** — `database/schema.sql` dan
  `database/schema.pgsql.sql` yang berlaku. `RANCANGAN_BASIS_DATA.md` hanya
  merangkum.

Dua butir yang pernah lama menggantung, BR-005 dan BR-014, ditutup pada
20 Agustus 2026 lewat revisi PRD v4.2 — bukan dengan mengubah kode.
