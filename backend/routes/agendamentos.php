<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

// Obtém a data do filtro ou usa a data atual
$dataFiltro = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

try {
    $database = new Database();
    $db = $database->connect();

    $sql = "SELECT 
                a.id,
                a.data,
                a.hora,
                u.nome AS cliente_nome,
                s.nome AS servico_nome,
                p.nome AS profissional_nome
            FROM agendamentos a
            INNER JOIN usuarios u ON a.usuario_id = u.id
            INNER JOIN servicos s ON a.servico_id = s.id
            INNER JOIN profissionais p ON a.profissional_id = p.id
            WHERE a.data = :data_agenda
            ORDER BY a.hora ASC";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':data_agenda', $dataFiltro);
    $stmt->execute();

    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($agendamentos);

} catch (Exception $e) {
    // Em caso de erro técnico, retorna erro para o console do navegador
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>