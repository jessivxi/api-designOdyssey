<?php
require_once '../conexao.php';
require_once '../headers.php';

header('Content-Type: application/json');

// Recebe dados do corpo da requisição (JSON)
$dados = json_decode(file_get_contents('php://input'), true);

// Validação
if (empty($dados['nome'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'O campo "nome" é obrigatório']);
    exit;
}

try {
    $sql = "INSERT INTO categoria (nome, descricao, preco_base, icone) 
            VALUES (:nome, :descricao, :preco_base, :icone)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $dados['nome'],
        ':descricao' => $dados['descricao'] ?? '',
        ':preco_base' => $dados['preco_base'] ?? null,
        ':icone' => $dados['icone'] ?? 'padrao.jpg' // Valor padrão se não for enviado
    ]);

    http_response_code(201);
    echo json_encode([
        'mensagem' => 'Categoria criada com sucesso',
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
}