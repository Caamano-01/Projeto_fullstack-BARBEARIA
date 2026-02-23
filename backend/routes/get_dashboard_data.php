<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$response = [
    "servicos" => ["nomes" => [], "valores" => []],
    "clientes" => ["nomes" => [], "valores" => []],
    "hoje" => []
];

try {
    $database = new Database();
    $conn = $database->connect();

    // ==============================
    // SERVIÇOS MAIS UTILIZADOS
    // ==============================
    $sqlServicos = "SELECT s.nome AS servico_nome, COUNT(a.id) as total 
                    FROM agendamentos a
                    JOIN servicos s ON a.servico_id = s.id
                    GROUP BY s.id 
                    ORDER BY total DESC 
                    LIMIT 4";

    $stmt = $conn->prepare($sqlServicos);
    $stmt->execute();
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($servicos as $row) {
        $response["servicos"]["nomes"][] = $row['servico_nome'];
        $response["servicos"]["valores"][] = (int)$row['total'];
    }

    // ==============================
    // CLIENTES MAIS FREQUENTES
    // ==============================
    $sqlClientes = "SELECT u.nome AS cliente_nome, COUNT(a.id) as visitas 
                    FROM agendamentos a
                    JOIN usuarios u ON a.usuario_id = u.id
                    GROUP BY u.id 
                    ORDER BY visitas DESC 
                    LIMIT 5";

    $stmt = $conn->prepare($sqlClientes);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($clientes as $row) {
        $response["clientes"]["nomes"][] = $row['cliente_nome'];
        $response["clientes"]["valores"][] = (int)$row['visitas'];
    }

    // ==============================
    // AGENDAMENTOS DE HOJE
    // ==============================
    $sqlHoje = "SELECT 
                    u.nome as cliente_nome, 
                    s.nome as servico_nome, 
                    p.nome as profissional_nome, 
                    TIME_FORMAT(a.hora, '%H:%i') as hora_formatada
                FROM agendamentos a
                JOIN usuarios u ON a.usuario_id = u.id
                JOIN servicos s ON a.servico_id = s.id
                JOIN profissionais p ON a.profissional_id = p.id
                WHERE a.data = CURDATE()
                ORDER BY a.hora ASC";

    $stmt = $conn->prepare($sqlHoje);
    $stmt->execute();
    $hoje = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($hoje as $row) {
        $response["hoje"][] = [
            "cliente" => $row['cliente_nome'],
            "servico" => $row['servico_nome'],
            "profissional" => $row['profissional_nome'],
            "hora" => $row['hora_formatada']
        ];
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "erro" => true,
        "mensagem" => $e->getMessage()
    ]);
}
?>