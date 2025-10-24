<?php
require_once '../../config/conection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $mensagem = $_POST['mensagem'] ?? null;

    if ($nome && $email && $mensagem) {
        echo "<script>alert('✅ Mensagem enviada com sucesso!'); window.location.href='../../public/index.php';</script>";
        header("<?= BASE_URL ?>/public/index.php");
    } else {
        echo "<script>alert('⚠️ Preencha todos os campos!'); window.history.back();</script>";
    }
}
