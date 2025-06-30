<?php
require_once '../headers.php';
require_once '../conexao.php';

// Roteamento baseado no método HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // GET /administrador/ - Lista todos
        // GET /administrador/?id=1 - Busca específico
        require 'get.php';
        break;
        
    case 'POST':
        // POST /administrador/ - Cria novo admin
        // Requer corpo JSON com dados do admin
        require 'post.php';
        break;
        
    case 'PUT':
        // PUT /administrador/ - Atualiza admin existente
        // Requer corpo JSON com dados e ID
        require 'put.php';
        break;
        
    case 'DELETE':
        // DELETE /administrador/ - Remove admin
        // Requer corpo JSON com ID e token de autorização
        require 'delete.php';
        break;
        
    default:
        // Método não permitido
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Método não permitido',
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE']
        ]);
        break;
}
?>