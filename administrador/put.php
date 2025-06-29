<?php
// Conexão com o banco
require_once '../conexao.php';
require_once '../headers.php'; // Para definir Content-Type JSON, CORS, etc.

// Somente aceita requisição PUT
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

// Lê o corpo da requisição PUT
// $input = json_decode(file_get_contents('php://input'), true);
// var_dump($input);
// exit;


// Validação simples
$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$email = $_POST['email'] ?? null;
$senha = $_POST['senha'] ?? null;
$nivel_acesso = $_POST['nivel_acesso'] ?? null;

if (!isset($id) || !isset($nome) || !isset($email) || !isset($senha) || !isset($nivel_acesso) ) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes']);
    exit;
}

if (!$id || !$nome || !$email || !$senha || !$nivel_acesso) {
    http_response_code(400);
    echo json_encode(['erro' => 'Todos os campos são obrigatórios']);
    exit;
}

// Atualiza no banco
try {
    $sql = "UPDATE administradores SET nome = :nome, email = :email, senha = :senha, nivel_acesso = :nivel_acesso WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT), // Sempre salvar senha criptografada
        ':nivel_acesso' => $nivel_acesso,
        ':id' => $id
    ]);

    echo json_encode(['mensagem' => 'Administrador atualizado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}
?>