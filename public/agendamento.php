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
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/profissionais.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/resumo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/responsivo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/contact.css">
    <style>
        
    </style>
    
</head>
<body>
    <!-- Header -->
    <header>
    <img src="<?= BASE_URL?>/img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
        <button class="menu-toggle" aria-label="Toggle menu">&#9776;</button> <!-- Botão para alternar o menu -->
        <nav class="menu-principal">
            <a href="<?= BASE_URL ?>/public/index.php">Início</a>
            <a href="<?= BASE_URL ?>/public/agendamento.php" class="selected">Agenda</a>
            <a href="<?= BASE_URL ?>/public/planos.php">Planos</a>
            <a href="<?= BASE_URL ?>/public/perfil.php">Perfil</a> <!-- Classe "selected" aplicada ao Perfil -->
            <a href="#" onclick="openContact()">Contato</a>
        </nav>

        <script src="menu.js"></script>
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
                <label for="user_name">Nome:</label>
                <input type="text" name="user_name" id="user_name" required>
                <label for="user_email">E-mail:</label>
                <input type="email" name="user_email" id="user_email" required>
                <label for="mensagem">Mensagem:</label>
                <textarea name="mensagem" id="mensagem" required></textarea>
                <button type="submit" name="sendEmail" data-button>Enviar</button>
            </form>
        </div>
    </div>
</div>



    
   
    <!-- Main Content -->
    <div class="main-container" id="main-container-servico">
        <h1><span class="span">SELECIONE UM</span> SERVIÇO</h1>
        <div style="margin: 10px auto; width: 140%; transform: translateX(-15%); height: 2px; background: linear-gradient( rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
         </div>

        
      <div class="seleciona-servicos" id="tituloServico">
        <div class="servicos-1">
            <h3 id="servico1" onclick="selecionarServico('Cortes')">Cortes</h3>
            <h3 id="servico2" onclick="selecionarServico('Combo')">Combo</h3>
        </div>
        <div class="servicos-2">
            <h3 id="servico3" onclick="selecionarServico('Barba')">Barba</h3>
            <h3 id="servico4" onclick="selecionarServico('Sobrancelha')">Sobrancelha</h3>
        </div>
        <button class="button-confirma" onclick="confirmarServico()">Confirma</button>
      </div>
      
    </div>

    

       <!-- Cortes -->
<!-- Cortes -->


<!-- Seção: Seleção de Corte -->
<div class="menu-container" id="Cortes-menu">
    <div class="main-container">
        <h1 class="seleciona-span"><span class="span">SELECIONE UM</span> CORTE</h1>
        <div style="position: relative; top: -12px; margin: 10px auto; width: 140%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    </div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png"  alt="Voltar" title="voltar" onclick="voltarParaSelecao()">
    <div class="estiliza-cortes">
        <h1 class="h1-titulos">CORTES</h1>
        <table>
            <tr id="corte1" onclick="selecionarTipoCorte('Corte Clássico', 40)">
                <td class="left"> <input type="checkbox" id="checkboxCorte1">CORTE CLÁSSICO</td>
                <td class="right">R$ 40,00</td>
            </tr>
            <tr id="corte2" onclick="selecionarTipoCorte('Corte Infantil', 40)">
                <td class="left"><input type="checkbox" id="checkboxCorte2">CORTE INFANTIL</td>
                <td class="right">R$ 40,00</td>
            </tr>
            <tr id="corte3" onclick="selecionarTipoCorte('Corte Degradê', 30)">
                <td class="left"> <input type="checkbox" id="checkboxCorte3">CORTE DEGRADÊ</td>
                <td class="right">R$ 30,00</td>
            </tr>
            <tr id="corte4" onclick="selecionarTipoCorte('Corte Americano', 60)">
                <td class="left"> <input type="checkbox" id="checkboxCorte4">CORTE AMERICANO</td>
                <td class="right">R$ 60,00</td>
            </tr>
            <tr id="corte5" onclick="selecionarTipoCorte('Corte Low Fade', 65)">
                <td class="left"> <input type="checkbox" id="checkboxCorte5">CORTE LOW FADE</td>
                <td class="right">R$ 65,00</td>
            </tr>
        </table>
    </div>

    <button class="confirma-corte" onclick="confirmarCorte()">CONFIRMAR</button>
