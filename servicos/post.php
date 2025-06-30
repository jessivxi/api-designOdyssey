<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


$titulo = $_POST['titulo'];
$descricão = $_POST['descricao'];
$categoria = $_POST['categoria'];
$preco_base = $_POST['preco_base'];
$pacotes = $_POST['pacotes'];
$data_publicaco = $_POST['data_publicacao'];

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO servico (titulo, descricao, categoria, preco_base, pacotes, data_publicacao) 
        VALUES (:titulo, :descricao, :categoria, :preco_base, :pacotes, :data_publicacao)";

try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':titulo' => $nome,
        ':descricao' => $email,
        ':categoria' => $categoria,
        ':preco_base' => $preco_base,
        ':pacotes' => $pacotes,
        ':data_publicacao' => $data_publicaco ?? $data_publicaco = date('Y-m-d H:i:s') // Define a data atual se não for fornecida
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'servico criado com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo servico
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao criar servico: ' . $e->getMessage()]);
}
?>