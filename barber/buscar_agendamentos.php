<?php
session_start();

function getUltimosAgendamentosBarbeiro($con) {

    $idBarbeiro = $_SESSION['idbarbeiro'] ?? null;
    if (!$idBarbeiro) return [];

    $sql = "SELECT 
                a.idagendamento,
                a.data,
                a.horario,
                s.nome_servico AS servico,
                u.nome_user AS cliente,
                -- Pegando iniciais
                CONCAT(
                    LEFT(u.nome_user, 1),
                    CASE 
                        WHEN LOCATE(' ', u.nome_user) > 0 
                        THEN SUBSTRING(u.nome_user, LOCATE(' ', u.nome_user) + 1, 1)
                        ELSE ''
                    END
                ) AS iniciais
            FROM agendamento a
            INNER JOIN servico s ON a.idservico = s.idservico
            INNER JOIN user u ON a.iduser = u.iduser
            WHERE a.idbarbeiro = ?
            ORDER BY a.data DESC, a.horario DESC
            LIMIT 5";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar SQL: " . $con->error);
    }

    $stmt->bind_param("i", $idBarbeiro);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTreeAgend($con){
   $idBarbeiro = $_SESSION['idbarbeiro'] ?? null;
    if (!$idBarbeiro) return [];

    $sql = "SELECT 
                a.idagendamento,
                a.data,
                a.horario,
                s.nome_servico AS servico,
                u.nome_user AS cliente,
                -- Pegando iniciais
                CONCAT(
                    LEFT(u.nome_user, 1),
                    CASE 
                        WHEN LOCATE(' ', u.nome_user) > 0 
                        THEN SUBSTRING(u.nome_user, LOCATE(' ', u.nome_user) + 1, 1)
                        ELSE ''
                    END
                ) AS iniciais
            FROM agendamento a
            INNER JOIN servico s ON a.idservico = s.idservico
            INNER JOIN user u ON a.iduser = u.iduser
            WHERE a.idbarbeiro = ?
            ORDER BY a.data DESC, a.horario DESC
            LIMIT 3";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar SQL: " . $con->error);
    }

    $stmt->bind_param("i", $idBarbeiro);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}