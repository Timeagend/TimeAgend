<?php
include_once '../../../config/conection.php';
include_once '../../../config/url.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=utf-8");

try {
    if (!isset($_SESSION["iduser"])) {
        throw new Exception("Usuário não logado.");
    }

    $iduser = intval($_SESSION["iduser"]);
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['servicos'], $data['barbeiro'], $data['data'], $data['horario'])) {
        throw new Exception("Dados não recebidos corretamente.");
    }

    $nomeBarbeiro = trim($data['barbeiro']);
    $dataAgendamento = $data['data'];
    $horario = $data['horario'];

    // 🔹 1. Buscar idbarbeiro
    $sqlBarbeiro = "SELECT idbarbeiro FROM barbeiro WHERE nome_barbeiro = ?";
    $stmt = $con->prepare($sqlBarbeiro);
    $stmt->bind_param("s", $nomeBarbeiro);
    $stmt->execute();
    $stmt->bind_result($idbarbeiro);
    $stmt->fetch();
    $stmt->close();

    if (!$idbarbeiro) {
        throw new Exception("Barbeiro não encontrado.");
    }

    // 🔹 2. Buscar desconto do plano ativo
    $desconto = 0;
    // 🔹 2. Verificar plano ativo do usuário
$sqlPlano = "
    SELECT p.desconto 
    FROM plano_ativo pa
    INNER JOIN planos p ON pa.idplano = p.idplanos
    WHERE pa.iduser = ? AND pa.status = 'ativo'
    LIMIT 1
";
$stmt = $con->prepare($sqlPlano);
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($desconto);
$stmt->fetch();
$stmt->close();

// Se não tiver plano, desconto = 0
$desconto = is_numeric($desconto) ? $desconto : 0;

// 🔹 3. Inserir agendamento com valor já calculado
foreach ($data['servicos'] as $nomeServico) {
    $nomeServico = trim($nomeServico);

    // Buscar id e preço do serviço
    $sqlServico = "SELECT idservico, preco FROM servico WHERE nome_servico = ?";
    $stmt = $con->prepare($sqlServico);
    $stmt->bind_param("s", $nomeServico);
    $stmt->execute();
    $stmt->bind_result($idservico, $preco);
    $stmt->fetch();
    $stmt->close();

    if (!$idservico) {
        throw new Exception("Serviço '$nomeServico' não encontrado.");
    }

    // Calcular valor com desconto
    $valorFinal = $preco - (($preco * $desconto) / 100);
    $valorFinal = max(0, $valorFinal);
    $_SESSION['valorFinal']= $valorFinal;

    // Inserir agendamento
    $sqlInsert = "INSERT INTO agendamento 
        (iduser, idbarbeiro, idservico, data, horario, status, valor_final, criado_em)
        VALUES (?, ?, ?, ?, ?, 'pendente', ?, NOW())";
    $stmt = $con->prepare($sqlInsert);
    $stmt->bind_param("iiissd", $iduser, $idbarbeiro, $idservico, $dataAgendamento, $horario, $valorFinal);
    $stmt->execute();
    $stmt->close();
}

    echo json_encode(["sucesso" => true, "mensagem" => "Agendamento criado com sucesso.", "desconto" => $desconto]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => $e->getMessage()
    ]);
}
