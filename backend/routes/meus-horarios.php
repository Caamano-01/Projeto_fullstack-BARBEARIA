<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();
    
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "INSERT INTO bloqueios_horarios (profissional_id, data, hora_inicio, hora_fim, motivo) 
            VALUES (:prof, :data, :inicio, :fim, :motivo)";
    
    $stmt = $db->prepare($sql);
    
    try {
        $stmt->execute([
            ':prof'   => $data['profissional_id'],
            ':data'   => $data['data'],
            ':inicio' => $data['hora_inicio'],
            ':fim'    => $data['hora_fim'],
            ':motivo' => $data['motivo']
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>