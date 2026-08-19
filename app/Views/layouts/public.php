<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    
    <link rel="stylesheet" href="<?= aset('assets/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= aset('assets/css/layout.css') ?>">
    <link rel="stylesheet" href="<?= aset('assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= aset('assets/css/responsive.css') ?>">
</head>
<body class="public-layout">
    <?php include APP_PATH . '/Views/components/navbar.php'; ?>
    
    <main class="public-main">
        <?= $content ?? '' ?>
    </main>
</body>
</html>
