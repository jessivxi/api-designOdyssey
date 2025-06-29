<?php
//cria um novo administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';


$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$nivel_acesso = $_POST['nivel_acesso'];

//isset() = detecta se o cliente esqueceu de enviar o campo
//empty() = não distingue entre não enviado e enviado vazio

// 3. Preparar a query SQL (PROTEJA CONTRA SQL INJECTION!)
$sql = "INSERT INTO administradores (nome, email, senha, nivel_acesso) 
        VALUES (:nome, :email, :senha, :nivel_acesso)";

// 4. Hash da senha (
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare($sql);
    
    // 5. Executar a query com os parâmetros
    $sucesso = $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':nivel_acesso' => $nivel_acesso ?? $nivel_acesso = 'suporte'
    ]);

    if ($sucesso) {
        http_response_code(201);
        echo json_encode([
            'mensagem' => 'Administrador criado com sucesso',
            'id' => $pdo->lastInsertId() // Retorna o ID do novo administrador
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao criar administrador: ' . $e->getMessage()]);
}
?>