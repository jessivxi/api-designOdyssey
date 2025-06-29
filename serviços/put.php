<?php
//atualiza um administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';

// 1. Pegar o ID da URL (ex: /administrador/5)
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID não fornecido']);
    exit;
}

// 2. Ler os dados do corpo da requisição
$dados = json_decode(file_get_contents('php://input'), true);

// 3. Validar campos (opcional: só atualiza campos enviados)
if (empty($dados)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Nenhum dado fornecido para atualização']);
    exit;
}  
// 4. Construir a query dinamicamente (atualiza apenas campos enviados)
$campos = [];
$valores = [':id' => $id];

foreach ($dados as $campo => $valor) {
    // Não permitir atualização do ID (por segurança)
    if ($campo === 'id') continue;
    
    if ($campo === 'senha') {
        // Se for senha, aplicar hash
        $valores[':senha'] = password_hash($valor, PASSWORD_DEFAULT);
        $campos[] = "senha = :senha";
    } else {
        $valores[":$campo"] = $valor;
        $campos[] = "$campo = :$campo";
    }
}

if (empty($campos)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Nenhum campo válido para atualização']);
    exit;
}

// 5. Executar a atualização
try {
    $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($valores);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['mensagem' => 'usuario atualizado']);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Nenhum registro encontrado ou dados idênticos']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Falha na atualização: ' . $e->getMessage()]);
}
?>