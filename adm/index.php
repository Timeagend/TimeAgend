<?php 
     $numUser = 0;
     $numAtendimentos = 0;
     $totalLucro = 0;
     $agendamentos = [];
     $barbeiroList = [];
     $servicos = [];
     
  include_once('../adm/services/controlBarber.php');
  include_once('../adm/services/controlService.php');
  require_once ('../config/url.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= BASE_URL?>/adm/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL?>/adm/assets/css/modal.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>AdminHub</title>
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">AdminHub</span>
    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="#" data-target="dashboard-content">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="meu-site-content">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Meu Site</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="analise-content">
                <i class='bx bxs-doughnut-chart'></i>
                <span class="text">Análise</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="equipe-content">
                <i class='bx bxs-group'></i>
                <span class="text">Equipe</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu">
        <li>
            <a href="<?= BASE_URL ?>adm/product.php">
               <i class="bx bx-package bx-remove-padding"></i> 
                <span class="text">Produtos</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>adm/configuracoes.php">
                <i class='bx bxs-cog'></i>
                <span class="text">Configurações</span>
            </a>
        </li>
        <li>
            <a href="<?=BASE_URL?>/user/login.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Sair</span>
            </a>
        </li>
        
    </ul>
</section>

<!-- CONTEÚDO PRINCIPAL -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Categories</a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Pesquisar...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a>
        <a href="#" class="profile">
            <img src="<?= BASE_URL?>/adm/img/people.png">
        </a>
    </nav>

    <!-- MAIN -->
    <main>

        <!-- ══════════════ DASHBOARD ══════════════ -->
        <div id="dashboard-content" class="content-section">
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Home</a></li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Download PDF</span>
                </a>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-calendar-check'></i>
                    <span class="text">
                        <h3><?= $numAtendimentos ?></h3>
                        <p>Atendimentos</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3><?= $numUser ?></h3>
                        <p>Clientes</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-dollar-circle'></i>
                    <span class="text">
                        <h3>R$ <?= $totalLucro ?></h3>
                        <p>Saldo total</p>
                    </span>
                </li>
            </ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Clientes Agendados</h3>
                        <i class='bx bx-search' id="search-icon"></i>
                        <i class='bx bx-filter' id="filter-icon"></i>
                    </div>

                    <div id="filter-modal" class="filter-modal">
                        <div class="modal-content">
                            <span class="close" id="close-modal">&times;</span>
                            <h2>Filtrar Clientes</h2>
                            <form id="filter-form">
                                <label for="filter-date">Data:</label>
                                <input type="date" id="filter-date" name="date">
                                <label for="filter-service">Serviço:</label>
                                <input type="text" id="filter-service" name="service" placeholder="Digite o serviço">
                                <button type="button" id="apply-filter">Aplicar Filtro</button>
                            </form>
                        </div>
                    </div>

                    <table id="client-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Serviço</th>
                                <th>Horário</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="client-table-body">
                            <?php foreach ($agendamentos as $a): ?>
                            <tr>
                                <td>
                                    <img src="<?= BASE_URL?>/adm/img/people.png" alt="Foto do cliente">
                                    <p><?= htmlspecialchars($a['nome_cliente']); ?></p>
                                </td>
                                <td><?= htmlspecialchars($a['data']);?></td>
                                <td><?= htmlspecialchars($a['nome_servico']);?></td>
                                <td><?= htmlspecialchars($a['horario']);?></td>
                                <td>
                                    <span class="status <?= htmlspecialchars($a['status']) ?>">
                                        <?= htmlspecialchars($a['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="todo">
                <div class="head">
                    <h3>Lista de Tarefas</h3>
                    <i class='bx bx-plus' id="add-task" title="Adicionar Tarefa"></i>
                </div>
                <ul class="todo-list" id="todo-list"></ul>
            </div>
        </div>

        <!-- ══════════════ MEU SITE ══════════════ -->
        <div id="meu-site-content" class="content-section" style="display: none;">

            <div class="adm-section-head">
                <div>
                    <h1>Meu Site</h1>
                    <p class="info">Gerencie as informações exibidas no seu site.</p>
                </div>
                <span class="adm-badge">Publicado</span>
            </div>

            <!-- Card Contato -->
            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-icon"><i class='bx bxs-map'></i></div>
                    <div>
                        <h2>Meios de Contato &amp; Endereço</h2>
                        <p>Estas informações aparecem na página de contato do seu site.</p>
                    </div>
                </div>

                <!-- ✔ action original: localiza.php | names originais: telefone, email, cidade, local -->
                <form action="<?= BASE_URL ?>/adm/services/localiza.php" method="POST">
                    <div class="contact-info">
                        <div>
                            <label>Telefone</label>
                            <div class="adm-input-wrap">
                                <i class='bx bx-phone'></i>
                                <input type="text" name="telefone" placeholder="(62) 99999-9999" />
                            </div>
                        </div>
                        <div>
                            <label>E-Mail</label>
                            <div class="adm-input-wrap">
                                <i class='bx bx-envelope'></i>
                                <input type="text" name="email" placeholder="contato@barbearia.com" />
                            </div>
                        </div>
                        <div>
                            <label>Cidade</label>
                            <div class="adm-input-wrap">
                                <i class='bx bx-buildings'></i>
                                <input type="text" name="cidade" placeholder="Goiânia, GO" />
                            </div>
                        </div>
                        <div>
                            <label>Endereço</label>
                            <div class="adm-input-wrap">
                                <i class='bx bx-location-plus'></i>
                                <input type="text" name="local" placeholder="Rua Exemplo, 100" />
                            </div>
                        </div>
                    </div>
                    <div class="adm-form-footer">
                        <!-- ✔ classe original: save-button -->
                        <button type="submit" class="save-button">
                            <i class='bx bx-save'></i> Salvar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Serviços -->
            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-icon" style="background:var(--light-yellow);color:var(--yellow);">
                        <i class="bx bx-package"></i>
                    </div>
                    <div>
                        <h2>Serviços &amp; Preços</h2>
                        <p>Clique no lápis para editar um serviço existente.</p>
                    </div>
                </div>

                <!-- ✔ classes originais: services-prices / categories / category / barber-card / edit-icon -->
                <div class="services-prices">
                    <div class="categories">
                        <?php foreach ($servicos as $s): ?>
                        <div class="category">
                            <div class="barber-card">
                                <div class="edit-icon"><i class="fas fa-edit"></i></div>
                                <span class="adm-chip-badge"><?= htmlspecialchars($s['tipo']); ?></span>
                                <strong><?= htmlspecialchars($s['nome_servico']); ?></strong>
                                <span class="adm-chip-meta">
                                    <i class='bx bx-time-five'></i> <?= htmlspecialchars($s['duracao']); ?>
                                </span>
                                <span class="adm-chip-price">R$ <?= number_format($s['preco'], 2, ',', '.'); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="adm-divider"></div>

                    <h2>Inserir novo Serviço</h2>
                    <!-- ✔ action original: saveService.php | names originais: service-name, service-tipo, service-valor, service-duracao -->
                    <form action="<?= BASE_URL?>/adm/services/controlService.php" method="POST">
                        <div class="adm-form-grid">
                            <div>
                                <label>Nome do serviço</label>
                                <input type="text" name="service-name" placeholder="Ex: Hidratação" required>
                            </div>
                            <div>
                                <label>Tipo de serviço</label>
                                <input type="text" name="service-tipo" placeholder="Ex: Cabelo" required>
                            </div>
                            <div>
                                <label>Valor do serviço</label>
                                <input type="number" name="service-valor" placeholder="0,00" step="0.01" required>
                            </div>
                            <div>
                                <label>Duração do serviço</label>
                                <input type="text" name="service-duracao" placeholder="Ex: 30 min" required>
                            </div>
                        </div>
                        <div class="adm-form-footer">
                            <!-- ✔ classe original: save-button-1 -->
                            <button type="submit" class="save-button-1">
                                <i class='bx bx-plus'></i> Adicionar Serviço
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ══════════════ ANÁLISE ══════════════ -->
        <div id="analise-content" class="content-section" style="display: none;">
            <h1>Análise</h1>
            <p>Relatórios e gráficos sobre o desempenho do site.</p>
            <div style="max-width:800px;margin:20px auto;"><canvas id="visitasChart"></canvas></div>
            <div style="max-width:800px;margin:20px auto;"><canvas id="servicosChart"></canvas></div>
            <div style="max-width:800px;margin:20px auto;"><canvas id="crescimentoChart"></canvas></div>
        </div>

        <!-- ══════════════ EQUIPE ══════════════ -->
        <div id="equipe-content" class="content-section" style="display: none;">

            <div class="adm-section-head">
                <div>
                    <h1>Equipe</h1>
                    <p class="info">Gerencie os profissionais exibidos no seu site.</p>
                </div>
                <span class="adm-badge adm-badge--green"><?= count($barbeiroList) ?> ativos</span>
            </div>

            <!-- ✔ classe original: barber-section -->
            <div class="barber-section adm-card">
                <div class="adm-card-header">
                    <!-- <div class="adm-card-icon" style="background:var(--light-yellow);color:var(--yellow);">
                        <i class='bx bxs-group'></i>
                    </div> -->
                    <div>
                        <h2>Profissionais</h2>
                        <p>Clique no ícone para editar nome ou foto.</p>
                    </div>
                </div>

                    <!-- ✔ classe original: barber-cards -->
<!-- ✔ classe original: barber-cards -->
<div class="barber-cards">
    <?php foreach ($barbeiroList as $barbeiro): ?>
    <!-- ✔ classe original: barber-card -->
    <div class="barber-card adm-team-card"
         data-id="<?= $barbeiro['idbarbeiro'] ?? '' ?>"
         data-obs="<?= htmlspecialchars($barbeiro['descricao'] ?? '') ?>">
        <div class="adm-team-img-wrap">
            <img src="<?= $barbeiro['foto'] ?>"
                 alt="Foto de <?= htmlspecialchars($barbeiro['nome_barbeiro']) ?>">
            <div class="adm-team-overlay"></div>
            <div class="adm-team-status">Ativo</div>
        </div>
        <!-- ✔ classe original: edit-icon -->
        <div class="edit-icon"><i class="fas fa-edit"></i></div>
        <!-- ✔ classe original: name -->
        <div class="name">
            <input type="text" value="<?= htmlspecialchars($barbeiro['nome_barbeiro']) ?>" />
        </div>
    </div>
    <?php endforeach; ?>
</div>
                <div class="adm-form-footer">
                    <!-- ✔ classe original: button-barber -->
                    <button class="button-barber">
                        <i class='bx bx-save'></i> Salvar Alterações
                    </button>
                </div>
            </div>

            <!-- ✔ classe original: barber-section -->
            <div class="barber-section adm-card">
                <div class="adm-card-header">
                    <!-- <div class="adm-card-icon" style="background:#E8F8EF;color:#27AE60;">
                        <i class='bx bx-user-plus'></i>
                    </div> -->
                    <div>
                        <h2>Adicionar Profissional</h2>
                        <p>Preencha os dados e salve para exibir no site.</p>
                    </div>
                </div>

                <!-- ✔ classe original: services-prices -->
                <div class="services-prices">
                    <!-- ✔ action original: controlBarber.php | names originais: nome, email, senha, obs, foto -->
                    <form action="<?= BASE_URL?>/adm/services/controlBarber.php" method="POST" enctype="multipart/form-data">
                        <div class="adm-form-grid">
                            <div>
                                <label>Nome Funcionário</label>
                                <input type="text" name="nome" placeholder="Ex: João Pereira">
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" placeholder="joao@barbearia.com">
                            </div>
                            <div>
                                <label>Senha</label>
                                <input type="password" name="senha" placeholder="••••••••">
                            </div>
                            <div>
                                <label>Descrição</label>
                                <input type="text" name="obs" placeholder="Especialidades, experiência...">
                            </div>
                            <div class="adm-file-wrap">
                                <label>Foto Perfil</label>
                                <div class="adm-file-drop">
                                    <input type="file" name="foto" accept="image/*">
                                    <i class='bx bx-image-add'></i>
                                    <p>Clique ou arraste uma imagem aqui</p>
                                </div>
                            </div>
                        </div>
                        <div class="adm-form-footer">
                            <!-- ✔ classe original: button-barber -->
                            <button type="submit" class="button-barber">
                                <i class='bx bx-user-plus'></i> Adicionar à Equipe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /equipe-content -->

    </main>
</section>

<script src="<?= BASE_URL?>/adm/assets/script/script.js"></script>
<script src="<?= BASE_URL?>/adm/assets/script/filtro.js"></script>
<script src="<?= BASE_URL?>/adm/assets/script/menuhub.js"></script>
<script src="<?= BASE_URL?>/adm/assets/script/modal.js"></script>
<script src="<?= BASE_URL?>/adm/assets/script/analise.js"></script>



</body>
</html>