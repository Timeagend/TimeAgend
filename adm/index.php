<?php 
  include_once('../adm/services/controlBarber.php');
  include_once('../adm/services/controlService.php');
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
            <a href="#">
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
<!-- SIDEBAR -->

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
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
        <!-- Dashboard -->
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

                    <!-- Modal filtro -->
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

            <!-- Lista de tarefas -->
            <div class="todo">
                <div class="head">
                    <h3>Lista de Tarefas</h3>
                    <i class='bx bx-plus' id="add-task" title="Adicionar Tarefa"></i>
                </div>
                <ul class="todo-list" id="todo-list"></ul>
            </div>
        </div>

        <!-- Meu Site -->
        <div id="meu-site-content" class="content-section" style="display: none;">
            <h1>Meu Site</h1>
			<p class="info">Aqui estão as informações sobre o seu site.</p>

			<form action="<?= BASE_URL ?>/adm/services/localiza.php" method="POST">
				<div class="meios">Meios de contato e endereços:</div>
				<div class="contact-info">
					<div>
						<label>Telefone</label>
						<input type="text" name="telefone" />
					</div>
					<div>
						<label>E-Mail</label>
						<input type="text" name="email" />
					</div>
					<div>
						<label>Cidade</label>
						<input type="text" name="cidade"/>
					</div>
					<div>
						<label>Endereço</label>
						<input type="text" name="local"/>
					</div>
				</div>

				<button type="submit" class="save-button">Salvar</button>
			</form>

            <div class="services-prices">
                <h2>Serviços & preços:</h2>
                <div class="categories">
                    <?php foreach ($servicos as $s): ?>
                    <div class="category" style="display: inline-block; border: 10px 10px;margin: 10px;margin-top: 30px;align-items: center; text-align: center;">
                        <div class="barber-card" style="display: inline-block; width: 160px; height: 160px; border: 40px 20px;
                        padding: 10px auto; text-align: center;" >
                            <div class="edit-icon"><i class="fas fa-edit"></i></div>
                            <strong><?= htmlspecialchars($s['nome_servico']); ?></strong><br>
                            Tipo: <?= htmlspecialchars($s['tipo']); ?><br>
                            Preço: R$ <?= number_format($s['preco'], 2, ',', '.'); ?><br>
                            Duração: <?= htmlspecialchars($s['duracao']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <h2>Inserir novo Serviço</h2>
                <form action="<?= BASE_URL?>/adm/services/controlService.php" method="POST">
                    <div><label>Nome do serviço :</label><input type="text" name="service-name" required></div>
                    <div><label>Tipo de serviço: </label><input type="text" name="service-tipo" required></div>
                    <div><label>Valor do serviço:</label><input type="number" name="service-valor" required></div>
                    <div><label>Duração do serviço:</label><input type="text" name="service-duracao" required></div>
                    <button type="submit" class="save-button-1">Salvar</button>
                </form>
            </div>
        </div>

        <!-- Análise -->
        <div id="analise-content" class="content-section" style="display: none;">
            <h1>Análise</h1>
            <p>Relatórios e gráficos sobre o desempenho do site.</p>
        </div>

        <!-- Equipe -->
        <div id="equipe-content" class="content-section" style="display: none;">
            <p>Lista de membros e informações sobre a equipe.</p>

            <div class="barber-section">
                <h2>Adicione as imagens dos barbeiros junto com seus nomes</h2>
                <div class="barber-cards">
                    <?php foreach ($barbeiroList as $barbeiro): ?>
                    <div class="barber-card">
                        <img src="<?= $barbeiro['foto'] ?>" height="150" width="150" alt="Foto de <?= htmlspecialchars($barbeiro['nome_barbeiro']) ?>">
                        <div class="edit-icon"><i class="fas fa-edit"></i></div>
                        <div class="name">Nome: <input type="text" value="<?= htmlspecialchars($barbeiro['nome_barbeiro']) ?>" /></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="button-barber">Salvar</button>
            </div>

            <div class="barber-section">
                <div class="services-prices">
                    <form action="<?= BASE_URL?>/adm/services/controlBarber.php" method="POST" enctype="multipart/form-data">
                        <div><label>Nome Funcionário:</label><input type="text" name="nome"></div>
                        <div><label>Email:</label><input type="text" name="email"></div>
                        <div><label>Senha:</label><input type="password" name="senha"></div>
                        <div><label>Descrição:</label><input type="textarea" name="obs"></div>
                        <div><label>Foto Perfil:</label><input type="file" name="foto"></div>
                        <div><button type="submit" class="button-barber">Salvar</button></div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</section>

<script src="<?= BASE_URL?>/adm/assets/script/script.js"></script>
<script src="<?= BASE_URL?>/adm/assets/script/menuhub.js"></script>
<script src="<?= BASE_URL?>/adm/assets/script/filtro.js"></script>
</body>
</html>
