<?php
namespace App\Core;

class App {
    protected $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function run() {
        // Run lazy date-based automation hook
        try {
            $syncService = new \App\Services\SyncStatusService();
            $syncService->sync();
        } catch (\Exception $e) {
            // Silently ignore sync errors so it doesn't break the application
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        // Metode diambil apa adanya dari permintaan. Pemalsuan lewat _method
        // sengaja tidak didukung: token CSRF hanya diperiksa pada POST,
        // sehingga POST yang menyamar jadi GET akan lolos pemeriksaan.
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Remove base url path from uri to get relative route
        $baseUrlPath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($baseUrlPath && strpos($uri, $baseUrlPath) === 0) {
            $uri = substr($uri, strlen($baseUrlPath));
        }
        
        $uri = parse_url($uri, PHP_URL_PATH);
        if (empty($uri)) $uri = '/';

        $this->router->dispatch($uri, $method);
    }
}
