<?php
require_once '../conexao.php';
require_once '../headers.php';

header('Content-Type: application/json');

// Verifica o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Se não for JSON, tenta pegar do POST normal
    if (json_last_error() !== JSON_ERROR_NONE) {
        $data = $_POST;
    }

    // Operação de login
    if (isset($data['email']) && isset($data['senha']) && !isset($data['nome'])) {
        $email = $data['email'];
        $senha = $data['senha'];

        try {
            // Busca usuário pelo email
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Remove a senha do retorno por segurança
                unset($usuario['senha']);

                http_response_code(200);
                echo json_encode([
                    'mensagem' => 'Login realizado com sucesso',
                    'usuario' => $usuario
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['erro' => 'Credenciais inválidas']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao verificar credenciais: ' . $e->getMessage()]);
        }
    }
    // Operação de criação de usuário
    elseif (isset($data['nome']) && isset($data['email']) && isset($data['senha'])) {
        $nome = $data['nome'];
        $email = $data['email'];
        $senha = $data['senha'];
        $tipo = $data['tipo'] ?? 'cliente';

        // Verifica se o usuário já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['erro' => 'Email já cadastrado']);
            exit;
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) 
                                  VALUES (:nome, :email, :senha, :tipo)");

            $sucesso = $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senhaHash,
                ':tipo' => $tipo
            ]);

            if ($sucesso) {
                http_response_code(201);
                echo json_encode([
                    'mensagem' => 'Usuário criado com sucesso',
                    'id' => $pdo->lastInsertId()
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao criar usuário: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['erro' => 'Dados incompletos']);
    }
} else {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
}
