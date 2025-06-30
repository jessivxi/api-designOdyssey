<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar categorias

//busca o id do servico
$id = isset($_GET['id']) ? $_GET['id'] : null;

try{
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->execute([$id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($categoria) {
            echo json_encode($categoria);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Categorias não encontrado']);
        }
    } else{
        $stmt = $pdo->query("SELECT * FROM categoria");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($categoria); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);

}