</div>

<!-- Seção: Seleção de Profissionais -->
<div class="seleciona-profissionais" id="profissional-menu" style="display: none;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA SEU</span> PROFISSIONAL</h1>
    <div style="overflow: hidden; position: relative; left: 200px; top: -12px; margin: 10px auto; width: 94%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>

    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaSelecaoCortes()">

    <div class="profissional-card" onclick="selecionarProfissionalCortes('Carlos')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Carlos">
        <h3>Carlos</h3>
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalCortes('Rafael')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Rafael">
        <h3>Rafael</h3>
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalCortes('Roberto')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Roberto">
        <h3>Roberto</h3>
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalCortes('João')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="João">
        <h3>João</h3>
    </div>

    <button class="button-confirma-profissional" onclick="irParaDataHoraCortes()">CONFIRMAR</button>
</div>

<!-- Seção: Seleção de Data e Hora -->
<div class="seleciona-data" id="selecionaData-cortes" style="display: none; text-align: center;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA UMA</span> DATA E HORA</h1>
    <div style="overflow: hidden; position: relative; right: 250px; top: -12px; margin: 10px auto; width: 280%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>

    <img class="icone-left1" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaProfissionaisCortes()">

    <!-- ======= INÍCIO DO FORMULÁRIO ADICIONADO ======= -->
    <div class="horarios-scroll-container">
    <div class="agendar-horario-form">
        <form id="agendarHorarioForm">
            <!-- Nome -->
            <!-- <label class="titulos-form">Nome completo</label>
            <input class="nome-form" type="text" placeholder="Digite seu nome" id="nomeCompletoCortes"> -->

            <!-- Data -->
            <label class="titulos-form">Selecione a data</label>
            <input class="data-form" type="date" id="dataCortes">
            <img class="horarios-icon-setas" src="<?= BASE_URL?>/img/uploads/double-arrow.png" alt="" width="40" >
            <!-- Horários -->
            <p class="titulo-seleciona-um-horario">Selecione um horário</p>
            
            <div class="horarios-grid" id="horariosCortes">
                        <button type="button" onclick="selecionarHorarioCortes('09:00')">09:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('10:00')">10:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('11:00')">11:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('12:00')">12:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('13:00')">13:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('14:00')">14:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('15:00')">15:00</button>
                        <button type="button" onclick="selecionarHorarioCortes('16:00')">16:00</button>
                    </div>

            <!-- Botão para confirmar a seleção do horário -->
  <button type="button" class="button-confirma-agenda" id="confirmarHorarioCortes" onclick="openCortes()">CONFIRMAR</button>
        </form>
    </div>

    </div>
</div>
  <!-- ======== FIM DO FORMULÁRIO ADICIONADO ======== --> 

   <!-- Modal de Resumo -->
<div id="modalResumoCortes" class="modal1" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="fecharModalCortes()">&times;</span>
    <h2 class="titulo-resumo">Resumo do Agendamento</h2>
    <p id="resumoServicoCortes">Serviço:</p>
    <p id="resumoProfissionalCortes">Profissional:</p>
    <p id="resumoDataCortes">Data:</p>
    <p id="resumoHorarioCortes">Horário:</p>
    <p id="resumoValorCortes">Valor:</p>
    <button type="button" class="btn-pix" onclick="confirmaAgendamentoCortes()">Confirmar Agendamento</button>
  </div>
