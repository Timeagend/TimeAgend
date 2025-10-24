<?php

include(__DIR__ . '/../../config/conection.php');
include_once(__DIR__ . '/../../config/url.php');
include(__DIR__.'/../../models/auth/authFunctions.php');

class Servicos {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function addServico($nome, $tipo, $preco, $duracao) {
        $descricao = ''; 

        $sql = "INSERT INTO servico (nome_servico, preco, descricao, tipo, duracao) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);

        if (!$stmt) {
            die("Erro ao preparar: " . $this->con->error);
        }

        $stmt->bind_param("sdsss", $nome, $preco, $descricao, $tipo, $duracao);
        return $stmt->execute();
    }

    public function getServicos() {
        $sql = "SELECT * FROM servico";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberUser($countUser){
        $count = 0;
        foreach($countUser as $user){
            $count = $user['iduser'];
        }
        return $count;
    }

    public function getAtendimento(){
        $sql = "SELECT * FROM agendamento";
        $stmt = $this ->con ->prepare($sql);
        $stmt-> execute();
        $result = $stmt->get_result();
        $count = 0;
        while($row = $result->fetch_assoc()){
            $count++;
        }
        return $count;
    }

    public function calcularLucro(){
        $sql = "
        SELECT SUM(s.preco) AS total_lucro
        FROM agendamento a
        JOIN servico s ON a.idservico = s.idservico
        WHERE a.status = 'confirmado'
    ";

    $resultado = mysqli_query($this->con, $sql);

    if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
        return $linha['total_lucro'] ?? 0;
    }

    return 0;

    }

    public function getAgendamentos(){
        $stmt = $this->con->prepare("
        SELECT 
            a.*, 
            u.nome_user AS nome_cliente, 
            u.foto_perfil,
            s.nome_servico
        FROM agendamento a
        JOIN user u ON a.iduser = u.iduser
        JOIN servico s ON a.idservico = s.idservico
        ORDER BY a.data, a.horario
        
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $agendamentos = [];
        while($row = $result->fetch_assoc()){
            if($row['status'] == 'confirmado'){
                $row['status'] = 'completed';
            }else if($row['status'] == 'pendente'){
                $row['status'] = 'pending';
            }else if($row['status'] == 'cancelado'){
                $row['status'] = 'canceled';
            }
            
            $agendamentos[] = $row;
        }
        return $agendamentos;
    }
}
// echo json_encode($agendamentos);

class Barbeiro{
    private $con;
   
    public function __construct($con){
        $this->con = $con;
    }

    public function getBarbeiros(){
        $sql = "SELECT nome_barbeiro, foto FROM barbeiro";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $barbeiros = [];
        while($row = $result->fetch_assoc()){
            $row['foto'] = BASE_URL . 'adm/img/' . $row['foto'];
            $barbeiros[] = $row;
        }
        return $barbeiros;
    
    }
    public function setBarbeiro($nome, $email,$senha,$descricao,$fotoPerfil){
        $sql = "INSERT INTO barbeiro (nome_barbeiro, descricao, foto, email,senha) values (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        if(!$stmt){
            die("Erro ao preparar: " . $this->con->error);
        }
        $stmt->bind_param("sssss", $nome, $descricao, $fotoPerfil, $email, $senha);
        return $stmt->execute();
    }


}

class Empresa{
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function getEmpresa(){
        $sql = "SELECT * FROM barbearia where id = 1";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);

    }

    public function setEmpresa($local,$telefone,$email,$cidade){
        $sql = "UPDATE barbearia SET local = ?, telefone = ?, email = ?, cidade = ? WHERE id = 1";
        $stmt = $this->con->prepare($sql);
        if(!$stmt){
            die("Erro ao preparar: " . $this->con->error);
        }
        $stmt->bind_param("ssss", $local, $telefone, $email, $cidade);
        return $stmt->execute();
        
    }

    public function mostrarDadosBarbearia(){
        global $con;
        $servico = new Empresa($con);
        $dados = $servico->getEmpresa();
        return $dados;
    }
}

