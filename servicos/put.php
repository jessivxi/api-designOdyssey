<?php
require_once '../conexao.php';
require_once '../headers.php';

// Permite apenas requisição PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['erro' => 'Use o método PUT']);
    exit;
}

// Lê os dados recebidos em JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se o ID e os principais campos foram enviados
if (
    empty($dados['id']) ||
    empty($dados['titulo']) ||
    empty($dados['descricao']) ||
    empty($dados['categoria']) ||
    empty($dados['preco_base'])
) {
    http_response_code(400);
    echo json_encode(['erro' => 'Preencha todos os campos obrigatórios']);
    exit;
}

// Atualiza no banco de dados
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

    echo json_encode([
        'mensagem' => $stmt->rowCount() > 0
            ? 'Serviço atualizado com sucesso'
            : 'Nada foi alterado ou serviço não encontrado'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar']);
}
?>