</div>
<!-- <form action="models/booking/createagend.php" method="POST">
  <div id="modalResumoCortes" class="modal1" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="fecharModalCortes()">&times;</span>
      <h2 class="titulo-resumo">Resumo do Agendamento</h2>

      <p id="resumoServicoCortes">Serviço:</p>
      <p id="resumoProfissionalCortes">Profissional:</p>
      <p id="resumoDataCortes">Data:</p>
      <p id="resumoHorarioCortes">Horário:</p>
      <p id="resumoValorCortes">Valor:</p>

       inputs escondidos para enviar pro PHP -->
      <!-- <input type="hidden" name="servico" id="inputServicoCortes">
      <input type="hidden" name="profissional" id="inputProfissionalCortes">
      <input type="hidden" name="data" id="inputDataCortes">
      <input type="hidden" name="horario" id="inputHorarioCortes">
      <input type="hidden" name="valor" id="inputValorCortes">

      <button type="submit" class="btn-pix" onclick="confirmaAgendamentoCortes()">Confirmar Agendamento</button>
    </div>
  </div>
</form> -->

<script>
function confirmaAgendamentoCortes() {
  document.getElementById("inputServicoCortes").value =
      document.getElementById("resumoServicoCortes").innerText.replace("Serviço: ", "");
  document.getElementById("inputProfissionalCortes").value =
      document.getElementById("resumoProfissionalCortes").innerText.replace("Profissional: ", "");
  document.getElementById("inputDataCortes").value =
      document.getElementById("resumoDataCortes").innerText.replace("Data: ", "");
  document.getElementById("inputHorarioCortes").value =
      document.getElementById("resumoHorarioCortes").innerText.replace("Horário: ", "");
  document.getElementById("inputValorCortes").value =
      document.getElementById("resumoValorCortes").innerText.replace("Valor: ", "");
}
</script>



 <!-- Modal de Sucesso -->
<div id="sucessoModalCortes" class="modal1" style="display: none;">
  <div class="modal-content">
    <h2 class="titulo-resumo">Agendamento Realizado com Sucesso!</h2>
    <!-- <p>Seu agendamento foi confirmado.</p> -->
    <button type="button" class="btn-pix" onclick="voltarParaInicio()">Voltar para o início</button>
  </div>
</div>
</div>
</div>





<!-- Combo -->
<div class="menu-container" id="Combo-menu">
    <div class="main-container">
        <h1 class="seleciona-span"><span class="span">SELECIONE UM</span> COMBO</h1>
        <div style="position: relative; top: -12px; margin: 10px auto; width: 140%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    </div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaMenuInicial()">
    <h1 class="h1-titulos">COMBO</h1>
    <table>
        <tr id="comboCorteBarba" class="combo-item" onclick="selecionarCombo('Corte + Barba', 60)">
            <td class="left"><input type="checkbox" >CORTE + BARBA</td>
            <td class="right">R$ 60,00</td>
        </tr>
        <tr id="comboCorteSobrancelha" class="combo-item" onclick="selecionarCombo('Corte + Sobrancelha', 55)">
            <td class="left"><input type="checkbox" >CORTE + SOBRANCELHA</td>
            <td class="right">R$ 55,00</td>
        </tr>
        <tr id="comboCompleto" class="combo-item" onclick="selecionarCombo('Completo (C/B/S)', 80)">
            <td class="left"><input type="checkbox" >COMPLETO (C/B/S)</td>
            <td class="right">R$ 80,00</td>
        </tr>
    </table>
    <button class="confirma-combo" onclick="confirmarCombo()">CONFIRMAR</button>
</div>
<!-- Profissionais da categoria Combo -->
<div class="seleciona-profissionais" id="profissional-menu-combo" style="display: none;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA SEU</span> PROFISSIONAL</h1>
    <div style="overflow: hidden ;position: relative; left: 200px;top: -12px; margin: 10px auto; width: 94%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaSelecaoCombo()">
    
    

    <div class="profissional-card" onclick="selecionarProfissional('Carlos')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Carlos">
        <h3>Carlos</h3>
        
    </div>

    <div class="profissional-card" onclick="selecionarProfissional('Rafael')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Rafael">
        <h3>Rafael</h3>
        
    </div>

    <div class="profissional-card" onclick="selecionarProfissional('Roberto')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Roberto">
        <h3>Roberto</h3>
        
    </div>
    <div class="profissional-card" onclick="selecionarProfissional('João')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="João">
        <h3>João</h3>
        
    </div>

    <button class="button-confirma-profissional" onclick="irParaDataHora()">CONFIRMAR</button>
