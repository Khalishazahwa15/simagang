# IMPLEMENTATION TRACEABILITY MATRIX

Status pada tabel ini ditinjau ulang pada **19 Agustus 2026** terhadap kode yang
berjalan, bukan sekadar diasumsikan selesai. Baris yang tidak berstatus `DONE`
disertai catatan mengenai apa yang masih kurang dan langkah tindak lanjutnya.

| PRD ID | Requirement | Module | File(s) | Status | Catatan |
|--------|-------------|--------|---------|--------|---------|
| BR-001 | Individual Application: 1 mhs = 1 akun = 1 pengajuan. TIDAK ADA fitur kelompok/tim. | Pengajuan | `MahasiswaController`, `PengajuanService` | DONE |  |
| BR-003 | Keputusan berdasarkan prodi & kebutuhan operasional. Kapasitas HANYA info, tidak menolak submission secara otomatis. | Sekretariat | `SekretariatController`, `bidang.php` | DONE |  |
| BR-005 | Preferensi divisi non-mengikat. Sekretariat menetapkan divisi final. TIDAK ADA persetujuan balik mahasiswa. | Sekretariat | `SekretariatController` | TIDAK SESUAI | Alur tawaran divisi dengan konfirmasi mahasiswa masih ada dan justru diperluas. Dua status (`menunggu_konfirmasi_tawaran`, `menunggu_finalisasi_sekretariat`) dan tiga rute tawaran masih terdaftar. Perlu keputusan: hapus alurnya atau ubah PRD. |
| BR-007 | Proses surat, paraf, & TTD Bappeda di LUAR sistem. Sistem BUKAN generator surat/digital signature. | Dokumen | `pengajuan_detail.php`, `status.php` | DONE |  |
| BR-008 | Rolling registration (berdasar tanggal individu). TIDAK ADA "periode magang" global (ganjil/genap). | Pengajuan | `MahasiswaController`, `PengajuanService` | DONE |  |
| BR-009 | Re-apply pasca penolakan membuat record BARU. Histori lama tidak ditimpa/dihapus. | Pengajuan | `MahasiswaController`, `PengajuanService` | DONE |  |
| BR-010 | Pengunduran diri WAJIB surat kampus resmi + alasan, diverifikasi Sekretariat pasca Diterima. | Keputusan | `MahasiswaController`, `pengunduran_diri.php`, `SekretariatController` | DONE |  |
| BR-011 | Revisi menggunakan Versioning. Versi lama dipertahankan, riwayat perubahan & catatan terlihat oleh mahasiswa. | Dokumen | `MahasiswaController`, `DokumenService`, `status.php` | DONE |  |
| BR-012 | Penolakan wajib alasan + notifikasi. TIDAK ADA pembuatan/surat penolakan. | Keputusan | `SekretariatController` | DONE |  |
| BR-014 | Sekretariat = 1 role login. Aktor operasional (Kepala, dll) TIDAK dibuat sebagai role login/approval di sistem. | RBAC | `schema.sql`, `Auth.php` | PERLU PEMUTAKHIRAN PRD | Peran login tetap tiga (admin, sekretariat, mahasiswa). Namun Admin kini juga berwenang memutuskan penerimaan, sedangkan PRD v4.1 catatan penutup butir 4 menyebut Admin sebagai peran teknis. Tim memutuskan 19 Agustus 2026 bahwa Admin memang berperan sebagai super admin, sehingga PRD yang perlu disesuaikan, bukan kodenya. |
| SEC-05 | IDOR Prevention. Mahasiswa hanya dapat mengunduh dokumen miliknya sendiri. | Dokumen | `DokumenService` | DONE |  |
| FR-015 | Sekretariat input penempatan final & pembina/pembimbing. Status langsung DITERIMA. | Sekretariat | `SekretariatController`, `pengajuan_detail.php` | DONE |  |
| FR-016 | Sekretariat tetapkan Ditolak (alasan wajib) tanpa surat otomatis. | Sekretariat | `SekretariatController`, `pengajuan_detail.php` | DONE |  |
| FR-018 | Sekretariat unggah dokumen final (Surat Penerimaan) hasil proses luar sistem. | Dokumen | `SekretariatController`, `pengajuan_detail.php` | DONE |  |
| FR-020 | Sekretariat unggah dokumen akhir (Selesai/Penilaian/Sertifikat) hasil proses luar sistem. | Dokumen | `SekretariatController`, `pengajuan_detail.php` | DONE |  |
| SEC-04 | State machine protection di layer backend (DRAFT -> DITERIMA = ILEGAL). | Services | `StatusService`, `PengajuanService`, `SekretariatController` | DONE | 19 Agustus 2026: status `dibatalkan` dihapus dari peta transisi karena tidak ada di ENUM `pengajuan.status` dan tidak pernah dipakai. Transisi otomatis saat halaman detail dibuka juga dipindahkan ke aksi POST. |
| UI-02 | Visual dan Layout mengacu pada Figma Make. Hindari elemen desain generik / glassmorphism. | Views | `app/Views/*` | DONE | 19 Agustus 2026: antarmuka internal kini muat di layar 382px tanpa geser mendatar (sebelumnya halaman melebar sampai 968px), dan 220 warna sisa palet lama dialihkan ke token di `tokens.css`. |
| FR-031 | Fitur Lupa dan Reset Password menggunakan token satu kali pakai (1 jam). | Auth | `AuthController`, `schema.sql` | DONE | Baru benar-benar berfungsi sejak 19 Agustus 2026. Sebelumnya halaman galat fatal karena `Session::getFlash()` belum ada, dan setiap token langsung kedaluwarsa karena masa berlaku ditulis dengan jam PHP lalu dibandingkan dengan jam MySQL. Token kini juga disimpan sebagai hash SHA-256. |
| FR-032 | Sistem Notifikasi untuk setiap perubahan status pengajuan. | Services | `NotificationService`, `StatusService`, `topbar.php` | DONE |  |
| FR-033 | Fitur Laporan Ekspor CSV untuk Sekretariat. | Sekretariat | `SekretariatController`, `laporan.php` | DONE | 19 Agustus 2026: penyaring periode dan divisi ditambahkan, dipakai bersama oleh ringkasan di layar dan berkas CSV. |
| FR-034 | Log Audit aktivitas administratif (perubahan status, manajemen user, divisi). | Admin | `AdminController`, `audit_log.php`, `AuditService` | DONE |  |

## Ringkasan

- **DONE** — 19 butir
- **TIDAK SESUAI** — 1 butir (BR-005)
- **PERLU PEMUTAKHIRAN PRD** — 1 butir (BR-014)
