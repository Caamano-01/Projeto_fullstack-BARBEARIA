<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Verifica se o usuário está logado e se recebeu o ID do agendamento
if (!isset($_SESSION['id']) || !isset($_POST['agendamento_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida']);
    exit();
}

$usuario_id = $_SESSION['id'];
$agendamento_id = $_POST['agendamento_id'];

// Atualiza apenas se o agendamento pertencer ao usuário logado e não estiver concluído
$sql = "UPDATE agendamentos SET status = 'cancelado' 
        WHERE id = ? AND usuario_id = ? AND status != 'concluido'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $agendamento_id, $usuario_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível cancelar este agendamento.']);
}
?>