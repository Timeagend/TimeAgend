<?php
session_start();

require_once '../../config/url.php';
require_once '../../config/conection.php';
require_once 'authFunctions.php';

$loginUrl = BASE_URL . '/user/login.php';

// Função padrão de resposta — agora redireciona em vez de JSON
function responder(bool $sucesso, string $redirect = '', string $mensagem = '') {
    global $loginUrl;
    $target = $sucesso ? ($redirect ?: $loginUrl) : $loginUrl;

    if ($sucesso) {
        header('Location: ' . $target . '?sucesso=' . urlencode('Conta criada! Faça o login para continuar.'));
    } else {
        header('Location: ' . $target . '?erro=' . urlencode($mensagem));
    }
    exit;
}

// 1️⃣ Verifica método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, '', 'Método não permitido.');
}

// 2️⃣ Captura os dados
$name     = trim($_POST['cadastro-username']       ?? '');
$email    = trim($_POST['cadastro-email']          ?? '');
$phone    = trim($_POST['cadastro-numero']         ?? '');
$senha    = trim($_POST['cadastro-senha']          ?? '');
$confirma = trim($_POST['cadastro-confirma-senha'] ?? '');

// 3️⃣ Validações
if (!$name || !$email || !$phone || !$senha || !$confirma) {
    responder(false, '', 'Preencha todos os campos.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responder(false, '', 'Insira um email válido.');
}

if (strlen($senha) < 6) {
    responder(false, '', 'A senha deve ter pelo menos 6 caracteres.');
}

if ($senha !== $confirma) {
    responder(false, '', 'As senhas não coincidem.');
}

// 4️⃣ Criptografa a senha
$password = password_hash($senha, PASSWORD_DEFAULT);

// 5️⃣ Instancia classe
$auth = new User($con);

// 6️⃣ Tenta cadastrar
$result = $auth->register($name, $email, $phone, $password);

// 7️⃣ Retorno
if ($result) {
    responder(true, BASE_URL . '/user/login.php');
} else {
    responder(false, '', 'Erro ao cadastrar. Email pode já estar em uso.');
}
