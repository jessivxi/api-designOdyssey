<?php
require_once '../conexao.php';
require_once '../headers.php';

// Recebe os dados do corpo da requisição (POST ou JSON)
$dados = $_POST;

if (empty($dados)) {
    $dados = json_decode(file_get_contents('php://input'), true);
}

// Verifica campos obrigatórios (AGORA INCLUINDO id_freelancer)
$camposObrigatorios = ['titulo', 'descricao', 'id_categoria', 'preco_base', 'id_freelancer'];
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
            (id_categoria, titulo, descricao, categoria, preco_base, pacotes, data_publicacao, id_freelancer) 
            VALUES (:id_categoria, :titulo, :descricao, :categoria, :preco_base, :pacotes, :data_publicacao, :id_freelancer)";
    
    $stmt = $pdo->prepare($sql);
    if ($dados['id_categoria'] == 1) {
        $stmt->execute([
            ':id_freelancer' => $dados['id_freelancer'],
            ':id_categoria' => $dados['id_categoria'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':categoria' => 'web',
            ':preco_base' => $dados['preco_base'],  
            ':pacotes' => $pacotes,
            ':data_publicacao' => $dados['data_publicacao'] ?? date('Y-m-d H:i:s')
        ]);
    } elseif ($dados['id_categoria'] == 2) {
        $stmt->execute([
            ':id_freelancer' => $dados['id_freelancer'],
            ':id_categoria' => $dados['id_categoria'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':categoria' => 'grafico',
            ':preco_base' => $dados['preco_base'],  
            ':pacotes' => $pacotes,
            ':data_publicacao' => $dados['data_publicacao'] ?? date('Y-m-d H:i:s')
        ]);
    } elseif ($dados['id_categoria'] == 3) {
        $stmt->execute([
            ':id_freelancer' => $dados['id_freelancer'],
            ':id_categoria' => $dados['id_categoria'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':categoria' => 'logotipo',
            ':preco_base' => $dados['preco_base'],  
            ':pacotes' => $pacotes,
            ':data_publicacao' => $dados['data_publicacao'] ?? date('Y-m-d H:i:s')
        ]);
    } elseif ($dados['id_categoria'] == 4) {
        $stmt->execute([
            ':id_freelancer' => $dados['id_freelancer'],
            ':id_categoria' => $dados['id_categoria'],
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':categoria' => 'digital',
            ':preco_base' => $dados['preco_base'],  
            ':pacotes' => $pacotes,
            ':data_publicacao' => $dados['data_publicacao'] ?? date('Y-m-d H:i:s')
        ]);
    } else {
        var_dump($dados);
        exit;
    }
    
    echo json_encode(['sucesso' => 'Serviço criado! ID: ' . $pdo->lastInsertId()]);
    header('Location: http://localhost/dashboard/designodyssey_amd/servicos/index.php');
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
?>