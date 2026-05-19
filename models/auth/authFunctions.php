<?php
require_once __DIR__ ."/../../config/url.php"; 
require_once __DIR__ . "/../../config/conection.php"; 


// ===================================================
// 👥 Classe do USUÁRIO
// ===================================================

class User{
    protected mysqli $con;

    public function __construct(mysqli $con){
        $this->con = $con;
    }


    public function countUser(): array {
        try {
            $stmt = $this->con->prepare("SELECT COUNT(*) AS iduser FROM user");
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            echo "Erro ao contar usuários: " . $e->getMessage();
            return [];
        }
    }

    public function register(string $name, string $email, string $phone, string $password): bool {
        $sql = "INSERT INTO user (nome_user, phone, email_user, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssss", $name, $phone, $email, $password);

        try {
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Código 1062 = Duplicate entry (email já cadastrado)
            if ($e->getCode() === 1062) {
                return false;
            }
            throw $e;
        }
    }

    public function getUserByEmail(string $email): ?array {
        $stmt = $this->con->prepare("SELECT * FROM user WHERE email_user = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
    
    public function alterPassword(string $email, string $newPassword): bool {
        $stmt = $this->con->prepare("UPDATE user SET password = ? WHERE email_user = ?");
        $stmt->bind_param("ss", $newPassword, $email);
        return $stmt->execute();
    }

    public function getPhoneById(int $iduser): ?string {
        $stmt = $this->con->prepare("SELECT phone FROM user WHERE iduser = ?");
        $stmt->bind_param("i", $iduser);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['phone'];
        } else {
            return null;
        }
    }
    
    public function updateProfile(int $iduser, string $nome_user, string $phone, string $foto_perfil): bool {
        $stmt = $this->con->prepare("UPDATE user SET nome_user = ?, phone = ?, foto_perfil = ? WHERE iduser = ?");
        $stmt->bind_param("sssi", $nome_user, $phone, $foto_perfil, $iduser);
        return $stmt->execute();
    }
}
// ===================================================
// 🔐 Classe base de AUTENTICAÇÃO
// ===================================================
class Auth {
    protected mysqli $con;

    public function __construct(mysqli $con) {
        $this->con = $con;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function verifyPassword(string $senhaDigitada, string $senhaHash): bool {
        return password_verify($senhaDigitada, $senhaHash);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/user/login.php");
        exit();
    }

    public function isAuthenticated(): bool {
        return isset($_SESSION['iduser']) || isset($_SESSION['idbarbeiro']) || isset($_SESSION['adm']);
    }
}

// ===================================================
// 👤 Classe do CLIENTE
// ===================================================
class UserAuth extends Auth {
    public function login(string $email, string $senha) {
        $stmt = $this->con->prepare("SELECT * FROM user WHERE email_user = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($this->verifyPassword($senha, $user['password'])) {
                $_SESSION['iduser'] = $user['iduser'];
                $_SESSION['nome_user'] = $user['nome_user'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['tipo'] = "cliente";
                $_SESSION['email_user'] = $user['email_user'];
                

                header("Location: " . BASE_URL . "/public/index.php");
                exit();
            } else {
                return ["status" => false, "mensagem" => "Senha incorreta."];
            }
        } else {
            return ["status" => false, "mensagem" => "Usuário não encontrado."];
        }
    }
}

// ===================================================
// 💈 Classe do BARBEIRO
// ===================================================
class BarbeiroAuth extends Auth {

    public function login(string $email, string $senha) {
        $stmt = $this->con->prepare("SELECT * FROM barbeiro WHERE email = ? LIMIT 1");
        if (!$stmt) {
            return [
                'status' => false,
                'mensagem' => "Erro ao preparar a consulta: " . $this->con->error
            ];
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $barbeiro = $result->fetch_assoc();
            if ($senha === $barbeiro['senha']) {
                
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['idbarbeiro'] = $barbeiro['idbarbeiro'];
                $_SESSION['nome_barbeiro'] = $barbeiro['nome_barbeiro']; 
                $_SESSION['tipo'] = "barbeiro";

                return [
                    'status' => true,
                    'mensagem' => 'Login do barbeiro realizado com sucesso.'
                ];
            } else {
                return [
                    'status' => false,
                    'mensagem' => "Senha incorreta."
                ];
            }

        } else {
            return [
                'status' => false,
                'mensagem' => "Barbeiro não encontrado."
            ];
        }
    }
}

