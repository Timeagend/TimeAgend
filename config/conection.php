<?php
$servername = "localhost:3306";
$dbname = "barbearia";
$password = "Agl2816!";
$username = "root";

$con = new mysqli($servername, $username, $password, $dbname);

// Tratamento de erro da conexão
if ($con->connect_error) {
    error_log("Erro de conexão: " . $con->connect_error); // Registra o erro no log
    die("Ocorreu um erro ao conectar ao banco de dados. Tente novamente mais tarde.");
}

// Função para reutilizar a conexão
function getDatabaseConnection() {
    global $con;
    return $con;
}
?>
