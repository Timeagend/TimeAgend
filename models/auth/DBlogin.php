<?php
session_start();

require_once '../../config/url.php';
require_once '../../config/conection.php'; // $con (MySQLi)
require_once 'authFunctions.php';

// 1️⃣ Verifica método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/user/login.php");
    exit;
}

// 2️⃣ Captura os dados
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$accessType = $_POST['accessType'] ?? ''; // opcional (caso ainda exista)

if (empty($email) || empty($password)) {
    $_SESSION['erro_login'] = "Preencha todos os campos.";
    header("Location: " . BASE_URL . "/user/login.php");
    exit;
}

// 3️⃣ Verifica se é ADMIN primeiro
$adminEmail = "adm@gmail.com";
$adminSenha = "7777777";

if ($email === $adminEmail && $password === $adminSenha) {
    $_SESSION['adm'] = $adminEmail;
    $_SESSION['tipo'] = 'admin';
    header("Location: " . BASE_URL . "/adm/index.php");
    exit;
}

// 4️⃣ Se não for admin, continua o fluxo normal
if ($accessType === 'user') {
    $dadosUser = new UserAuth($con);
    $login = $dadosUser->login($email, $password);
    if (!empty($login['status'])) {
        header("Location: " . BASE_URL . "/public/index.php");
        exit;
    } else {
        $_SESSION['erro_login'] = $login['mensagem'];
        header("Location: " . BASE_URL . "/user/login.php");
        exit;
    }
}

if ($accessType === 'barbeiro') {
    $dadosBarbeiro = new BarbeiroAuth($con);
    $login = $dadosBarbeiro->login($email, $password);
    if (!empty($login['status'])) {
        header("Location: " . BASE_URL . "/barber/barber.php");
        exit;
    } else {
        $_SESSION['erro_login'] = $login['mensagem'];
        header("Location: " . BASE_URL . "/user/login.php");
        exit;
    }
}

// 5️⃣ Se nenhum tipo foi informado ou não bateu com nada
$_SESSION['erro_login'] = "Credenciais inválidas.";
header("Location: " . BASE_URL . "/user/login.php");
exit;
