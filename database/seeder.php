<?php
require_once dirname(__DIR__) . '/app/Core/Env.php';
require_once dirname(__DIR__) . '/app/Core/Sql.php';
\App\Core\Env::load(dirname(__DIR__) . '/.env');
require_once dirname(__DIR__) . '/config/database.php';

try {
    $dsn = dsn_basis_data();
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    terapkan_schema($pdo);

    // Clear existing data
    \App\Core\Sql::truncate($pdo, [
        'mahasiswa_profiles',
        'dokumen',
        'status_history',
        'audit_logs',
        'pengajuan',
        'users',
        'divisi',
    ]);

    // Seed Users
    $password = password_hash('password123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    
    // Admin
    $stmt->execute(['Admin Bappeda', 'admin@bappeda.lampung.go.id', $password, 'admin']);
    
    // Sekretariat
    $stmt->execute(['Sekretariat (Umum & Kepegawaian)', 'sekretariat@bappeda.lampung.go.id', $password, 'sekretariat']);
    
    // Mahasiswa
    $stmt->execute(['Najwa Ramadhani', 'najwa@student.unila.ac.id', $password, 'mahasiswa']);
    $mahasiswa_id = \App\Core\Sql::lastInsertId($pdo, 'users');

    // Seed Mahasiswa Profile
    $stmt_profile = $pdo->prepare("INSERT INTO mahasiswa_profiles (user_id, nim, tempat_lahir, tanggal_lahir, universitas, fakultas, program_studi, semester, nomor_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_profile->execute([
        $mahasiswa_id,
        '2015061001',
        'Bandar Lampung',
        '2003-05-10',
        'Universitas Lampung',
        'Teknik',
        'Teknik Informatika',
        6,
        '081234567890',
        'Jl. Zainal Abidin Pagar Alam, Bandar Lampung'
    ]);

    // Seed Divisi
    $stmt_divisi = $pdo->prepare("INSERT INTO divisi (nama_divisi, deskripsi, kapasitas) VALUES (?, ?, ?)");
    $stmt_divisi->execute(['Perencanaan Makro', 'Bidang Perencanaan Makro dan Evaluasi', 5]);
    $stmt_divisi->execute(['Infrastruktur dan Wilayah', 'Bidang Infrastruktur dan Pengembangan Wilayah', 3]);
    $stmt_divisi->execute(['Ekonomi', 'Bidang Perekonomian', 4]);
    $stmt_divisi->execute(['Pemerintahan dan Sosial Budaya', 'Bidang Pemerintahan dan Pembangunan Manusia', 2]);

    echo "Database seeded successfully.\n";

} catch (PDOException $e) {
    die("Seeding failed: " . $e->getMessage());
}
