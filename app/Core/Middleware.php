<?php
namespace App\Core;

class Middleware {
    public static function resolve($key) {
        if (!$key) return;
        
        switch ($key) {
            case 'auth':
                if (!Auth::check()) {
                    header('Location: ' . BASE_URL . '/login');
                    exit();
                }
                break;
                
            case 'guest':
                if (Auth::check()) {
                    $role = Auth::role();
                    header('Location: ' . BASE_URL . '/' . $role . '/dashboard');
                    exit();
                }
                break;
                
            case 'role:mahasiswa':
                self::checkRole('mahasiswa');
                break;
                
            case 'role:sekretariat':
                self::checkRole('sekretariat');
                break;
                
            case 'role:admin':
                self::checkRole('admin');
                break;
                
            default:
                throw new \Exception("Middleware {$key} not found.");
        }
    }

    private static function checkRole($requiredRole) {
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        
        $userRole = Auth::role();
        
        // Super Admin (admin) has access to sekretariat routes
        if ($requiredRole === 'sekretariat' && $userRole === 'admin') {
            return;
        }
        
        if ($userRole !== $requiredRole) {
            http_response_code(403);
            die("403 Forbidden. You do not have permission to access this resource.");
        }
    }
}
