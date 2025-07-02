<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo'];

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO usuarios (nome, email, senha, tipo) 
        VALUES (:nome, :email, :senha, :tipo)";

// 4. Hash da senha (
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
 
try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':tipo' => $tipo ?? $tipo = 'cliente'
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'usuario criado com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo administrador
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao criar usuario: ' . $e->getMessage()]);
}
?>