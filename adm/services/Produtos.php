<?php 

include_once "../config/conection.php";

class Produtos {
    private $con;

    public function __construct($conexao) {
        $this->con = $conexao;
    }

    // ✅ LISTAR PRODUTOS
    public function listarProdutos() {
        $result = $this->con->query("SELECT * FROM produtos");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ✅ BUSCAR PRODUTO PELO NOME (usado no pedido)
    public function buscarPorNome($nome) {
        $stmt = $this->con->prepare("SELECT id FROM produtos WHERE nome = ?");
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ EXCLUIR PRODUTO
    public function excluirProduto($id) {
        $stmt = $this->con->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}