</div>

   <!-- Seção de Seleção de Data e Horário - COMBO -->
<div class="seleciona-data" id="selecionaData" style="display: none; text-align: center;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA UMA</span> DATA E HORA</h1>
    <div style="overflow: hidden ;position: relative; right: 250px;top: -12px; margin: 10px auto; width: 280%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    <img class="icone-left1" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaProfissionais()">
    
   <!-- ======= INÍCIO DO FORMULÁRIO ADICIONADO ======= -->
   <div class="horarios-scroll-container">
    <div class="agendar-horario-form">
        <form id="agendarHorarioForm">
            <!-- Nome -->
            <!-- <label class="titulos-form">Nome completo</label>
            <input class="nome-form2" type="text" placeholder="Digite seu nome" id="nomeCompletoCombo"> -->

            <!-- Data -->
            <label class="titulos-form">Selecione a data</label>
            <input class="data-form2" type="date" id="dataCombo">

            <img class="horarios-icon-setas" src="<?= BASE_URL?>/img/uploads/double-arrow.png" alt="" width="40" >
            <!-- Horários -->
            <p class="titulo-seleciona-um-horario">Selecione um horário</p>
            <div class="horarios-grid" id="horariosCombo">
                        <button type="button" onclick="selecionarHorarioCombo('09:00')">09:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('10:00')">10:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('11:00')">11:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('12:00')">12:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('13:00')">13:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('14:00')">14:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('15:00')">15:00</button>
                        <button type="button" onclick="selecionarHorarioCombo('16:00')">16:00</button>
                    </div>

            <!-- Botão para confirmar a seleção do horário -->
  <button type="button" class="button-confirma-agenda" id="confirmarHorarioCombo" onclick="opencombo()" >CONFIRMAR</button>
        </form>
    </div>

    </div>
</div>
  <!-- ======== FIM DO FORMULÁRIO ADICIONADO ======== -->
<!-- Modal de Resumo -->
<div id="modalResumoCombo" class="modal1" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="fecharModalCombo()">&times;</span>
    <h2 class="titulo-resumo">Resumo do Agendamento</h2>
    <!-- <p id="resumoNomeCombo">Nome:</p> -->
    <p id="resumoServicoCombo">Serviço:</p>
    <p id="resumoProfissionalCombo">Profissional:</p>
    <p id="resumoDataCombo">Data:</p>
    <p id="resumoHorarioCombo">Horário:</p>
    <p id="resumoValorCombo">Valor:</p>
    <button type="button" class="btn-pix" onclick="confirmaAgendamentoCombo()">Confirmar Agendamento</button>
  </div>
</div>

 <!-- Modal de Sucesso -->
<div id="sucessoModalCombo" class="modal1" style="display: none;">
  <div class="modal-content">
    <h2 class="titulo-resumo">Agendamento Realizado com Sucesso!</h2>
    <!-- <p>Seu agendamento foi confirmado.</p> -->
    <button type="button" class="btn-pix" onclick="voltarParaInicio()">Voltar para o início</button>
  </div>
</div>
</div>
  <!-- ======== FIM DO FORMULÁRIO ADICIONADO ======== -->
</div>


<!-- Barba -->
<div class="menu-container" id="Barba-menu" >
    <div class="main-container">
        <h1 class="seleciona-span"><span class="span">SELECIONE UM TIPO</span> BARBA</h1 >
        <div style="position: relative;top: -12px ;margin: 10px auto; width: 140%; transform: translateX(-15%); height: 2px; background: linear-gradient( rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    </div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaSelecao()">
    <h1 class="h1-titulos">BARBA</h1>
    <table>
        <tr onclick="selecionarTipoBarba('Barba Completa', 35)">
            <td class="left"><input type="checkbox" >BARBA COMPLETA</td>
            <td class="right">R$ 35,00</td>
          </tr>
          <tr onclick="selecionarTipoBarba('Barba Desenhada', 30)">
            <td class="left"><input type="checkbox" >BARBA DESENHADA</td>
            <td class="right">R$ 30,00</td>
          </tr>
          <tr onclick="selecionarTipoBarba('Só Navalha', 25)">
            <td class="left"><input type="checkbox" >SÓ NAVALHA</td>
            <td class="right">R$ 25,00</td>
          </tr>          
    </table>
    <button class="confirm-button" onclick="confirmaBarba()">CONFIRMA</button>
