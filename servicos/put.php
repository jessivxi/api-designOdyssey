<?php
require_once '../conexao.php';
require_once '../headers.php';

// Permite apenas requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Use o método POST']);
    exit;
}

$dados = $_POST;
var_dump($dados);

// Validação básica
if (!isset($dados['id']) || !isset($dados['id_freelancer']) || !isset($dados['id_categoria']) || 
    !isset($dados['titulo']) || !isset($dados['descricao']) || !isset($dados['preco_base'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Preencha todos os campos obrigatórios']);
    exit;
}

// Atualiza no banco de dados
try {
    $sql = "UPDATE servicos SET
                id_freelancer = :id_freelancer,
                id_categoria = :id_categoria,
                titulo = :titulo,
                descricao = :descricao,
                categoria = :categoria,
                preco_base = :preco_base,
                pacotes = :pacotes
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    
    // Definindo a categoria conforme seu código original
    if ($dados['id_categoria'] == 1) {
        $categoria = 'web';
    } elseif ($dados['id_categoria'] == 2) {
        $categoria = 'grafico';
    } elseif ($dados['id_categoria'] == 3) {
        $categoria = 'logotipo';
    } elseif ($dados['id_categoria'] == 4) {
        $categoria = 'digital';
    } else {
        $categoria = 'outro'; // Valor padrão caso não corresponda a nenhum
    }
    
    $params = [
        ':id' => $dados['id'],
        ':id_freelancer' => $dados['id_freelancer'],
        ':id_categoria' => $dados['id_categoria'],
        ':titulo' => $dados['titulo'],
        ':descricao' => $dados['descricao'],
        ':categoria' => $categoria,
        ':preco_base' => $dados['preco_base'],
        ':pacotes' => $dados['pacotes'] ?? null
    ];
    
    $stmt->execute($params);

    echo json_encode([
        'mensagem' => $stmt->rowCount() > 0
            ? 'Serviço atualizado com sucesso'
            : 'Nada foi alterado ou serviço não encontrado'
    ]);
    header('Location: http://localhost/dashboard/designodyssey_amd/servicos/index.php');
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'erro' => 'Erro ao atualizar',
        'detalhes' => $e->getMessage() // Apenas para desenvolvimento
    ]);
}