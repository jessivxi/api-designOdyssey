<?php
session_start();
require '../conexao.php'; // Sua conexão com o banco

// Recebe dados do formulário (POST)
$email = $_POST['email'];
$senha = $_POST['senha'];
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Busca o admin no banco
$sql = "SELECT * FROM administradores WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$admin = $stmt->fetch();

// Verifica se a senha está correta
if ($admin && password_verify($senha, $hash)) {
    // Cria a sessão (usuário logado)
    $_SESSION['logado'] = true;
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_nome'] = $admin['nome'];
    
    header('Location: http://localhost/dashboard/designodyssey_amd/index.php'); // Redireciona para a área restrita
    exit;
} else {
    // Se falhar, volta para o login com erro
    header('Location: http://localhost/dashboard/designodyssey_amd/login/login.php?erro=1');
    exit;
}
?>