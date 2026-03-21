<?php
session_start();
include_once '../../config/url.php';
include_once '../../config/conection.php';  

$user_id = $_SESSION['iduser'];  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagem"])) {
    $imagem = $_FILES["imagem"];

    if ($imagem["error"] == 0) {
        $extensao = pathinfo($imagem["name"], PATHINFO_EXTENSION);
        $extensoes_permitidas = ["jpg", "jpeg", "png", "gif"];

        if (in_array($extensao, $extensoes_permitidas)) {
            $sql = "SELECT foto_perfil FROM user WHERE iduser = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();

            if ($usuario && $usuario['foto_perfil'] != 'img/uploads/default.png') {
                $foto_antiga = $_SERVER['DOCUMENT_ROOT'] . '../' . $usuario['foto_perfil'];  
                if (file_exists($foto_antiga)) {
                    unlink($foto_antiga);  
                }
            }

            $nome_arquivo = "perfil_" . $user_id . "_" . uniqid() . "." . $extensao;
            $caminho = $_SERVER['DOCUMENT_ROOT'] . "/TimeAgend/img/uploads/" . $nome_arquivo;

            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/TimeAgend/img/uploads/")) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . "/TimeAgend/img/uploads/", 0777, true);
            }

            if (move_uploaded_file($imagem["tmp_name"], $caminho)) {
                $caminho_relativo = "img/uploads/" . $nome_arquivo;  
                $sql = "UPDATE user SET foto_perfil = ? WHERE iduser = ?";
                $stmt = $con->prepare($sql);
                $stmt->execute([$caminho_relativo, $user_id]);

                header("Location:". BASE_URL."public/perfil.php");
                exit();

            } else {
                echo "Erro ao mover o arquivo.";
            }
        } else {
            echo "Formato inválido. Use JPG, JPEG, PNG ou GIF.";
        }
    } else {
        echo "Erro ao enviar a imagem.";
    }
}
?>
