<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$dataFiltro = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

try {

    $database = new Database();
    $db = $database->connect();

    $sql = "SELECT 
                a.id,
                a.data,
                a.hora,
                a.usuario_id,
                a.servico_id,
                a.profissional_id
            FROM agendamentos a
            WHERE a.data = :data_agenda
            ORDER BY a.hora ASC";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':data_agenda', $dataFiltro);
    $stmt->execute();

    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // GARANTE QUE SEMPRE É ARRAY
    if(!$agendamentos){
        $agendamentos = [];
    }

    echo json_encode($agendamentos);

} catch (Exception $e) {

    // SEMPRE RETORNA ARRAY
    echo json_encode([]);

}
?>