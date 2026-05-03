
<?php

require_once "../config/conection.php";
include_once "services/Produtos.php";
include_once "services/Pedidos.php";

$controlProduct = new Produtos($con);

$listaprodutos = $controlProduct->listarProdutos();
$totalProdutos = $controlProduct->contarProdutos();
$mediaProdutos = $controlProduct->mediaValorProdutos();

$controlPedidos = new Pedidos($con);
$totalVendas = $controlPedidos->totalVendas();


if (isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $con->query("INSERT INTO produtos (nome, preco) VALUES ('$nome', '$preco')");
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $con->query("UPDATE produtos SET nome='$nome', preco='$preco' WHERE id=$id");
}

$result = $con->query("SELECT * FROM produtos");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel de Produtos — TimeAgend</title>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
a{text-decoration:none}
li{list-style:none}

:root{
  --poppins:'Poppins',sans-serif;
  --lato:'Lato',sans-serif;
  --light:#F9F9F9;
  --blue:#3C91E6;
  --light-blue:#CFE8FF;
  --grey:#eee;
  --dark-grey:#AAAAAA;
  --dark:#342E37;
  --red:#DB504A;
  --yellow:#FFCE26;
  --light-yellow:#FFF2C6;
  --orange:#FD7238;
  --light-orange:#FFE0D3;
  --green:#27AE60;
  --light-green:#E8F8EF;
}

html{overflow-x:hidden}
body{background:var(--grey);overflow-x:hidden;font-family:var(--poppins)}
body.dark{--light:#0C0C1E;--grey:#060714;--dark:#FBFBFB}

/* ── SIDEBAR ── */
#sidebar{
  position:fixed;top:0;left:0;
  width:280px;height:100%;
  background:var(--light);
  z-index:2000;font-family:var(--lato);
  transition:.3s ease;overflow-x:hidden;scrollbar-width:none;
}
#sidebar.hide{width:60px}
#sidebar .brand{
  font-size:24px;font-weight:700;height:56px;
  display:flex;align-items:center;color:var(--blue);
  position:sticky;top:0;left:0;background:var(--light);
  z-index:500;padding-bottom:20px;box-sizing:content-box;
}
#sidebar .brand .bx{min-width:60px;display:flex;justify-content:center}
#sidebar .side-menu{width:100%;margin-top:48px}
#sidebar .side-menu li{
  height:48px;background:transparent;
  margin-left:6px;border-radius:48px 0 0 48px;padding:4px;
}
#sidebar .side-menu li.active{background:var(--grey);position:relative}
#sidebar .side-menu li.active::before{
  content:'';position:absolute;width:40px;height:40px;
  border-radius:50%;top:-40px;right:0;
  box-shadow:20px 20px 0 var(--grey);z-index:-1;
}
#sidebar .side-menu li.active::after{
  content:'';position:absolute;width:40px;height:40px;
  border-radius:50%;bottom:-40px;right:0;
  box-shadow:20px -20px 0 var(--grey);z-index:-1;
}
#sidebar .side-menu li a{
  width:100%;height:100%;background:var(--light);
  display:flex;align-items:center;border-radius:48px;
  font-size:16px;color:var(--dark);white-space:nowrap;overflow-x:hidden;
}
#sidebar .side-menu.top li.active a{color:var(--blue)}
#sidebar.hide .side-menu li a{width:calc(48px - 8px);transition:width .3s ease}
#sidebar .side-menu li a.logout{color:var(--red)}
#sidebar .side-menu.top li a:hover{color:var(--blue)}
#sidebar .side-menu li a .bx{min-width:calc(60px - 20px);display:flex;justify-content:center}

/* ── CONTENT ── */
#content{
  position:relative;
  width:calc(100% - 280px);
  left:280px;transition:.3s ease;
}
#sidebar.hide ~ #content{width:calc(100% - 60px);left:60px}

