<?php
require_once dirname(__DIR__) . '/app/Core/Env.php';
\App\Core\Env::load(dirname(__DIR__) . '/.env');
require_once dirname(__DIR__) . '/config/database.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Clear existing data
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE mahasiswa_profiles");
    $pdo->exec("TRUNCATE TABLE dokumen");
    $pdo->exec("TRUNCATE TABLE status_history");
    $pdo->exec("TRUNCATE TABLE audit_logs");
    $pdo->exec("TRUNCATE TABLE pengajuan");
    $pdo->exec("TRUNCATE TABLE users");
    $pdo->exec("TRUNCATE TABLE divisi");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Seed Users
    $password = password_hash('password123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    
    // Admin
    $stmt->execute(['Admin Bappeda', 'admin@bappeda.lampung.go.id', $password, 'admin']);
    
    // Sekretariat
    $stmt->execute(['Sekretariat (Umum & Kepegawaian)', 'sekretariat@bappeda.lampung.go.id', $password, 'sekretariat']);
    
    // Mahasiswa
    $stmt->execute(['Najwa Ramadhani', 'najwa@student.unila.ac.id', $password, 'mahasiswa']);
    $mahasiswa_id = $pdo->lastInsertId();

    // Seed Mahasiswa Profile
    $stmt_profile = $pdo->prepare("INSERT INTO mahasiswa_profiles (user_id, nim, universitas, program_studi, semester, nomor_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_profile->execute([
        $mahasiswa_id,
        '2015061001',
        'Universitas Lampung',
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
