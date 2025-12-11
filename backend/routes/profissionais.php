<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Ajuste o caminho conforme sua estrutura de pastas
include_once '../config/db.php';

$database = new Database();
$db = $database->connect();

if ($db) {
    try {
        $query = "SELECT nome, contato, foto_url, especialidade FROM profissionais WHERE ativo = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retorna os dados em formato JSON
        echo json_encode($profissionais);
    } catch (PDOException $e) {
        echo json_encode(['erro' => 'Erro ao buscar profissionais: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['erro' => 'Não foi possível conectar ao banco de dados.']);
}
?>