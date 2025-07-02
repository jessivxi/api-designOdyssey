<?php
require_once '../conexao.php';
require_once '../headers.php';

// Forçar o header JSON
header('Content-Type: application/json');

// Listar categorias
$id = isset($_GET['id']) ? $_GET['id'] : null;

try {
    if ($id) {
        // Busca categoria específica
        $stmt = $pdo->prepare("SELECT * FROM categoria WHERE id_categoria = ?");
        $stmt->execute([$id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($categoria) {
            echo json_encode($categoria);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Categoria não encontrada']);
        }
    } else {
        // Lista todas as categorias
        $stmt = $pdo->query("SELECT * FROM categoria");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($categorias); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);
}