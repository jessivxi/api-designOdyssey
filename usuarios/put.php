<?php
//atualiza um administrador
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';
require_once '../headers.php';

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
$tipo = $_POST['tipo'] ?? null;

if (!isset($id) || !isset($nome) || !isset($email) || !isset($senha) || !isset($tipo) ) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos ou ausentes']);
    exit;
}

if (!$id || !$nome || !$email || !$senha || !$tipo) {
    http_response_code(400);
    echo json_encode(['erro' => 'Todos os campos são obrigatórios']);
    exit;
}

// Atualiza no banco
try {
    $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, tipo = :tipo WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT), // Sempre salvar senha criptografada
        ':tipo' => $tipo,
        ':id' => $id
    ]);

    echo json_encode(['mensagem' => 'Usuário atualizado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar: ' . $e->getMessage()]);
}

// 1. Pegar o ID da URL (ex: /administrador/5)
// $id = isset($_GET['id']) ? $_GET['id'] : null;
// if (!$id) {
//     http_response_code(400);
//     echo json_encode(['erro' => 'ID não fornecido']);
//     exit;
// }

// // 2. Ler os dados do corpo da requisição
// $dados = json_decode(file_get_contents('php://input'), true);

// // 3. Validar campos (opcional: só atualiza campos enviados)
// if (empty($dados)) {
//     http_response_code(400);
//     echo json_encode(['erro' => 'Nenhum dado fornecido para atualização']);
//     exit;
// }

// // 4. Construir a query dinamicamente (atualiza apenas campos enviados)
// $campos = [];
// $valores = [':id' => $id];

// foreach ($dados as $campo => $valor) {
//     // Não permitir atualização do ID (por segurança)
//     if ($campo === 'id') continue;
    
//     if ($campo === 'senha') {
//         // Se for senha, aplicar hash
//         $valores[':senha'] = password_hash($valor, PASSWORD_DEFAULT);
//         $campos[] = "senha = :senha";
//     } else {
//         $valores[":$campo"] = $valor;
//         $campos[] = "$campo = :$campo";
//     }
// }

// if (empty($campos)) {
//     http_response_code(400);
//     echo json_encode(['erro' => 'Nenhum campo válido para atualização']);
//     exit;
// }

// // 5. Executar a atualização
// try {
//     $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = :id";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute($valores);

//     if ($stmt->rowCount() > 0) {
//         http_response_code(200);
//         echo json_encode(['mensagem' => 'usuario atualizado']);
//     } else {
//         http_response_code(404);
//         echo json_encode(['erro' => 'Nenhum registro encontrado ou dados idênticos']);
//     }
// } catch (PDOException $e) {
//     http_response_code(500);
//     echo json_encode(['erro' => 'Falha na atualização: ' . $e->getMessage()]);
// }
?>