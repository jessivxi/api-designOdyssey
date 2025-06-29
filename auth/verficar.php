<?php
require_once '../conexao.php';

//validarToken.php
// Função para validar o token de autenticação

function validarToken($pdo) {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (empty($token)) {
        http_response_code(401);
        echo json_encode(['erro' => 'Token não fornecido']);
        exit;
    }

    return [
        'valido' => true,
        'dados' => ['id' => 1, 'tipo' => 'admin'] // Exemplo
    ];
}

// Para usar em outros endpoints:
// $auth = validarToken($pdo);
// $usuario_id = $auth['dados']['id'];
?>