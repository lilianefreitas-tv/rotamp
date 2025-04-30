<?php
// Arquivo: includes/conexao.php
date_default_timezone_set('America/Sao_Paulo');

$host = '192.168.72.75'; // pode ser 127.0.0.1 se estiver local
$dbname = 'rotamp'; 
$username = 'dbadmin';
$password = 'dbadmin@2025@MP';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Ativar modo de erros do PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro na conexão com o banco de dados: ' . $e->getMessage());
}
?>
