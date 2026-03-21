<?php
include_once("authfunctions.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["cadastro-username"]);
    $email = trim($_POST["cadastro-email"]);
    $phone = trim($_POST["cadastro-numero"]);
    $password = password_hash(trim($_POST["cadastro-senha"]), PASSWORD_DEFAULT);

    $auth = new User($con);
    $auth->register($name, $email, $phone, $password);
  
}
?>
