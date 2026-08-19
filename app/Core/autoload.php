<?php
/**
 * Pendaftaran autoloader.
 *
 * Dipakai bersama oleh front controller dan seluruh skrip baris perintah
 * (seeder, pengujian, perkakas). Sebelumnya autoloader PHPMailer hanya
 * terdaftar di public/index.php, sehingga skrip CLI yang mencoba mengirim
 * email berhenti dengan "Class PHPMailer not found".
 *
 * ROOT_PATH dan APP_PATH harus sudah terdefinisi sebelum berkas ini dimuat.
 */

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// PHPMailer dipasang manual di lib/, proyek ini tidak memakai Composer
spl_autoload_register(function ($class) {
    $prefix = 'PHPMailer\\PHPMailer\\';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $file = ROOT_PATH . '/lib/PHPMailer/' . substr($class, $len) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
