<?php
require_once '../conexao.php';
require_once '../headers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['erro' => 'Use o método PUT']);
    exit;
}

// Lê o corpo da requisição JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Verificação básica de campos obrigatórios
$camposObrigatorios = ['id_perfil', 'id_usuarios', 'nome_exibicao', 'foto', 'tipo'];
foreach ($camposObrigatorios as $campo) {
    if (empty($dados[$campo])) {
        http_response_code(400);
        echo json_encode(['erro' => "O campo '$campo' é obrigatório"]);
        exit;
    }
}

try {
    $sql = "UPDATE perfis SET 
                nome_exibicao = :nome_exibicao,
                foto = :foto,
                tipo = :tipo,
                id_usuarios = :id_usuarios
            WHERE id_perfil = :id_perfil";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome_exibicao' => $dados['nome_exibicao'],
        ':foto' => $dados['foto'],
        ':tipo' => $dados['tipo'],
        ':id_usuarios' => $dados['id_usuarios'],
        ':id_perfil' => $dados['id_perfil']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['sucesso' => 'Perfil atualizado com sucesso']);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Perfil não encontrado ou dados iguais']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
