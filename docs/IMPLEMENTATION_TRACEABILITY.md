# IMPLEMENTATION TRACEABILITY MATRIX (KOREKSI FINAL)

| PRD ID | Requirement | Module | File(s) | Status |
|--------|-------------|--------|---------|--------|
| BR-001 | Individual Application: 1 mhs = 1 akun = 1 pengajuan. TIDAK ADA fitur kelompok/tim. | Pengajuan | `MahasiswaController`, `PengajuanService` | DONE |
| BR-003 | Keputusan berdasarkan prodi & kebutuhan operasional. Kapasitas HANYA info, tidak menolak submission secara otomatis. | Sekretariat | `SekretariatController`, `bidang.php` | DONE |
| BR-005 | Preferensi divisi non-mengikat. Sekretariat menetapkan divisi final. TIDAK ADA persetujuan balik mahasiswa. | Sekretariat | `SekretariatController` | DONE |
| BR-007 | Proses surat, paraf, & TTD Bappeda di LUAR sistem. Sistem BUKAN generator surat/digital signature. | Dokumen | `pengajuan_detail.php`, `status.php` | DONE |
| BR-008 | Rolling registration (berdasar tanggal individu). TIDAK ADA "periode magang" global (ganjil/genap). | Pengajuan | `MahasiswaController`, `PengajuanService` | DONE |
| BR-009 | Re-apply pasca penolakan membuat record BARU. Histori lama tidak ditimpa/dihapus. | Pengajuan | `MahasiswaController`, `PengajuanService` | DONE |
| BR-010 | Pengunduran diri WAJIB surat kampus resmi + alasan, diverifikasi Sekretariat pasca Diterima. | Keputusan | `MahasiswaController`, `pengunduran_diri.php`, `SekretariatController` | DONE |
| BR-011 | Revisi menggunakan Versioning. Versi lama dipertahankan, riwayat perubahan & catatan terlihat oleh mahasiswa. | Dokumen | `MahasiswaController`, `DokumenService`, `status.php` | DONE |
| BR-012 | Penolakan wajib alasan + notifikasi. TIDAK ADA pembuatan/surat penolakan. | Keputusan | `SekretariatController` | DONE |
| BR-014 | Sekretariat = 1 role login. Aktor operasional (Kepala, dll) TIDAK dibuat sebagai role login/approval di sistem. | RBAC | `schema.sql`, `Auth.php` | DONE |
| SEC-05 | IDOR Prevention. Mahasiswa hanya dapat mengunduh dokumen miliknya sendiri. | Dokumen | `DokumenService` | DONE |
| FR-015 | Sekretariat input penempatan final & pembina/pembimbing. Status langsung DITERIMA. | Sekretariat | `SekretariatController`, `pengajuan_detail.php` | DONE |
| FR-016 | Sekretariat tetapkan Ditolak (alasan wajib) tanpa surat otomatis. | Sekretariat | `SekretariatController`, `pengajuan_detail.php` | DONE |
| FR-018 | Sekretariat unggah dokumen final (Surat Penerimaan) hasil proses luar sistem. | Dokumen | `SekretariatController`, `pengajuan_detail.php` | DONE |
| FR-020 | Sekretariat unggah dokumen akhir (Selesai/Penilaian/Sertifikat) hasil proses luar sistem. | Dokumen | `SekretariatController`, `pengajuan_detail.php` | DONE |
| SEC-04 | State machine protection di layer backend (DRAFT -> DITERIMA = ILEGAL). | Services | `PengajuanService`, `SekretariatController` | DONE |
| UI-02  | Visual dan Layout mengacu pada Figma Make. Hindari elemen desain generik / glassmorphism. | Views | `app/Views/*` | DONE |
| FR-031 | Fitur Lupa dan Reset Password menggunakan token satu kali pakai (1 jam). | Auth | `AuthController`, `schema.sql` | DONE |
| FR-032 | Sistem Notifikasi untuk setiap perubahan status pengajuan. | Services | `NotificationService`, `StatusService`, `topbar.php` | DONE |
| FR-033 | Fitur Laporan Ekspor CSV untuk Sekretariat. | Sekretariat | `SekretariatController`, `laporan.php` | DONE |
| FR-034 | Log Audit aktivitas administratif (perubahan status, manajemen user, divisi). | Admin | `AdminController`, `audit_log.php`, `AuditService` | DONE |
