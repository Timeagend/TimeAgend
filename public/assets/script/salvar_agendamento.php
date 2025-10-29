<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once "../../../config/conection.php"; 
include_once "../../../models/auth/authFunctions.php";
include_once "../../../models/emails/email.php";

$validAuth = new Auth($con);
if (!$validAuth->isAuthenticated()) {
    echo json_encode(["status" => "error", "mensagem" => "Usuário não autenticado"]);
    exit;
}

$id_user = $_SESSION['iduser'];

$input = file_get_contents("php://input");
$dados = json_decode($input, true);

$nomeServico = $dados['tipoServico'] ?? null;
$nomeBarbeiro = $dados['profissional'] ?? null;
$data = $dados['data'] ?? null;       
$horario = $dados['horario'] ?? null; 

$email = $_SESSION['email_user'];
$nameCliente = $_SESSION['nome_user'];

if (!$nomeServico || !$nomeBarbeiro || !$data || !$horario) {
    echo json_encode(["status" => "error", "mensagem" => "Campos obrigatórios não preenchidos"]);
    exit;
}

if (!strtotime($data)) {
    echo json_encode(["status" => "error", "mensagem" => "Data inválida"]);
    exit;
}

if (!preg_match('/^\d{2}:\d{2}$/', $horario)) {
    echo json_encode(["status" => "error", "mensagem" => "Horário inválido. Formato esperado HH:MM"]);
    exit;
}

function nomebarbeiro($nome){
    global $con;
    $stmt = $con->prepare("SELECT idbarbeiro FROM barbeiro WHERE nome_barbeiro = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();
    $barbeiro = $result->fetch_assoc();
    return $barbeiro['idbarbeiro'] ?? null;
}   

function nomeservico($nome){
    global $con;
    $stmt = $con->prepare("SELECT idservico FROM servico WHERE nome_servico = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();
    $servico = $result->fetch_assoc();
    return $servico['idservico'] ?? null;
}

$id_barbeiro = nomebarbeiro($nomeBarbeiro);
$id_servico = nomeservico($nomeServico);

if (!$id_barbeiro || !$id_servico) {
    echo json_encode(["status" => "error", "mensagem" => "Serviço ou barbeiro não encontrados"]);
    exit;
}

$dataFormatada = date('Y-m-d 00:00:00', strtotime($data));
$horarioFormatado = date('Y-m-d H:i:s', strtotime($data . ' ' . $horario));

$status = 'pendente';
$criado_em = date('Y-m-d H:i:s');

$stmt = $con->prepare("
    INSERT INTO agendamento (iduser, idbarbeiro, idservico, data, horario, status, plano_ativo, criado_em)
    VALUES (?, ?, ?, ?, ?, ?, NULL, ?)
");

$stmt->bind_param(
    "iiissss",
    $id_user,
    $id_barbeiro,
    $id_servico,
    $dataFormatada,
    $horarioFormatado,
    $status,
    $criado_em
);

if ($stmt->execute()) {
   $emailEnviado = enviarConfirmacaoAgendamento(
             // e-mail do cliente
        $nameCliente,
        $email,   // nome do cliente
        $nomeServico, // nome do serviço
        $nomeBarbeiro, // barbeiro         // data
        $data,
        $horario      // horário
    );

    if ($emailEnviado) {
        echo json_encode(["status" => "success", "mensagem" => "Agendamento salvo e e-mail enviado!"]);
    } else {
        echo json_encode(["status" => "success", "mensagem" => "Agendamento salvo, mas o e-mail falhou."]);
    }
} else {
    echo json_encode(["status" => "error", "mensagem" => "Erro ao salvar agendamento: " . $stmt->error]);

}

$stmt->close();
$con->close();
?>
