<?php
require_once '../conexao.php';
require_once '../headers.php';

//listar administradores

//busca o id do servico
$id = isset($_GET['id']) ? $_GET['id'] : null;

try {
    if ($id) {
        $stmt = $pdo->prepare("SELECT s.*, u.nome as nome 
                             FROM servicos s
                             LEFT JOIN usuarios u ON s.id_freelancer = u.id
                             WHERE s.id = ?");
        $stmt->execute([$id]);
        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($servico) {
            echo json_encode($servico);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'serviço não encontrado']);
        }
    } else {
        $stmt = $pdo->query("SELECT s.*, u.nome as nome 
                            FROM servicos s
                            LEFT JOIN usuarios u ON s.id_freelancer = u.id");
        $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($servicos);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);
}
