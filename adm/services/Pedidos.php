<?php


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
    public function listarPedidos() {
        $sql = "SELECT p.status, p.id, u.nome_user AS cliente, p.data_pedido
                FROM pedidos p
                JOIN user u ON p.cliente_id = u.iduser
                ORDER BY p.data_pedido DESC";

        $result = $this->con->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function totalVendas(){
        $sql = "SELECT COUNT(*) AS total FROM pedidos
                WHERE status = 'concluido'";
        $result = $this->con->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}