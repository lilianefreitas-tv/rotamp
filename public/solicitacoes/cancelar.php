<?php
session_start();
require_once '../../includes/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$solicitacao_id = intval($_GET['id']);

// Verifica se a solicitação é do próprio usuário (se não for admin)
if ($_SESSION['usuario_tipo'] === 'solicitante') {
    $stmt = $pdo->prepare("SELECT * FROM solicitacoes WHERE id = :id AND solicitante_id = :usuario_id");
    $stmt->bindParam(':id', $solicitacao_id);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    $stmt->execute();
    $solicitacao = $stmt->fetch();

    if (!$solicitacao || $solicitacao['status'] !== 'pendente') {
        echo "Solicitação não pode ser cancelada.";
        exit;
    }
} else {
    // Admin pode cancelar qualquer uma, desde que não finalizada
    $stmt = $pdo->prepare("SELECT * FROM solicitacoes WHERE id = :id");
    $stmt->bindParam(':id', $solicitacao_id);
    $stmt->execute();
    $solicitacao = $stmt->fetch();

    if (!$solicitacao || $solicitacao['status'] === 'finalizado') {
        echo "Solicitação não pode ser cancelada.";
        exit;
    }
}

// Atualiza o status para cancelado
$stmt = $pdo->prepare("UPDATE solicitacoes SET status = 'cancelado' WHERE id = :id");
$stmt->bindParam(':id', $solicitacao_id);
$stmt->execute();

header("Location: index.php");
exit;
