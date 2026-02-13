<?php
session_start();
header('Content-Type: application/json');

// Verifica se existe a sessão e se o tipo é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo json_encode(['autorizado' => false]);
    exit;
}

echo json_encode(['autorizado' => true]);
?>