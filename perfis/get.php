<?php
require_once '../conexao.php';
require_once '../headers.php';

// Busca o ID do perfil ou do usuário
$id_perfil = isset($_GET['id']) ? $_GET['id'] : null;
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

try {
    if ($id_perfil) {
        // CASO 1: Busca um perfil específico pelo ID (como antes)
        $stmt = $pdo->prepare("SELECT * FROM perfis WHERE id = ?");
        $stmt->execute([$id_perfil]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($perfil) {
            echo json_encode($perfil);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Perfil não encontrado']);
        }
    } elseif ($id_usuario) {
        // CASO 2: Busca todos os perfis de um usuário + tipo de relacionamento
        $stmt = $pdo->prepare("
            SELECT p.*, up.tipo 
            FROM perfis p
            JOIN rl_usuario_perfil up ON p.id = up.id_perfis
            WHERE up.id_usuarios = ?
        ");
        $stmt->execute([$id_usuario]);
        $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($perfis);
    } else {
        // CASO 3: Busca todos os perfis (como antes)
        $stmt = $pdo->query("SELECT * FROM perfis");
        $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($perfis);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao acessar o banco de dados: ' . $e->getMessage()]);
}
?>