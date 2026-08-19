<?php
$namaLengkap = $user['nama'] ?? 'Mahasiswa';
$initial = strtoupper(substr($namaLengkap, 0, 1));

$profilKurang = [];
foreach (\App\Models\MahasiswaProfile::FIELD_WAJIB as $kolom => $label) {
    if (trim((string)($user[$kolom] ?? '')) === '') {
        $profilKurang[] = $label;
    }
}
?>
<div style="display: flex; flex-direction: column; gap: 24px; max-width: 911px;">
    <?php if (!empty($profilKurang)): ?>
    <div style="background: #FEF3C7; border: 1px solid #FBBF24; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 14px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7A5A00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        <div style="flex: 1;">
            <div style="font-family: var(--font-body); font-weight: 700; font-size: 14px; color: #7A5A00; margin-bottom: 4px;">Profil belum lengkap</div>
            <div style="font-family: var(--font-body); font-size: 13px; color: #7A5A00; line-height: 1.6;">
                Data berikut belum terisi: <strong><?= htmlspecialchars(implode(', ', $profilKurang)) ?></strong>.
                Isi kolom tersebut di formulir di bawah, lalu simpan.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <div>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="<?= BASE_URL ?>/mahasiswa/dashboard" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; color: var(--text-secondary); letter-spacing: 0.88px; text-transform: uppercase;">Pengaturan Akun</div>
        </div>
        <h1 style="font-family: var(--font-display); font-size: 24px; font-weight: 600; color: var(--text-primary); margin: 0;">Profil Saya</h1>
    </div>

    <!-- Main Profile Card -->
    <div class="card fade-up interactive-card fade-up interactive-card" style="margin-bottom: 0;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 16px; background: var(--bg-soft);">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center;">
                <span style="font-family: var(--font-body); font-size: 24px; font-weight: 600; color: var(--bg-main);"><?= $initial ?></span>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= htmlspecialchars($namaLengkap) ?></div>
                <div style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary);"><?= htmlspecialchars($user['email'] ?? 'email@mahasiswa.com') ?></div>
            </div>
        </div>

        <div class="card-body" style="padding: 32px 24px;">
            <form action="<?= BASE_URL ?>/mahasiswa/profil/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                
                <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.5px; color: var(--text-primary); text-transform: uppercase; margin-bottom: 16px;">Data Pribadi</div>
                
                <div class="grid grid-2" style="gap: 20px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">NPM / NIM</label>
                        <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($user['nim'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="grid grid-2" style="gap: 20px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($user['tempat_lahir'] ?? '') ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($user['tanggal_lahir'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label required">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($user['nomor_hp'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 32px;">
                    <label class="form-label required">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
                </div>

                <div style="height: 1px; background: var(--border); margin-bottom: 32px;"></div>
                
                <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.5px; color: var(--text-primary); text-transform: uppercase; margin-bottom: 16px;">Informasi Akademik</div>

                <div class="grid grid-2" style="gap: 20px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Asal Perguruan Tinggi</label>
                        <input type="text" name="universitas" class="form-control" value="<?= htmlspecialchars($user['universitas'] ?? '') ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Fakultas</label>
                        <input type="text" name="fakultas" class="form-control" value="<?= htmlspecialchars($user['fakultas'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="grid grid-2" style="gap: 20px; margin-bottom: 32px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Program Studi</label>
                        <input type="text" name="prodi" class="form-control" value="<?= htmlspecialchars($user['program_studi'] ?? '') ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Semester Saat Ini</label>
                        <input type="number" name="semester" class="form-control" value="<?= htmlspecialchars($user['semester'] ?? '') ?>" placeholder="Contoh: 6" min="1" max="14" required>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Card -->
    <div class="card fade-up interactive-card fade-up interactive-card" style="margin-bottom: 0;">
        <div class="card-header" style="border-bottom: 1px solid var(--border); padding: 20px 24px;">
            <h3 style="font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0;">Keamanan Akun</h3>
        </div>
        <div class="card-body" style="padding: 32px 24px;">
            <form action="<?= BASE_URL ?>/mahasiswa/profil/password" method="POST">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                
                <div class="grid grid-2" style="gap: 20px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Kata Sandi Baru</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label required">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirm" class="form-control" required>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-outline" style="padding: 10px 24px;">Ubah Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>
</div>


