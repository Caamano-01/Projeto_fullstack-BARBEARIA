<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'] ?? 2;
    $servico_id = $_POST['servico_id'];
    $profissional_id = $_POST['profissional_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    $sql = "INSERT INTO agendamentos (usuario_id, servico_id, profissional_id, data, hora, status) 
            VALUES (?, ?, ?, ?, ?, 'pendente')";
    
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$usuario_id, $servico_id, $profissional_id, $data, $hora])) {
        echo "<script>alert('Agendado com sucesso!'); window.location.href='meus-horarios.html';</script>";
    }
}
?>