<?php
session_start();
header('Content-Type: application/json');

include_once '../config/db.php';

$database = new Database();
$db = $database->connect();

// VERIFICAÇÃO DE LOGIN
// Se não tiver sessão, retorna erro (ajuste conforme seu sistema de login)
if (!isset($_SESSION['user_id'])) {
    // PARA TESTES: Se quiser testar sem logar, descomente a linha abaixo e coloque um ID válido do seu banco
    // $_SESSION['user_id'] = 1; 
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
        exit;
    }
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

// 1. GET: BUSCAR DADOS DO USUÁRIO
if ($method === 'GET') {
    $query = "SELECT nome, email, telefone FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $user]);
    exit;
}

// 2. POST: ATUALIZAR DADOS
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->nome) && !empty($data->email)) {
        $query = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':nome', $data->nome);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':telefone', $data->telefone); // Agora salva o telefone
        $stmt->bindParam(':id', $user_id);

        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar dados.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Preencha os campos obrigatórios.']);
    }
    exit;
}

// 3. DELETE: APAGAR CONTA
if ($method === 'DELETE') {
    $query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);

    if($stmt->execute()) {
        session_destroy(); // Destroi a sessão (desloga)
        echo json_encode(['success' => true, 'message' => 'Conta excluída com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir conta.']);
    }
    exit;
}
?>