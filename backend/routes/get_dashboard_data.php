<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$response = [
    "servicos" => ["nomes" => [], "valores" => []],
    "clientes" => ["nomes" => [], "valores" => []],
    "hoje" => []
];

try {
    // BUSCAR SERVIÇOS MAIS USADOS
    $sqlServicos = "SELECT servico_nome, COUNT(*) as total 
                    FROM agendamentos 
                    GROUP BY servico_nome 
                    ORDER BY total DESC LIMIT 4";
    $resServicos = $conn->query($sqlServicos);
    while($row = $resServicos->fetch_assoc()) {
        $response["servicos"]["nomes"][] = $row['servico_nome'];
        $response["servicos"]["valores"][] = (int)$row['total'];
    }

    // BUSCAR CLIENTES QUE MAIS FREQUENTAM
    $sqlClientes = "SELECT cliente_nome, COUNT(*) as visitas 
                    FROM agendamentos 
                    GROUP BY cliente_nome 
                    ORDER BY visitas DESC LIMIT 5";
    $resClientes = $conn->query($sqlClientes);
    while($row = $resClientes->fetch_assoc()) {
        $response["clientes"]["nomes"][] = $row['cliente_nome'];
        $response["clientes"]["valores"][] = (int)$row['visitas'];
    }

    // BUSCAR AGENDAMENTOS DE HOJE
    // CURDATE() pega a data atual do servidor
    $sqlHoje = "SELECT cliente_nome, servico_nome, profissional_nome, hora_agendamento 
                FROM agendamentos 
                WHERE data_agendamento = CURDATE() 
                ORDER BY hora_agendamento ASC";
    $resHoje = $conn->query($sqlHoje);
    while($row = $resHoje->fetch_assoc()) {
        $response["hoje"][] = [
            "cliente" => $row['cliente_nome'],
            "servico" => $row['servico_nome'],
            "profissional" => $row['profissional_nome'],
            "hora" => $row['hora_agendamento']
        ];
    }

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(["erro" => $e->getMessage()]);
}
?>