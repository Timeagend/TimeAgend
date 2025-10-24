<?php  
session_start();
include_once '../models/auth/authFunctions.php';  
include_once '../config/conection.php';     

$user_id = $_SESSION['user_id']; 

$validAuth = new Auth($con);
if (!$validAuth->isAuthenticated()) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

$sql = "SELECT foto_perfil FROM user WHERE iduser = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);  
$stmt->execute();

$result = $stmt->get_result(); 
$usuario = $result->fetch_assoc(); 

if ($usuario) {
    $foto_perfil = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'uploads/default.png';
    $foto_perfil = "../" . $foto_perfil;
} else {
    $foto_perfil = 'uploads/default.png';
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/perfil.css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/contact.css">
</head>
<body>
    <!-- Header -->
<header>
    <img src="<?= BASE_URL?>img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
    <button class="menu-toggle" aria-label="Toggle menu">&#9776;</button> <!-- Botão para alternar o menu -->
    <nav class="menu-principal">
        <a href="<?= BASE_URL?>public/index.php">Início</a>
        <a href="<?= BASE_URL?>public/agendamento.php">Agenda</a>
        <a href="<?= BASE_URL?>public/planos.php">Planos</a>
        <a href="#" class="selected">Perfil</a> <!-- Classe "selected" aplicada ao Perfil -->
        <a href="#" onclick="openContactModal()">Contato</a>
    </nav>
</header>

<div class="modal" id="contactModal">
    <div class="modal-content-1">

    <?php if (!empty($successMessage) || !empty($errorMessage)): ?>
    <div id="mensagemModal" class="custom-modal" style="display: block;">
        <div class="custom-modal-content">
            <p>
                <?php 
                    echo !empty($successMessage) 
                        ? htmlspecialchars($successMessage) 
                        : htmlspecialchars($errorMessage); 
                ?>
            </p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>
    <?php endif; ?>


        <span class="close" onclick="closeContactModal()">&times;</span>
    
        <div id="contato" class="contato-container">
      
            <form class="form-email" method="POST">
            <h3 class="fale-conosco">Fale <span class="conosco">Conosco</span></h3>
              <div>
                <label for="user_name">Nome:</label>
                <input type="text" name="user_name" id="user_name" required/>
              </div>
              <div>
                <label for="user_email">E-mail:</label>
                <input type="email" name="user_email" id="user_email" required>
              </div>
                <label for="mensagem">Mensagem:</label>
                <textarea name="mensagem" id="mensagem" required></textarea>
                <button type="submit" name="sendEmail" data-button>Enviar</button>
            </form>
        </div>
    </div>
</div>

<main>
    
    <div class="profile-image">
        <img src="<?= $foto_perfil ?>" alt="Foto de Perfil">
       
    </div>
    
    <div class="historico" onclick="window.location.href='<?= BASE_URL?>/public/historico.php' ">Histórico e movimentações</div>
    <class class="container">
       
        <div>
          <form id="form-upload" action="<?= BASE_URL ?>models/auth/upload_foto.php" method="POST" enctype="multipart/form-data">
    <input type="file" id="upload-imagem" name="imagem" required hidden>

    <!-- label com ícone -->
    <label for="upload-imagem" class="upload-label">
        <img src="<?= BASE_URL ?>/img/icons8-camera-48.png" alt="Selecionar foto" width="40" height="40">
    </label>

    <!-- botão de salvar removido -->
</form>

                <stYle>
                  
                    .upload-label {
    cursor: pointer;
    display: inline-block;
    margin-top: 10px;
    margin-right: 480px;
    position: relative; /* em vez do z-index */
   background-color: #9ed74dff ;
  padding: 5px;
  height: 40px;
   border-radius: 50%;
}

                    .upload-label:hover {
    background-color: #aae657e6;
    
                    }
</stYle>
            </form>
        </div>

        <div class="info">
            <h1>Informações pessoais</h1>
            <label>Nome:</label>
            <input type="text" value="<?= $_SESSION['nome_user']?>" />
            <label>Telefone:</label>
            <input type="tel" value="<?= $_SESSION['user_phone']?>" />
            <label>Email:</label>
            <input type="email" value="<?= $_SESSION['email_user']?>" />
        </div>
            <button class="button">Salvar</button>
            <form action="<?= BASE_URL ?>models/auth/logout.php" method="POST">
                <button type="submit" class="button-logout" name="logout">Desvincular</button>
            </form>
            <style>
                .button{
                    margin-right: 150px;
                }
            </style>
    
    </class>
    
</main>
 <script src="<?= BASE_URL?>public/assets/script/menu.js"></script>
 <script src="<?=BASE_URL?>/public/assets/script/contact.js"></script>
 <script>
document.getElementById("upload-imagem").addEventListener("change", function() {
    const arquivo = this.files[0];
    if (!arquivo) return;

    const formData = new FormData();
    formData.append("imagem", arquivo);

    fetch("<?= BASE_URL ?>models/auth/upload_foto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Mensagem de retorno do PHP
        // Atualiza a página para mostrar a nova foto
        location.reload();
    })
    .catch(error => {
        console.error("Erro ao enviar imagem:", error);
    });
});
</script>

</body>
</html>



