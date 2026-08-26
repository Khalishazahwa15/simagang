CREATE DATABASE IF NOT EXISTS simagang_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simagang_db;

-- 1. users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('mahasiswa', 'sekretariat', 'admin') NOT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    INDEX idx_users_role_status (role, status),
    reset_token VARCHAR(64) NULL,
    reset_token_expires DATETIME NULL,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. mahasiswa_profiles
CREATE TABLE IF NOT EXISTS mahasiswa_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    nim VARCHAR(50) NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    universitas VARCHAR(150) NOT NULL,
    fakultas VARCHAR(150),
    program_studi VARCHAR(150) NOT NULL,
    semester INT,
    nomor_hp VARCHAR(20),
    alamat TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. divisi
CREATE TABLE IF NOT EXISTS divisi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_divisi VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    kapasitas INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB;

-- 4. pengajuan
CREATE TABLE IF NOT EXISTS pengajuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_pengajuan VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    divisi_id_preferensi INT,
    divisi_id_tawaran INT NULL,
    divisi_id_final INT NULL,
    tanggal_mulai_rencana DATE NOT NULL,
    tanggal_selesai_rencana DATE NOT NULL,
    tanggal_mulai_aktual DATE NULL,
    tanggal_selesai_aktual DATE NULL,
    status ENUM('draft', 'diajukan', 'dalam_verifikasi', 'revisi', 'menunggu_konfirmasi_tawaran', 'menunggu_finalisasi_sekretariat', 'diterima', 'ditolak', 'dibatalkan_oleh_mahasiswa', 'sedang_magang', 'selesai', 'mengundurkan_diri') DEFAULT 'draft',
    alasan_penolakan TEXT NULL,
    catatan_verifikasi TEXT NULL,
    pembina_lapangan VARCHAR(150) NULL,
    diputuskan_oleh INT NULL,
    diputuskan_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pengajuan_status (status),
    INDEX idx_pengajuan_dibuat (created_at),
    INDEX idx_pengajuan_user_status (user_id, status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (divisi_id_preferensi) REFERENCES divisi(id) ON DELETE SET NULL,
    FOREIGN KEY (divisi_id_tawaran) REFERENCES divisi(id) ON DELETE SET NULL,
    FOREIGN KEY (divisi_id_final) REFERENCES divisi(id) ON DELETE SET NULL,
    FOREIGN KEY (diputuskan_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. dokumen
CREATE TABLE IF NOT EXISTS dokumen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT NOT NULL,
    jenis_dokumen VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    version INT DEFAULT 1,
    is_current BOOLEAN DEFAULT TRUE,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 6. status_history
CREATE TABLE IF NOT EXISTS status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT NOT NULL,
    status_awal VARCHAR(50) NOT NULL,
    status_baru VARCHAR(50) NOT NULL,
    changed_by INT NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pengajuan_id INT NULL,
    pesan TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notif_user_baca (user_id, is_read),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. login_attempts
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_waktu (email, attempted_at),
    INDEX idx_ip_waktu (ip_address, attempted_at)
) ENGINE=InnoDB;

-- 9. audit_logs
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    entity VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_dibuat (created_at),
    INDEX idx_audit_entitas (entity, entity_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;
