<?php
session_start();
include_once '../models/auth/authFunctions.php';
include_once '../config/conection.php';

if (!isset($_SESSION['iduser'])) die("Usuário não logado.");
if (!isset($con) || !$con) die("Conexão com BD não encontrada.");

$user_id = (int) $_SESSION['iduser'];
$validAuth = new Auth($con);
if (!$validAuth->isAuthenticated()) {
    header("Location: " . (defined('BASE_URL') ? BASE_URL : '/') . "user/login.php");
    exit();
}

$foto_perfil = null;
$stmt = $con->prepare("SELECT foto_perfil FROM user WHERE iduser = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $usuario = $result->fetch_assoc()) {
        $foto_perfil = !empty($usuario['foto_perfil'])
            ? BASE_URL . $usuario['foto_perfil']
            : BASE_URL . "uploads/default.png";
    } else {
        $foto_perfil = BASE_URL . "uploads/default.png";
    }
    $stmt->close();
} else {
    $foto_perfil = BASE_URL . "uploads/default.png";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil — TimeAgend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/contact.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/logo.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/perfil.css">
</head>
<body>

<!-- ── Header (logo + menu mantidos) ─────────────────────── -->
<header>
    <a href="<?= BASE_URL ?>/public/index.php" class="logo-link">
        <div class="logo-icon"><i class="fa-solid fa-scissors"></i></div>
        <div class="brand-logo">TimeAgend</div>
    </a>
    <button class="menu-toggle" aria-label="Toggle menu">&#9776;</button>
    <nav class="menu-principal">
        <a href="<?= BASE_URL?>/public/index.php">Início</a>
        <a href="<?= BASE_URL?>/public/agendamento.php">Agenda</a>
        <a href="<?= BASE_URL?>/public/planos.php">Planos</a>
        <a href="#" class="selected">Perfil</a>
        <a href="#" onclick="openContactModal()">Contato</a>
    </nav>
</header>

<!-- ── Modal de Contato ───────────────────────────────────── -->
<div class="modal" id="contactModal">
    <div class="modal-content-1">
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

<!-- ── Main ───────────────────────────────────────────────── -->
<main>

    <!-- Hero: foto + saudação + histórico -->
    <div class="hero">

        <!-- Foto + câmera -->
        <div class="profile-image">
            <img src="<?= htmlspecialchars($foto_perfil) ?>" alt="Foto de Perfil">
            <form id="form-upload" action="<?= BASE_URL ?>models/auth/upload_foto.php"
                  method="POST" enctype="multipart/form-data">
                <input type="file" id="upload-imagem" name="imagem" required hidden>
                <label for="upload-imagem" class="upload-label" title="Alterar foto">
                    <i class="fa-solid fa-camera"></i>
                </label>
            </form>
        </div>

        <!-- Texto -->
        <div class="hero-text">
            <p class="eyebrow">Bem-vindo</p>
            <h1>Olá, <?= htmlspecialchars($_SESSION['nome_user'] ?? 'Cliente') ?> 👋</h1>
            <p>Gerencie suas informações e preferências.</p>
        </div>

        <!-- Card histórico -->
        <a class="historico" href="<?= BASE_URL?>/public/historico.php">
            <div class="historico-icon">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <span class="historico-label">Histórico e<br>movimentações</span>
            <span class="historico-arrow"><i class="fa-solid fa-chevron-right"></i></span>
        </a>

    </div>

    <!-- Card de informações pessoais -->
    <div class="info-card">

        <div class="info-card-header">
            <div class="info-card-header-icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <h2>Informações pessoais</h2>
                <p>Atualize seus dados pessoais e de contato.</p>
            </div>
        </div>

        <div class="info-row">
            <div class="info-row-icon"><i class="fa-solid fa-user"></i></div>
            <div class="info-row-content">
                <label>Nome</label>
                <input type="text" id="input-nome"
                       value="<?= htmlspecialchars($_SESSION['nome_user'] ?? '') ?>"
                       readonly/>
            </div>
            <button class="edit-btn" onclick="toggleEdit('input-nome')" title="Editar">
                <i class="fa-solid fa-pen"></i>
            </button>
        </div>

        <div class="info-row">
            <div class="info-row-icon"><i class="fa-solid fa-phone"></i></div>
            <div class="info-row-content">
                <label>Telefone</label>
                <input type="tel" id="input-tel"
                       value="<?= htmlspecialchars($_SESSION['phone'] ?? '') ?>"
                       readonly/>
            </div>
            <button class="edit-btn" onclick="toggleEdit('input-tel')" title="Editar">
                <i class="fa-solid fa-pen"></i>
            </button>
        </div>

        <div class="info-row">
            <div class="info-row-icon"><i class="fa-solid fa-envelope"></i></div>
            <div class="info-row-content">
                <label>Email</label>
                <input type="email" id="input-email"
                       value="<?= htmlspecialchars($_SESSION['email_user'] ?? '') ?>"
                       readonly/>
            </div>
            <button class="edit-btn" onclick="toggleEdit('input-email')" title="Editar">
                <i class="fa-solid fa-pen"></i>
            </button>
        </div>

    </div>

    <!-- Ações -->
    <div class="actions-row">
        <form action="<?= BASE_URL ?>models/auth/logout.php" method="POST">
            <button type="submit" name="logout" class="button-logout">
                <i class="fa-solid fa-trash-can"></i> Desvincular conta
            </button>
        </form>
        <button class="button" onclick="salvarPerfil()">
            <i class="fa-regular fa-floppy-disk"></i> Salvar alterações
        </button>
    </div>

</main>

<script src="<?= BASE_URL?>public/assets/script/menu.js"></script>
<script src="<?= BASE_URL?>/public/assets/script/contact.js"></script>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="<?= BASE_URL?>/public/assets/script/edita-perfil.js"></script>


</body>
</html>