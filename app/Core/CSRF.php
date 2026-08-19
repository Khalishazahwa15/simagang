<?php
namespace App\Core;

class CSRF {
    public static function generate() {
        if (empty(Session::get('csrf_token'))) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function verify($token) {
        $stored_token = Session::get('csrf_token');
        if (!empty($stored_token) && hash_equals($stored_token, $token)) {
            return true;
        }
        return false;
    }
}
