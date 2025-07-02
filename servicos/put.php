<?php
require_once '../conexao.php';
require_once '../headers.php';

// Verifica se é PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido. Use PUT']);
    exit;
}

// Recebe os dados JSON do corpo da requisição
$input = file_get_contents('php://input');
$dados = json_decode($input, true);

// Verificação básica dos campos
if (empty($dados['id'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'O ID do serviço é obrigatório']);
    exit;
}

// Campos mínimos necessários
$camposNecessarios = ['titulo', 'descricao', 'categoria', 'preco_base'];
foreach ($camposNecessarios as $campo) {
    if (empty($dados[$campo])) {
        http_response_code(400);
        echo json_encode(['erro' => "O campo '$campo' é obrigatório"]);
        exit;
    }
}

try {
    $sql = "UPDATE servicos SET 
            titulo = :titulo, 
            descricao = :descricao, 
            categoria = :categoria, 
            preco_base = :preco_base
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $dados['titulo'],
        ':descricao' => $dados['descricao'],
        ':categoria' => $dados['categoria'],
        ':preco_base' => $dados['preco_base'],
        ':id' => $dados['id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['sucesso' => 'Serviço atualizado com PUT!']);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Nenhum serviço encontrado com este ID']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>