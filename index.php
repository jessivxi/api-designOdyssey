<?php
require_once '../headers.php';
require_once '../conexao.php';

// Roteamento baseado no método HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        require 'get.php';
        break;
        
    case 'POST':
        require 'post.php';
        break;
        
    case 'PUT':
        require 'put.php';
        break;
        
    case 'DELETE':
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