</div>
<!-- Profissionais da categoria Barba -->
<div class="seleciona-profissionais" id="profissional-menu-barba" style="display: none;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA SEU</span> PROFISSIONAL</h1>
    <div style="overflow: hidden ;position: relative; left: 200px;top: -12px; margin: 10px auto; width: 94%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaSelecaoBarba()">
    
    

    <div class="profissional-card" onclick="selecionarProfissionalBarba('Carlos')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Carlos">
        <h3>Carlos</h3>
        
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalBarba('Rafael')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Rafael">
        <h3>Rafael</h3>
        
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalBarba('Roberto')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Rafael">
        <h3>Roberto</h3>
        
    </div>
    <div class="profissional-card" onclick="selecionarProfissionalBarba('João')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="João">
        <h3>João</h3>
        
    </div>

    <button class="button-confirma-profissional" onclick="irParaDataHoraBarba()">CONFIRMAR</button>
</div>

   <!-- Seção de Seleção de Data e Horário - Barba -->
<div class="seleciona-data" id="selecionadata-barba" style="display: none; text-align: center;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA UMA</span> DATA E HORA</h1>
    <div style="overflow: hidden ;position: relative; right: 250px;top: -12px; margin: 10px auto; width: 280%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    <img class="icone-left1" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaBarba()">
    
    <!-- ======= INÍCIO DO FORMULÁRIO ADICIONADO ======= -->
    <div class="horarios-scroll-container">
    <div class="agendar-horario-form">
        <form id="agendarHorarioForm">
            <!-- Nome -->
            <!-- <label class="titulos-form">Nome completo</label>
            <input class="nome-form2" type="text" placeholder="Digite seu nome" id="nomeCompletoBarba"> -->

            <!-- Data -->
            <label class="titulos-form">Selecione a data</label>
            <input class="data-form2" type="date" id="dataBarba">

            <img class="horarios-icon-setas" src="<?= BASE_URL?>/img/uploads/double-arrow.png" alt="" width="40" >
            <!-- Horários -->
            <p class="titulo-seleciona-um-horario">Selecione um horário</p>
            <div class="horarios-grid" id="horariosBarba">
                        <button type="button" onclick="selecionarHorarioBarba('09:00')">09:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('10:00')">10:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('11:00')">11:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('12:00')">12:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('13:00')">13:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('14:00')">14:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('15:00')">15:00</button>
                        <button type="button" onclick="selecionarHorarioBarba('16:00')">16:00</button>
                    </div>

            <!-- Botão para confirmar a seleção do horário -->
            <button type="button" class="button-confirma-agenda" id="confirmarHorarioBarba" onclick="openContactModal3()">CONFIRMAR</button>
        </form>
    </div>

    </div>
</div>
  <!-- ======== FIM DO FORMULÁRIO ADICIONADO ======== -->
<!-- Modal de Resumo -->
<div id="modalResumoBarba" class="modal1" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="fecharModalBarba()">&times;</span>
    <h2 class="titulo-resumo">Resumo do Agendamento</h2>
    <!-- <p id="resumoNomeBarba">Nome:</p> -->
    <p id="resumoServicoBarba">Serviço:</p>
    <p id="resumoProfissionalBarba">Profissional:</p>
    <p id="resumoDataBarba">Data:</p>
    <p id="resumoHorarioBarba">Horário:</p>
    <p id="resumoValorBarba">Valor:</p>
    <button type="button" class="btn-pix" onclick="confirmaAgendamentoBarba()">Confirmar Agendamento</button>
  </div>
</div>

 <!-- Modal de Sucesso -->
