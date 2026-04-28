<?php
include_once "../../config/url.php";
require_once './Pedidos.php';
require_once './Produtos.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cartData = $_POST['cart_data'] ?? null;
    $cliente_id = $_POST['cliente_id'] ?? 1;

    if (!$cartData) {
        echo json_encode(["erro" => "Carrinho não enviado"]);
        exit;
    }

    $produtos = json_decode($cartData, true);

    if (!$produtos) {
        echo json_encode(["erro" => "Erro ao converter dados"]);
        exit;
    }

    $pedidoModel = new Pedidos($con);
    $produtoModel = new Produtos($con); // 🔥 AGORA EXISTE

    // 🔥 1. CRIA O PEDIDO
    $pedido_id = $pedidoModel->criarPedido($cliente_id);

    // 🔥 2. INSERE OS ITENS
    foreach ($produtos as $item) {

    $produto_id = (int) filter_var($item['id'], FILTER_SANITIZE_NUMBER_INT);

    if ($produto_id <= 0) {
        continue;
    }

    $quantidade = $item['quantity'] ?? 1;

    $pedidoModel->adicionarItem($pedido_id, $produto_id, $quantidade);
}
    echo json_encode([
        "sucesso" => true,
        "pedido_id" => $pedido_id
    ]);

    // 🔥 REDIRECIONAMENTO CORRETO
    header('Location: ' . BASE_URL . 'public/Produtos/produtos.php');
    exit();
}
?>