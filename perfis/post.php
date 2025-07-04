<?php
require_once '../conexao.php';
require_once '../headers.php';

// Verifica se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Use o método POST']);
    exit;
}

// Lê o corpo da requisição JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se os campos obrigatórios existem
if (
    empty($dados['nome_exibicao']) ||
    empty($dados['foto']) ||
    empty($dados['tipo']) ||
    empty($dados['id_usuarios'])
) {
    http_response_code(400);
    echo json_encode(['erro' => 'Todos os campos são obrigatórios, inclusive o ID do usuário']);
    exit;
}


try {
    // Prepara a query para inserção
    $sql = "INSERT INTO perfis (nome_exibicao, foto, tipo, id_usuarios) 
            VALUES (:nome_exibicao, :foto, :tipo, :id_usuarios)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome_exibicao' => $dados['nome_exibicao'],
        ':foto' => $dados['foto'],
        ':tipo' => $dados['tipo'],
        ':id_usuarios' => $dados['id_usuarios']
    ]);
    http_response_code(201);
    echo json_encode([
        'mensagem' => 'Perfil criado com sucesso',
        'id_perfil' => $pdo->lastInsertId() // Retorna o ID do novo perfil
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao inserir no banco: ' . $e->getMessage()]);
}
