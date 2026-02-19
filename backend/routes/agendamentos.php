<?php
require_once __DIR__ . '/../config/db.php';

// Define que o retorno será um JSON
header('Content-Type: application/json');

// Captura a data enviada via GET
$dataFiltro = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

try {
    // Instancia a classe Database e obtém a conexão
    $database = new Database();
    $db = $database->connect();

    // Consulta SQL com JOIN para buscar nomes
    $sql = "SELECT 
                a.id, 
                a.data, 
                a.hora, 
                c.nome AS cliente_nome, 
                s.nome AS servico_nome, 
                p.nome AS profissional_nome
            FROM agendamentos a
            INNER JOIN clientes c ON a.cliente_id = c.id
            INNER JOIN servicos s ON a.servico_id = s.id
            INNER JOIN profissionais p ON a.profissional_id = p.id
            WHERE a.data = :data_agenda
            ORDER BY a.hora ASC";

    // Prepara e executa a query com segurança
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':data_agenda', $dataFiltro);
    $stmt->execute();

    // Transforma os dados em um Array
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Envia os dados para o JavaScript
    echo json_encode($agendamentos);

} catch (PDOException $e) {
    // Em caso de erro, retorna a mensagem em formato JSON
    echo json_encode(["error" => "Erro ao listar agendamentos: " . $e->getMessage()]);
}
?>