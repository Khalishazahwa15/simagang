<?php
namespace App\Services;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Core\Session;

class AuthService {
    const MIN_PASSWORD_LENGTH = 8;

    private $userModel;
    private $profileModel;

    public function __construct() {
        $this->userModel = new User();
        $this->profileModel = new MahasiswaProfile();
    }

    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        if (($user['status'] ?? 'aktif') !== 'aktif') {
            throw new \Exception("Akun Anda dinonaktifkan. Silakan hubungi Administrator.");
        }

        \App\Core\Auth::login($user);
        return true;
    }

    public function registerMahasiswa($nama, $email, $password, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat) {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw new \Exception("Kata sandi minimal " . self::MIN_PASSWORD_LENGTH . " karakter.");
        }

        $wajib = [
            'Nama' => $nama, 'Email' => $email, 'NIM' => $nim,
            'Universitas' => $universitas, 'Fakultas' => $fakultas,
            'Program studi' => $programStudi, 'Nomor HP' => $nomorHp, 'Alamat' => $alamat
        ];
        foreach ($wajib as $label => $nilai) {
            if (trim((string)$nilai) === '') {
                throw new \Exception("{$label} wajib diisi.");
            }
        }

        $semester = (int)$semester;
        if ($semester < 1 || $semester > 14) {
            throw new \Exception("Semester harus berupa angka 1 sampai 14.");
        }

        // Validate email uniqueness
        if ($this->userModel->findByEmail($email)) {
            throw new \Exception("Email sudah terdaftar.");
        }

        $this->userModel->beginTransaction();
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $userId = $this->userModel->create($nama, $email, $passwordHash, 'mahasiswa');
            
            $this->profileModel->create($userId, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat);
            
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
