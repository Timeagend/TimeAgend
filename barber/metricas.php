<?php

require_once "../config/conection.php";
require_once 'buscar_agendamentos.php';


// --- Verifica se barbeiro está logado ---
$idBarbeiro = $_SESSION['idbarbeiro'] ?? null;
if (!$idBarbeiro) {
    die("Barbeiro não logado.");
}

// --- Inicializa variáveis ---
$agendamentosHoje = [];
$porcentagens = array_fill(1, 7, 0); // domingo=1 ... sábado=7
$nomeBarbeiro = "";
$fotoBarbeiro = "";

// --- Agendamentos de hoje ---
$sqlHoje = "SELECT COUNT(*) FROM agendamento WHERE idbarbeiro = ? AND DATE(data) = CURDATE()";
$stmt = $con->prepare($sqlHoje);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resHoje = $stmt->get_result()->fetch_row()[0] ?? 0;

// --- Agendamentos de ontem (para percentual) ---
$sqlOntem = "SELECT COUNT(*) FROM agendamento WHERE idbarbeiro = ? AND DATE(data) = CURDATE() - INTERVAL 1 DAY";
$stmt = $con->prepare($sqlOntem);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resOntem = $stmt->get_result()->fetch_row()[0] ?? 0;

$percHoje = ($resOntem > 0) ? (($resHoje - $resOntem) / $resOntem) * 100 : 100;

// --- Agendamentos da semana ---
$sqlSemana = "SELECT COUNT(*) FROM agendamento WHERE idbarbeiro = ? AND YEARWEEK(data,1) = YEARWEEK(CURDATE(),1)";
$stmt = $con->prepare($sqlSemana);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resSemana = $stmt->get_result()->fetch_row()[0] ?? 0;

// --- Semana passada ---
$sqlSemanaPassada = "SELECT COUNT(*) FROM agendamento WHERE idbarbeiro = ? AND YEARWEEK(data,1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK,1)";
$stmt = $con->prepare($sqlSemanaPassada);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resSemanaPassada = $stmt->get_result()->fetch_row()[0] ?? 0;

$percSemana = ($resSemanaPassada > 0) ? (($resSemana - $resSemanaPassada) / $resSemanaPassada) * 100 : 100;

// --- Clientes totais ---
$sqlClientes = "SELECT COUNT(DISTINCT iduser) FROM agendamento WHERE idbarbeiro = ?";
$stmt = $con->prepare($sqlClientes);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resClientes = $stmt->get_result()->fetch_row()[0] ?? 0;

// --- Clientes mês passado ---
$sqlClientesMesPassado = "SELECT COUNT(DISTINCT iduser) FROM agendamento WHERE idbarbeiro = ? AND MONTH(data) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(data) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
$stmt = $con->prepare($sqlClientesMesPassado);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resClientesMesPassado = $stmt->get_result()->fetch_row()[0] ?? 0;

$percClientes = ($resClientesMesPassado > 0) ? (($resClientes - $resClientesMesPassado) / $resClientesMesPassado) * 100 : 100;

// --- Faturamento mensal ---
$sqlFaturamento = "SELECT SUM(valor_final) FROM agendamento WHERE idbarbeiro = ? AND MONTH(data) = MONTH(CURDATE()) AND YEAR(data) = YEAR(CURDATE())";
$stmt = $con->prepare($sqlFaturamento);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resFaturamento = $stmt->get_result()->fetch_row()[0] ?? 0;

// --- Faturamento mês passado ---
$sqlFaturamentoMesPassado = "SELECT SUM(valor_final) FROM agendamento WHERE idbarbeiro = ? AND MONTH(data) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(data) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
$stmt = $con->prepare($sqlFaturamentoMesPassado);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resFaturamentoMesPassado = $stmt->get_result()->fetch_row()[0] ?? 0;

$percFaturamento = ($resFaturamentoMesPassado > 0) ? (($resFaturamento - $resFaturamentoMesPassado) / $resFaturamentoMesPassado) * 100 : 100;

