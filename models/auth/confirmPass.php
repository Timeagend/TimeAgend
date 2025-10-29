<?php
include_once("../../config/conection.php");
include_once("authFunctions.php");
session_start();

if (!isset($_SESSION['var_email'])) {
    echo "Sessão expirada. Volte e tente novamente.";
    exit;
}

if (isset($_POST['senha'], $_POST['confirma_senha'])) {
    $senha = $_POST['senha'];
    $confirmaSenha = $_POST['confirma_senha'];
    $email = $_SESSION['var_email'];

    if ($senha === $confirmaSenha) {
        $user = new User($con);
        $hash = password_hash($senha, PASSWORD_BCRYPT);

        if ($user->alterPassword($email, $hash)) {
            echo "Senha alterada com sucesso!";
            unset($_SESSION['var_email']);
            header("Location: " . BASE_URL . "user/login.php");
        } else {
            echo "Erro ao atualizar a senha.";
        }
    } else {
        echo "As senhas não coincidem.";
    }
} else {
    echo "Preencha todos os campos.";
}