/* ── TOPBAR ── */
#content nav{
  height:72px;background:var(--light);
  padding:0 32px;display:flex;align-items:center;
  justify-content:space-between;
  font-family:var(--lato);
  position:sticky;top:0;left:0;z-index:1000;
  border-bottom:1px solid var(--grey);
}
#content nav::before{display:none}
.topbar-left{display:flex;flex-direction:column;justify-content:center}
.topbar-title{
  font-family:var(--poppins);
  font-size:26px;font-weight:600;
  color:var(--dark);line-height:1.2;
}
.topbar-title span{color:var(--blue)}
.topbar-breadcrumb{
  display:flex;align-items:center;gap:8px;
  margin-top:3px;
}
.topbar-breadcrumb a{
  font-size:12px;color:var(--dark-grey);font-family:var(--lato);
}
.topbar-breadcrumb a.active{color:var(--blue)}
.topbar-breadcrumb i{font-size:12px;color:var(--dark-grey)}
.topbar-actions{display:flex;align-items:center;gap:12px}

/* ── MAIN ── */
#content main{
  width:100%;padding:36px 24px;font-family:var(--poppins);
  max-height:calc(100vh - 72px);overflow-y:auto;
}
#content main .head-title{display:none}

/* ── BOX INFO ── */
#content main .box-info{
  display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:24px;margin-top:36px;
}
#content main .box-info li{
  padding:24px;background:var(--light);border-radius:20px;
  display:flex;align-items:center;gap:24px;
}
#content main .box-info li .bx{
  width:80px;height:80px;border-radius:10px;
  font-size:36px;display:flex;justify-content:center;align-items:center;
}
#content main .box-info li:nth-child(1) .bx{background:var(--light-blue);color:var(--blue)}
#content main .box-info li:nth-child(2) .bx{background:var(--light-yellow);color:var(--yellow)}
#content main .box-info li:nth-child(3) .bx{background:var(--light-orange);color:var(--orange)}
#content main .box-info li .text h3{font-size:24px;font-weight:600;color:var(--dark)}
#content main .box-info li .text p{color:var(--dark-grey);font-size:14px;margin-top:2px}

/* ── TABLE DATA ── */
#content main .table-data{
  display:flex;flex-wrap:wrap;gap:24px;
  margin-top:24px;width:100%;color:var(--dark);
}
#content main .table-data > div{
  border-radius:20px;background:var(--light);
  padding:24px;overflow-x:auto;
}
#content main .table-data .head{
  display:flex;align-items:center;gap:16px;margin-bottom:24px;
}
#content main .table-data .head h3{margin-right:auto;font-size:24px;font-weight:600}
#content main .table-data .head .bx{cursor:pointer;color:var(--dark-grey);font-size:20px}

#content main .table-data .order{flex-grow:1;flex-basis:500px}
#content main .table-data .order table{width:100%;border-collapse:collapse}
#content main .table-data .order table th{
  padding-bottom:12px;font-size:13px;text-align:left;
  border-bottom:1px solid var(--grey);color:var(--dark-grey);
}
#content main .table-data .order table td{padding:16px 0;font-size:14px}
#content main .table-data .order table tr td:first-child{
  display:flex;align-items:center;gap:12px;padding-left:6px;
}
#content main .table-data .order table td img{
  width:40px;height:40px;border-radius:8px;object-fit:cover;
}
#content main .table-data .order table tbody tr:hover{background:var(--grey)}
#content main .table-data .order table tr td .status{
  font-size:10px;padding:6px 14px;color:var(--light);
  border-radius:20px;font-weight:700;
}
#content main .table-data .order table tr td .status.completed{background:var(--blue)}
#content main .table-data .order table tr td .status.process{background:var(--yellow);color:var(--dark)}
#content main .table-data .order table tr td .status.pending{background:var(--orange)}

/* ── TABS ── */
.tab-section{display:none}
.tab-section.active{display:block}

