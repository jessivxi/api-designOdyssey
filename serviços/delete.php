<?php
//deleta um administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';

try {
    $dados = json_decode(file_get_contents('php://input'), true);

    // Autenticação 
    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!hash_equals('TOKEN_SEGURO_AQUI', $token)) {
        http_response_code(403);
        echo json_encode(['erro' => 'Acesso não autorizado']);
        exit;
    }

    // Validação do ID
    if (!isset($dados['id']) || !is_numeric($dados['id']) || $dados['id'] <= 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'ID inválido ou não informado']);
        exit;
    }

    // Verifica existência antes de deletar
    $check = $pdo->prepare("SELECT id FROM servico WHERE id = ?");
    $check->execute([$dados['id']]);
    
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['erro' => 'serviço não encontrado']);
        exit;
    }

    // Executa a exclusão
    $stmt = $pdo->prepare("DELETE FROM servico WHERE id = ?");
    
    if ($stmt->execute([$dados['id']])) {
        http_response_code(200);
        echo json_encode(['sucesso' => 'serviço removido com sucesso']);
    } else {
        http_response_code(500);
        echo json_encode(['erro' => 'Falha ao remover serviço']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>