<?php
$judul = $data['judul'] ?? '';
$isLoginPage = stripos($judul, 'Login') !== false;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul; ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <?php if ($isLoginPage): ?>
        <!-- CSS LOGIN -->
        <link rel="stylesheet" href="<?= BASEURL; ?>/css/login.css">
    <?php else: ?>
        <!-- CSS DASHBOARD / MAIN -->
        <link rel="stylesheet" href="<?= BASEURL; ?>/css/dashboard.css">
    <?php endif; ?>
</head>

<body class="<?= $isLoginPage ? 'login-page' : '' ?>">