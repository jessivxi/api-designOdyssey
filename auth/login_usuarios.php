<?php
session_start();
require '../conexao.php';

header("Content-Type: application/json"); // Importante para comunicação com frontend

// Recebe dados do POST (modo JSON)
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

// Validação básica
if (empty($email) || empty($senha)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

// Busca usuário no banco
$stmt = $pdo->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verifica credenciais (CORREÇÃO IMPORTANTE: compare com senha do banco, não com hash novo)
if ($user && password_verify($senha, $user['senha'])) {
    // Cria sessão
    $_SESSION['logado'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nome'] = $user['nome'];
    $_SESSION['user_tipo'] = $user['tipo'];
    
    // Retorna JSON para o frontend (não usa header Location)
    echo json_encode([
        'success' => true,
        'redirect' => '/perfil', // Ou determine dinamicamente com base no tipo
        'user' => [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'tipo' => $user['tipo']
        ]
    ]);
    exit;
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);
    exit;
}
?>