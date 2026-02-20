<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php'; 

$db = new Database();
$conn = $db->connect();

$method = $_SERVER['REQUEST_METHOD'];

// --- LÓGICA DE EXCLUSÃO (DELETE) ---
if ($method === 'DELETE') {
    try {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $id = $data['id'] ?? null;

        if (!$id) {
            echo json_encode(["success" => false, "error" => "ID não fornecido"]);
            exit;
        }

        $conn->beginTransaction();

        // buscar o usuario_id associado para excluir o login também
        $stmtSearch = $conn->prepare("SELECT usuario_id FROM profissionais WHERE id = ?");
        $stmtSearch->execute([$id]);
        $prof = $stmtSearch->fetch(PDO::FETCH_ASSOC);

        // Deletar associações de serviços (FK)
        $stmtDelRel = $conn->prepare("DELETE FROM profissional_servico WHERE profissional_id = ?");
        $stmtDelRel->execute([$id]);

        // Deletar o registro do profissional
        $stmt = $conn->prepare("DELETE FROM profissionais WHERE id = ?");
        $stmt->execute([$id]);

        // Deletar o usuário (login) se ele existir
        if ($prof && $prof['usuario_id']) {
            $stmtUser = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmtUser->execute([$prof['usuario_id']]);
        }

        $conn->commit();
        echo json_encode(["success" => true]);
        exit;
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
        exit;
    }
}

// --- LÓGICA DE CRIAÇÃO / EDIÇÃO (POST) ---
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
    $email = $data['email'] ?? null;
    $senha = $data['senha'] ?? null;
    $contato = $data['whatsapp']; 
    $foto_url = $data['foto_url'];
    $servicos = isset($data['servicos']) ? $data['servicos'] : [];

    if (!$id) {
        // Criar o Usuário Admin
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmtUser = $conn->prepare("INSERT INTO usuarios (nome, email, senha_hash, telefone, tipo) VALUES (?, ?, ?, ?, 'admin')");
        $stmtUser->execute([$nome, $email, $senha_hash, $contato]);
        $usuario_id = $conn->lastInsertId();

        // Criar o Profissional vinculado a esse usuario_id
        $stmt = $conn->prepare("INSERT INTO profissionais (nome, contato, foto_url, usuario_id, ativo) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$nome, $contato, $foto_url, $usuario_id]);
        $profissional_id = $conn->lastInsertId();
    } else {
        // MODO EDIÇÃO
        $stmt = $conn->prepare("UPDATE profissionais SET nome = ?, contato = ?, foto_url = ? WHERE id = ?");
        $stmt->execute([$nome, $contato, $foto_url, $id]);
        
        $stmtDel = $conn->prepare("DELETE FROM profissional_servico WHERE profissional_id = ?");
        $stmtDel->execute([$id]);
        $profissional_id = $id;
    }

    // Insere as especialidades (serviços)
    if (!empty($servicos)) {
        $stmtServ = $conn->prepare("INSERT INTO profissional_servico (profissional_id, servico_id) VALUES (?, ?)");
        foreach ($servicos as $servico_id) {
            $stmtServ->execute([$profissional_id, $servico_id]);
        }
    }

    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>