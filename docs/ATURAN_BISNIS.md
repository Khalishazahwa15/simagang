# ATURAN BISNIS SIMAGANG

Ringkasan aturan yang ditegakkan sistem, disarikan dari PRD. Bila ada yang
berbeda antara berkas ini dan `PRD.md`, PRD yang berlaku.

Terakhir disamakan dengan sistem yang berjalan pada 20 Agustus 2026 (PRD v4.2).

## 1. Role Aplikasi vs Aktor Operasional
Terdapat perbedaan tegas antara aktor operasional Bappeda di dunia nyata dengan role login di dalam sistem SIMAGANG.
- **Aktor Operasional (Luar Sistem)**: Kepala Bappeda, Kasubag Umum dan Kepegawaian, Pelaksana/JFU, dan bidang/divisi. Mereka memproses paraf, tanda tangan, dan verifikasi administratif fisik di luar sistem.
- **Role Login Sistem**: Hanya terdapat **3 role mutlak** di dalam sistem:
  1. **Mahasiswa**: Mengajukan magang secara individu, memantau status, unggah dokumen, unduh dokumen final.
  2. **Sekretariat**: Menjalankan fungsi operasional di dalam aplikasi (verifikasi kelengkapan, minta revisi, tetapkan Diterima/Ditolak, tentukan divisi final & pembina, unggah dokumen final).
  3. **Admin**: Super admin. Mengelola master data (divisi, kapasitas) dan akun pengguna, **serta berwenang menjalankan seluruh fungsi Sekretariat**, termasuk memutuskan penerimaan dan penempatan. Disediakan agar pengajuan tetap dapat diproses bila Sekretariat berhalangan. (Direvisi pada PRD v4.2.)

**SANGAT DILARANG**: Membuat role/dashboard untuk "Kepala", "Sekretaris", atau "Verifikator" berjenjang. Sistem tidak melacak tahapan approval internal/paraf.

## 2. Individual Application (Wajib)
Sistem hanya memproses mahasiswa sebagai individu. **1 mahasiswa = 1 pengajuan = 1 keputusan**.
- TIDAK ADA fitur pengajuan kelompok/tim.
- Jika ada mahasiswa dari kampus/kelompok yang sama, pengajuan mereka tetap terpisah dan tidak saling bergantung (A bisa diterima, B bisa ditolak).

## 3. Rolling Registration
Tidak ada periode pendaftaran global (seperti periode ganjil/genap atau kuartal). Pendaftaran dibuka sepanjang waktu berdasarkan tanggal rencana yang diinput mahasiswa secara independen. Tidak ada batasan *hard rule* seperti H-7.

## 4. Kapasitas & Penempatan Divisi
- **Kapasitas Bersifat Informasional**: Angka kapasitas divisi hanya sebagai panduan bagi Sekretariat. Sistem TIDAK BOLEH melakukan *hard validation* (otomatis menolak submission karena kuota penuh).
- **Preferensi Tidak Mengikat**: Mahasiswa memberikan preferensi divisi, namun **Sekretariat yang menentukan divisi final dan pembimbing lapangan**.
- **Penempatan sesuai preferensi langsung final**: Bila Sekretariat menerima dan menempatkan mahasiswa pada divisi yang ia pilih sendiri, status langsung menjadi DITERIMA tanpa langkah tambahan.
- **Penempatan di luar preferensi ditawarkan lebih dulu**: Bila Sekretariat hendak menempatkan mahasiswa pada divisi lain, penempatan itu dikirim sebagai tawaran. Mahasiswa menyetujui atau menolaknya, lalu Sekretariat mengunci penempatannya. (Direvisi pada PRD v4.2; sebelumnya dinyatakan tidak ada konfirmasi mahasiswa sama sekali.)
- **Menolak tawaran menghentikan pengajuan**: Statusnya menjadi `dibatalkan_oleh_mahasiswa`, dan mahasiswa boleh mendaftar ulang sebagai pengajuan baru.

## 5. Dokumen Awal & Revisi
- **Dokumen Wajib Awal**: Hanya Surat Lamaran, CV, dan Transkrip Nilai (Proposal/KTP/Foto TIDAK wajib).
- **Dokumen Tambahan**: Opsional.
- **Sistem Revisi & Versioning**: Jika direvisi, mahasiswa mengunggah ulang. Dokumen lama TIDAK dihapus. Sistem menerapkan *versioning* (versi lama tersimpan, versi baru di-set sebagai *current*). Catatan revisi wajib tersimpan dan terlihat oleh mahasiswa.

## 6. Penolakan & Re-apply
Jika Sekretariat menolak:
- Status menjadi DITOLAK.
- Alasan penolakan WAJIB diisi dan dikirim via notifikasi.
- TIDAK ADA surat penolakan otomatis dari sistem.
- Jika mahasiswa mengajukan ulang (re-apply), sistem akan membuat **record pengajuan baru**. Riwayat lama tetap utuh dan tidak ditimpa.

## 7. Surat Penerimaan & Dokumen Akhir Magang
Sistem BUKAN generator surat dan TIDAK memiliki fitur *digital signature*.
- Surat Penerimaan dan Dokumen Akhir (Bukti Selesai, Sertifikat, dll) dibuat, diparaf, dan ditandatangani **secara administratif di luar sistem**.
- Sekretariat hanya melakukan **upload file final** ke sistem agar bisa diunduh mahasiswa.

## 8. Pengunduran Diri
Hanya dapat dilakukan setelah berstatus DITERIMA.
- Wajib mengunggah surat pengunduran diri resmi dari kampus dengan alasan yang profesional.
- Membutuhkan verifikasi dari Sekretariat sebelum status berubah menjadi MENGUNDURKAN_DIRI.

## 9. Status Lifecycle & Backend Protection
Jalur utama: `DRAFT` → `DIAJUKAN` → `DALAM_VERIFIKASI` → (bisa `REVISI` atau `DITOLAK`) → `DITERIMA` → `SEDANG_MAGANG` → `SELESAI`. (Serta opsi `MENGUNDURKAN_DIRI` pasca Diterima).

Jalur tawaran divisi, dipakai bila penempatannya di luar preferensi mahasiswa:
`DALAM_VERIFIKASI` → `MENUNGGU_KONFIRMASI_TAWARAN` → `MENUNGGU_FINALISASI_SEKRETARIAT` → `DITERIMA`.
Bila mahasiswa menolak tawaran: `MENUNGGU_KONFIRMASI_TAWARAN` → `DIBATALKAN_OLEH_MAHASISWA`.

Peta transisi yang berlaku ada di `StatusService::$allowedTransitions`, dan
kesesuaiannya dengan kolom `pengajuan.status` diperiksa `tests/TestJalurRusak.php`.
- **Backend Protection**: Sistem / Service Layer wajib menolak keras manipulasi POST untuk melompati status (misal: DRAFT → DITERIMA, DITOLAK → SELESAI).
