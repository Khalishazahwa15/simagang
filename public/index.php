<?php
/**
 * SIMAGANG Bappeda Provinsi Lampung
 * Entry Point
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', __DIR__);

require_once APP_PATH . '/Core/autoload.php';

// Load Environment Variables
// Berkas .env tambahan untuk menjalankan aplikasi di atas basis data lain,
// dipilih lewat variabel lingkungan SIMAGANG_ENV. Dimuat lebih dulu karena
// nilai yang masuk pertama yang dipakai.
if (getenv('SIMAGANG_ENV')) {
    \App\Core\Env::load(ROOT_PATH . '/' . basename(getenv('SIMAGANG_ENV')));
}
\App\Core\Env::load(ROOT_PATH . '/.env');

// Load configurations
require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';

// Initialize Application
use App\Core\App;
use App\Core\Router;
use App\Core\Session;

Session::init();
\App\Core\CSRF::generate();

$router = new Router();
require_once CONFIG_PATH . '/routes.php';

$app = new App($router);
$app->run();
