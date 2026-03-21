<?php  

  include_once('../config/url.php');
  require_once '../models/agenda/perfil.php';
  //require_once '../adm/services/servicos.php';
//   setlocale(LC_TIME, 'pt_BR.UTF-8');
  
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/perfil.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/historico.css">
</head>
<body>
  
</head>
<body>
    <!-- Header -->
<header>
    <img src="<?= BASE_URL?>img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
    <button class="menu-toggle" aria-label="Toggle menu">&#9776;</button> <!-- Botão para alternar o menu -->
    <nav class="menu-principal">
        <a href="<?= BASE_URL?>/public/index.php">Início</a>
        <a href="<?= BASE_URL?>/public/agendamento.php">Agenda</a>
        <a href="<?= BASE_URL?>/public/planos.php">Planos</a>
        <a href="#" class="selected">Perfil</a> <!-- Classe "selected" aplicada ao Perfil -->
        <a href="#">Contato</a>
    </nav>
</header>
<main>
    <button class="button-1" onclick="window.location.href='<?= BASE_URL?>/public/perfil.php' " >Voltar</button>
    <h1>Olá, Cliente</h1>
    <div class="separator"></div>
         <?php if(isset($_SESSION['feedback'])): ?>
            <script>
                alert("<?= addslashes($_SESSION['feedback']) ?>");
            </script>
            <?php unset($_SESSION['feedback']); ?>
        <?php endif; ?>

    
    
        <?php if (empty($agendamentos)) : ?>
            <p>Você não possui agendamentos.</p>
        <?php else: ?>

            <?php foreach ($agendamentos as $agendamento): 
                $servico = nomeServico($agendamento['idservico']);
                $barbeiro = nomeBarbeiro($agendamento['idbarbeiro']); 
                
                $dataHora = new DateTime($agendamento['data']);
                $dataFormatada = $dataHora->format('d/m');
                $horaFormatada = (new DateTime($agendamento['horario']))->format('H:i');
            ?>

            <div class="appointment-card" style = "margin: 20px; display:inline-block; ">
                <h2>MEUS AGENDAMENTOS</h2>
                <p>
                    <strong><?= htmlspecialchars($servico['nome_servico'] ?? 'Serviço não encontrado') ?></strong><br>
                    Barbeiro: <?= htmlspecialchars($barbeiro) ?><br>
                    <?= $dataFormatada ?><br>
                    <?= $horaFormatada ?><br>
                    <span class="price">R$ <?= number_format($servico['preco'] ?? 0, 2, ',', '.') ?></span>
                </p>
                <h2>LOCALIZAÇÃO</h2>
                <p><?= htmlspecialchars($dados[0]['local'] ?? 'Não informado')?><br><?= htmlspecialchars($dados[0]['cidade']) ?></p>
                
                <form method="POST" action="<?= BASE_URL?>/models/agenda/perfil.php">
                    <input type="hidden" name="idservico" value="<?= $agendamento['idservico'] ?>">
                    <input type="hidden" name="idbarbeiro" value="<?= $agendamento['idbarbeiro'] ?>">
                    <input type="hidden" name="data" value="<?= $agendamento['data'] ?>">
                    <input type="hidden" name="horario" value="<?= $agendamento['horario'] ?>">
                    <input type="hidden" name="idagendamento" value="<?= $agendamento['idagendamento'] ?>">
                    <button class="cancel-button" type="submit" name="cancelar">CANCELAR</button>
                </form>
               
                
            </div>
            

            <?php endforeach; ?>

        <?php endif; ?>



    
     <!-- <div class="appointment-card">
         
         <p><strong>Corte Degradê</strong><br>
         28 de Setembro<br>
         09:00-09:30<br>
         <span class="price">R$ 30,00</span></p>

        

        <button class="cancel-button">CANCELAR</button>
     </div>
    -->
</main>
 <script src="<?= BASE_URL?>/public/assets/script/menu.js"></script>

 
</body>
</html>
