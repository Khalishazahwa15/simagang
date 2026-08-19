<?php
// Base URL detection for Laragon/localhost
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$path = str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME'] ?? '');
// Ensure path doesn't end with a slash if it's just the directory
$path = rtrim($path, '/');
$baseUrl = $protocol . '://' . $host . $path;

define('BASE_URL', $baseUrl);
define('APP_NAME', 'SIMAGANG Bappeda Provinsi Lampung');
define('UPLOAD_DIR', ROOT_PATH . '/storage/uploads');

// Environment & Error Handling
define('APP_ENV', \App\Core\Env::get('APP_ENV', 'production'));

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');
}