<div id="sucessoModalBarba" class="modal1" style="display: none;">
  <div class="modal-content">
    <h2 class="titulo-resumo">Agendamento Realizado com Sucesso!</h2>
    <!-- <p>Seu agendamento foi confirmado.</p> -->
    <button type="button" class="btn-pix" onclick="voltarParaInicio()">Voltar para o início</button>
  </div>
</div>

</div>

<!-- Sobrancelha -->
<div class="menu-container" id="Sobrancelha-menu" style="display: none;">
    <div class="main-container">
        <h1 class="seleciona-span"><span class="span">SELECIONE UM TIPO DE</span> SOBRANCELHA</h1 >
        <div style="position: relative;top: -12px ;margin: 10px auto; width: 140%; transform: translateX(-15%); height: 2px; background: linear-gradient( rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    </div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaSelecao()">
    <h1 class="h1-titulos">SOBRANCELHA</h1>
    <table>
    <tr id="sobrancelha1" onclick="selecionarTipoSobrancelha('Sobrancelha Navalha', 20)">
    <td class="left"><input type="checkbox" >SOBRANCELHA NAVALHA</td>
    <td class="right">R$ 20,00</td>
    </tr>
    <tr id="sobrancelha2" onclick="selecionarTipoSobrancelha('Sobrancelha Pinça', 25)">
    <td class="left"><input type="checkbox" >SOBRANCELHA PINÇA</td>
    <td class="right">R$ 25,00</td>
    </tr>

    </table>
    <button class="confirm-button" onclick="confirmarSobrancelha()">CONFIRMA</button>


</div>
    <!-- Botão para confirmar a seleção do horário -->
    <button class="button-confirma-agenda" id="confirmarHorario" style="display: none; margin-top: 10px;" onclick="finalizarAgendamento()">CONFIRMAR</button>
</div>

<!-- Profissionais da categoria Sobrancelha -->
<div class="seleciona-profissionais" id="profissional-menu-sobrancelha" style="display: none;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA SEU</span> PROFISSIONAL</h1>
    <div style="overflow: hidden ;position: relative; left: 200px;top: -12px; margin: 10px auto; width: 94%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    <img class="icone-left" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaSelecaoSobrancelha()">
    
    

    <div class="profissional-card" onclick="selecionarProfissionalSobrancelha('Carlos')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Carlos">
        <h3>Carlos</h3>
        
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalSobrancelha('Rafael')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Rafael">
        <h3>Rafael</h3>
        
    </div>

    <div class="profissional-card" onclick="selecionarProfissionalSobrancelha('Roberto')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="Roberto">
        <h3>Roberto</h3>
        
    </div>
    <div class="profissional-card" onclick="selecionarProfissionalSobrancelha('João')">
        <img src="<?= BASE_URL?>/img/barber.png" alt="João">
        <h3>João</h3>
        
    </div>

    <button class="button-confirma-profissional" onclick="irParaDataHoraSobrancelha()">CONFIRMAR</button>
</div>

   <!-- Seção de Seleção de Data e Horário - Sobrancelha -->
