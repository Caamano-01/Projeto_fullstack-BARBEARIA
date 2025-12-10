<?php
require __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Receber os dados do formulário
    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["password"]);

    // Validar campos vazios (extra segurança)
    if (empty($nome) || empty($telefone) || empty($email) || empty($senha)) {
        echo "<script>alert('Preencha todos os campos!'); history.back();</script>";
        exit;
    }

    // Verificar se e-mail já existe
    $sqlCheck = "SELECT id FROM usuarios WHERE email = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); history.back();</script>";
        exit;
    }

    // Criptografar senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir novo usuário (tipo cliente)
    $sql = "INSERT INTO usuarios (nome, email, telefone, senha_hash, tipo) VALUES (?, ?, ?, ?, 'cliente')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $telefone, $senha_hash);

    if ($stmt->execute()) {
        echo "<script>
                alert('Conta criada com sucesso! Você será redirecionado para o login.');
                window.location = '../../index.html';
              </script>";
    } else {
        echo "<script>alert('Erro ao criar conta!'); history.back();</script>";
    }
}
?>