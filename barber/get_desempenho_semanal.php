<?php
session_start();
require_once __DIR__ . "/../config/conection.php";

// Verifica barbeiro logado
$idBarbeiro = $_SESSION['idbarbeiro'] ?? null;
if (!$idBarbeiro) {
    die(json_encode(['error' => 'Barbeiro não logado']));
}

// Define início e fim da semana atual
$inicioSemana = date('Y-m-d', strtotime('monday this week'));
$fimSemana    = date('Y-m-d', strtotime('sunday this week'));

// Prepara consulta
$sql = "SELECT DAYOFWEEK(data) AS dia_semana, COUNT(*) AS total
        FROM agendamento
        WHERE idbarbeiro = ? AND DATE(data) BETWEEN ? AND ?
        GROUP BY dia_semana";

$stmt = $con->prepare($sql);
$stmt->bind_param("iss", $idBarbeiro, $inicioSemana, $fimSemana);
$stmt->execute();
$result = $stmt->get_result();

// Inicializa array dos dias da semana (domingo=1, sábado=7)
$dias = array_fill(1,7,0);

// Preenche valores do resultado
while ($linha = $result->fetch_assoc()) {
    $dias[$linha['dia_semana']] = $linha['total'];
}

// Retorna JSON
echo json_encode($dias);
?>
