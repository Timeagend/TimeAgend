<?php
include_once "../config/conection.php";
include_once "services/controlProduct.php";
include_once "services/Produtos.php";
$controlProduct = new Produtos($con);

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
<title>Painel de Produtos</title>
<style>
body { font-family: Arial; background: #0f172a; color: #fff; margin: 0; }
header { background: #020617; padding: 15px; text-align: center; font-size: 22px; }
.container { padding: 20px; }
.tabs button { padding: 10px 20px; background: #1e293b; color: #fff; border: none; border-radius: 5px; margin-right: 10px; cursor: pointer; }
.tabs button:hover { background: #334155; }
.card { background: #1e293b; padding: 20px; border-radius: 10px; }
input, textarea { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
textarea { height: 80px; }
button.submit { background: #22c55e; padding: 10px; border: none; border-radius: 5px; color: white; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 10px; }
.table tr { border-bottom: 1px solid #334155; }
img.prod { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
.tab-content { display: none; }
.active { display: block; }
</style>
</head>
<body>

<header>Painel da Barbearia - Produtos</header>

<div class="container">

<div class="tabs">
    <button onclick="openTab('listar')">📦 Produtos</button>
    <button onclick="openTab('add')">➕ Adicionar</button>
    <button onclick="redirect()">Voltar</button>
</div>

<div id="listar" class="tab-content active">
    <div class="card">
        <h3>Lista de Produtos</h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ação</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?php if($row['imagem']): ?>
                        <img class="prod" src="uploads/<?= $row['imagem'] ?>">
                    <?php endif; ?>
                </td>
                <td><?= $row['nome'] ?></td>
                <td>R$ <?= number_format($row['preco'],2,',','.') ?></td>
                <td>
                    <button onclick="editar(<?= $row['id'] ?>, '<?= $row['nome'] ?>', '<?= $row['descricao'] ?>', <?= $row['preco'] ?>)">Editar</button>
                    <button onclick="excluir(<?= $row['id'] ?>)">Excluir</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<div id="add" class="tab-content">
    <div class="card">
        <h3>Novo Produto</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nome" placeholder="Nome" required>
            <textarea name="descricao" placeholder="Descrição"></textarea>
            <input type="number" step="0.01" name="preco" placeholder="Preço" required>
            <input type="file" name="imagem">
            <button class="submit" name="add">Salvar</button>
        </form>
    </div>
</div>

<div id="editar" class="tab-content">
    <div class="card">
        <h3>Editar Produto</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="nome" id="edit_nome">
            <textarea name="descricao" id="edit_descricao"></textarea>
            <input type="number" step="0.01" name="preco" id="edit_preco">
            <input type="file" name="imagem">
            <button class="submit" name="update">Atualizar</button>
        </form>
    </div>
</div>

</div>

<script>
function openTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.getElementById(tab).classList.add('active');
}

function editar(id, nome, descricao, preco) {
    openTab('editar');
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_descricao').value = descricao;
    document.getElementById('edit_preco').value = preco;
}
function redirect() {
    window.location.href = "index.php";
}
function excluir(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        fetch(`controlProduct.php?id=${id}`, { method: 'GET' })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
    }
}
</script>

</body>
</html>
