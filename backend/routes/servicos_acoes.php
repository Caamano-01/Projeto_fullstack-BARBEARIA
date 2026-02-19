<?php
header("Content-Type: application/json");
require __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->connect();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // EXCLUIR
        if (isset($data['action']) && $data['action'] === 'delete') {
            $stmt = $conn->prepare("UPDATE servicos SET ativo = 0 WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(["success" => true, "message" => "Serviço desativado"]);
        } 
        // ADICIONAR OU EDITAR
        else {
            $nome = $data['nome'] ?? '';
            $preco = $data['preco'] ?? 0;
            $descricao = $data['descricao'] ?? '';
            $duracao = $data['duracao'] ?? 0;
            $id = !empty($data['id']) ? $data['id'] : null;

            if ($id) {
                $stmt = $conn->prepare("UPDATE servicos SET nome=?, descricao=?, preco=?, duracao_minutos=? WHERE id=?");
                $stmt->execute([$nome, $descricao, $preco, $duracao, $id]);
            } else {
                $stmt = $conn->prepare("INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo) VALUES (?, ?, ?, ?, 1)");
                $stmt->execute([$nome, $descricao, $preco, $duracao]);
            }
            echo json_encode(["success" => true]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}