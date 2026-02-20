<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

try {
    $db = (new Database())->connect();
    
    // Consulta que traz os bloqueios e o nome do profissional
    $sql = "SELECT b.*, p.nome as profissional_nome 
            FROM bloqueios_horarios b
            JOIN profissionais p ON b.profissional_id = p.id
            ORDER BY b.data DESC, b.hora_inicio ASC";
            
    $stmt = $db->query($sql);
    $bloqueios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($bloqueios);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>