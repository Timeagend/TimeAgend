<?php
include_once("../../config/conection.php");
include_once("authFunctions.php");
include_once("../../config/url.php");

session_start();

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $user = new User($con);
    $dados = $user->getUserByEmail($email);

    if ($dados) {
        $_SESSION['var_email'] = $email;
        header("Location: " . BASE_URL . "user/newpassword.php");
        exit;
    } else {
        echo "E-mail não encontrado.";
    }
} else {
    echo "E-mail não fornecido.";
}
