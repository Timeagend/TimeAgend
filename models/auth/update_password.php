<?php
/**
 * ETAPA 3 — Valida o token e atualiza a senha no banco
 * Caminho: models/auth/update_password.php
 */

session_start();
include_once('../../config/url.php');
include_once('../../config/conection.php'); // ← nome real do arquivo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    exit;
}

$token           = $_POST['token']            ?? '';
$email           = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password        = $_POST['password']         ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

// --- Validações ---
$errors = [];

if (!$email)                            $errors[] = 'E-mail inválido.';
if (strlen($password) < 8)             $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
if ($password !== $passwordConfirm)    $errors[] = 'As senhas não coincidem.';
if (!preg_match('/[A-Z]/', $password)) $errors[] = 'A senha deve conter pelo menos uma letra maiúscula.';
if (!preg_match('/[0-9]/', $password)) $errors[] = 'A senha deve conter pelo menos um número.';

if ($errors) {
    $_SESSION['error'] = implode(' ', $errors);
    header('Location: ' . BASE_URL . 'user/reset_password.php?token=' . urlencode($token) . '&email=' . urlencode($email));
    exit;
}

$con       = getDatabaseConnection();
$tokenHash = hash('sha256', $token);

// 1. Busca o token válido
$stmt = $con->prepare("
    SELECT pr.id AS reset_id, u.iduser
    FROM password_resets pr
    INNER JOIN user u ON u.iduser = pr.user_id
    WHERE u.email_user = ?
      AND pr.token     = ?
      AND pr.expires_at > NOW()
    LIMIT 1
");
$stmt->bind_param("ss", $email, $tokenHash);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();
$stmt->close();

if (!$record) {
    $_SESSION['error'] = 'Token inválido ou expirado. Solicite uma nova redefinição.';
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    exit;
}

// 2. Atualiza a senha com bcrypt
$passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $con->prepare("UPDATE user SET password = ? WHERE iduser = ?");
$stmt->bind_param("si", $passwordHash, $record['iduser']);

if (!$stmt->execute()) {
    error_log('Erro ao atualizar senha: ' . $stmt->error);
    $_SESSION['error'] = 'Erro interno. Tente novamente.';
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    $stmt->close();
    exit;
}
$stmt->close();

// 3. Remove o token usado
$stmt = $con->prepare("DELETE FROM password_resets WHERE user_id = ?");
$stmt->bind_param("i", $record['iduser']);
$stmt->execute();
$stmt->close();

$_SESSION['success'] = 'Senha redefinida com sucesso! Faça login.';
header('Location: ' . BASE_URL . 'user/login.php');
exit;