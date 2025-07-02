<?php
require_once '../conexao.php';
require_once '../headers.php';

// Recebe os dados do corpo da requisição (POST ou JSON)
$dados = $_POST;
if (empty($dados)) {
    $dados = json_decode(file_get_contents('php://input'), true);
}

// Verifica campos obrigatórios (AGORA INCLUINDO id_freelancer)
$camposObrigatorios = ['titulo', 'descricao', 'categoria', 'preco_base', 'id_freelancer'];
foreach ($camposObrigatorios as $campo) {
    if (empty($dados[$campo])) {
        http_response_code(400);
        echo json_encode(['erro' => "O campo '$campo' é obrigatório"]);
        exit;
    }
}

// Converte pacotes para JSON se for array (CORREÇÃO DO WARNING)
$pacotes = $dados['pacotes'] ?? '{}';
if (is_array($pacotes)) {
    $pacotes = json_encode($pacotes);
}

// Executa no banco (MESMO CÓDIGO ORIGINAL, SÓ ADICIONEI id_freelancer)
try {
    $sql = "INSERT INTO servicos 
            (titulo, descricao, categoria, preco_base, pacotes, data_publicacao, id_freelancer) 
            VALUES (:titulo, :descricao, :categoria, :preco_base, :pacotes, :data_publicacao, :id_freelancer)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $dados['titulo'],
        ':descricao' => $dados['descricao'],
        ':categoria' => $dados['categoria'],
        ':preco_base' => $dados['preco_base'],
        ':pacotes' => $pacotes,
        ':data_publicacao' => $dados['data_publicacao'] ?? date('Y-m-d H:i:s'),
        ':id_freelancer' => $dados['id_freelancer']
    ]);
    
    echo json_encode(['sucesso' => 'Serviço criado! ID: ' . $pdo->lastInsertId()]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
?>