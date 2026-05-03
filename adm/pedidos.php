<?php 


require_once "../config/conection.php";
require_once "services/Pedidos.php";

$pedidoModel = new Pedidos($con);
$pedidos = $pedidoModel->listarPedidos();

?>

HTML E PHP PARA EXIBIR OS PEDIDOS
<div id="pedidos" class="tab-content active">
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data do Pedido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= $pedido['id'] ?></td>
                            <td><?= $pedido['cliente'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                            <td><?= $pedido['status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

</div>