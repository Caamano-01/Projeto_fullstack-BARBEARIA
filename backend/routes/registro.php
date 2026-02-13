<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Instancia a conexão usando a classe Database
    $database = new Database();
    $db = $database->connect();

    // Receber os dados do formulário
    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["password"]);

    // Validar campos vazios
    if (empty($nome) || empty($telefone) || empty($email) || empty($senha)) {
        echo "<script>alert('Preencha todos os campos!'); history.back();</script>";
        exit;
    }

    try {
        // Verificar se e-mail já existe usando PDO
        $sqlCheck = "SELECT id FROM usuarios WHERE email = :email";
        $stmtCheck = $db->prepare($sqlCheck);
        $stmtCheck->bindParam(':email', $email);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            echo "<script>alert('Este e-mail já está cadastrado!'); history.back();</script>";
            exit;
        }

        // Criptografar senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir novo usuário usando PDO
        $sql = "INSERT INTO usuarios (nome, email, telefone, senha_hash, tipo) VALUES (:nome, :email, :telefone, :senha_hash, 'cliente')";
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':senha_hash', $senha_hash);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Conta criada com sucesso! Faça login para continuar.');
                    window.location.href = '../../index.html';
                  </script>";
        } else {
            echo "<script>alert('Erro ao criar conta!'); history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('Erro no banco de dados: " . $e->getMessage() . "'); history.back();</script>";
    }
}
?>