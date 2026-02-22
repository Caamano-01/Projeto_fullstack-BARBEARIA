<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';

try {
    $database = new Database();
    $pdo = $database->connect();

    $acao = $_GET['acao'] ?? '';

    if ($acao == 'servicos') {
        $stmt = $pdo->query("SELECT id, nome, preco FROM servicos WHERE ativo = 1");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($acao == 'profissionais') {
        $servico_id = $_GET['servico_id'] ?? 0;
        $stmt = $pdo->prepare("SELECT p.id, p.nome FROM profissionais p 
                               JOIN profissional_servico ps ON p.id = ps.profissional_id 
                               WHERE ps.servico_id = ? AND p.ativo = 1");
        $stmt->execute([$servico_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }
    
    if ($acao == 'horarios') {
        $pro_id = $_GET['profissional_id'] ?? 0;
        $data = $_GET['data'] ?? '';

        $todos_horarios = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
            '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
            '17:00', '17:30', '18:00', '18:30', '19:00'
        ];

        $stmt = $pdo->prepare("SELECT DATE_FORMAT(hora, '%H:%i') as hora FROM agendamentos 
                               WHERE profissional_id = ? AND data = ? AND status != 'cancelado'");
        $stmt->execute([$pro_id, $data]);
        $ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $disponiveis = [];
        foreach ($todos_horarios as $h) {
            if (!in_array($h, $ocupados)) {
                $disponiveis[] = ['hora' => $h];
            }
        }

        echo json_encode($disponiveis);
        exit;
    }
} catch (Exception $e) {
    echo json_encode([]); // Retorna vazio em caso de erro
}