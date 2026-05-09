<?php
include_once "../../config/conection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    // 🔥 1. Apaga itens relacionados
    $sql1 = "DELETE FROM itens_pedido WHERE produto_id = ?";
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // 🔥 2. Agora apaga o produto
    $sql2 = "DELETE FROM produtos WHERE id = ?";
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("i", $id);

    if ($stmt2->execute()) {
        echo "ok";
        header("Location: ../product.php?deleted=1");
    } else {
        echo "erro: " . $stmt2->error;
    }
}