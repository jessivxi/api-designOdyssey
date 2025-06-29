<?php
header("Content-Type: application/json; charset=UTF-8");

$host = 'localhost';
$db   = 'design_odyssey';
$user = 'root';
$pass = '';
$charset = 'utf8mb4'; // Charset recomendado para suportar caracteres especiais

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Falha na conexão com o banco de dados"]);
    exit;
}
?>