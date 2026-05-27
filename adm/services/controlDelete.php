<?php

include_once '../services/servicos.php';
include_once(__DIR__ . '/../../config/url.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        die('ID do serviço não enviado.');
    }

    $servicos = new Servicos($con);

    $result = $servicos->deleteServico($id);

   if ($result['success']) {
    header('Location: ' . BASE_URL . 'adm/index.php?deleted=1');
    exit();
} else {
    $mensagem = urlencode($result['message']);

    header(
        'Location: ' .
        BASE_URL .
        'adm/index.php?deleted=0&error=' .
        $mensagem
    );
    exit();
}

}