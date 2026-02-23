<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $pdo = $database->connect();

    $usuario_id = $_SESSION['usuario_id'] ?? 2;
    $servico_id = $_POST['servico_id'];
    $profissional_id = $_POST['profissional_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    $sqlCheckOcupado = "SELECT id FROM agendamentos 
                    WHERE data = ? 
                    AND hora = ? 
                    AND profissional_id = ? 
                    AND status != 'cancelado'";

    $sqlCheck = "SELECT id FROM bloqueios_horarios 
                 WHERE data = ? 
                 AND profissional_id = ? 
                 AND (? BETWEEN hora_inicio AND hora_fim)";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$data, $profissional_id, $hora]);

    if($stmtCheck->rowCount() > 0) {
        echo "<script>
        alert('Este horário foi bloqueado pelo administrador.');
        window.history.back();
        </script>";
        exit;
    }

    $sql = "INSERT INTO agendamentos 
            (usuario_id, servico_id, profissional_id, data, hora, status) 
            VALUES (?, ?, ?, ?, ?, 'confirmado')";

    $stmt = $pdo->prepare($sql);

    if($stmt->execute([$usuario_id, $servico_id, $profissional_id, $data, $hora])){
        echo "<script>
        alert('Agendado com sucesso!');
        window.location.href='../../public/user/meus-horarios.html';
        </script>";
    } else {
        echo "Erro ao agendar";
    }
}
?>