// --- Agendamentos de hoje ---
$sqlAgendamentosHoje = "SELECT a.idagendamento, a.horario, a.status, a.valor_final, c.nome AS nome_cliente, s.nome_servico
                        FROM agendamento a
                        INNER JOIN clientes c ON a.iduser = c.id
                        INNER JOIN servico s ON a.idservico = s.idservico
                        WHERE a.idbarbeiro = ? AND DATE(a.data) = CURDATE()
                        ORDER BY a.horario ASC";
$stmt = $con->prepare($sqlAgendamentosHoje);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resultAgendamentos = $stmt->get_result();
while ($row = $resultAgendamentos->fetch_assoc()) {
    $agendamentosHoje[] = $row;
}

// --- Gráfico semanal ---
$sqlGraf = "SELECT DAYOFWEEK(data) AS dia, COUNT(*) AS total
            FROM agendamento
            WHERE idbarbeiro = ? AND YEARWEEK(data,1) = YEARWEEK(CURDATE(),1)
            GROUP BY dia";
$stmt = $con->prepare($sqlGraf);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$resGraf = $stmt->get_result();
while ($row = $resGraf->fetch_assoc()) {
    $porcentagens[$row['dia']] = $row['total'];
}

// --- Foto e nome do barbeiro ---
$sqlBarbeiro = "SELECT foto, nome_barbeiro FROM barbeiro WHERE idbarbeiro = ?";
$stmt = $con->prepare($sqlBarbeiro);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$dadosBarbeiro = $stmt->get_result()->fetch_assoc();
$nomeBarbeiro = $dadosBarbeiro['nome_barbeiro'];
$fotoBarbeiro = $dadosBarbeiro['foto'];

// --- Taxa de Conclusão diária ---
$sqlConcluidosHoje = "SELECT COUNT(*) FROM agendamento WHERE idbarbeiro = ? AND DATE(data) = CURDATE() AND status = 'concluido'";
$stmt = $con->prepare($sqlConcluidosHoje);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$concluidosHoje = $stmt->get_result()->fetch_row()[0] ?? 0;

$taxaConclusao = ($resHoje > 0) ? ($concluidosHoje / $resHoje) * 100 : 0;

// --- Taxa de Conclusão semanal ---
$sqlConcluidosSemana = "SELECT COUNT(*) FROM agendamento WHERE idbarbeiro = ? AND YEARWEEK(data,1) = YEARWEEK(CURDATE(),1) AND status = 'concluido'";
$stmt = $con->prepare($sqlConcluidosSemana);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$concluidosSemana = $stmt->get_result()->fetch_row()[0] ?? 0;

$taxaConclusaoSemana = ($resSemana > 0) ? ($concluidosSemana / $resSemana) * 100 : 0;

// --- Passa dados para JS ---
$dataSelecionada = $_GET['data'] ?? date('Y-m-d');

$sqlCalendario = "SELECT a.idagendamento, a.horario, a.status, a.valor_final, c.nome AS nome_cliente, s.nome_servico
                  FROM agendamento a
                  INNER JOIN clientes c ON a.iduser = c.id
                  INNER JOIN servico s ON a.idservico = s.idservico
                  WHERE a.idbarbeiro = ? AND DATE(a.data) = ?
                  ORDER BY a.horario ASC";
$stmt = $con->prepare($sqlCalendario);
$stmt->bind_param("is", $idBarbeiro, $dataSelecionada);
$stmt->execute();
$resultCalendario = $stmt->get_result();

$agendamentosCalendario = [];
while ($row = $resultCalendario->fetch_assoc()) {
    $agendamentosCalendario[] = $row;
}
$agendamentos = getUltimosAgendamentosBarbeiro($con);

if (empty($agendamentos)) {
    echo "<p>Não há agendamentos recentes para este barbeiro.</p>";
} else {
    foreach ($agendamentos as $a) {
        // echo "📅 {$a['data']} — ⏰ {$a['horario']} — ✂ {$a['servico']} — Cliente: {$a['cliente']} ({$a['iniciais']})<br>";
    }
}


$firstTree = getTreeAgend($con);




// echo json_encode([
//     'agendamentosHoje' => $agendamentosHoje,
//     'porcentagensSemana' => array_values($porcentagens),
//     'taxaConclusao' => $taxaConclusao,
//     'taxaConclusaoSemana' => $taxaConclusaoSemana,
//     'agendamentosCalendario' => $agendamentosCalendario
// ]);
?>
