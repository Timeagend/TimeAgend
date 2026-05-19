<?php
require_once "../config/conection.php";
require_once "services/Pedidos.php";

$pedidoModel = new Pedidos($con);
$pedidos = $pedidoModel->listarPedidos();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="../adm/assets/css/pedidos.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div id="pedidos" class="tab-content active">
  <div class="card">

    <div class="card-titulo">
  <div class="card-titulo-left">
    <button class="btn-voltar" onclick="history.back()">
      <i class='bx bx-arrow-back'></i>
    </button>
    <i class='bx bx-receipt'></i>
    <h2>Pedidos</h2>
  </div>
  <span class="total-badge"><?= count($pedidos) ?> pedido(s)</span>
</div>
    

    <?php if (empty($pedidos)): ?>
      <div class="empty-state">
        <i class='bx bx-inbox'></i>
        <p>Nenhum pedido encontrado.</p>
      </div>
    <?php else: ?>
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>#ID</th>
              <th>Cliente</th>
              <th>Data do Pedido</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pedidos as $pedido): ?>
              <?php
                $status = strtolower($pedido['status'] ?? 'pendente');
                $statusMap = [
                  'pendente'  => ['label' => 'Pendente',  'class' => 'status-pendente'],
                  'aprovado'  => ['label' => 'Aprovado',  'class' => 'status-aprovado'],
                  'cancelado' => ['label' => 'Cancelado', 'class' => 'status-cancelado'],
                  'concluido' => ['label' => 'Concluído', 'class' => 'status-concluido'],
                ];
                $s = $statusMap[$status] ?? ['label' => ucfirst($status), 'class' => 'status-pendente'];
              ?>
              <tr>
                <td><span class="id-badge">#<?= $pedido['id'] ?></span></td>
                <td>
                  <div class="cliente-info">
                    <i class='bx bx-user-circle'></i>
                    <?= htmlspecialchars($pedido['cliente']) ?>
                  </div>
                </td>
                <td>
                  <div class="data-info">
                    <i class='bx bx-calendar'></i>
                    <?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?>
                    <span class="hora"><?= date('H:i', strtotime($pedido['data_pedido'])) ?></span>
                  </div>
                </td>
                <td>
                  <span
                    class="status-badge <?= $s['class'] ?> status-clicavel"
                    data-id="<?= $pedido['id'] ?>"
                    data-status="<?= $status ?>"
                    title="Clique para alterar"
                  >
                    <?= $s['label'] ?>
                    <i class='bx bx-pencil'></i>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>
</div>

<!-- Modal de Status -->
<div id="modalStatus" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <div class="modal-header">
      <h3><i class='bx bx-edit-alt'></i> Alterar Status</h3>
      <button class="modal-fechar" id="fecharModalStatus"><i class='bx bx-x'></i></button>
    </div>
    <p class="modal-sub">Pedido <strong id="modalPedidoId"></strong></p>
    <div class="status-opcoes">
      <button class="opcao-status status-pendente"  data-valor="pendente">Pendente</button>
      <button class="opcao-status status-aprovado"  data-valor="aprovado">Aprovado</button>
      <button class="opcao-status status-concluido" data-valor="concluido">Concluído</button>
      <button class="opcao-status status-cancelado" data-valor="cancelado">Cancelado</button>
    </div>
    <div id="modalFeedback" class="modal-feedback" style="display:none;"></div>
  </div>
</div>

<script>
  const statusLabels = {
    pendente:  'Pendente',
    aprovado:  'Aprovado',
    concluido: 'Concluído',
    cancelado: 'Cancelado'
  };

  const statusClasses = {
    pendente:  'status-pendente',
    aprovado:  'status-aprovado',
    concluido: 'status-concluido',
    cancelado: 'status-cancelado'
  };

  let pedidoIdAtual = null;
  let badgeAtual    = null;

  // Abre modal ao clicar no badge
  document.querySelectorAll('.status-clicavel').forEach(badge => {
    badge.addEventListener('click', function () {
      pedidoIdAtual = this.dataset.id;
      badgeAtual    = this;

      document.getElementById('modalPedidoId').textContent = '#' + pedidoIdAtual;

      // Marca o status atual
      document.querySelectorAll('.opcao-status').forEach(btn => {
        btn.classList.toggle('ativo', btn.dataset.valor === this.dataset.status);
      });

      document.getElementById('modalFeedback').style.display = 'none';
      document.getElementById('modalStatus').style.display   = 'flex';
    });
  });

  // Fecha modal
  document.getElementById('fecharModalStatus').addEventListener('click', fecharModal);
  document.getElementById('modalStatus').addEventListener('click', function (e) {
    if (e.target === this) fecharModal();
  });

  function fecharModal() {
    document.getElementById('modalStatus').style.display = 'none';
    pedidoIdAtual = null;
    badgeAtual    = null;
  }

  // Clique nas opções de status
  document.querySelectorAll('.opcao-status').forEach(btn => {
    btn.addEventListener('click', async function () {
      const novoStatus = this.dataset.valor;

      document.querySelectorAll('.opcao-status').forEach(b => b.classList.remove('ativo'));
      this.classList.add('ativo');

      const feedback = document.getElementById('modalFeedback');
      feedback.style.display   = 'block';
      feedback.className       = 'modal-feedback carregando';
      feedback.textContent     = 'Salvando...';

      try {
        const response = await fetch('services/atualizar_status.php', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json' },
          body:    JSON.stringify({ id: pedidoIdAtual, status: novoStatus })
        });

        const result = await response.json();

        if (result.success) {
          // Atualiza o badge na tabela sem recarregar
          badgeAtual.textContent  = '';
          badgeAtual.innerHTML    = statusLabels[novoStatus] + ' <i class="bx bx-pencil"></i>';
          badgeAtual.className    = 'status-badge status-clicavel ' + statusClasses[novoStatus];
          badgeAtual.dataset.status = novoStatus;

          feedback.className   = 'modal-feedback sucesso';
          feedback.textContent = 'Status atualizado com sucesso!';

          setTimeout(fecharModal, 1200);
        } else {
          feedback.className   = 'modal-feedback erro';
          feedback.textContent = 'Erro ao atualizar. Tente novamente.';
        }
      } catch {
        feedback.className   = 'modal-feedback erro';
        feedback.textContent = 'Falha na conexão.';
      }
    });
  });
</script>

</body>
</html>