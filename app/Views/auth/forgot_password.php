<div class="auth-card" style="padding: 40px; box-sizing: border-box;">
    <!-- Logo & Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 24px;">
            <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--bg-card)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
            </div>
            <div style="text-align: left;">
                <div style="font-family: var(--font-display); font-weight: 600; font-size: 20px; line-height: 24px; color: var(--text-primary);">SIMAGANG</div>
                <div style="font-weight: 500; font-size: 12px; line-height: 14px; letter-spacing: 0.04em; color: var(--text-secondary); text-transform: uppercase;">Bappeda Lampung</div>
            </div>
        </div>
        
        <h1 style="font-family: var(--font-display); font-weight: 600; font-size: 24px; line-height: 32px; color: var(--text-primary); margin: 0 0 8px 0; letter-spacing: -0.02em;">Lupa Password</h1>
        <p style="margin: 0; font-weight: 400; font-size: 14px; line-height: 22px; color: var(--text-secondary);">Masukkan alamat email Anda untuk menerima tautan reset password.</p>
    </div>

    <!-- Alert -->
    <?php if ($flash = \App\Core\Session::getFlash('error')): ?>
        <div style="margin-bottom: 24px; padding: 12px 16px; background: var(--color-danger-soft); border-left: 4px solid var(--color-danger); border-radius: 4px;">
            <p style="margin: 0; font-size: var(--text-body-sm); color: var(--color-danger-ink);"><?= htmlspecialchars($flash) ?></p>
        </div>
    <?php endif; ?>
    <?php if ($flash = \App\Core\Session::getFlash('success')): ?>
        <div style="margin-bottom: 24px; padding: 12px 16px; background: var(--color-success-soft); border-left: 4px solid var(--color-success); border-radius: 4px;">
            <p style="margin: 0; font-size: var(--text-body-sm); color: var(--color-success-ink);"><?= htmlspecialchars($flash) ?></p>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= BASE_URL ?>/forgot-password" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label for="app-views-auth-forgot-password-email" class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: var(--text-body-sm); color: var(--text-primary);">Alamat Email</label>
            <div style="position: relative;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
                <input id="app-views-auth-forgot-password-email" type="email" name="email" class="form-control" style="width: 100%; height: 48px; padding: 0 16px 0 44px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; color: var(--text-primary); box-sizing: border-box; outline: none; transition: all 0.2s;" placeholder="Masukkan email terdaftar" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; display: flex; align-items: center; justify-content: center; gap: 8px; background: var(--primary); color: var(--bg-card); border: none; border-radius: 8px; font-weight: 500; font-size: var(--text-body-sm); cursor: pointer; transition: background 0.2s; margin-bottom: 24px;">
            Kirim Tautan Reset
        </button>

        <div style="text-align: center;">
            <p style="margin: 0; font-size: var(--text-body-sm); color: var(--text-secondary);">
                Ingat password Anda? 
                <a href="<?= BASE_URL ?>/login" style="color: var(--primary); text-decoration: none; font-weight: 600;">Kembali ke Login</a>
            </p>
        </div>
    </form>
</div>

