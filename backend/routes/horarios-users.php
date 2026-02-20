<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['erro' => 'Não autorizado']);
    exit();
}

$usuario_id = $_SESSION['id'];
$agendamentos = [];

$sql = "SELECT a.data, a.hora, a.status, s.nome AS servico, p.nome AS profissional 
        FROM agendamentos a
        JOIN servicos s ON a.servico_id = s.id
        JOIN profissionais p ON a.profissional_id = p.id
        WHERE a.usuario_id = ?
        ORDER BY a.data DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}

// Retorna o array para o JavaScript
echo json_encode($agendamentos);
?>