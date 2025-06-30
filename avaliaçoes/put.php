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
$nota = $_POST['nota'];
$comentario = $_POST['comentario'];
$data_avaliacao = $_POST['data_avaliacao'];
$resposta_designer = $_POST['resposta_designer'];


if (!isset($nota) || !isset($comentario) || !isset($data_avaliacao) || !isset($resposta_designer)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes']);
    exit;
}

if (!$nota || !$comentario || !$data_avaliacao || !$resposta_designer) { 
    http_response_code(400);
    echo json_encode(['erro' => 'Todos os campos são obrigatórios']);
    exit;
}

// Atualiza no banco
try {
    $sql = "UPDATE avaliacoes SET nota = :nota, comentario = :comentario, data_avaliacao = :data_avaliacao, resposta_designer = :resposta_designer WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nota' => $nota,
        ':comentario' => $comentario,
        ':data_avaliacao' => $data_avaliacao,
        ':resposta_designer' => $resposta_designer,
        ':id' => $id
    ]);

    echo json_encode(['mensagem' => 'avaliaçoes atualizado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>