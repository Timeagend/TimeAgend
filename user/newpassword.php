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
    <title>Recuperar Senha — TimeAgend</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>user/assets/css/recuperar-senha.css">
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <h1 class="card__title">Recuperar Senha</h1>
            <p class="card__subtitle">Informe seu e-mail e enviaremos um link para redefinir sua senha.</p>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert--success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert--error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>models/auth/newpass.php" method="POST" class="form">
                <div class="form__group">
                    <label for="email" class="form__label">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form__input"
                        placeholder="seu@email.com"
                        required
                    >
                </div>
                <button type="submit" class="btn-primary">Enviar link de recuperação</button>
            </form>

            <a href="<?= BASE_URL ?>user/login.php" class="back-link">← Voltar para o login</a>
        </div>
    </div>
</body>
</html>