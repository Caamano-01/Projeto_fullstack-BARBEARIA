<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

include_once '../config/db.php';

$database = new Database();
$db = $database->connect();

if ($db) {
    try {
        $query = "SELECT p.id, p.nome, p.contato, p.foto_url, 
                  GROUP_CONCAT(s.nome SEPARATOR ', ') AS especialidades
                  FROM profissionais p
                  LEFT JOIN profissional_servico ps ON p.id = ps.profissional_id
                  LEFT JOIN servicos s ON ps.servico_id = s.id
                  WHERE p.ativo = 1
                  GROUP BY p.id";
        
        $stmt = $db->prepare($query);
        $stmt->execute();

        $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($profissionais);
    } catch (PDOException $e) {
        echo json_encode(['erro' => 'Erro ao buscar profissionais: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['erro' => 'Não foi possível conectar ao banco de dados.']);
}
?>