<div class="seleciona-data" id="selecionadata-sobrancelha" style="display: none; text-align: center;">
    <h1 class="seleciona-span"><span class="span">ESCOLHA UMA</span> DATA E HORA</h1>
    <div style="overflow: hidden ;position: relative; right: 250px;top: -12px; margin: 10px auto; width: 280%; transform: translateX(-15%); height: 2px; background: linear-gradient(rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>
    <img class="icone-left1" src="<?= BASE_URL?>/img/left-arrow.png" alt="Voltar" title="voltar" onclick="voltarParaProfissionaisSobrancelha()">
    
    <!-- ======= INÍCIO DO FORMULÁRIO ADICIONADO ======= -->
    <div class="horarios-scroll-container">
    <div class="agendar-horario-form">
        <form id="agendarHorarioForm">
            <!-- Nome -->
            <!-- <label class="titulos-form">Nome completo</label> -->
            <!-- <input class="nome-form2" type="text" placeholder="Digite seu nome" id="nomeCompleto"> -->

            <!-- Data -->
            <label class="titulos-form">Selecione a data</label>
            <input class="data-form2" type="date" id="dataSobrancelha" id="dataSobrancelha">

            <img class="horarios-icon-setas" src="<?= BASE_URL?>/img/uploads/double-arrow.png" alt="" width="40" >
            <!-- Horários -->
            <p class="titulo-seleciona-um-horario">Selecione um horário</p>
            <div class="horarios-grid">
                        <button type="button" onclick="selecionarHorarioSobrancelha('09:00')">09:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('10:00')">10:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('11:00')">11:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('12:00')">12:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('13:00')">13:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('14:00')">14:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('15:00')">15:00</button>
                        <button type="button" onclick="selecionarHorarioSobrancelha('16:00')">16:00</button>
                    </div>

            <!-- Botão para confirmar a seleção do horário -->
         <button type="button" class="button-confirma-agenda" id="confirmarHorario" onclick="openContactModal()">CONFIRMAR</button>
        </form>
    </div>

    </div>
</div>
  <!-- ======== FIM DO FORMULÁRIO ADICIONADO ======== -->

  <!-- Modal de Resumo -->
<div id="resumoModal" class="modal1" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="fecharModal()">&times;</span>
    <h2 class="titulo-resumo">Resumo do Agendamento</h2>
    <!-- <p id="resumoNome">Nome:</p> -->
    <p id="resumoServico">Serviço:</p>
    <p id="resumoProfissional">Profissional:</p>
    <p id="resumoData">Data:</p>
    <p id="resumoHorario">Horário:</p>
    <p id="resumoValor">Valor:</p>
    <button type="button" class="btn-pix" onclick="confirmaAgendamento()">Confirmar Agendamento</button>
  </div>
</div>

 <!-- Modal de Sucesso -->
<div id="sucessoModal" class="modal1" style="display: none;">
  <div class="modal-content">
    <h2 class="titulo-resumo">Agendamento Realizado com Sucesso!</h2>
    <!-- <p>Seu agendamento foi confirmado.</p> -->
    <button type="button" class="btn-pix" onclick="voltarParaInicio()">Voltar para o início</button>
  </div>
</div>

    
    
</div>
 <script>
    function confirmaAgendamento() {
    fecharModal(); // Fecha o resumo
    document.getElementById("sucessoModal").style.display = "block"; // Abre modal de sucesso
}

    function voltarParaInicio() {
        window.location.href = "<?= BASE_URL ?>/public/agendamento.php"; // Redireciona para a página inicial
    }
 </script>
    
    <!-- Conteúdo da página -->


    <div style="margin: 50px ;margin-left: 270px; width: 92%; transform: translateX(-15%); height: 2px; background: linear-gradient( rgba(255, 255, 255, 0.414), rgba(255, 255, 255, 0));"></div>

    <script>
        // Função para abrir o modal de contato
        function openContactModal() {
            document.getElementById("resumoModal").style.display = "block";
        }

        // Função para fechar o modal de contato
        function fecharModal() {
            document.getElementById("resumoModal").style.display = "none";
        }
        // Função para pagar com Pix            
        function pagarPix() {
            alert("Pagamento realizado com sucesso!");
            fecharModal(); // Fecha o modal após o pagamento
        }
    </script>


    <script src="<?= BASE_URL?>public/assets/script/agendamento1.js"></script>
    <script src="<?= BASE_URL?>public/assets/script/agendamento2.js"></script>
    <script src="<?= BASE_URL?>public/assets/script/agendamento3.js"></script>
    <script src="<?= BASE_URL?>public/assets/script/modalcortes.js?v=1"></script>
    <script src="<?= BASE_URL?>public/assets/script/modalcombo.js?v=1"></script>
    <script src="<?= BASE_URL?>public/assets/script/modalbarba.js"></script>
    <script src="<?= BASE_URL?>public/assets/script/modalsobrancelha.js"></script>  
    <script src="<?= BASE_URL?>public/assets/script/contact.js"></script>
    
</body>


</html>
