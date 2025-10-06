<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="favicon.ico">

    <script src="https://cdn.tailwindcss.com"></script>
    <?php $cssPath = __DIR__ . '/../../assets/css/styles.css'; $v = file_exists($cssPath) ? filemtime($cssPath) : time(); ?>
    <link rel="stylesheet" href="/PII-GARCIA-PARCIAL_1/assets/css/styles.css?v=<?= $v; ?>">
    <link rel="icon" type="image/png" href="/PII-GARCIA-PARCIAL_1/assets/img/Logo-Mitics.png">
    <script src="assets/js/main.js" defer></script>
    <title><?= $title; ?></title>
</head>
