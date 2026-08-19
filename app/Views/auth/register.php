<div style="display: flex; min-height: 100vh; width: 100%;">
    <!-- Left: Branding / Illustration (Hidden on mobile) -->
    <div style="flex: 1; background-color: var(--primary); display: none; flex-direction: column; justify-content: center; padding: 64px; position: relative; overflow: hidden;" class="auth-brand-side">
        <!-- Abstract shape -->
        <div style="position: absolute; top: -100px; right: -100px; width: 500px; height: 500px; border-radius: 50%; background: var(--primary-dark); opacity: 0.5;"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 300px; height: 300px; border-radius: 50%; border: 40px solid var(--primary-dark); opacity: 0.5;"></div>

        <div style="position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                <div style="width: 48px; height: 48px; background-color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 24px; color: var(--primary-dark);">S</div>
                <div>
                    <div style="font-family: var(--font-body); font-weight: 800; font-size: 20px; color: var(--bg-main); line-height: 1.2;">SIMAGANG</div>
                    <div style="font-family: var(--font-body); font-size: 11px; color: rgba(255,255,255,0.6); letter-spacing: 0.1em; text-transform: uppercase;">BAPPEDA LAMPUNG</div>
                </div>
            </div>
            
            <h1 style="font-family: var(--font-display); font-size: 42px; color: var(--bg-main); line-height: 1.15; margin-bottom: 24px;">
                Bergabunglah untuk memulai<br>pengalaman magang terbaik.
            </h1>
            <p style="font-family: var(--font-body); font-size: 16px; color: rgba(255,255,255,0.7); line-height: 1.6; max-width: 480px;">
                Daftarkan akun dengan menggunakan email resmi instansi atau universitas Anda agar dapat mengajukan permohonan magang.
            </p>
        </div>
    </div>
    
    <!-- Right: Form -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; background-color: var(--bg-main); padding: 32px;">
        <div style="width: 100%; max-width: 440px;">
            <div class="mobile-logo-only" style="display: none; margin-bottom: 32px; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background-color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 20px; color: var(--primary-dark);">S</div>
                <div>
                    <div style="font-family: var(--font-body); font-weight: 800; font-size: 18px; color: var(--text-primary); line-height: 1.2;">SIMAGANG</div>
                </div>
            </div>

            <div style="margin-bottom: 32px;">
                <h2 style="font-family: var(--font-display); font-size: 28px; color: var(--text-primary); margin: 0 0 8px 0;">Buat Akun Baru</h2>
                <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); margin: 0;">Isi formulir di bawah ini untuk mendaftar sebagai mahasiswa magang.</p>
            </div>

            <?php if ($flash = \App\Core\Session::getFlash('error')): ?>
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </div>
                    <div>
                        <div class="alert-title">Gagal Mendaftar</div>
                        <p class="alert-body"><?= htmlspecialchars($flash) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?>">
                
                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Sesuai KTP/KTM" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Gunakan email universitas jika ada" required>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label required">Kata Sandi</label>
                        <div style="position: relative;">
                            <input type="password" id="reg-password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8" style="padding-right: 40px;">
                            <button type="button" onclick="togglePassword('reg-password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Konfirmasi Sandi</label>
                        <div style="position: relative;">
                            <input type="password" id="reg-password-conf" name="password_confirm" class="form-control" placeholder="Ketik ulang" required minlength="8" style="padding-right: 40px;">
                            <button type="button" onclick="togglePassword('reg-password-conf', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div style="margin: 24px 0 16px 0; padding-top: 20px; border-top: 1px solid var(--border);">
                    <div style="font-family: var(--font-body); font-weight: 700; font-size: 13px; color: var(--text-primary); margin-bottom: 4px;">Data Akademik</div>
                    <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Dipakai Sekretariat untuk menilai permohonan magang Anda.</div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label required">NIM</label>
                        <input type="text" name="nim" class="form-control" placeholder="Nomor Induk Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Semester</label>
                        <input type="number" name="semester" class="form-control" placeholder="Contoh: 6" min="1" max="14" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Universitas</label>
                    <input type="text" name="universitas" class="form-control" placeholder="Contoh: Universitas Lampung" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label required">Fakultas</label>
                        <input type="text" name="fakultas" class="form-control" placeholder="Contoh: Teknik" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" placeholder="Contoh: Teknik Informatika" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Nomor HP</label>
                    <input type="text" name="nomor_hp" class="form-control" placeholder="Contoh: 081234567890" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Alamat</label>
                    <textarea name="alamat" class="form-control" placeholder="Alamat tempat tinggal saat ini" rows="2" required></textarea>
                </div>

                <div class="form-group" style="margin-top: 8px;">
                    <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                        <input type="checkbox" required style="margin-top: 4px; accent-color: var(--primary);">
                        <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); line-height: 1.5;">
                            Saya menyetujui <a href="<?= BASE_URL ?>" style="color: var(--primary); font-weight: 600;">Syarat dan Ketentuan</a> serta <a href="<?= BASE_URL ?>" style="color: var(--primary); font-weight: 600;">Kebijakan Privasi</a> yang berlaku.
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 12px;">Daftar Sekarang</button>
            </form>
            
            <div style="margin-top: 32px; text-align: center; font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary);">
                Sudah memiliki akun? <a href="<?= BASE_URL ?>/login" style="font-weight: 600; color: var(--primary);">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 900px) {
        .auth-brand-side { display: flex !important; }
    }
    @media (max-width: 899px) {
        .mobile-logo-only { display: flex !important; }
    }
</style>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
}
</script>


