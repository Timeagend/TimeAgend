<?php
session_start();
include_once '../../config/conection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['iduser'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado'
    ]);
    exit;
}

$user_id = (int) $_SESSION['iduser'];

$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');

$stmt = $con->prepare("
    SELECT iduser
    FROM user
    WHERE email_user = ?
    AND iduser != ?
");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Este email já está em uso por outro usuário.'
    ]);
    exit;
}

$stmt = $con->prepare("
    UPDATE user
    SET nome_user = ?, phone = ?, email_user = ?
    WHERE iduser = ?
");

$stmt->bind_param(
    "sssi",
    $nome,
    $telefone,
    $email,
    $user_id
);

if ($stmt->execute()) {

    $_SESSION['nome_user'] = $nome;
    $_SESSION['phone'] = $telefone;
    $_SESSION['email_user'] = $email;

    echo json_encode([
        'success' => true,
        'message' => 'Dados atualizados com sucesso!'
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar os dados.'
    ]);
}