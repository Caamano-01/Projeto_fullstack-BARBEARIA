<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$acao = $_GET['acao'] ?? '';

// Buscar profissionais por serviço
if($acao == 'profissionais') {
    $servico_id = $_GET['servico_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT p.id, p.nome FROM profissionais p 
                           JOIN profissional_servico ps ON p.id = ps.profissional_id 
                           WHERE ps.servico_id = ? AND p.ativo = 1");
    $stmt->execute([$servico_id]);
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($profissionais);
    exit;
}

// Buscar horários ocupados
if($acao == 'horarios') {
    $pro_id = $_GET['profissional_id'] ?? 0;
    $data = $_GET['data'] ?? '';
    
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(hora, '%H:%i') as hora FROM agendamentos 
                           WHERE profissional_id = ? AND data = ? AND status != 'cancelado'");
    $stmt->execute([$pro_id, $data]);
    
    $ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode($ocupados);
    exit;
}

echo json_encode(['error' => 'Ação inválida']);
exit;
?>