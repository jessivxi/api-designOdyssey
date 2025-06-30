<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar administradores

//busca o id do servico
$id = isset($_GET['id']) ? $_GET['id'] : null;

try{
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM avaliacoes WHERE id = ?"); // Prepara a consulta para buscar um perfil específico
        $stmt->execute([$id]);
        $perfis = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($perifs) {
            echo json_encode($perfis);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'serviço não encontrado']);
        }
    } else{
        $stmt = $pdo->query("SELECT * FROM perfil"); // Seleciona todos os perfis
        $perfil = $stmt->fetchAll(PDO::FETCH_ASSOC); // Busca todos os perfis

        echo json_encode($servicos); 
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);

}