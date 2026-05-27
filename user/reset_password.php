<?php
/**
 * ETAPA 2 — Formulário para digitar a nova senha
 * Caminho: user/reset_password.php
 */

session_start();
include_once('../config/url.php');
include_once('../config/conection.php');

$token = $_GET['token'] ?? '';
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);

$validToken = false;

if ($token && $email) {
    $tokenHash = hash('sha256', $token);
    $con       = getDatabaseConnection();

    $stmt = $con->prepare("
        SELECT pr.id
        FROM password_resets pr
        INNER JOIN user u ON u.iduser = pr.user_id
        WHERE u.email_user = ?
          AND pr.token     = ?
          AND pr.expires_at > NOW()
        LIMIT 1
    ");
    $stmt->bind_param("ss", $email, $tokenHash);
    $stmt->execute();
    $stmt->store_result();
    $validToken = $stmt->num_rows > 0;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>user/assets/css/logout.css">
    <style>
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .msg-error   { color: red;   font-size: 14px; margin-bottom: 12px; }
        .msg-success { color: green; font-size: 14px; margin-bottom: 12px; }
        .back-link   { display: block; text-align: center; margin-top: 12px; font-size: 13px; color: #666; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">

    <?php if (!$validToken): ?>

        <h1>Link inválido ou expirado</h1>
        <p>Solicite uma nova redefinição de senha.</p>
        <a href="<?= BASE_URL ?>user/newpassword.php" class="back-link">← Voltar</a>

    <?php else: ?>

        <h1>Redefinir senha</h1>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="msg-error"><?= htmlspecialchars($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>models/auth/update_password.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

            <input type="password" name="password"
                   placeholder="Nova senha" required minlength="8">

            <input type="password" name="password_confirm"
                   placeholder="Confirmar nova senha" required minlength="8">

            <button type="submit">Salvar nova senha</button>
        </form>

        <a href="<?= BASE_URL ?>user/login.php" class="back-link">← Voltar ao login</a>

    <?php endif; ?>

</div>
</body>
</html>