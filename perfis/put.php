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
$nome_exibicão = $_POST['perfil'] ?? null;
$foto = $_POST['foto'] ?? null;
$tipo = $_POST['tipo'] ?? null;



if (!isset($id) || !isset($nome_exibicão) || !isset($foto) || !isset($tipo)  ) {
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
        ':nome_exibicão' => $nome_exibicão,
        ':foto' => $foto,
        ':tipo' => $tipo,
        ':id' => $id
    ]);

    echo json_encode(['mensagem' => 'perfil atualizado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>