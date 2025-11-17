<?php
include_once('../../../../config/conection.php');
header('Content-Type: application/json');

class Barbeiros{
    public $con;
    public function __construct($con){
        $this->con = $con;
    }
    public static function getById($con, $idbarbeiro){
        $sql = "SELECT idbarbeiro, nome_barbeiro, descricao, foto FROM barbeiro WHERE idbarbeiro = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $idbarbeiro);
        $stmt->execute();
        $result = $stmt->get_result();
        $barbeiro = $result->fetch_assoc();
        return $barbeiro;
    }
    public static function getAll($con){
        $sql = "SELECT idbarbeiro, nome_barbeiro, descricao, foto FROM barbeiro";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $barbeiros = $result->fetch_all(MYSQLI_ASSOC);
        return $barbeiros;
    }
    public static function searchByName($con, $nome_barbeiro){
        $sql = "SELECT idbarbeiro, nome_barbeiro, descricao, foto FROM barbeiro WHERE nome_barbeiro LIKE ?";
        $likeNome = "%" . $nome_barbeiro . "%";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $likeNome);
        $stmt->execute();
        $result = $stmt->get_result();
        $barbeiros = $result->fetch_all(MYSQLI_ASSOC);
        return $barbeiros;
    }

    // public static function
}

// --- API Endpoint ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // listar todos
    if (!isset($_GET['id']) && !isset($_GET['nome'])) {
        $barbeiros = Barbeiros::getAll($con);
        echo json_encode($barbeiros);
        exit;
    }

    // buscar por ID
    if (isset($_GET['id'])) {
        $barbeiro = Barbeiros::getById($con, $_GET['id']);
        echo json_encode($barbeiro);
        exit;
    }

    // buscar por nome
    if (isset($_GET['nome'])) {
        $barbeiros = Barbeiros::searchByName($con, $_GET['nome']);
        echo json_encode($barbeiros);
        exit;
    }
}
