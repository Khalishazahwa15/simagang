<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Services\NotificationService;

class NotificationController extends Controller {
    private $notificationService;

    public function __construct() {
        Auth::requireLogin();
        $this->notificationService = new NotificationService();
    }

    public function markAsRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            $csrfToken = $_POST['csrf_token'] ?? '';
            $sessionToken = \App\Core\Session::get('csrf_token');
            
            if (!$csrfToken || $csrfToken !== $sessionToken) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'CSRF token mismatch.']);
                exit;
            }

            $userId = \App\Core\Auth::user()['id'];
            $this->notificationService->markAllAsRead($userId);

            // Respond with JSON if AJAX, otherwise redirect back
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
            
            header('Location: ' . $this->kembaliKeHalamanSebelumnya());
            exit;
        }
    }

    /**
     * Referer datang dari peramban dan bisa diisi apa saja, termasuk domain
     * luar. Hanya alamat di dalam aplikasi ini yang boleh dijadikan tujuan
     * pengalihan; selain itu dikembalikan ke beranda.
     */
    private function kembaliKeHalamanSebelumnya() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer === '') {
            return BASE_URL;
        }

        $tujuan = parse_url($referer);
        $aplikasi = parse_url(BASE_URL);

        $hostTujuan = $tujuan['host'] ?? '';
        $hostAplikasi = $aplikasi['host'] ?? '';

        if ($hostTujuan === '' || strcasecmp($hostTujuan, $hostAplikasi) !== 0) {
            return BASE_URL;
        }

        if (isset($tujuan['port']) !== isset($aplikasi['port'])
            || (isset($tujuan['port']) && $tujuan['port'] != $aplikasi['port'])) {
            return BASE_URL;
        }

        return $referer;
    }
}
