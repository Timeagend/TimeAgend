<?php
require_once '../../config/conection.php';

class Pedidos {

    private $con;

    public function __construct($con) {
       
        $this->con = $con;
    }

    public function criarPedido($cliente_id) {

        $sql = "INSERT INTO pedidos (cliente_id, data_pedido)
                VALUES (?, NOW())";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();

        return $this->con->insert_id;
    }

    public function adicionarItem($pedido_id, $produto_id, $quantidade) {

        $sql = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade)
                VALUES (?, ?, ?)";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iii", $pedido_id, $produto_id, $quantidade);

        return $stmt->execute();
    }
}