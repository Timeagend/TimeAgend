<?php 
include_once('../config/url.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Redefinir Senha</h1>
        <form action="<?= BASE_URL ?>models/auth/confirmPass.php" method="POST">
            <input type="password" name="senha" placeholder="Digite sua nova senha" required>
            <input type="password" name="confirma_senha" placeholder="Confirme sua nova senha" required>
            <button type="submit">Alterar Senha</button>
        </form>
    </div>
</body>
</html>
