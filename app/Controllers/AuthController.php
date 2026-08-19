<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\ErrorHandler;
use App\Services\AuthService;
use App\Services\LoginThrottleService;

class AuthController extends Controller {
    private $authService;
    private $throttleService;

    public function __construct() {
        $this->authService = new AuthService();
        $this->throttleService = new LoginThrottleService();
    }

    public function login() {
        if (Auth::check()) {
            $role = Auth::role();
            if (empty($role)) {
                // Fix for ghost sessions with missing role
                Auth::logout();
            } else {
                return $this->redirect($role . '/dashboard');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $terkunci = $this->throttleService->lockedForMinutes($email);
            if ($terkunci > 0) {
                Session::setFlash('error', "Terlalu banyak percobaan login. Coba lagi dalam {$terkunci} menit.");
                return $this->redirect('login');
            }

            try {
                if ($this->authService->login($email, $password)) {
                    $this->throttleService->clear($email);
                    $role = \App\Core\Auth::role();
                    return $this->redirect($role . '/dashboard');
                }
                $this->throttleService->recordFailure($email);
                Session::setFlash('error', 'Email atau password salah.');
            } catch (\Exception $e) {
                Session::setFlash('error', ErrorHandler::userMessage($e));
            }
            return $this->redirect('login');
        }

        ob_start();
        $this->view('auth/login');
        $content = ob_get_clean();
        
        $this->view('layouts/auth', [
            'content' => $content, 
            'title' => 'Login | SIMAGANG Bappeda Provinsi Lampung'
        ]);
    }

    public function register() {
        if (Auth::check()) {
            $role = Auth::role();
            if (empty($role)) {
                Auth::logout();
            } else {
                return $this->redirect($role . '/dashboard');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $nim = $_POST['nim'] ?? '';
            $universitas = $_POST['universitas'] ?? '';
            $fakultas = $_POST['fakultas'] ?? '';
            $programStudi = $_POST['program_studi'] ?? '';
            $semester = $_POST['semester'] ?? 0;
            $nomorHp = $_POST['nomor_hp'] ?? '';
            $alamat = $_POST['alamat'] ?? '';

            if ($password !== $passwordConfirm) {
                Session::setFlash('error', 'Konfirmasi kata sandi tidak cocok.');
                return $this->redirect('register');
            }

            try {
                $this->authService->registerMahasiswa($nama, $email, $password, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat);
                Session::setFlash('success', 'Registrasi berhasil. Silakan login.');
                return $this->redirect('login');
            } catch (\Exception $e) {
                Session::setFlash('error', ErrorHandler::userMessage($e));
                return $this->redirect('register');
            }
        }

        ob_start();
        $this->view('auth/register');
        $content = ob_get_clean();
        
        $this->view('layouts/auth', [
            'content' => $content, 
            'title' => 'Daftar | SIMAGANG Bappeda Provinsi Lampung'
        ]);
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $db = \App\Core\Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("SELECT id, nama FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                
                $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
                $stmt->execute([hash('sha256', $token), $user['id']]);
                
                // Fallback email mechanism (writes to local log)
                $resetLink = BASE_URL . '/reset-password?token=' . $token;
                $logMessage = "[" . date('Y-m-d H:i:s') . "] RESET PASSWORD LINK FOR {$email}:\n";
                $logMessage .= "Halo {$user['nama']},\nKlik link ini untuk reset password Anda (berlaku 1 jam): {$resetLink}\n";
                $logMessage .= "--------------------------------------------------\n";
                
                $logDir = ROOT_PATH . '/storage/logs';
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0777, true);
                }
                // TODO: Replace this file-based mock with a real SMTP implementation (e.g. PHPMailer) before production go-live
                file_put_contents($logDir . '/mail.log', $logMessage, FILE_APPEND);
            }
            
            // Always show success message to prevent email enumeration
            Session::setFlash('success', 'Jika email terdaftar, tautan untuk mereset password telah dikirim (Cek storage/logs/mail.log).');
            return $this->redirect('forgot-password');
        }

        ob_start();
        $this->view('auth/forgot_password');
        $content = ob_get_clean();
        
        $this->view('layouts/auth', [
            'content' => $content, 
            'title' => 'Lupa Password | SIMAGANG Bappeda Provinsi Lampung'
        ]);
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            Session::setFlash('error', 'Token reset password tidak valid atau tidak ditemukan.');
            return $this->redirect('login');
        }
        
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
        $stmt->execute([hash('sha256', $token)]);
        $user = $stmt->fetch();
        
        if (!$user) {
            Session::setFlash('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
            return $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($password) || empty($confirmPassword)) {
                Session::setFlash('error', 'Semua kolom wajib diisi.');
                return $this->redirect('reset-password?token=' . $token);
            }
            
            if (strlen($password) < AuthService::MIN_PASSWORD_LENGTH) {
                Session::setFlash('error', 'Kata sandi minimal ' . AuthService::MIN_PASSWORD_LENGTH . ' karakter.');
                return $this->redirect('reset-password?token=' . $token);
            }

            if ($password !== $confirmPassword) {
                Session::setFlash('error', 'Konfirmasi password tidak cocok.');
                return $this->redirect('reset-password?token=' . $token);
            }
            
            // Hash new password and clear token
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
            
            Session::setFlash('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
            return $this->redirect('login');
        }

        ob_start();
        $this->view('auth/reset_password', ['token' => $token]);
        $content = ob_get_clean();
        
        $this->view('layouts/auth', [
            'content' => $content, 
            'title' => 'Reset Password | SIMAGANG Bappeda Provinsi Lampung'
        ]);
    }

    public function logout() {
        $this->authService->logout();
        return $this->redirect('');
    }
}
