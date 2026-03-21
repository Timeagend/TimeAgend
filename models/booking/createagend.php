<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servico = $_POST['servico'] ?? '';
    $profissional = $_POST['profissional'] ?? '';
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $valor = $_POST['valor'] ?? '';

    echo "Serviço: $servico <br>";
    echo "Profissional: $profissional <br>";
    echo "Data: $data <br>";
    echo "Horário: $horario <br>";
    echo "Valor: $valor <br>";
}
