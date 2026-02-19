<?php
header("Content-Type: application/json");
require __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->connect();

try {
    $query = "SELECT id, nome, descricao, preco, duracao_minutos
              FROM servicos
              WHERE ativo = 1
              ORDER BY preco ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $servicos
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}