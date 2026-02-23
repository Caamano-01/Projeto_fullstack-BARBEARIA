<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$usuario_id = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? null;

if (!$usuario_id) {
    echo json_encode(['erro' => 'Não autorizado']);
    exit();
}

try {
    $database = new Database();
    $pdo = $database->connect();
    
    $sql = "SELECT a.id, a.data, a.hora, a.status, s.nome AS servico, p.nome AS profissional 
            FROM agendamentos a
            JOIN servicos s ON a.servico_id = s.id
            JOIN profissionais p ON a.profissional_id = p.id
            WHERE a.usuario_id = ?
            ORDER BY a.data DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

     // Array manual de dias da semana
    $diasSemana = [
        'Sunday'    => 'Domingo',
        'Monday'    => 'Segunda-feira',
        'Tuesday'   => 'Terça-feira',
        'Wednesday' => 'Quarta-feira',
        'Thursday'  => 'Quinta-feira',
        'Friday'    => 'Sexta-feira',
        'Saturday'  => 'Sábado'
    ];

    foreach ($agendamentos as &$agendamento) {

        // Criar timestamp juntando data e hora
        $timestamp = strtotime($agendamento['data'] . ' ' . $agendamento['hora']);

        // Pegar dia da semana em inglês
        $diaIngles = date('l', $timestamp);

        // Traduzir para português
        $diaSemana = $diasSemana[$diaIngles] ?? $diaIngles;

        // Formatar data
        $dataFormatada = date('d/m/Y', $timestamp);

        // Formatar hora
        $horaFormatada = date('H\hi', $timestamp);

        // Atualizar valores
        $agendamento['data'] = "$diaSemana, $dataFormatada";
        $agendamento['hora'] = $horaFormatada;
    }

    echo json_encode($agendamentos);

} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
?>