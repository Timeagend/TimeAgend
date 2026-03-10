<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../config/conection.php"; // ajuste o caminho se necessário

// Barbeiro logado
$idBarbeiro = $_SESSION['idbarbeiro'] ?? null;
if (!$idBarbeiro) {
    die("<p class='text-sm text-red-500'>Barbeiro não logado.</p>");
}

// Consulta: últimos 10 agendamentos desse barbeiro
$sql = "SELECT a.idagendamento, a.data, c.nome AS nome_cliente, s.nome_servico
        FROM agendamento a
        INNER JOIN clientes c ON a.iduser = c.id
        INNER JOIN servico s ON a.idservico = s.idservico
        WHERE a.idbarbeiro = ?
        ORDER BY a.data DESC, a.idagendamento DESC
        LIMIT 5";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $idBarbeiro);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nome = $row['nome_cliente'];
        $iniciais = strtoupper((explode(' ', $nome)[0][0] ?? '') . (explode(' ', $nome)[1][0] ?? ''));
        $servico = $row['nome_servico'];
        $data = date("d/m/y", strtotime($row['data']));

        // Cor aleatória entre as disponíveis (opcional)
        $cores = ['blue','amber','indigo','purple','green','rose','teal','orange','cyan','lime','pink','violet','emerald','sky','red'];
        $cor = $cores[array_rand($cores)];
        ?>

        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-<?= $cor ?>-100 flex items-center justify-center mr-3">
                <span class="font-medium text-<?= $cor ?>-600"><?= $iniciais ?></span>
            </div>
            <div class="flex-1">
                <h3 class="font-medium text-gray-800"><?= htmlspecialchars($nome) ?></h3>
                <p class="text-xs text-gray-500">Último serviço: <?= htmlspecialchars($servico) ?></p>
            </div>
            <div class="text-right">
                <span class="text-xs text-gray-500"><?= $data ?></span>
            </div>
        </div>

        <?php
    }
} else {
    echo "<p class='text-sm text-gray-500'>Nenhum cliente recente encontrado.</p>";
}
?>
