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
$categoria = $_POST['categoria'] ?? null;
$preco_base = $_POST['preco_base'] ?? null;
$pacotes = $_POST['pacotes'] ?? null;
$data_publicacao = $_POST['data_publicacao'] ?? null;


if (!isset($id) || !isset($titulo) || !isset($descricao) || !isset($categoria) || !isset($preco_base) || !isset($pacotes) || !isset($data_publicacao)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes']);
    exit;
}

if (!$id || !$titulo || !$descricao || !$categoria || !$preco_base || !$pacotes || !$data_publicacao) {
    http_response_code(400);
    echo json_encode(['erro' => 'Todos os campos são obrigatórios']);
    exit;
}

// Atualiza no banco
try {
    $sql = "UPDATE servico SET titulo = :titulo, descricao = :descricao, categoria = :categoria, preco_base = :preco_base, pacotes = :pacotes, data_publicacao = :data_publicacao WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':categoria' => $categoria,
        ':preco_base' => $preco_base,
        ':pacotes' => $pacotes,
        ':data_publicacao' => $data_publicacao,
        ':id' => $id
    ]);

    echo json_encode(['mensagem' => 'Administrador atualizado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>