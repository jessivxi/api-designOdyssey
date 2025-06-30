<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


$nota = $_POST['nota'];
$comentario = $_POST['comentario'];
$data_avaliacao = $_POST['data_avaliacao'];
$resposta_designer = $_POST['resposta_designer'];

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO avaliacoes (nota, comentario, data_avaliacao, resposta_designer) 
        VALUES (:nota, :comentario, :data_avaliacao, :resposta_designer)";
try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':nota' => $nota,
        ':comentario' => $comentario,
        ':data_avaliacao' => $data_avaliacao ?? date('Y-m-d H:i:s'), // Define a data atual se não for fornecida
        ':resposta_designer' => $resposta_designer ?? null // Permite que seja nulo se não for fornecido
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'avaliaçoes criada com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo servico
        ]);
    }
} catch (PDOException $e) {-
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao listar avaliaçoes: ' . $e->getMessage()]);
}
?>