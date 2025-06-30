<?php
// Example delete.php
header("Content-Type: application/json; charset=UTF-8");

// Conexão com o banco
require_once '../conexao.php';
require_once '../headers.php';


$dados = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval($dados ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM servico WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>