/* ── FORM CARD (add/edit) ── */
.form-card{
  background:var(--light);border-radius:20px;
  padding:28px;max-width:680px;
}
.form-card h3{
  font-size:20px;font-weight:600;color:var(--dark);
  margin-bottom:22px;padding-bottom:16px;
  border-bottom:1px solid var(--grey);
  display:flex;align-items:center;gap:10px;
}
.form-card h3 .bx{color:var(--blue)}

.form-row{margin-bottom:18px}
.form-row label{
  display:block;font-size:12px;font-weight:700;
  text-transform:uppercase;letter-spacing:.06em;
  color:var(--dark-grey);margin-bottom:7px;
}
.form-row input,
.form-row textarea,
.form-row select{
  width:100%;padding:10px 14px;
  border:1.5px solid var(--grey);border-radius:10px;
  background:#fff;color:var(--dark);
  font-family:var(--lato);font-size:14px;
  outline:none;transition:border-color .2s,box-shadow .2s;
  appearance:none;
}
body.dark .form-row input,
body.dark .form-row textarea,
body.dark .form-row select{background:var(--light)}
.form-row input:focus,
.form-row textarea:focus,
.form-row select:focus{
  border-color:var(--blue);
  box-shadow:0 0 0 3px rgba(60,145,230,.13);
}
.form-row textarea{height:88px;resize:vertical}
.form-row input::placeholder,
.form-row textarea::placeholder{color:var(--dark-grey)}

.form-2col{display:grid;grid-template-columns:1fr 1fr;gap:16px}

.form-footer{
  display:flex;gap:12px;justify-content:flex-end;
  margin-top:24px;padding-top:20px;
  border-top:1px solid var(--grey);
}

/* file drop */
.file-drop{
  position:relative;border:2px dashed var(--grey);
  border-radius:10px;padding:28px;text-align:center;
  cursor:pointer;transition:border-color .2s,background .2s;
}
.file-drop:hover{border-color:var(--blue);background:var(--light-blue)}
.file-drop input[type=file]{
  position:absolute;inset:0;opacity:0;
  cursor:pointer;width:100%;height:100%;
}
.file-drop .bx{font-size:2rem;color:var(--dark-grey);display:block;margin-bottom:6px}
.file-drop p{font-size:13px;color:var(--dark-grey)}
.file-drop p span{color:var(--blue);font-weight:700}

