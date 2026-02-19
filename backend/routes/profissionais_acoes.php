<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php'; 

$db = new Database();
$conn = $db->connect();

// Lê o corpo da requisição JSON
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "Dados inválidos ou não recebidos"]);
    exit;
}

try {
    $conn->beginTransaction();

    $id = !empty($data['id']) ? $data['id'] : null;
    $nome = $data['nome'];
    $contato = $data['whatsapp']; // Recebe 'whatsapp' do JS e guarda como contato
    $foto_url = $data['foto_url'];
    $servicos = isset($data['servicos']) ? $data['servicos'] : [];

    if ($id) {
        // MODO EDIÇÃO: Usa a coluna 'contato' conforme o seu SQL
        $stmt = $conn->prepare("UPDATE profissionais SET nome = ?, contato = ?, foto_url = ? WHERE id = ?");
        $stmt->execute([$nome, $contato, $foto_url, $id]);

        // Limpa especialidades antigas
        $stmtDel = $conn->prepare("DELETE FROM profissional_servico WHERE profissional_id = ?");
        $stmtDel->execute([$id]);
        $profissional_id = $id;
    } else {
        // MODO CRIAÇÃO: Usa a coluna 'contato'
        $stmt = $conn->prepare("INSERT INTO profissionais (nome, contato, foto_url, ativo) VALUES (?, ?, ?, 1)");
        $stmt->execute([$nome, $contato, $foto_url]);
        $profissional_id = $conn->lastInsertId();
    }

    // Insere as especialidades na tabela intermediária
    if (!empty($servicos)) {
        $stmtServ = $conn->prepare("INSERT INTO profissional_servico (profissional_id, servico_id) VALUES (?, ?)");
        foreach ($servicos as $servico_id) {
            $stmtServ->execute([$profissional_id, $servico_id]);
        }
    }

    $conn->commit();
    echo json_encode(["success" => true, "id" => $profissional_id]);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(["success" => false, "error" => "Erro no Banco: " . $e->getMessage()]);
}
?>