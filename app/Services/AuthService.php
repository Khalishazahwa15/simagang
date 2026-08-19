<?php
namespace App\Services;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Core\Session;

class AuthService {
    private $userModel;
    private $profileModel;

    public function __construct() {
        $this->userModel = new User();
        $this->profileModel = new MahasiswaProfile();
    }

    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            \App\Core\Auth::login($user);
            return true;
        }
        
        return false;
    }

    public function registerMahasiswa($nama, $email, $password, $nim, $universitas, $programStudi, $semester, $nomorHp, $alamat) {
        // Validate email uniqueness
        if ($this->userModel->findByEmail($email)) {
            throw new \Exception("Email sudah terdaftar.");
        }

        $this->userModel->beginTransaction();
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $userId = $this->userModel->create($nama, $email, $passwordHash, 'mahasiswa');
            
            $this->profileModel->create($userId, $nim, $universitas, $programStudi, $semester, $nomorHp, $alamat);
            
            $this->userModel->commit();
            return true;
        } catch (\Exception $e) {
            $this->userModel->rollBack();
            throw $e;
        }
    }

    public function logout() {
        Session::destroy();
    }
}
