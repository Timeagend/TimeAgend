<?php  

  include_once('../config/url.php');
  require_once '../models/agenda/perfil.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/perfil.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/historico.css">
</head>
<body>

<header>
    <img src="<?= BASE_URL?>img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
    <button class="menu-toggle" aria-label="Toggle menu">&#9776;</button>
    <nav class="menu-principal">
        <a href="<?= BASE_URL?>/public/index.php">Início</a>
        <a href="<?= BASE_URL?>/public/agendamento.php">Agenda</a>
        <a href="<?= BASE_URL?>/public/planos.php">Planos</a>
        <a href="#" class="selected">Perfil</a>
        <a href="#">Contato</a>
    </nav>
</header>

<main>
    <button class="button-1" onclick="window.location.href='<?= BASE_URL?>/public/perfil.php'">Voltar</button>
    <h1>Olá, Cliente</h1>
    <div class="separator"></div>

    <!-- Modal de Feedback -->
    <?php if (isset($_SESSION['feedback'])): ?>
    <div class="overlay active" id="feedbackOverlay">
        <div class="modal">
            <div class="modal-icon success">&#10003;</div>
            <p><?= htmlspecialchars($_SESSION['feedback']) ?></p>
            <button class="modal-btn" onclick="document.getElementById('feedbackOverlay').classList.remove('active')">OK</button>
        </div>
    </div>
    <?php unset($_SESSION['feedback']); ?>
    <?php endif; ?>

    <!-- Modal de Confirmação -->
    <div class="overlay" id="confirmOverlay">
        <div class="modal">
            <div class="modal-icon warning">&#9888;</div>
            <h3>Cancelar agendamento?</h3>
            <p>Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <button class="modal-btn secondary" id="btnNao">Não, voltar</button>
                <button class="modal-btn danger"    id="btnSim">Sim, cancelar</button>
            </div>
        </div>
    </div>

    <!-- Filtro -->
    <div class="filter-bar">
        <button class="filter-btn active" data-filter="todos">Todos</button>
        <button class="filter-btn" data-filter="proximos">Próximos</button>
        <button class="filter-btn" data-filter="passados">Passados</button>
    </div>

    <!-- Cards -->
    <?php if (empty($agendamentos)): ?>
        <div class="empty-state">
            <span class="empty-icon">&#128197;</span>
            <p>Você não possui agendamentos.</p>
            <a href="<?= BASE_URL ?>/public/agendamento.php" class="button-new">Agendar agora</a>
        </div>
    <?php else: ?>

        <div class="cards-wrapper" id="cardsWrapper">
            <?php foreach ($agendamentos as $agendamento):
                $servico       = nomeServico($agendamento['idservico']);
                $barbeiro      = nomeBarbeiro($agendamento['idbarbeiro']);
                $dataFormatada = (new DateTime($agendamento['data']))->format('d/m');
                $horaFormatada = (new DateTime($agendamento['horario']))->format('H:i');

                $dataISO   = (new DateTime($agendamento['data']))->format('Y-m-d');
                $hojeISO   = (new DateTime())->format('Y-m-d');
                $isPassado = $dataISO < $hojeISO ? 'passado' : 'proximo';
            ?>
            <div class="appointment-card" data-periodo="<?= $isPassado ?>">

                <?php if ($isPassado === 'passado'): ?>
                    <span class="badge badge-passado">Passado</span>
                <?php else: ?>
                    <span class="badge badge-proximo">Próximo</span>
                <?php endif; ?>

                <div>
                    <h2>MEUS AGENDAMENTOS</h2>
                    <p>
                        <strong><?= htmlspecialchars($servico['nome_servico'] ?? 'Serviço não encontrado') ?></strong><br>
                        Barbeiro: <?= htmlspecialchars($barbeiro) ?><br>
                        <?= $dataFormatada ?> &mdash; <?= $horaFormatada ?><br>
                        <span class="price">R$ <?= number_format($servico['preco'] ?? 0, 2, ',', '.') ?></span>
                    </p>
                    <h2>LOCALIZAÇÃO</h2>
                    <p>
                        <?= htmlspecialchars($dados[0]['local']  ?? 'Não informado') ?><br>
                        <?= htmlspecialchars($dados[0]['cidade'] ?? '') ?>
                    </p>
                </div>

                <?php if ($isPassado !== 'passado'): ?>
                <form class="cancel-form" method="POST" action="<?= BASE_URL ?>/models/agenda/perfil.php">
                    <input type="hidden" name="cancelar"      value="1">
                    <input type="hidden" name="idservico"     value="<?= $agendamento['idservico'] ?>">
                    <input type="hidden" name="idbarbeiro"    value="<?= $agendamento['idbarbeiro'] ?>">
                    <input type="hidden" name="data"          value="<?= $agendamento['data'] ?>">
                    <input type="hidden" name="horario"       value="<?= $agendamento['horario'] ?>">
                    <input type="hidden" name="idagendamento" value="<?= $agendamento['idagendamento'] ?>">
                    <button class="cancel-button" type="button">CANCELAR</button>
                </form>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<script src="<?= BASE_URL?>/public/assets/script/menu.js"></script>
<script src="<?= BASE_URL?>/public/assets/script/historico.js"></script>

</body>
</html>
