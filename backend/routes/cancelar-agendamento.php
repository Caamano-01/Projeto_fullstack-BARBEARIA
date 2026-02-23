<?php
header('Content-Type: application/json');
session_start();

require_once '../config/db.php';

$database = new Database();
$conn = $database->connect();

if (!$conn) {
    echo json_encode(["erro" => "Erro de conexão com o banco"]);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(["erro" => "ID não enviado"]);
    exit;
}

$id = intval($_POST['id']);

try {
    $sql = "UPDATE agendamentos SET status = 'cancelado' WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["sucesso" => true]);

} catch (PDOException $e) {
    echo json_encode(["erro" => $e->getMessage()]);
}