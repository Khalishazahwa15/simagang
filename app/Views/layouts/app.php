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
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="background: #FEE2E2; border: 1px solid #F87171; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-family: var(--font-body); font-size: 13.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="background: #E7F2EF; border: 1px solid #A7D4CB; color: var(--primary-dark); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-family: var(--font-body); font-size: 13.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    <div><?= $_SESSION['success'] ?></div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['warning'])): ?>
                <div class="alert alert-warning" style="background: #FEF3C7; border: 1px solid #FBBF24; color: #7A5A00; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-family: var(--font-body); font-size: 13.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <div><?= $_SESSION['warning'] ?></div>
                </div>
                <?php unset($_SESSION['warning']); ?>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>
    </main>
</body>
</html>
