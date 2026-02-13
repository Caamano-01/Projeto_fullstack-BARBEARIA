<?php
session_start();

require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $database = new Database();
    $db = $database->connect();

    $email = trim($_POST["email"]);
    $senha = trim($_POST["password"]);

    try {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if (password_verify($senha, $usuario['senha_hash'])) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $usuario['tipo'];

                if ($usuario['tipo'] === 'admin') {
                    header("Location: ../../public/admin/dashboard_admin.html");
                    exit;
                } else {
                    header("Location: ../../public/user/home.html");
                    exit;
                }

            } else {
                echo "<script>
                    alert('Senha incorreta! Tente novamente.');
                    window.location.href = '../../index.html';
                </script>";
                exit;
            }
        } else {
            echo "<script>
                alert('E-mail não encontrado em nossa base de dados.');
                window.location.href = '../../index.html';
            </script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro no servidor. Tente novamente mais tarde.');
            window.location.href = '../../index.html';
        </script>";
        exit;
    }
}
?>