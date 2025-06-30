<?php
// Conexão com o banco
require_once '../conexao.php';
require_once '../headers.php'; // Para definir Content-Type JSON, CORS, etc.

// Somente aceita requisição PUT
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

// Lê o corpo da requisição PUT
// $input = json_decode(file_get_contents('php://input'), true);
// var_dump($input);
// exit;


// Validação simples
$id = $_POST['id'] ?? null;
$titulo = $_POST['titulo'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$preco_base = $_POST['preco_base'] ?? null;
$icone = $_POST['icone'] ?? null;


if (!isset($id) || !isset($titulo) || !isset($descricao) || !isset($preco_base) || !isset($icone) ) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes']);
    exit;
}

if (!$id || !$titulo || !$descricao || !$preco_base || !$icone) { 
    http_response_code(400);
    echo json_encode(['erro' => 'Todos os campos são obrigatórios']);
    exit;
}

// Atualiza no banco
try {
    $sql = "UPDATE categoria SET nome = :titulo, descricao = :descricao, icone = :icone WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':icone' => $icone,
        ':id' => $id
    ]);

    echo json_encode(['mensagem' => 'Administrador atualizado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>