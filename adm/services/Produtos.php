<?php 

include_once "../config/conection.php";

class Produtos{
    private $con;

    public function __construct($conexao) {
        $this->con = $conexao;
    }

    public function cadastrarProduto($nome, $preco) {
        $stmt = $this->con->prepare("INSERT INTO servico (nome, preco,imagem,categoria,descricao) VALUES (?, ?)");
        $stmt->bind_param("sd", $nome, $preco);
        return $stmt->execute();
    }

    public function listarProdutos() {
        $result = $this->con->query("SELECT * FROM servico");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function excluirProduto($idservico) {
        $stmt = $this->con->prepare("DELETE FROM servico WHERE idservico = ?");
        $stmt->bind_param("i", $idservico);
        return $stmt->execute();
    }
}