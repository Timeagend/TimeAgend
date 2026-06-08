<?php

echo '<pre>';
var_dump($_POST);
var_dump($_FILES);
exit;
include_once '../../config/conection.php';
include_once '../../config/url.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/adm/index.php');
    exit;
}

$id = $_POST['idbarbeiro'] ?? null;
$acao = $_POST['acao'] ?? null;

if (!$id || !$acao) {
    header('Location: ' . BASE_URL . '/adm/index.php?barber_error=1');
    exit;
}

/* EXCLUIR BARBEIRO */
if ($acao === 'excluir') {
    $sql = "DELETE FROM barbeiro WHERE idbarbeiro = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: ' . BASE_URL . '/adm/index.php?barber_deleted=1');
    } else {
        header('Location: ' . BASE_URL . '/adm/index.php?barber_deleted=0');
    }

    exit;
}

/* EDITAR BARBEIRO */
if ($acao === 'editar') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    if (empty($nome)) {
        header('Location: ' . BASE_URL . '/adm/index.php?barber_updated=0');
        exit;
    }

    /* Se enviou nova foto */
    if (!empty($_FILES['foto']['name'])) {
        $fotoNome = time() . '_' . basename($_FILES['foto']['name']);
        $pastaDestino = '../../public/img/barbeiros/';

        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        $caminhoArquivo = $pastaDestino . $fotoNome;
        $fotoBanco = BASE_URL . '/public/img/barbeiros/' . $fotoNome;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoArquivo)) {
            $sql = "UPDATE barbeiro 
                    SET nome_barbeiro = ?, descricao = ?, foto = ?
                    WHERE idbarbeiro = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssi", $nome, $descricao, $fotoBanco, $id);
        } else {
            header('Location: ' . BASE_URL . '/adm/index.php?barber_updated=0');
            exit;
        }

    } else {
        /* Sem alterar foto */
        $sql = "UPDATE barbeiro 
                SET nome_barbeiro = ?, descricao = ?
                WHERE idbarbeiro = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $nome, $descricao, $id);
    }

    if ($stmt->execute()) {
        header('Location: ' . BASE_URL . '/adm/index.php?barber_updated=1');
    } else {
        header('Location: ' . BASE_URL . '/adm/index.php?barber_updated=0');
    }

    exit;
}

header('Location: ' . BASE_URL . '/adm/index.php?barber_error=1');
exit;