-- Perubahan struktur untuk instalasi yang sudah terlanjur ada.
-- schema.sql memakai CREATE TABLE IF NOT EXISTS, jadi database lama tidak ikut
-- berubah saat menarik perubahan dari Git. Jalankan berkas ini sekali.
--
-- Instalasi baru tidak perlu menjalankan berkas ini; schema.sql sudah lengkap.
--
--   mysql -u root simagang_db < database/UPGRADE.sql
--
-- Galat "Duplicate column name" atau "table already exists" berarti bagian
-- tersebut sudah pernah dijalankan dan aman diabaikan.

USE simagang_db;

-- 2026-08-19 - Tahap 1
-- Formulir profil mengirim tiga field yang belum punya kolom, sehingga setiap
-- penyimpanan profil gagal dan seluruh transaksinya dibatalkan.
ALTER TABLE mahasiswa_profiles
  ADD COLUMN tempat_lahir  VARCHAR(100) NULL AFTER nim,
  ADD COLUMN tanggal_lahir DATE         NULL AFTER tempat_lahir,
  ADD COLUMN fakultas      VARCHAR(150) NULL AFTER universitas;

-- 2026-08-19 - Tahap 2
-- Tabel pencatat percobaan login untuk pembatasan laju.
-- WAJIB dijalankan: tanpa tabel ini halaman login akan galat.
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_waktu (email, attempted_at),
    INDEX idx_ip_waktu (ip_address, attempted_at)
) ENGINE=InnoDB;

-- 2026-08-19 - Tahap 3
-- Indeks sekunder. Sebelumnya tidak ada satu pun, padahal setiap halaman
-- daftar menyaring dan mengurutkan lewat kolom-kolom ini.
ALTER TABLE users      ADD INDEX idx_users_role_status (role, status);
ALTER TABLE pengajuan  ADD INDEX idx_pengajuan_status (status),
                       ADD INDEX idx_pengajuan_dibuat (created_at),
                       ADD INDEX idx_pengajuan_user_status (user_id, status);
ALTER TABLE notifications ADD INDEX idx_notif_user_baca (user_id, is_read);
ALTER TABLE audit_logs ADD INDEX idx_audit_dibuat (created_at),
                       ADD INDEX idx_audit_entitas (entity, entity_id);

-- Kolom waktu login terakhir. Halaman Kelola Pengguna sebelumnya
-- menampilkan updated_at dengan label "Terakhir Aktif", padahal kolom itu
-- berubah setiap kali data pengguna disunting, bukan saat pengguna masuk.
ALTER TABLE users ADD COLUMN last_login_at DATETIME NULL AFTER reset_token_expires;
