<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../../includes/conexao.php';

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Proteção: não permitir que o usuário exclua a si mesmo
if ($_SESSION['usuario_id'] == $id) {
    echo "<div style='padding:20px; text-align:center; font-family:sans-serif;'>";
    echo "<h2>⚠️ Você não pode excluir sua própria conta!</h2>";
    echo "<a href='index.php' style='display:inline-block; margin-top:20px; background:#007bff; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;'>Voltar</a>";
    echo "</div>";
    exit;
}

// Proteção extra: evitar exclusão de um usuário do tipo 'admin'
$stmt = $pdo->prepare("SELECT tipo FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit;
}

if ($usuario['tipo'] == 'admin') {
    echo "<div style='padding:20px; text-align:center; font-family:sans-serif;'>";
    echo "<h2>⚠️ Não é permitido excluir um administrador!</h2>";
    echo "<a href='index.php' style='display:inline-block; margin-top:20px; background:#007bff; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;'>Voltar</a>";
    echo "</div>";
    exit;
}

// Se passou pelas proteções, pode excluir
$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    echo "Erro ao excluir usuário.";
}
?>
