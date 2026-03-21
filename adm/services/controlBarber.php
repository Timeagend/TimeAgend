<?php 

include_once('servicos.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $descricao = $_POST['obs'];
    $fotoPerfil = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = $_FILES['foto']['name'];
        $caminhoTemporario = $_FILES['foto']['tmp_name'];

        $check = getimagesize($caminhoTemporario);
        if ($check !== false) {
            $fotoPerfil = $nomeArquivo;
            $targetDir = __DIR__ . '/../../adm/img/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $caminhoFinal = $targetDir . basename($nomeArquivo);
            move_uploaded_file($caminhoTemporario, $caminhoFinal);
        } else {
            echo "O arquivo enviado não é uma imagem válida.";
            exit;
        }
    } else {
        echo "Nenhum arquivo de imagem foi enviado.";
        exit;
    }
    $instancia = new Barbeiro($con);
    $instancia->setBarbeiro($nome, $email, $senha, $descricao, $fotoPerfil);

    header('Location: ' . BASE_URL . 'adm/index.php');
    exit;
}
?>
