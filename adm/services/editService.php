<?php

include_once '../services/servicos.php';
include_once(__DIR__.'/../../config/url.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idservico = $_POST['service-id'];
    $nome = $_POST['service-nome'];
    $tipo = $_POST['service-tipo'];
    $preco = $_POST['service-valor'];
    $duracao = $_POST['service-duracao'];

    
    $servicos = new Servicos($con);
    $result = $servicos->editServico($idservico, $nome, $tipo, $preco, $duracao);

    if ($result) {
        header("Location: " . BASE_URL . "adm/servicos.php");
        exit();
    } else {
        echo "Erro ao editar serviço.";
    }
}