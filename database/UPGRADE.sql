-- Perubahan struktur untuk instalasi yang sudah terlanjur ada.
--
-- Tidak perlu dijalankan sendiri. `php database/pasang.php` sudah menerapkan
-- berkas ini setelah skema, baik pada instalasi baru maupun instalasi lama.
--
-- Seluruh perintah di sini aman dijalankan berulang. Setiap perubahan
-- diperiksa dulu ke information_schema, sehingga tidak ada galat "Duplicate
-- column" yang membuat sisa berkas berhenti di tengah jalan.
--

USE simagang_db;


-- =====================================================================
-- 2026-08-19 - Tahap 1
-- Formulir profil mengirim tiga field yang belum punya kolom, sehingga
-- setiap penyimpanan profil gagal dan seluruh transaksinya dibatalkan.
-- =====================================================================

SET @ada := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mahasiswa_profiles'
               AND COLUMN_NAME = 'tempat_lahir');
SET @sql := IF(@ada = 0,
    'ALTER TABLE mahasiswa_profiles ADD COLUMN tempat_lahir VARCHAR(100) NULL AFTER nim',
    'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mahasiswa_profiles'
               AND COLUMN_NAME = 'tanggal_lahir');
SET @sql := IF(@ada = 0,
    'ALTER TABLE mahasiswa_profiles ADD COLUMN tanggal_lahir DATE NULL AFTER tempat_lahir',
    'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mahasiswa_profiles'
               AND COLUMN_NAME = 'fakultas');
SET @sql := IF(@ada = 0,
    'ALTER TABLE mahasiswa_profiles ADD COLUMN fakultas VARCHAR(150) NULL AFTER universitas',
    'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;


-- =====================================================================
-- 2026-08-19 - Tahap 2
-- Tabel pencatat percobaan login untuk pembatasan laju.
-- WAJIB ada: tanpa tabel ini halaman login akan galat.
-- =====================================================================

CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_waktu (email, attempted_at),
    INDEX idx_ip_waktu (ip_address, attempted_at)
) ENGINE=InnoDB;


-- =====================================================================
-- 2026-08-19 - Tahap 3
-- Kolom waktu login terakhir. Halaman Kelola Pengguna sebelumnya
-- menampilkan updated_at dengan label "Terakhir Aktif", padahal kolom itu
-- berubah setiap kali data pengguna disunting, bukan saat pengguna masuk.
-- =====================================================================

SET @ada := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'
               AND COLUMN_NAME = 'last_login_at');
SET @sql := IF(@ada = 0,
    'ALTER TABLE users ADD COLUMN last_login_at DATETIME NULL AFTER reset_token_expires',
    'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;


-- Indeks sekunder. Sebelumnya tidak ada satu pun, padahal setiap halaman
-- daftar menyaring dan mengurutkan lewat kolom-kolom ini.

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'
               AND INDEX_NAME = 'idx_users_role_status');
SET @sql := IF(@ada = 0,
    'ALTER TABLE users ADD INDEX idx_users_role_status (role, status)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pengajuan'
               AND INDEX_NAME = 'idx_pengajuan_status');
SET @sql := IF(@ada = 0,
    'ALTER TABLE pengajuan ADD INDEX idx_pengajuan_status (status)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pengajuan'
               AND INDEX_NAME = 'idx_pengajuan_dibuat');
SET @sql := IF(@ada = 0,
    'ALTER TABLE pengajuan ADD INDEX idx_pengajuan_dibuat (created_at)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pengajuan'
               AND INDEX_NAME = 'idx_pengajuan_user_status');
SET @sql := IF(@ada = 0,
    'ALTER TABLE pengajuan ADD INDEX idx_pengajuan_user_status (user_id, status)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'notifications'
               AND INDEX_NAME = 'idx_notif_user_baca');
SET @sql := IF(@ada = 0,
    'ALTER TABLE notifications ADD INDEX idx_notif_user_baca (user_id, is_read)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'audit_logs'
               AND INDEX_NAME = 'idx_audit_dibuat');
SET @sql := IF(@ada = 0,
    'ALTER TABLE audit_logs ADD INDEX idx_audit_dibuat (created_at)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @ada := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'audit_logs'
               AND INDEX_NAME = 'idx_audit_entitas');
SET @sql := IF(@ada = 0,
    'ALTER TABLE audit_logs ADD INDEX idx_audit_entitas (entity, entity_id)', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;


-- =====================================================================
-- 2026-08-19 - Audit ketiga
-- Sinkronisasi tanggal berjalan tanpa sesi pengguna. Selama changed_by
-- wajib terisi, seluruh transisi otomatis (diterima -> sedang_magang dan
-- sedang_magang -> selesai) gagal diam-diam bagi pengunjung anonim.
-- =====================================================================

SET @nullable := (SELECT IS_NULLABLE FROM information_schema.COLUMNS
                  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'status_history'
                    AND COLUMN_NAME = 'changed_by');

SET @fk := (SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'status_history'
              AND COLUMN_NAME = 'changed_by' AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1);

SET @sql := IF(@nullable = 'NO' AND @fk IS NOT NULL,
    CONCAT('ALTER TABLE status_history DROP FOREIGN KEY ', @fk), 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @sql := IF(@nullable = 'NO',
    'ALTER TABLE status_history MODIFY changed_by INT NULL', 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;

SET @sql := IF(@nullable = 'NO' AND @fk IS NOT NULL,
    CONCAT('ALTER TABLE status_history ADD CONSTRAINT ', @fk,
           ' FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL'), 'DO 0');
PREPARE p FROM @sql; EXECUTE p; DEALLOCATE PREPARE p;


SELECT 'UPGRADE.sql selesai dijalankan.' AS hasil;
