<?php
include_once(__DIR__ . '/../../adm/services/servicos.php');
include_once(__DIR__ . '/../../config/conection.php');
include_once(__DIR__ . '/../../config/url.php');

session_start();

$user = $_SESSION['iduser'] ?? null;

// === FUNÇÃO: Atualiza status dos agendamentos ===
function atualizarStatusAgendamentos() {
    global $con;

    // Atualiza para "confirmado" todos os agendamentos que passaram há mais de 1 dia
    $stmt = $con->prepare("
        UPDATE agendamento 
        SET status = 'confirmado'
        WHERE status = 'pendente'
          AND horario < DATE_SUB(NOW(), INTERVAL 1 DAY)
    ");
    $stmt->execute();
}


// === FUNÇÃO: Buscar histórico ===
function historico($user) {
    global $con;
    $stmt = $con->prepare("
        SELECT * FROM agendamento 
        WHERE iduser = ? AND status = 'pendente'
        ORDER BY data, horario ASC
    ");
    $stmt->bind_param("i", $user);
    $stmt->execute();         
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// === FUNÇÕES AUXILIARES ===
function nomeServico($idservico) {
    global $con;
    $stmt = $con->prepare("SELECT nome_servico, preco FROM servico WHERE idservico = ?");
    $stmt->bind_param("i", $idservico);
    $stmt->execute();         
    return $stmt->get_result()->fetch_assoc() ?? null;
}

function nomeBarbeiro($idbarbeiro) {
    global $con;
    $stmt = $con->prepare("SELECT nome_barbeiro FROM barbeiro WHERE idbarbeiro = ?");
    $stmt->bind_param("i", $idbarbeiro);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['nome_barbeiro'] ?? 'Barbeiro não encontrado';
}

// === CANCELAMENTO ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar'], $_POST['idagendamento'])) {
    $idagendamento = (int)$_POST['idagendamento'];

    $stmt = $con->prepare("SELECT data, horario FROM agendamento WHERE idagendamento = ? AND iduser = ?");
    $stmt->bind_param("ii", $idagendamento, $user);
    $stmt->execute();
    $agendamento = $stmt->get_result()->fetch_assoc();

    if (!$agendamento) {
        $_SESSION['feedback'] = "Agendamento não encontrado.";
        header("Location: " . BASE_URL . "/public/historico.php");
        exit();
    }

    // Junta data e hora do agendamento
    // Cria DateTime corretamente
    $dataHoraAgendamento = new DateTime(date('Y-m-d', strtotime($agendamento['data'])));
    $dataHoraAgendamento->setTime(
        (int)substr($agendamento['horario'], 0, 2),
        (int)substr($agendamento['horario'], 3, 2),
        (int)substr($agendamento['horario'], 6, 2) ?? 0
    );

    $agora = new DateTime();
    
    // Define o limite de cancelamento (2h antes)
    $limiteCancelamento = clone $dataHoraAgendamento;
    $limiteCancelamento->sub(new DateInterval('PT2H'));

    // Verifica se já passou do limite
    if ($agora > $limiteCancelamento) {
        $_SESSION['feedback'] = "Não é possível cancelar o agendamento com menos de 2 horas de antecedência.";
        header("Location: " . BASE_URL . "/public/historico.php");
        exit();
    }

    // Atualiza status para cancelado
    $stmt = $con->prepare("UPDATE agendamento SET status = 'cancelado' WHERE idagendamento = ? AND iduser = ?");
    $stmt->bind_param("ii", $idagendamento, $user);
    $stmt->execute();

    $_SESSION['feedback'] = "Agendamento cancelado com sucesso!";
    header("Location: " . BASE_URL . "/public/historico.php");
    exit();
}

// === EXECUÇÃO PRINCIPAL ===
atualizarStatusAgendamentos(); // <- atualiza antes de buscar
$agendamentos = historico($user);

$dadosBarbearia = new Empresa($con);
$dados = $dadosBarbearia->mostrarDadosBarbearia();
?>
