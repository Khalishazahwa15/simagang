<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/tokens.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>
<body class="admin-layout">
    <?php include APP_PATH . '/Views/components/sidebar.php'; ?>
    
    <main class="admin-main">
        <?php include APP_PATH . '/Views/components/topbar.php'; ?>
        
        <div class="content-pad">
            <?php if ($flash = \App\Core\Session::getFlash('error')): ?>
                <div class="alert alert-danger" style="background: var(--color-danger-soft); border: 1px solid var(--color-danger-border); color: var(--color-danger-ink); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-family: var(--font-body); font-size: 13.5px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <div><?= htmlspecialchars($flash) ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($flash = \App\Core\Session::getFlash('success')): ?>
                <div class="alert alert-success" style="background: var(--color-success-soft); border: 1px solid var(--color-success-border); color: var(--primary-dark); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-family: var(--font-body); font-size: 13.5px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    <div><?= htmlspecialchars($flash) ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($flash = \App\Core\Session::getFlash('warning')): ?>
                <div class="alert alert-warning" style="background: var(--color-warning-soft); border: 1px solid var(--color-warning-border); color: var(--color-warning-ink); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-family: var(--font-body); font-size: 13.5px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <div><?= htmlspecialchars($flash) ?></div>
                </div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>
    </main>
</body>
</html>
