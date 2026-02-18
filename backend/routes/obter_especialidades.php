<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode([]);
    exit;
}

// Busca apenas os IDs dos serviços vinculados a este profissional
$stmt = $conn->prepare("SELECT servico_id FROM profissional_servico WHERE profissional_id = ?");
$stmt->execute([$id]);
$servicos = $stmt->fetchAll(PDO::FETCH_COLUMN); // Retorna um array simples: [1, 4, 8]

echo json_encode($servicos);