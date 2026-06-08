<?php 
include_once('../config/url.php');
require_once('../models/auth/authFunctions.php');

session_start();
$validAuth = new Auth($con);
if (!$validAuth->isAuthenticated()) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}


$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['sendEmail'])) {
    $nome = trim($_POST['user_name']);
    $email = trim($_POST['user_email']);
    $mensagem = trim($_POST['mensagem']);

    if (empty($nome) || empty($email) || empty($mensagem)) {
        $errorMessage = "Por favor, preencha todos os campos.";
    } else {
        // Enviar email ou salvar no banco
        // mail() ou outro processamento

        $successMessage = "Mensagem enviada com sucesso!";
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeAgend Barber Shop - Serviços</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/agendamento1.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/agendamento2.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/agendamentos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/resumo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/responsivo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/contact.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>adm/img">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/logo.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/contato.css">

    <style>
      
    </style>
    
</head>
<body>
    <!-- Header -->
    <header>
    <!-- Logo -->
           <a href="<?= BASE_URL ?>/public/index.php" class="logo-link">
    <div class="logo-icon">
        <i class="fa-solid fa-scissors"></i>
    </div>
    <div class="brand-logo">TimeAgend</div>
</a>
           
        <!-- Botão para alternar o menu (visível apenas no mobile) -->
  <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu-principal">
    &#9776;
  </button>
 

  <!-- Menu principal -->
  <nav class="menu-principal" id="menu-principal">
    <a href="<?= BASE_URL ?>/public/index.php">Início</a>
    <a href="<?= BASE_URL ?>/public/agendamento.php" class="selected">Agenda</a>
    <a href="<?= BASE_URL ?>/public/planos.php">Planos</a>
    <a href="<?= BASE_URL ?>/public/perfil.php">Perfil</a>
    <a onclick="abrirModalContato()" class="nav-link font-medium hover:text-accent transition-colors cursor-pointer">Contato</a>
  </nav>

       
        <style>
            @media (max-width: 768px)
            {.menu-principal {
                z-index: 10;
            }}
          
        </style>
    </header>
   <div id="modalContato">
  <div class="modal-card">

    <button class="btn-fechar" onclick="fecharModalContato()">&times;</button>

    <!-- Cabeçalho -->
    <div id="m_cabecalho">
      <h2>FALE <span>CONOSCO</span></h2>
      <p>Envie sua dúvida ou sugestão. Respondemos em breve.</p>
      <hr>
    </div>

    <!-- Formulário -->
    <form id="contactFormModal">
      <label>Nome</label>
      <input type="text" id="m_nome" placeholder="Seu nome completo">
      <span id="m_erroNome" class="erro-campo"></span>

      <label>E-mail</label>
      <input type="email" id="m_email" placeholder="seuemail@exemplo.com">
      <span id="m_erroEmail" class="erro-campo"></span>

      <label>Mensagem</label>
      <textarea id="m_mensagem" rows="4" placeholder="Digite sua mensagem..."></textarea>
      <span id="m_erroMensagem" class="erro-campo"></span>

      <button type="submit" class="btn-enviar" id="m_btnEnviar">Enviar Mensagem</button>
    </form>

    <!-- Feedback -->
    <div id="m_feedback">
      <div id="m_feedbackIcone"></div>
      <div id="m_feedbackTitulo"></div>
      <p id="m_feedbackMsg"></p>
      <button class="btn-ok" onclick="fecharModalContato()">OK</button>
    </div>

  </div>
</div>

</head>

  <main class="agenda-container">
  <!-- ETAPA 1 -->
  <div id="etapa1" class="fade">
    <div class="steps">
      <div class="step active"><span class="circle">1</span><span>Serviços</span></div>
      <div class="step"><span class="circle">2</span><span>Profissional</span></div>
      <div class="step"><span class="circle">3</span><span>Data & Hora</span></div>
      <div class="step"><span class="circle">4</span><span>Confirmação</span></div>
    </div>

    <h2 class="titulo">SELECIONE UM <span style="color:#f0c000">SERVIÇO</span></h2>

    <div class="categorias">
      <button class="categoria ativa" data-categoria="cortes">Cortes</button>
      <button class="categoria" data-categoria="sobrancelha">Sobrancelha</button>
      <button class="categoria" data-categoria="barba">Barba</button>
      <button class="categoria" data-categoria="combo">Combo</button>
    </div>

    <div id="listaServicos" class="lista-servicos"></div>

    <div id="resumoServicos" class="resumo-servicos">
      <p><strong>SERVIÇOS SELECIONADOS:</strong></p>
      <div id="listaSelecionados">Nenhum serviço selecionado.</div>
      <p><strong>Total:</strong> R$ <span id="totalValor">0,00</span></p>
    </div>

    <div class="btn-row">
      <button id="btnConfirmarServicos" class="btn-confirmar">CONFIRMAR</button>
    </div>
  </div>

  <!-- ETAPA 2 -->
  <div id="etapa2" class="fade" style="display:none;">
    <div class="steps">
      <div class="step"><span class="circle">1</span><span>Serviços</span></div>
      <div class="step active"><span class="circle">2</span><span>Profissional</span></div>
      <div class="step"><span class="circle">3</span><span>Data & Hora</span></div>
      <div class="step"><span class="circle">4</span><span>Confirmação</span></div>
    </div>

    <h2 class="titulo">SELECIONE O <span style="color:#f0c000">PROFISSIONAL</span></h2>

    <!-- <div id="resumoTopo" class="resumo-servicos"></div> -->

    <div id="listaProfissionais" class="lista-profissionais">
      <img src="<?= BASE_URL ?>img/barber.png" alt="Foto do barbeiro">
    </div>
    

    <div class="btn-row" style="margin-top:18px">
      <button id="btnVoltar" class="btn-voltar">← VOLTAR</button>
      <button id="btnConfirmarProf" class="btn-confirmar">CONFIRMAR</button>
    </div>
  </div>
 <!-- ETAPA 3 (inicialmente oculta) -->
<div id="etapa3" style="display:none;">
  <div class="steps">
    <div class="step"><span class="circle">1</span><span>Serviços</span></div>
    <div class="step"><span class="circle">2</span><span>Profissional</span></div>
    <div class="step active"><span class="circle">3</span><span>Data & Hora</span></div>
    <div class="step"><span class="circle">4</span><span>Confirmação</span></div>
  </div>

  <h2 class="titulo">SELECIONE <span style="color:#f0c000">DATA E HORA</span></h2>

  <div class="data-hora-box">
    <div class="data-section">
      <p><i class="fa-regular fa-calendar"></i> SELECIONE A DATA</p>
      <input type="date" id="dataSelecionada">
      <small>Dica: Escolha um dia útil para mais horários</small>
    </div>

    <div class="horarios-section">
      <p><i class="fa-regular fa-clock"></i> HORÁRIOS DISPONÍVEIS:</p>
      <div class="horarios-grid">
        <button class="hora">09:00</button>
        <button class="hora">10:00</button>
        <button class="hora">11:00</button>
        <button class="hora">13:00</button>
        <button class="hora">14:00</button>
        <button class="hora">15:00</button>
      </div>
    </div>
  </div>

  <div class="btn-row" style="margin-top:18px">
    <button id="btnVoltar2" class="btn-voltar">← VOLTAR</button>
    <button id="btnConfirmarData" class="btn-confirmar">CONFIRMAR</button>
  </div>
</div>
  <!-- ETAPA 4 (inicialmente oculta) -->
 <div class="etapa-confirmacao" style="display:none;">
            <div class="steps">
                <div class="step"><span class="circle">1</span><span>Serviços</span></div>
                <div class="step"><span class="circle">2</span><span>Profissional</span></div>
                <div class="step"><span class="circle">3</span><span>Data & Hora</span></div>
                <div class="step active"><span class="circle">4</span><span>Confirmação</span></div>
            </div>

<h2 class="titulo">CONFIRMAÇÃO DO <span style="color:#f0c000">AGENDAMENTO</span></h2>

            <div class="resumo-completo">
                <div class="resumo-item">
                    <h3>📋 SERVIÇOS SELECIONADOS</h3>
                    <div id="resumoServicosConfirmacao"></div>
                </div>

                <div class="resumo-item">
                    <h3>👨‍💼 PROFISSIONAL</h3>
                    <div id="resumoProfissionalConfirmacao"></div>
                </div>

                <div class="resumo-item">
                    <h3>📅 DATA E HORA</h3>
                    <div id="resumoDataHoraConfirmacao"></div>
                </div>

                <div class="resumo-total">
                    <h3>TOTAL: R$ <span id="totalConfirmacao"><?= $_SESSION['valorFinal']?></span></h3>
                </div>
            </div>

            <div class="btn-row">
                <button id="btnVoltarConfirmacao" class="btn-voltar">← VOLTAR</button>
                <button id="btnFinalizarAgendamento" class="btn-confirmar">FINALIZAR AGENDAMENTO</button>
            </div>
        </div>

        <div id="popupSucesso" style="display:none;">
        <div class="popup-overlay"></div>
        <div class="popup-box">
           <div class="popup-icone">✂️</div>
            <h2>Agendamento Realizado!</h2>
            <p>Seu horário foi confirmado com sucesso.</p>
        <button id="popupBtnOk">OK</button>
  </div>
</div>
</main>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="<?= BASE_URL?>public/assets/script/agendamento.js"></script>
<script src="<?= BASE_URL?>public/assets/script/contato.js"></script>
<script src="<?= BASE_URL?>public/assets/script/menu.js"></script>



</body>
</html>