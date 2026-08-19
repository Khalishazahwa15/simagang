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
                Mulai perjalanan karir<br>profesional Anda bersama kami.
            </h1>
            <p style="font-family: var(--font-body); font-size: 16px; color: rgba(255,255,255,0.7); line-height: 1.6; max-width: 480px;">
                Sistem Informasi Pengelolaan Magang Mahasiswa Bappeda Provinsi Lampung. Ajukan, pantau, dan kelola administrasi magang secara digital.
            </p>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
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
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@kampus.ac.id" autocomplete="off" required>
                </div>
                
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label class="form-label required" style="margin-bottom: 0;">Kata Sandi</label>
                        <a href="<?= BASE_URL ?>/forgot-password" style="font-family: var(--font-body); font-size: 12px; font-weight: 600; color: var(--primary);">Lupa kata sandi?</a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" id="login-password" name="password" class="form-control" placeholder="••••••••" required style="padding-right: 40px;">
                        <button type="button" onclick="togglePassword('login-password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
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


