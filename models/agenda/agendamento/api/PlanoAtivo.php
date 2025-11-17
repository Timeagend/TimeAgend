<?php
include_once '../../../config/conection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=utf-8");

try {
    if (!isset($_SESSION['iduser'])) {
        throw new Exception("Usuário não logado.");
    }

    $iduser = intval($_SESSION['iduser']);

    // Buscar plano ativo do usuário
    $sql = "
        SELECT p.desconto
        FROM plano_ativo pa
        INNER JOIN planos p ON pa.idplano = p.idplanos
        WHERE pa.iduser = ? AND pa.status = 'ativo'
        ORDER BY pa.data_inicio DESC
        LIMIT 1
    ";
    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception("Erro na preparação da query: ".$con->error);

    $stmt->bind_param("i", $iduser);
    $stmt->execute();
    $stmt->bind_result($desconto);
    $stmt->fetch();
    $stmt->close();

    if (!$desconto) $desconto = 0;

    echo json_encode([
        "sucesso" => true,
        "desconto" => $desconto
    ]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => $e->getMessage(),
        "desconto" => 0
    ]);
}
