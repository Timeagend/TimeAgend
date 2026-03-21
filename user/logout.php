<?php 
  include_once('../config/url.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>user/assets/css/logout.css">

</head>
<body>
    <div class="container">
        <h1>Recuperar senha</h1>
        <form action="<?= BASE_URL ?>models/auth/newpass.php" method="POST">
            <input type="email" id="email" name="email" placeholder="Digite seu email">
            <button type="submit">Enviar e-mail</button>
        </form>
    </div>
    </form>
 </body>
</html>