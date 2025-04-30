<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM promotorias WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

header("Location: index.php");
exit;
