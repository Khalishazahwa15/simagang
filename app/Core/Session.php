<?php
namespace App\Core;

class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_samesite', 'Strict');

            // Saat dilayani lewat HTTPS, cookie sesi tidak boleh ikut terkirim
            // pada koneksi biasa. Di lingkungan lokal yang masih HTTP, penanda
            // ini dibiarkan mati agar sesi tetap berjalan.
            $lewatHttps = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
                || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
                || (($_SERVER['SERVER_PORT'] ?? '') == 443);
            ini_set('session.cookie_secure', $lewatHttps ? 1 : 0);
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function setFlash($type, $message) {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
        // For backwards compatibility with views looking for specific session keys directly
        if ($type === 'error' || $type === 'success' || $type === 'warning') {
            $_SESSION[$type] = $message;
        }
    }

    public static function getFlash($type) {
        $message = $_SESSION[$type] ?? null;
        unset($_SESSION[$type]);

        if (!empty($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $index => $flash) {
                if ($flash['type'] === $type) {
                    if ($message === null) {
                        $message = $flash['message'];
                    }
                    unset($_SESSION['flash_messages'][$index]);
                }
            }
            $_SESSION['flash_messages'] = array_values($_SESSION['flash_messages']);
        }

        return $message;
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }

    public static function regenerate() {
        session_regenerate_id(true);
    }
}
