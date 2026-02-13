<?php 
session_start();
header('Content-Type: application/json');

// verifica se o usuário está logado e retorna os dados
if (isset($SESSION['usuario_id'])) {
    echo json_encode ([
        'logado' => true,
        'nome' => $_SESSION['usuario_nome'],
        'tipo' => $SESSION['usuario_tipo']
    ]);
} else {
    echo json_encode([
        'logado' => false
    ])
}
?>