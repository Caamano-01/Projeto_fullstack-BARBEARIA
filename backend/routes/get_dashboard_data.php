<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$response = [
    "servicos" => ["nomes" => [], "valores" => []],
    "clientes" => ["nomes" => [], "valores" => []],
    "hoje" => []
];

try {

    if (!$conn) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // --- SERVIÇOS MAIS UTILIZADOS ---
    $sqlServicos = "SELECT s.nome AS servico_nome, COUNT(a.id) as total 
                    FROM agendamentos a
                    JOIN servicos s ON a.servico_id = s.id
                    GROUP BY s.id 
                    ORDER BY total DESC LIMIT 4";
    
    $resServicos = $conn->query($sqlServicos);
    if (!$resServicos) throw new Exception($conn->error);

    while($row = $resServicos->fetch_assoc()) {
        $response["servicos"]["nomes"][] = $row['servico_nome'];
        $response["servicos"]["valores"][] = (int)$row['total'];
    }

    // --- CLIENTES MAIS FREQUENTES ---
    $sqlClientes = "SELECT u.nome AS cliente_nome, COUNT(a.id) as visitas 
                    FROM agendamentos a
                    JOIN usuarios u ON a.usuario_id = u.id
                    GROUP BY u.id 
                    ORDER BY visitas DESC LIMIT 5";
                    
    $resClientes = $conn->query($sqlClientes);
    if (!$resClientes) throw new Exception($conn->error);

    while($row = $resClientes->fetch_assoc()) {
        $response["clientes"]["nomes"][] = $row['cliente_nome'];
        $response["clientes"]["valores"][] = (int)$row['visitas'];
    }

    // --- AGENDAMENTOS DE HOJE ---
    $sqlHoje = "SELECT u.nome as cliente_nome, 
                       s.nome as servico_nome, 
                       p.nome as profissional_nome, 
                       TIME_FORMAT(a.hora, '%H:%i') as hora_formatada
                FROM agendamentos a
                JOIN usuarios u ON a.usuario_id = u.id
                JOIN servicos s ON a.servico_id = s.id
                JOIN profissionais p ON a.profissional_id = p.id
                WHERE a.data = CURDATE() 
                ORDER BY a.hora ASC";

    $resHoje = $conn->query($sqlHoje);
    if (!$resHoje) throw new Exception($conn->error);

    while($row = $resHoje->fetch_assoc()) {
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