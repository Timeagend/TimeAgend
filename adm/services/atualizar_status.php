<?php
require_once "../../config/conection.php";

header('Content-Type: application/json');

$data   = json_decode(file_get_contents('php://input'), true);
$id     = intval($data['id']     ?? 0);
$status = $data['status'] ?? '';

$permitidos = ['pendente', 'aprovado', 'cancelado', 'concluido'];

if (!$id || !in_array($status, $permitidos)) {
    echo json_encode(['success' => false, 'erro' => 'Dados inválidos.']);
    exit;
}

$stmt = $con->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'erro' => 'Erro ao atualizar.']);
}

$stmt->close();