<?php
session_start();

require __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha = trim($_POST["password"]);

    // Buscar usuário
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha_hash'])) {

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redireciona conforme o tipo
            if ($usuario['tipo'] === 'admin') {
                header("Location: ..");
                exit;
            } else {
                header("Location: ..");
                exit;
            }

        } else {
            echo "Senha incorreta!";
        }

    } else {
        echo "Email não encontrado!";
    }
}
?>
