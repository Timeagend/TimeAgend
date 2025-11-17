<?php
include_once('../../../../config/conection.php');
header('Content-Type: application/json');
class Servico {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }
    
    public function getAll() {
        $sql = "SELECT * FROM servico";
        $result = $this->con->query($sql);

        $servicos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $servicos[] = $row;
            }
        }
        return $servicos;
    }


    public function getById($id) {
        $sql = "SELECT * FROM servico WHERE idservico = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $servico = $result->fetch_assoc();
        $stmt->close();
        return $servico;
    }

    public function buscarPorNome($nome) {
        $sql = "SELECT * FROM servico WHERE nome_servico = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        $servico = $result->fetch_assoc();
        $stmt->close();
        return $servico;
    }

    public function formatarPreco($preco) {
        return "R$ " . number_format($preco, 2, ",", ".");
    }

    public function create($dados) {
        $sql = "INSERT INTO servico (nome_servico, descricao_servico, preco) VALUES (?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssd", $dados['nome_servico'], $dados['descricao_servico'], $dados['preco']);
        $stmt->execute();
        $stmt->close();
        return $this->con->insert_id;
    }

    public function update($id, $dados) {
        $sql = "UPDATE servico SET nome_servico = ?, descricao_servico = ?, preco = ? WHERE idservico = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssdi", $dados['nome_servico'], $dados['descricao_servico'], $dados['preco'], $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function delete($id) {
        $sql = "DELETE FROM servico WHERE idservico = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}

// --- API Endpoint ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $servico = new Servico($con);

    // se for buscar por ID
    if (isset($_GET['id'])) {
        echo json_encode($servico->getById($_GET['id']));
        exit;
    }

    // se quiser filtrar por nome
    if (isset($_GET['nome'])) {
        echo json_encode($servico->buscarPorNome($_GET['nome']));
        exit;
    }

    // se quiser retornar todos agrupados por tipo
    $todos = $servico->getAll();
    $agrupados = [];

    foreach ($todos as $s) {
        $categoria = strtolower($s['tipo']); // exemplo: cortes, barba, sobrancelha
        $agrupados[$categoria][] = [
            'id' => $s['idservico'],
            'nome' => $s['nome_servico'],
            'preco' => (float)$s['preco'],
            'descricao' => $s['descricao'],
            'duracao' => $s['duracao']
        ];
    }

    echo json_encode($agrupados);
    exit;
}

?>