/* action buttons */
.btn-blue{
  height:36px;padding:0 20px;border-radius:36px;
  background:var(--blue);color:var(--light);
  display:inline-flex;align-items:center;gap:8px;
  font-weight:600;font-family:var(--lato);font-size:13px;
  border:none;cursor:pointer;transition:background .2s;
}
.btn-blue:hover{background:#2e7fd4}
.btn-ghost{
  height:36px;padding:0 20px;border-radius:36px;
  background:transparent;border:1.5px solid var(--grey);
  color:var(--dark-grey);display:inline-flex;align-items:center;gap:8px;
  font-weight:600;font-family:var(--lato);font-size:13px;cursor:pointer;
  transition:border-color .2s,color .2s;
}
.btn-ghost:hover{border-color:var(--dark-grey);color:var(--dark)}

.btn-sm-edit{
  font-size:11px;padding:5px 12px;border-radius:20px;
  background:var(--light-blue);color:var(--blue);
  border:none;cursor:pointer;font-family:var(--lato);
  font-weight:700;transition:background .15s;
  display:inline-flex;align-items:center;gap:4px;
}
.btn-sm-edit:hover{background:#b5d8fc}
.btn-sm-del{
  font-size:11px;padding:5px 12px;border-radius:20px;
  background:#fde8e8;color:var(--red);
  border:none;cursor:pointer;font-family:var(--lato);
  font-weight:700;transition:background .15s;
  display:inline-flex;align-items:center;gap:4px;
}
.btn-sm-del:hover{background:#fac8c8}

/* status badges on table */
.status{
  font-size:10px;padding:5px 14px;border-radius:20px;
  font-weight:700;display:inline-block;
}
.status.active{background:var(--light-green);color:var(--green)}
.status.inactive{background:var(--light-orange);color:var(--orange)}

/* prod id pill */
.prod-id{
  font-family:var(--lato);font-size:12px;color:var(--dark-grey);
  background:var(--grey);padding:3px 8px;border-radius:20px;
}

/* ── MODAL CONFIRM ── */
.modal-overlay{
  position:fixed;inset:0;z-index:3000;
  background:rgba(0,0,0,.55);
  display:none;align-items:center;justify-content:center;
  backdrop-filter:blur(3px);
}
.modal-overlay.open{display:flex}
.modal-box{
  background:var(--light);border-radius:20px;
  padding:32px 28px;max-width:380px;width:90%;text-align:center;
  box-shadow:0 12px 40px rgba(0,0,0,.18);
}
.modal-icon{
  width:60px;height:60px;border-radius:50%;
  background:#fde8e8;display:flex;align-items:center;
  justify-content:center;margin:0 auto 18px;
  font-size:26px;color:var(--red);
}
.modal-box h3{font-size:18px;font-weight:600;color:var(--dark);margin-bottom:10px}
.modal-box p{font-size:14px;color:var(--dark-grey);line-height:1.6;margin-bottom:24px}
.modal-actions{display:flex;gap:12px;justify-content:center}

/* ── TOAST ── */
.toast{
  position:fixed;bottom:24px;right:24px;z-index:4000;
  background:var(--light);border-radius:12px;
  padding:14px 20px;display:flex;align-items:center;gap:12px;
  font-family:var(--lato);font-size:14px;color:var(--dark);
  box-shadow:0 8px 30px rgba(0,0,0,.15);
  transform:translateY(70px);opacity:0;
  transition:all .3s cubic-bezier(.16,1,.3,1);pointer-events:none;
}
.toast.show{transform:translateY(0);opacity:1}
.toast .bx{font-size:20px;color:var(--green)}

/* search in head */
.head-search{
  display:flex;align-items:center;height:34px;
  background:var(--grey);border-radius:34px;overflow:hidden;
}
.head-search input{
  padding:0 14px;background:transparent;border:none;
  outline:none;font-family:var(--lato);font-size:13px;
  color:var(--dark);width:180px;
}
.head-search button{
  width:34px;height:100%;background:var(--blue);color:#fff;
  border:none;cursor:pointer;font-size:16px;
  display:flex;align-items:center;justify-content:center;
}

/* thumb in table */
.prod-thumb{
  width:40px;height:40px;border-radius:8px;
  object-fit:cover;flex-shrink:0;
}
.prod-name-cell{display:flex;flex-direction:column}
.prod-name-cell strong{font-size:14px;font-weight:600;color:var(--dark)}
.prod-name-cell span{font-size:12px;color:var(--dark-grey)}

/* price in table */
.price-td{font-weight:700;color:var(--dark);font-size:14px}

@media(max-width:768px){
  #sidebar{width:200px}
  #content{width:calc(100% - 60px);left:200px}
  .form-2col{grid-template-columns:1fr}
}
@media(max-width:576px){
  #content nav form .form-input input{display:none}
  #content main .box-info{grid-template-columns:1fr}
}
</style>

</head>
<body>


<!-- Modal -->
<div class="modal-overlay" id="del-modal">
  <div class="modal-box">
    <div class="modal-icon"><i class='bx bx-trash'></i></div>
    <h3>Excluir produto</h3>
    <p>Tem certeza? Esta ação é irreversível e o produto será removido permanentemente do catálogo.</p>
    <div class="modal-actions">
      <button class="btn-ghost" onclick="closeModal()">Cancelar</button>
      <button class="btn-blue" style="background:var(--red)" id="confirm-del">Excluir</button>
    </div>
  </div>

</div>

<!-- Toast -->
<div class="toast" id="toast">
  <i class='bx bx-check-circle'></i>
  <span id="toast-msg">Operação realizada com sucesso</span>
</div>

<!-- Sidebar -->
<section id="sidebar">
  <a href="#" class="brand">
    <i class='bx bx-scissors bx'></i>
    <span>TimeAgend</span>
  </a>
  <ul class="side-menu top">
    <li onclick="setActive(this)" class="active">
      <a href="#" onclick="openTab('listar')">
        <i class='bx bx-package bx'></i>
        <span>Produtos</span>
      </a>
    </li>
    <li onclick="setActive(this)">
      <a href="#" onclick="openTab('add')">
        <i class='bx bx-plus-circle bx'></i>
        <span>Novo Produto</span>
      </a>
    </li>
    <li>
      <a href="index.php">
        <i class='bx bx-home bx'></i>
        <span>Início</span>
      </a>
    </li>
    <li>
      <a href="pedidos.php">
        <i class='bx bx-home bx'></i>
        <span>Pedidos</span>
      </a>
    </li>
  </ul>
  <!-- <ul class="side-menu">
    <li>
      <a href="login.php" class="logout">
        <i class='bx bx-log-out-circle bx'></i>
        <span>Sair</span>
      </a>
    </li>
  </ul> -->
</section>

<!-- Content -->
<section id="content">

  <!-- Topbar -->
  <nav>
    <div class="topbar-left">
      <div class="topbar-title" id="topbar-title">Lista de <span id="topbar-span">Produtos</span></div>
      <div class="topbar-breadcrumb">
        <a href="index.php" class="active">Dashboard</a>
        <i class='bx bx-chevron-right'></i>
        <a href="#" id="bread-cur">Produtos</a>
      </div>
    </div>
    <div class="topbar-actions">
      <button class="btn-ghost" onclick="window.location.href='index.php'">
        <i class='bx bx-arrow-back'></i> Voltar
      </button>
      <button class="btn-blue" onclick="openTab('add');setActiveByIndex(1)">
        <i class='bx bx-plus'></i> Adicionar
      </button>
    </div>
  </nav>

  <!-- Main -->
  <main>


    <!-- ── TAB: LISTAR ── -->
    <div id="listar" class="tab-section active">

      <!-- Box info -->
      <ul class="box-info">
        <li>
          <i class='bx bx-package bx'></i>
          <div class="text">
            <h3><?= $totalProdutos;?></h3>
            <p>Produtos cadastrados</p>
          </div>
        </li>
        <li>
          <i class='bx bx-dollar-circle bx'></i>
          <div class="text">
            <h3><?php echo "R$ " . number_format($mediaProdutos, 2, ',', '.'); ?></h3>
            <p>Ticket médio</p>
          </div>
        </li>
        <li>
          <i class='bx bx-cart bx'></i>
          <div class="text">
            <h3><?php echo "R$ ",$totalVendas; ?></h3>
            <p>Valor do portfólio</p>
          </div>
        </li>
      </ul>

      <!-- Table -->
      <div class="table-data">
        <div class="order">
          <div class="head">
            <h3>Catálogo de Produtos</h3>
            <div class="head-search">
              <input type="text" placeholder="Filtrar..." id="search-input">
              <button><i class='bx bx-search'></i></button>
            </div>
            <i class='bx bx-dots-vertical-rounded'></i>
          </div>
          <table>
            <thead>
              <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody id="prod-tbody">
              <?php foreach ($listaprodutos as $produto): ?>
              <tr data-name="Pomada Matte Premium">
                
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1621607512214-68297480165e?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong><?= $produto['nome']; ?></strong>
                    <span>#001</span>
                  </div>
                </td>
                <td><?= $produto['categoria_nome'] ?></td>
                <td><span class="price-td"><?=  $produto['preco']; ?></span></td>
                <td><span class="status active">Ativo</span></td>
                <td> 
                  <button class="btn-sm-edit" 
                    onclick="editarItem(
                      '<?= $produto['nome'] ?>',
                      '<?= $produto['descricao'] ?>',
                      <?= $produto['preco'] ?>,
                      <?= $produto['categoria_id'] ?>,
                      <?= $produto['id'] ?>
                    )">
                    <i class='bx bx-edit-alt'></i> Editar
                    </button>                                      <!--  -->
                   <button class="btn-sm-del" onclick="confirmarExcluir(1)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
                
              </tr>
              <?php endforeach; ?>
              <!-- <tr data-name="Pomada Matte Premium">
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1621607512214-68297480165e?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong>Pomada Matte Premium</strong>
                    <span>#001</span>
                  </div>
                </td>
                <td>Cabelo</td>
                <td><span class="price-td">R$ 89,90</span></td>
                <td><span class="status active">Ativo</span></td>
                <td>
                  <button class="btn-sm-edit" onclick="editarItem('Pomada Matte Premium','Fixação forte com acabamento natural.','89.90','Cabelo')"><i class='bx bx-edit-alt'></i> Editar</button>
                  <button class="btn-sm-del" onclick="confirmarExcluir(1)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
              </tr>
              <tr data-name="Óleo para Barba">
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong>Óleo para Barba</strong>
                    <span>#002</span>
                  </div>
                </td>
                <td>Barba</td>
                <td><span class="price-td">R$ 69,90</span></td>
                <td><span class="status active">Ativo</span></td>
                <td>
                  <button class="btn-sm-edit" onclick="editarItem('Óleo para Barba','Hidrata e amacia os fios.','69.90','Barba')"><i class='bx bx-edit-alt'></i> Editar</button>
                  <button class="btn-sm-del" onclick="confirmarExcluir(2)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
              </tr>
              <tr data-name="Balm Modelador">
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong>Balm Modelador</strong>
                    <span>#003</span>
                  </div>
                </td>
                <td>Barba</td>
                <td><span class="price-td">R$ 59,90</span></td>
                <td><span class="status inactive">Promoção</span></td>
                <td>
                  <button class="btn-sm-edit" onclick="editarItem('Balm Modelador','Modela e nutre a barba.','59.90','Barba')"><i class='bx bx-edit-alt'></i> Editar</button>
                  <button class="btn-sm-del" onclick="confirmarExcluir(3)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
              </tr>
              <tr data-name="Shampoo Antiqueda">
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong>Shampoo Antiqueda</strong>
                    <span>#004</span>
                  </div>
                </td>
                <td>Cabelo</td>
                <td><span class="price-td">R$ 54,90</span></td>
                <td><span class="status active">Ativo</span></td>
                <td>
                  <button class="btn-sm-edit" onclick="editarItem('Shampoo Antiqueda','Fortalece os fios.','54.90','Cabelo')"><i class='bx bx-edit-alt'></i> Editar</button>
                  <button class="btn-sm-del" onclick="confirmarExcluir(4)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
              </tr>
              <tr data-name="Hidratante Facial">
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong>Hidratante Facial</strong>
                    <span>#005</span>
                  </div>
                </td>
                <td>Skincare</td>
                <td><span class="price-td">R$ 79,90</span></td>
                <td><span class="status active">Ativo</span></td>
                <td>
                  <button class="btn-sm-edit" onclick="editarItem('Hidratante Facial','Hidratação profunda.','79.90','Skincare')"><i class='bx bx-edit-alt'></i> Editar</button>
                  <button class="btn-sm-del" onclick="confirmarExcluir(5)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
              </tr>
              <tr data-name="Kit Pentes Premium">
                <td>
                  <img class="prod-thumb" src="https://images.unsplash.com/photo-1621607512022-6aecc4fed814?w=120&q=70" alt="">
                  <div class="prod-name-cell">
                    <strong>Kit Pentes Premium</strong>
                    <span>#006</span>
                  </div>
                </td>
                <td>Acessórios</td>
                <td><span class="price-td">R$ 129,90</span></td>
                <td><span class="status active">Ativo</span></td>
                <td>
                  <button class="btn-sm-edit" onclick="editarItem('Kit Pentes Premium','3 pentes de acetato.','129.90','Acessórios')"><i class='bx bx-edit-alt'></i> Editar</button>
                  <button class="btn-sm-del" onclick="confirmarExcluir(6)"><i class='bx bx-trash'></i> Excluir</button>
                </td>
              </tr> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

</div>

    <!-- ── TAB: ADICIONAR ── -->
    <div id="add" class="tab-section" style="margin-top:36px">
      <div class="form-card">
        <h3><i class='bx bx-plus-circle'></i> Novo Produto</h3>
        <form method="POST" enctype="multipart/form-data" action="services/newProduto.php">
          <div class="form-row">
            <label>Nome do produto</label>
            <input type="text" name="nome" placeholder="Ex: Pomada Matte Premium" required>
          </div>
          <div class="form-row">
            <label>Descrição</label>
            <textarea name="descricao" placeholder="Descreva o produto..."></textarea>
          </div>
          <div class="form-2col">
            <div class="form-row">
              <label>Preço (R$)</label>
              <input type="number" step="0.01" name="preco" placeholder="0,00" required>
            </div>
            <div class="form-row">
              <label>Categoria</label>
              <select name="categoria_id">
                <option value="" disabled selected>Selecionar...</option>
                <?php foreach($listaprodutos as $produtos): ?>
                  <option value="<?= $produtos['categoria_id'] ;?>"><?= $produtos['categoria_nome']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <label>Imagem do produto</label>
            <div class="file-drop" id="drop-add">
              <input type="file" name="imagem" accept="image/*" onchange="previewFile(this,'drop-add','prev-add')">
              <i class='bx bx-image-add'></i>
              <p>Arraste ou <span>clique para selecionar</span></p>
              <img id="prev-add" style="display:none;max-height:80px;border-radius:8px;margin:10px auto 0;display:none">
            </div>
          </div>
          <div class="form-footer">
            <button type="button" class="btn-ghost" onclick="openTab('listar');setActiveByIndex(0)">Cancelar</button>
            <button type="submit" class="btn-blue" name="add"><i class='bx bx-save'></i> Salvar Produto</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ── TAB: EDITAR ── -->
    <div id="editar" class="tab-section" style="margin-top:36px">
      <div class="form-card">
        <h3><i class='bx bx-edit'></i> Editar Produto</h3>
        <form method="POST" enctype="multipart/form-data" action="services/updateProduto.php">
          <input type="hidden" name="id" id="edit_id">
          <div class="form-row">
            <label>Nome do produto</label>
            <input type="text" name="nome" id="edit_nome" required>
          </div>
          <div class="form-row">
            <label>Descrição</label>
            <textarea name="descricao" id="edit_descricao"></textarea>
          </div>
          <div class="form-2col">
            <div class="form-row">
              <label>Preço (R$)</label>
              <input type="number" step="0.01" name="preco" id="edit_preco" required>
            </div>
            <div class="form-row">
              <label>Categoria</label>
              <select name="categoria" id="edit_cat">
                <?php foreach($listaprodutos as $produtos): ?>
                  <option value="<?= $produtos['categoria_id'] ;?>"><?= $produtos['categoria_nome']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <label>Nova imagem (opcional)</label>
            <div class="file-drop" id="drop-edit">
              <input type="file" name="imagem" accept="image/*" onchange="previewFile(this,'drop-edit','prev-edit')">
              <i class='bx bx-image-add'></i>
              <p>Arraste ou <span>clique para selecionar</span></p>
              <img id="prev-edit" style="display:none;max-height:80px;border-radius:8px;margin:10px auto 0">
            </div>
          </div>
          <div class="form-footer">
            <button type="button" class="btn-ghost" onclick="openTab('listar');setActiveByIndex(0)">Cancelar</button>
            <button type="submit" class="btn-blue" name="update"><i class='bx bx-save'></i> Atualizar Produto</button>
          </div>
        </form>
      </div>
    </div>

  </main>
</section>

<script>
/* dark mode toggle */
const switchMode=document.getElementById('switch-mode');
if(switchMode) switchMode.addEventListener('change',()=>document.body.classList.toggle('dark',switchMode.checked));

/* sidebar active */
function setActive(el){
  document.querySelectorAll('#sidebar .side-menu li').forEach(l=>l.classList.remove('active'));
  el.classList.add('active');
}
function setActiveByIndex(i){
  const items=document.querySelectorAll('#sidebar .side-menu.top li');
  items.forEach(l=>l.classList.remove('active'));
  if(items[i]) items[i].classList.add('active');
}

const titles={listar:'Produtos',add:'Novo Produto',editar:'Editar Produto'};
const titleSpans={listar:'Produtos',add:'Novo Produto',editar:'Editar Produto'};
const breadTexts={listar:'Produtos',add:'Adicionar',editar:'Editar'};
function openTab(tab){
  document.querySelectorAll('.tab-section').forEach(s=>s.classList.remove('active'));
  document.getElementById(tab).classList.add('active');
  const plain={listar:'Lista de ',add:'Novo ',editar:'Editar '};
  const colored={listar:'Produtos',add:'Produto',editar:'Produto'};
  document.getElementById('topbar-title').innerHTML=plain[tab]+'<span id="topbar-span" style="color:var(--blue)">'+colored[tab]+'</span>';
  document.getElementById('bread-cur').textContent=breadTexts[tab]||tab;
}

/* search */
document.getElementById('search-input').addEventListener('input',function(){
  const q=this.value.toLowerCase();
  document.querySelectorAll('#prod-tbody tr').forEach(tr=>{
    tr.style.display=tr.dataset.name.toLowerCase().includes(q)?'':'none';
  });
});

/* delete modal */
let pendingId=null;
function confirmarExcluir(id){pendingId=id;document.getElementById('del-modal').classList.add('open')}
function closeModal(){document.getElementById('del-modal').classList.remove('open');pendingId=null}
document.getElementById('del-modal').addEventListener('click',e=>{if(e.target===document.getElementById('del-modal'))closeModal()});
document.getElementById('confirm-del').addEventListener('click',()=>{
  if(pendingId){
    fetch(`controlProduct.php?id=${pendingId}`,{method:'GET'})
      .catch(()=>{})
      .finally(()=>{closeModal();showToast('Produto excluído com sucesso');});
  }
});

/* edit */
function editarItem(nome,desc,preco,cat){
  document.getElementById('edit_nome').value=nome;
  document.getElementById('edit_descricao').value=desc;
  document.getElementById('edit_preco').value=preco;
  const sel=document.getElementById('edit_cat');
  for(let o of sel.options) if(o.text===cat) o.selected=true;
  openTab('editar');
  setActiveByIndex(1);

  

}

/* form submit */
function handleSubmit(e,type){
  e.preventDefault();
  showToast(type==='add'?'Produto adicionado com sucesso!':'Produto atualizado com sucesso!');
  setTimeout(()=>{openTab('listar');setActiveByIndex(0)},700);
}

/* file preview */
function previewFile(input,dropId,prevId){
  const file=input.files[0];if(!file)return;
  const prev=document.getElementById(prevId);
  const reader=new FileReader();
  reader.onload=e=>{
    prev.src=e.target.result;
    prev.style.display='block';
    const drop=document.getElementById(dropId);
    drop.querySelector('p').style.display='none';
    drop.querySelector('i').style.display='none';
  };
  reader.readAsDataURL(file);
}

/* toast */
function showToast(msg,ms=3000){
  const t=document.getElementById('toast');
  document.getElementById('toast-msg').textContent=msg;
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),ms);
}
</script>
</body>
</html>