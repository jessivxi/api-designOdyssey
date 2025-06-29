<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar administradores

//busca o id do administrador
$id = isset($_GET['id']) ? $_GET['id'] : null;

try{
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM administradores WHERE id = ?");
        $stmt->execute([$id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            echo json_encode($admin);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Administrador não encontrado']);
        }
    } else{
        $stmt = $pdo->query("SELECT * FROM administradores");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($admins); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);

}