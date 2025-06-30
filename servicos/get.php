<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar administradores

//busca o id do servico
$id = isset($_GET['id']) ? $_GET['id'] : null;

try{
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM servicos WHERE id = ?");
        $stmt->execute([$id]);
        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($servico) {
            echo json_encode($servico);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'serviço não encontrado']);
        }
    } else{
        $stmt = $pdo->query("SELECT * FROM servicos");
        $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($servicos); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);

}