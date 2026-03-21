<?php 

header('Content-Type: application/json');

$response = ['success' => true, 'message' => 'Dados salvos com sucesso!'];
echo json_encode($response);


include_once('servicos.php');
include_once(__DIR__ . '/../../config/conection.php');
include_once(__DIR__ . '/../../config/url.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $local = $_POST['local'];

    echo $telefone;
    echo $email;    
    echo $cidade;
    echo $local;

    $info = new Empresa($con);
    $info->setEmpresa($local,$telefone,$email,$cidade);

    header('Location: ' . BASE_URL . 'adm/index.php');
    exit;
}