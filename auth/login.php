<?php

// função para Autenticar usuários
require_once '../conexao.php';
require_once '../headers.php';

$dados = json_decode(file_get_contents('php://input'), true);

// Validação básica
if (empty($dados['email']) || empty($dados['senha'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Email e senha são obrigatórios']);
    exit;
}

try {
    // Busca usuário no banco (clientes ou administradores)
    $stmt = $pdo->prepare("
        SELECT id, nome, email, senha, tipo 
        FROM usuarios 
        WHERE email = ?
        UNION
        SELECT id, nome, email, senha, 'admin' as tipo
        FROM administradores
        WHERE email = ?
    ");
    
    $stmt->execute([$dados['email'], $dados['email']]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($dados['senha'], $usuario['senha'])) {
        http_response_code(401);
        echo json_encode(['erro' => 'Credenciais inválidas']);
        exit;
    }

    // Gera token simples (em produção, use JWT)
    $token = base64_encode(random_bytes(32));
    $expiracao = time() + 3600; // 1 hora

    // Retorna dados do usuário + token
    echo json_encode([
        'token' => $token,
        'expiracao' => $expiracao,
        'usuario' => [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'tipo' => $usuario['tipo']
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no servidor']);
}
?>