<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


$nome = $_POST['nome'];
$descricão = $_POST['descricao'];
$preco_base = $_POST['preco_base'];
$icone = $_POST['icone'];

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO categoria (nome, descricao, preco_base, icone) 
        VALUES (:nome, :descricao, :preco_base, :icone)";

try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricão,
        ':preco_base' => $preco_base,
        ':icone' => $icone
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'categorias criado com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo servico
        ]);
    }
} catch (PDOException $e) {-
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao listar categorias: ' . $e->getMessage()]);
}
?>