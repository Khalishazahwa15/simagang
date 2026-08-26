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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#3B6FD6', 'primary-dark': '#24487E', accent: '#F0AE45' } } }
        };
    </script>
</head>
<body>
    <div class="auth-container">
        <?= $content ?? '' ?>
    </div>
</body>
</html>
