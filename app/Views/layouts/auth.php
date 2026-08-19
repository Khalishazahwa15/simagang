<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/tokens.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>
<body>
    <div class="auth-container">
        <?= $content ?? '' ?>
    </div>
</body>
</html>
