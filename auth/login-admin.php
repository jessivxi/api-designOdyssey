<?php
session_start();
require 'conexao.php'; // Sua conexão com o banco

// Recebe dados do formulário (POST)
$email = $_POST['email'];
$senha = $_POST['senha'];

// Busca o admin no banco
$sql = "SELECT * FROM administradores WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$admin = $stmt->fetch();

// Verifica se a senha está correta
if ($admin && password_verify($senha, $admin['senha'])) {
    // Cria a sessão (usuário logado)
    $_SESSION['logado'] = true;
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_nome'] = $admin['nome'];
    
    header('Location: painel.php'); // Redireciona para a área restrita
    exit;
} else {
    // Se falhar, volta para o login com erro
    header('Location: login.php?erro=1');
    exit;
}
?>