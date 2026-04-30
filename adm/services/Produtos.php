<?php 


class Produtos {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }
    
    // ✅ LISTAR PRODUTOS
    public function listarProdutos() {
        $result = $this->con->query("SELECT 
                produtos.*, 
                categorias.nome AS categoria_nome
            FROM produtos
            JOIN categorias 
            ON produtos.categoria_id = categorias.id");
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
    public function contarProdutos() {
        $sql = "SELECT COUNT(*) as total FROM produtos";
        $result = $this->con->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    public function mediaValorProdutos(){
        $sql = "SELECT AVG(preco) as media FROM produtos";
        $result = $this->con->query($sql);
        $row = $result->fetch_assoc();
        return $row['media'];
    }

    public function adicionarProduto($nome, $preco, $categoria_id, $imagem, $descricao) {

            $sql = "INSERT INTO produtos (nome, preco, categoria_id, imagem, descricao) 
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->con->prepare($sql);

            $stmt->bind_param("sdiss", 
                $nome,          
                $preco,         
                $categoria_id,  
                $imagem,        
                $descricao      
            );

            return $stmt->execute();
        }
        public function atualizarProduto($id, $nome, $preco, $categoria_id, $imagem, $descricao) {

        $sql = "UPDATE produtos 
                SET nome = ?, preco = ?, categoria_id = ?, imagem = ?, descricao = ?
                WHERE id = ?";

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param("sdissi",
            $nome,          
            $preco,         
            $categoria_id,  
            $imagem,        
            $descricao,     
            $id             
        );

        return $stmt->execute();
    }
}

