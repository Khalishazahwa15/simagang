<?php
/**
 * SIMAGANG Bappeda Provinsi Lampung
 * Entry Point
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', __DIR__);

// Autoload Core Classes
spl_autoload_register(function ($class) {
    // Prefix 'App\' maps to 'app/' directory
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

// Load Environment Variables
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
