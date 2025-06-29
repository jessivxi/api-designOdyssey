<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar serviço

//busca o id do serviço
$id = isset($_GET['id']) ? $_GET['id'] : null;

try{
    if ($id) {
        $stmt = $pdo->prepare("SELECT id, id_designer, titulo, categoria, preco_base FROM servicos WHERE id = ?"); 
        $stmt->execute([$id]); //stmt= Statement = Prepara uma declaração para execução
        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($service) {
            echo json_encode($service);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'servico não encontrado']);
        }
    } else{
        $stmt = $pdo->query("SELECT  id, id_designer, titulo, categoria, preco_base  FROM servicos");
        $service = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($service); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);

}