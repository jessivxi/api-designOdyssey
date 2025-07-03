<?php
require_once '../conexao.php';
require_once '../headers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido. Use PUT']);
    exit;
}

// Lê os dados JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se o ID foi enviado
if (empty($dados['id_categoria'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'O ID da categoria é obrigatório']);
    exit;
}

// Verifica se os outros campos foram enviados
$campos = ['nome', 'descricao', 'preco_base', 'icone'];
foreach ($campos as $campo) {
    if (empty($dados[$campo])) {
        http_response_code(400);
        echo json_encode(['erro' => "O campo '$campo' é obrigatório"]);
        exit;
    }
}

// Atualiza a categoria
try {
    $sql = "UPDATE categoria SET 
                nome = :nome,
                descricao = :descricao,
                preco_base = :preco_base,
                icone = :icone
            WHERE id_categoria = :id_categoria";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $dados['nome'],
        ':descricao' => $dados['descricao'],
        ':preco_base' => $dados['preco_base'],
        ':icone' => $dados['icone'],
        ':id_categoria' => $dados['id_categoria']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['sucesso' => 'Categoria atualizada com sucesso']);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Categoria não encontrada ou dados iguais']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
