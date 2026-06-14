<?php  
  include_once('../config/url.php');
  require_once '../models/agenda/perfil.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico — TimeAgend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/logo.css">
        <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/perfil.css">

    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/historico.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/public/assets/css/modal-historico.css">



 
</head>
<body>

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
        <a href="#">Contato</a>
    </nav>
</header>

<main>

    <!-- Modal de Feedback -->
    <?php if (isset($_SESSION['feedback'])): ?>
    <div class="overlay active" id="feedbackOverlay">
        <div class="modal">
            <span class="modal-icon success">&#10003;</span>
            <h3>Pronto!</h3>
            <p><?= htmlspecialchars($_SESSION['feedback']) ?></p>
            <div class="modal-actions">
                <button class="modal-btn" onclick="document.getElementById('feedbackOverlay').classList.remove('active')">OK</button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['feedback']); ?>
    <?php endif; ?>

    <!-- Modal de Confirmação -->
    <div class="overlay" id="confirmOverlay">
        <div class="modal">
            <span class="modal-icon warning">&#9888;</span>
            <h3>Cancelar agendamento?</h3>
            <p>Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <button class="modal-btn secondary" id="btnNao">Não, voltar</button>
                <button class="modal-btn danger"    id="btnSim">Sim, cancelar</button>
            </div>
        </div>
    </div>

    <!-- Hero -->
    <div class="hero">
        <div class="hero-text">
            <p class="eyebrow"></p>
            <h1 eyebrow>Seu histórico</h1>
            <p>Veja todos os seus agendamentos por aqui.</p>
        </div>
        <a href="<?= BASE_URL?>/public/perfil.php" class="button-1">
            <i class="fa-solid fa-arrow-left" style="font-size:.8rem"></i> Voltar
        </a>
    </div>

    <div class="separator"></div>

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
                $dataISO       = (new DateTime($agendamento['data']))->format('Y-m-d');
                $hojeISO       = (new DateTime())->format('Y-m-d');
                $isPassado     = $dataISO < $hojeISO ? 'passado' : 'proximo';
            ?>
            <div class="appointment-card <?= $isPassado ?>" data-periodo="<?= $isPassado ?>">

                <span class="badge <?= $isPassado === 'passado' ? 'badge-passado' : 'badge-proximo' ?>">
                    <?= $isPassado === 'passado' ? 'PASSADO' : 'PRÓXIMO' ?>
                </span>

                <p class="card-section-label">Meus Agendamentos</p>

                <h2 class="card-service-name">
                    <?= strtoupper(htmlspecialchars($servico['nome_servico'] ?? 'Serviço não encontrado')) ?>
                </h2>

                <div class="card-meta">
                    <div class="card-meta-row">
                        <i class="fa-solid fa-scissors"></i>
                        <span>Barbeiro: <?= htmlspecialchars($barbeiro) ?></span>
                    </div>
                    <div class="card-meta-row">
                        <i class="fa-regular fa-calendar"></i>
                        <span><?= $dataFormatada ?> &mdash; <?= $horaFormatada ?></span>
                    </div>
                </div>

                <p class="card-price">R$ <?= number_format($servico['preco'] ?? 0, 2, ',', '.') ?></p>

                <div class="card-divider"></div>

                <div class="card-location">
                    <p class="card-location-label">
                        <i class="fa-solid fa-location-dot"></i> Localização
                    </p>
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