<?php 

include_once('servicos.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['service-name'];
    $tipo = $_POST['service-tipo'];
    $preco = $_POST['service-valor'];
    $duracao = $_POST['service-duracao'];

    $servicos = new Servicos($con);
    $servicos->addServico($nome, $tipo, $preco, $duracao);

    header('Location: ' . BASE_URL . 'adm/index.php');
    exit;
}
$texto = new Servicos($con);
$servicos = $texto->getServicos();

$users = new User($con);
$countUser = $users ->countUser();

$numUser = $texto ->getNumberUser($countUser);
$numAtendimentos = $texto->getAtendimento();
$totalLucro = $texto->calcularLucro();
$agendamentos = $texto->getAgendamentos();
$barbeiros = new Barbeiro($con);
$barbeiroList = $barbeiros->getBarbeiros();

