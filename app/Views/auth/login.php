<div style="display: flex; min-height: 100vh; width: 100%;">
    <!-- Left: Branding / Illustration (Hidden on mobile) -->
    <div class="auth-brand-side">
        <div class="auth-brand-pattern"></div>
        <div class="auth-brand-glow-1"></div>
        <div class="auth-brand-glow-2"></div>

        <div style="position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 48px;">
                <div style="width: 48px; height: 48px; background-color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 24px; color: var(--primary-dark); box-shadow: 0 8px 20px rgba(0,0,0,0.18);">S</div>
                <div>
                    <div style="font-family: var(--font-body); font-weight: 800; font-size: 20px; color: var(--bg-main); line-height: 1.2;">SIMAGANG</div>
                    <div style="font-family: var(--font-body); font-size: 12px; color: rgba(255,255,255,0.65); letter-spacing: 0.1em; text-transform: uppercase;">BAPPEDA LAMPUNG</div>
                </div>
            </div>

            <h1 style="font-family: var(--font-display); font-size: 40px; color: var(--bg-main); line-height: 1.18; margin-bottom: 20px;">
                Mulai perjalanan karir<br>profesional Anda bersama kami.
            </h1>
            <p style="font-family: var(--font-body); font-size: 15.5px; color: rgba(255,255,255,0.72); line-height: 1.65; max-width: 440px; margin-bottom: 8px;">
                Sistem Informasi Pengelolaan Magang Mahasiswa Bappeda Provinsi Lampung. Ajukan, pantau, dan kelola administrasi magang secara digital.
            </p>

            <div class="auth-feature-list">
                <div class="auth-feature-item">
                    <span class="auth-feature-icon">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <span class="auth-feature-text">Ajukan permohonan magang sepenuhnya online</span>
                </div>
                <div class="auth-feature-item">
                    <span class="auth-feature-icon">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <span class="auth-feature-text">Pantau status pengajuan secara real-time</span>
                </div>
                <div class="auth-feature-item">
                    <span class="auth-feature-icon">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <span class="auth-feature-text">Kelola dokumen &amp; surat resmi secara digital</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right: Form -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; background-color: var(--bg-main); padding: 32px;">
        <div style="width: 100%; max-width: 400px;">
            <div class="mobile-logo-only" style="display: none; margin-bottom: 32px; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background-color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 20px; color: var(--primary-dark);">S</div>
                <div>
                    <div style="font-family: var(--font-body); font-weight: 800; font-size: 18px; color: var(--text-primary); line-height: 1.2;">SIMAGANG</div>
                </div>
            </div>

            <div style="margin-bottom: 32px;">
                <h2 style="font-family: var(--font-display); font-size: 28px; color: var(--text-primary); margin: 0 0 8px 0;">Masuk ke Akun</h2>
                <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); margin: 0;">Silakan masukkan email dan kata sandi Anda.</p>
            </div>
            
            <?php if ($flash = \App\Core\Session::getFlash('error')): ?>
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </div>
                    <div>
                        <div class="alert-title">Gagal Masuk</div>
                        <p class="alert-body"><?= htmlspecialchars($flash) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login" method="POST" autocomplete="off">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?>">
                
                <div class="form-group">
                    <label for="app-views-auth-login-email" class="form-label required">Alamat Email</label>
                    <input id="app-views-auth-login-email" type="email" name="email" class="form-control" placeholder="nama@kampus.ac.id" autocomplete="off" required>
                </div>
                
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label for="login-password" class="form-label required" style="margin-bottom: 0;">Kata Sandi</label>
                        <a href="<?= BASE_URL ?>/forgot-password" style="font-family: var(--font-body); font-size: 12px; font-weight: 600; color: var(--primary);">Lupa kata sandi?</a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" id="login-password" name="password" class="form-control" placeholder="••••••••" required style="padding-right: 40px;">
                        <button aria-label="Tampilkan atau sembunyikan kata sandi" type="button" onclick="togglePassword('login-password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 12px;">Masuk</button>
            </form>
            
            <div style="margin-top: 32px; text-align: center; font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary);">
                Belum punya akun? <a href="<?= BASE_URL ?>/register" style="font-weight: 600; color: var(--primary);">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-brand-side {
        flex: 1;
        display: none;
        flex-direction: column;
        justify-content: center;
        padding: 64px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(155deg, var(--primary) 0%, var(--primary-dark) 100%);
    }
    .auth-brand-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.14) 1.5px, transparent 1.5px);
        background-size: 24px 24px;
        opacity: 0.6;
    }
    .auth-brand-glow-1 {
        position: absolute;
        top: -160px;
        right: -140px;
        width: 460px;
        height: 460px;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 35%, rgba(255,255,255,0.20), rgba(255,255,255,0) 70%);
    }
    .auth-brand-glow-2 {
        position: absolute;
        bottom: -180px;
        left: -120px;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        border: 56px solid rgba(255,255,255,0.06);
    }
    .auth-feature-list {
        margin-top: 40px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .auth-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .auth-feature-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(224, 176, 75, 0.18);
        border: 1px solid rgba(224, 176, 75, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--color-text-inverse);
    }
    .auth-feature-text {
        font-family: var(--font-body);
        font-size: 14px;
        color: rgba(255,255,255,0.85);
        line-height: 1.5;
    }
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
        btn.innerHTML = '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
}
</script>


