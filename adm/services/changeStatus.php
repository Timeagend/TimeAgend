<?php
header('Content-Type: application/json');

require_once(__DIR__ . '/../../config/conection.php');

$ALLOWED = ['pendente', 'confirmado', 'cancelado'];

$id     = isset($_POST['id'])     ? (int)trim($_POST['id'])        : 0;
$status = isset($_POST['status']) ? (string)trim($_POST['status']) : '';

if ($id <= 0 || !in_array($status, $ALLOWED, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$stmt = $con->prepare("UPDATE agendamento SET status = ? WHERE idagendamento = ?");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar query.']);
    exit;
}

$stmt->bind_param('si', $status, $id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true, 'message' => 'Status atualizado.']);