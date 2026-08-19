<?php
namespace App\Core;

class Auth {
    public static function login($user) {
        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['nama']);
    }

    public static function logout() {
        Session::destroy();
    }

    public static function check() {
        return Session::get('user_id') !== null;
    }

    public static function user() {
        return [
            'id' => Session::get('user_id'),
            'role' => Session::get('user_role'),
            'nama' => Session::get('user_name')
        ];
    }

    public static function role() {
        return Session::get('user_role');
    }

    public static function id() {
        return Session::get('user_id');
    }

    public static function requireRole($role) {
        if (!self::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        
        $userRole = self::role();
        
        if ($role === 'sekretariat' && $userRole === 'admin') {
            return;
        }

        if ($userRole !== $role) {
            http_response_code(403);
            die("403 Forbidden. You do not have permission to access this resource.");
        }
    }
}
