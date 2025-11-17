<?php
include_once('../../../../config/conection.php');
header('Content-Type: application/json');

class Horario {
    private $con;
    public function __construct($con) { $this->con = $con; }

    // Horários padrão do dia
    public function getHorariosPadrao($inicio = "09:00", $fim = "18:00", $intervaloMin = 60) {
        $horarios = [];
        $horaAtual = strtotime($inicio);
        $horaFim = strtotime($fim);
        while ($horaAtual <= $horaFim) {
            $horarios[] = date("H:i", $horaAtual);
            $horaAtual = strtotime("+$intervaloMin minutes", $horaAtual);
        }
        return $horarios;
    }

    // Horários ocupados no banco
    public function getHorariosOcupados($idbarbeiro, $data) {
        $sql = "SELECT horario FROM agendamento WHERE idbarbeiro = ? AND DATE(data) = ? AND status != 'cancelado'";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("is", $idbarbeiro, $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $ocupados = [];
        while ($row = $result->fetch_assoc()) {
            $ocupados[] = date("H:i", strtotime($row['horario']));
        }
        $stmt->close();
        return $ocupados;
    }


    // Horários disponíveis
    public function getHorariosDisponiveis($idbarbeiro, $data) {
        $todos = $this->getHorariosPadrao();
        $ocupados = $this->getHorariosOcupados($idbarbeiro, $data);
        return array_values(array_diff($todos, $ocupados));
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $horario = new Horario($con);

    $idbarbeiro = isset($_GET['idbarbeiro']) ? intval($_GET['idbarbeiro']) : 0;
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    if($idbarbeiro && $data){
        $disponiveis = $horario->getHorariosDisponiveis($idbarbeiro, $data);
        echo json_encode(['disponiveis' => array_values($disponiveis)]);
        exit;
    }

    echo json_encode(['disponiveis' => []]);
    exit;
}

} catch (Exception $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
