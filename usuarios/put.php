<?php
require_once '../conexao.php';
require_once '../headers.php';

// 1. Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

// 2. Pega os dados do formulário (POST ou JSON)
$dados = $_POST;
if (empty($dados)) {
    $dados = json_decode(file_get_contents('php://input'), true);
}

// 3. Validação dos campos obrigatórios
$camposObrigatorios = ['id', 'nome', 'email', 'senha', 'tipo'];
foreach ($camposObrigatorios as $campo) {
    if (empty($dados[$campo])) {
        http_response_code(400);
        echo json_encode(['erro' => "O campo '$campo' é obrigatório"]);
        exit;
    }
}

// 4. Atualiza na tabela  (usuarios)
try {
    $sql = "UPDATE usuarios SET 
            nome = :nome, 
            email = :email, 
            senha = :senha, 
            tipo = :tipo 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $dados['nome'],
        ':email' => $dados['email'],
        ':senha' => password_hash($dados['senha'], PASSWORD_DEFAULT),
        ':tipo' => $dados['tipo'],
        ':id' => $dados['id']
    ]);
    
    echo json_encode(['mensagem' => 'Usuário atualizado com sucesso!']);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>