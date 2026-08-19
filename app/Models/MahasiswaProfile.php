<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class MahasiswaProfile extends Model {
    protected $table = 'mahasiswa_profiles';

    // Data yang harus terisi sebelum mahasiswa boleh mengajukan magang.
    const FIELD_WAJIB = [
        'nim' => 'NIM',
        'tempat_lahir' => 'Tempat lahir',
        'tanggal_lahir' => 'Tanggal lahir',
        'universitas' => 'Universitas',
        'fakultas' => 'Fakultas',
        'program_studi' => 'Program studi',
        'semester' => 'Semester',
        'nomor_hp' => 'Nomor HP',
        'alamat' => 'Alamat',
    ];

    /**
     * Kembalikan label field yang masih kosong. Array kosong berarti lengkap.
     */
    public function fieldBelumLengkap($userId) {
        $profil = $this->findByUserId($userId);

        $kurang = [];
        foreach (self::FIELD_WAJIB as $kolom => $label) {
            if (!$profil || trim((string)($profil[$kolom] ?? '')) === '') {
                $kurang[] = $label;
            }
        }

        return $kurang;
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userId, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (user_id, nim, universitas, fakultas, program_studi, semester, nomor_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $nim, $universitas, $fakultas, $programStudi, $semester, $nomorHp, $alamat]);
        return $this->db->lastInsertId();
    }
}
