<?php

include_once "../../config/conection.php";
include_once "Produtos.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];
    $nome = trim($_POST['nome']);
    $preco = floatval($_POST['preco']);
    $categoria_id = (int) $_POST['categoria'];
    $descricao = trim($_POST['descricao']);

    $imagem = null;
    
    // Se enviou nova imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

        $imagem_nome = uniqid() . "_" . basename($_FILES['imagem']['name']);
        $upload_dir = '../uploads/';
        $upload_path = $upload_dir . $imagem_nome;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_path);

        $imagem = 'uploads/' . $imagem_nome;
    }

    $produtoModel = new Produtos($con);

    // 🔥 Se NÃO mandou imagem → não atualiza imagem
    if ($imagem) {
        $ok = $produtoModel->atualizarProduto($id, $nome, $preco, $categoria_id, $imagem, $descricao);
    } else {
        // mantém imagem antiga
        $sql = "UPDATE produtos 
                SET nome = ?, preco = ?, categoria_id = ?, descricao = ?
                WHERE id = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sdisi", $nome, $preco, $categoria_id, $descricao, $id);
        $ok = $stmt->execute();
    }

    if ($ok) {
        header("Location: ../product.php?updated=1");
    } else {
        header("Location: ../product.php?error=1");
    }
}