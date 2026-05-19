<?php
session_start();

require_once '../../config/url.php';
require_once '../../config/conection.php';
require_once 'authFunctions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/user/login.php?erro=Método+não+permitido');
    exit;
}

$email      = trim($_POST['email']      ?? '');
$password   = trim($_POST['password']   ?? '');
$accessType = trim($_POST['accessType'] ?? '');

if (empty($email) || empty($password)) {
    header('Location: ' . BASE_URL . '/user/login.php?erro=Preencha+todos+os+campos');
    exit;
}

// Admin
$adminEmail = "adm@gmail.com";
$adminSenha = "7777777";

if ($email === $adminEmail && $password === $adminSenha) {
    $_SESSION['adm']  = $adminEmail;
    $_SESSION['tipo'] = 'admin';
    header('Location: ' . BASE_URL . '/adm/index.php');
    exit;
}

// Usuário
if ($accessType === 'user') {
    $dadosUser = new UserAuth($con);
    $login = $dadosUser->login($email, $password);

    if (!empty($login['status'])) {
        header('Location: ' . BASE_URL . '/public/index.php');
        exit;
    } else {
        $msg = urlencode($login['mensagem'] ?? 'Email ou senha incorretos.');
        header('Location: ' . BASE_URL . '/user/login.php?erro=' . $msg);
        exit;
    }
}

// Barbeiro
if ($accessType === 'barbeiro') {
    $dadosBarbeiro = new BarbeiroAuth($con);
    $login = $dadosBarbeiro->login($email, $password);

    if (!empty($login['status'])) {
        header('Location: ' . BASE_URL . '/barber/barber.php');
        exit;
    } else {
        $msg = urlencode($login['mensagem'] ?? 'Email ou senha incorretos.');
        header('Location: ' . BASE_URL . '/user/login.php?erro=' . $msg);
        exit;
    }
}

header('Location: ' . BASE_URL . '/user/login.php?erro=Credenciais+inválidas');
exit;