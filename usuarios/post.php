<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


// 1. Pegar os dados enviados no corpo da requisição
$dados = json_decode(file_get_contents('php://input'), true);

// 2. Validar campos obrigatórios 
if (!isset($dados['nome']) || !isset($dados['email']) || !isset($dados['senha'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Campos obrigatórios faltando']);
    exit;
}

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO usuarios (nome, email, senha, tipo, ) 
        VALUES (:nome, :email, :senha, :tipo, )";

// 4. Hash da senha (
$senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':nome' => $dados['nome'],
        ':email' => $dados['email'],
        ':senha' => $senhaHash,
        ':tipo' => $dados['tipo'] ?? 'designer','cliente'
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'cliente criado com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo administrador
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao criar cliente: ' . $e->getMessage()]);
}
?>