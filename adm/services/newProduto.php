<?php 

include_once "../../config/conection.php";
include_once "Produtos.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $preco = floatval($_POST['preco']);
    $categoria_id = (int) $_POST['categoria_id'];
    $descricao = trim($_POST['descricao']);

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_nome = uniqid() . "_" . basename($_FILES['imagem']['name']);
        $upload_dir = '../uploads/';
        $upload_path = $upload_dir . $imagem_nome;

        // cria pasta se não existir
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($imagem_tmp, $upload_path)) {

            $produtoModel = new Produtos($con);

            if ($produtoModel->adicionarProduto(
                $nome,
                $preco,
                $categoria_id,
                'uploads/' . $imagem_nome,
                $descricao
            )) {
                header("Location: ../product.php?success=1");
                exit();
            } else {
                header("Location: ../product.php?error=1");
                exit();
            }

        } else {
            header("Location: ../product.php?error=2");
            exit();
        }

    } else {
        header("Location: ../product.php?error=3");
        exit();
    }
}