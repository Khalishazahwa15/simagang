<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/tokens.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css?v=<?= time() ?>">
</head>
<body class="public-layout">
    <?php include APP_PATH . '/Views/components/navbar.php'; ?>
    
    <main class="public-main">
        <?= $content ?? '' ?>
    </main>
</body>
</html>
