<?php
session_start();
include_once('../config/url.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>user/assets/css/logout.css">
</head>
<body>
    <div class="container">
        <h1>Recuperar senha</h1>

        <?php if (!empty($_SESSION['success'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>models/auth/newpass.php" method="POST">
            <input type="email" id="email" name="email" placeholder="Digite seu email" required>
            <button type="submit">Enviar e-mail</button>
        </form>
    </div>
</body>
</html>