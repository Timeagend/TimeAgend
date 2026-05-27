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

    <!-- ── STATUS CHANGE STYLES (added) ── -->
    <style>
        /* ── Badge cores por status — especificidade igual ao style.css ── */
        #content main .table-data .order table tr td .status.pendente {
            background: var(--orange);
        }
        #content main .table-data .order table tr td .status.confirmado {
            background: var(--blue)  ;
        }
        #content main .table-data .order table tr td .status.cancelado {
            background: var(--red)   ;
        }

        /* ── Wrapper e dropdown ── */
        .status-wrapper {
            position: relative;
            display: inline-block;
        }

        .status {
            cursor: pointer;
            user-select: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .03em;
            transition: opacity .15s, background .2s, color .2s;
        }
        .status:hover { opacity: .8; }

        .status::after {
            content: "▾";
            font-size: .7em;
            opacity: .7;
        }

        .status-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--white, #fff);
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0,0,0,.12);
            z-index: 999;
            min-width: 150px;
            overflow: hidden;
            animation: fadeDropdown .15s ease;
        }

        @keyframes fadeDropdown {
            from { opacity: 0; transform: translateX(-50%) translateY(-4px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0);    }
        }

        .status-dropdown.open { display: block; }

        .status-option {
            padding: 8px 14px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background .12s;
        }
        .status-option:hover { background: #f5f5f5; }

        .status-option::before {
            content: "";
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .status-option[data-value="pendente"]::before   { background: var(--orange); }
        .status-option[data-value="confirmado"]::before { background: var(--blue);   }
        .status-option[data-value="cancelado"]::before  { background: var(--red);    }

        .status-saving { opacity: .45; pointer-events: none; }
    </style>
    <!-- ── /STATUS CHANGE STYLES ── -->
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
            <a href="<?=BASE_URL?>user/login.php" class="logout">
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

                                <!-- ── STATUS CELL (added wrapper + data-id) ── -->
                                <td>
                                    <div class="status-wrapper">
                                        <span class="status <?= htmlspecialchars($a['status']) ?>"
                                              data-id="<?= htmlspecialchars($a['id'] ?? $a['idagendamento'] ?? '') ?>"
                                              data-status="<?= htmlspecialchars($a['status']) ?>">
                                            <?= htmlspecialchars($a['status']); ?>
                                        </span>
                                        <div class="status-dropdown">
                                            <div class="status-option" data-value="pendente">Pendente</div>
                                            <div class="status-option" data-value="confirmado">Confirmado</div>
                                            <div class="status-option" data-value="cancelado">Cancelado</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- ── /STATUS CELL ── -->

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
                                <div class="icon" onclick="abrirModalServico<?= $s['idservico']; ?>()">
                                    <i class="fas fa-edit"></i>
                                </div>

                                <span class="adm-chip-badge">
                                    <?= htmlspecialchars($s['tipo']); ?>
                                </span>

                                <strong><?= htmlspecialchars($s['nome_servico']); ?></strong>

                                <span class="adm-chip-meta">
                                    <i class='bx bx-time-five'></i> <?= htmlspecialchars($s['duracao']); ?>
                                </span>

                                <span class="adm-chip-price">
                                    R$ <?= number_format($s['preco'], 2, ',', '.'); ?>
                                </span>
                            </div>
                        </div>

                        <!-- MODAL DO SERVIÇO -->
                        <div id="modal-servico-<?= $s['idservico']; ?>" class="modal-overlay">
                            <div class="modal-box">
                                <div class="modal-header">
                                    <h3>Editar Serviço</h3>
                                    <button type="button" onclick="fecharModalServico<?= $s['idservico']; ?>()">
                                        &times;
                                    </button>
                                </div>

                                <!-- FORM EDITAR -->
                                <form action="<?= BASE_URL ?>adm/services/editService.php" method="POST">
                                    <input type="hidden" name="service-id" value="<?= $s['idservico']; ?>">

                                    <label>Nome do serviço</label>
                                    <input type="text"
                                        name="service-name"
                                        value="<?= htmlspecialchars($s['nome_servico']); ?>"
                                        required>

                                    <label>Tipo</label>
                                    <input type="text"
                                        name="service-tipo"
                                        value="<?= htmlspecialchars($s['tipo']); ?>"
                                        required>

                                    <label>Duração</label>
                                    <input type="text"
                                        name="service-duracao"
                                        value="<?= htmlspecialchars($s['duracao']); ?>"
                                        required>

                                    <label>Valor</label>
                                    <input type="number"
                                        name="service-valor"
                                        value="<?= htmlspecialchars($s['preco']); ?>"
                                        step="0.01"
                                        required>

                                    <button type="submit">Atualizar</button>
                                </form>

                                <!-- FORM EXCLUIR -->
                                <form action="<?= BASE_URL ?>adm/services/controlDelete.php"
                                    method="POST"
                                    onsubmit="return confirm('Deseja realmente excluir este serviço?');">

                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $s['idservico']; ?>">

                                    <button type="submit">Excluir Serviço</button>
                                </form>
                                
                                <?php if (isset($_GET['error'])): ?>
                                    <div style="
                                        background: #fdecea;
                                        color: #c0392b;
                                        border: 1px solid #f5c6cb;
                                        padding: 12px 16px;
                                        border-radius: 8px;
                                        margin: 15px 0;
                                        font-weight: 500;
                                    ">
                                        <?= htmlspecialchars(urldecode($_GET['error'])) ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>

                        <script>
                            function abrirModalServico<?= $s['idservico']; ?>() {
                                document.getElementById('modal-servico-<?= $s['idservico']; ?>').classList.add('open');
                            }

                            function fecharModalServico<?= $s['idservico']; ?>() {
                                document.getElementById('modal-servico-<?= $s['idservico']; ?>').classList.remove('open');
                            }
                        </script>

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
                    <div>
                        <h2>Profissionais</h2>
                        <p>Clique no ícone para editar nome ou foto.</p>
                    </div>
                </div>

<<<<<<< HEAD
<div class="barber-cards">
    <?php foreach ($barbeiroList as $barbeiro): ?>
    <div class="barber-card adm-team-card"
         data-id="<?= $barbeiro['idbarbeiro'] ?? '' ?>"
         data-obs="<?= htmlspecialchars($barbeiro['descricao'] ?? '') ?>">
        <div class="adm-team-img-wrap">
            <img src="<?= $barbeiro['foto'] ?>"
                 alt="Foto de <?= htmlspecialchars($barbeiro['nome_barbeiro']) ?>">
            <div class="adm-team-overlay"></div>
            <div class="adm-team-status">Ativo</div>
        </div>
        <div class="edit-icon"><i class="fas fa-edit"></i></div>
        <div class="name">
            <input type="text" value="<?= htmlspecialchars($barbeiro['nome_barbeiro']) ?>" />
        </div>
    </div>
    <?php endforeach; ?>
</div>
=======
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
>>>>>>> 5f27645 (Atualização)
                <div class="adm-form-footer">
                    <!-- ✔ classe original: button-barber -->
                    <button class="button-barber">
                        <i class='bx bx-save'></i> Salvar Alterações
                    </button>
                </div>
                <div id="modal-edit-team" class="modal-overlay">

                    <div class="modal-box">
                        <div class="modal-header">
                            <h3><i class='bx bx-user-circle'></i> Editar Profissional</h3>
                            <button type="button" class="modal-close-btn">&times;</button>
                        </div>

                        <form action="<?= BASE_URL ?>/adm/services/editBarber.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="idbarbeiro" id="edit-barber-id">

                            <label>Nome do profissional</label>
                            <input type="text" name="nome" id="edit-barber-nome" required>

                            <label>Descrição</label>
                            <input type="text" name="descricao" id="edit-barber-obs">

                            <label>Foto de perfil</label>
                            <input type="file" name="foto" id="edit-barber-foto" accept="image/*">

                            <div class="modal-footer">
                                <button type="submit" name="acao" value="excluir" class="modal-btn-cancel">
                                    <i class='bx bx-trash'></i> Excluir
                                </button>

                                <button type="button" class="modal-btn-cancel close-modal">Cancelar</button>

                                <button type="submit" name="acao" value="editar" class="modal-btn-save">
                                    <i class='bx bx-save'></i> Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ✔ classe original: barber-section -->
            <div class="barber-section adm-card">
                <div class="adm-card-header">
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

<!-- ── STATUS CHANGE SCRIPT (added) ── -->
<script src="<?= BASE_URL?>/adm/assets/script/status.js"></script>
<!-- ── /STATUS CHANGE SCRIPT ── -->

</body>
</html>