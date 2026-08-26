<?php
namespace App\Core;

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $file = APP_PATH . "/Views/{$view}.php";
        
        if (file_exists($file)) {
            require $file;
        } else {
            die("View {$view} not found.");
        }
    }

    protected function redirect($url) {
        $url = ltrim($url, '/');
        header("Location: " . BASE_URL . '/' . $url);
        exit();
    }
}
