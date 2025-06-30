<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


$nome_exibicao = $_POST['nome_exibicao'];
$foto = $_POST['foto'];
$tipo = $_POST['tipo'];

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO perfil (nome_exibicao, foto, tipo) 
        VALUES (:nome_exibicao, :foto, :tipo)";

try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':nome_exibicao' => $nome_exibicao,
        ':foto' => $foto,
        ':tipo' => $tipo
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'perfil criado com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo servico
        ]);
    }
} catch (PDOException $e) {-
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao listar perfil: ' . $e->getMessage()]);
}
?>