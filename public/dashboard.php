<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Pega o tipo de usuário
$tipo = $_SESSION['usuario_tipo'];

// Redireciona baseado no tipo
switch ($tipo) {
    case 'solicitante':
        header("Location: solicitacoes/index.php");
        exit;
    case 'motorista':
        header("Location: motorista/index.php");
        exit;
    case 'admin':
        header("Location: admin/index.php");
        exit;
    case 'fiscal':
        // Pode ir para a tela de usuários ou um painel de admin geral
        header("Location: fiscal/index.php");
        exit;
    default:
        // Se tipo desconhecido, volta pro login (segurança extra)
        session_destroy();
        header("Location: login.php");
        exit;
}
?>
