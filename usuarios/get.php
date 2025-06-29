<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar usuarios

//busca o id do usuario
$id = isset($_GET['id']) ? $_GET['id'] : null;

try{
    if ($id) {
        $stmt = $pdo->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuarios = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarios) {
            echo json_encode($usuarios);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'usuarios não encontrado']);
        }
    } else{
        $stmt = $pdo->query("SELECT id, nome, email, tipo FROM usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);

}