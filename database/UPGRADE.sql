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
