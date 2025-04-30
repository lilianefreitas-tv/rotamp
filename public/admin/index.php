<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Contagem geral
$total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_motoristas = $pdo->query("SELECT COUNT(*) FROM motoristas")->fetchColumn();
$total_solicitacoes = $pdo->query("SELECT COUNT(*) FROM solicitacoes")->fetchColumn();
$solicitacoes_ativas = $pdo->query("SELECT COUNT(*) FROM solicitacoes WHERE status IN ('pendente', 'em andamento')")->fetchColumn();
$solicitacoes_finalizadas = $pdo->query("SELECT COUNT(*) FROM solicitacoes WHERE status = 'finalizado'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Painel Administrativo</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Usuários</h5>
                    <p class="card-text display-3"><?php echo $total_usuarios; ?></p>
                    <a href="../usuarios/index.php" class="btn btn-primary w-100">Gerenciar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Motoristas</h5>
                    <p class="card-text display-3"><?php echo $total_motoristas; ?></p>
                    <a href="../motorista/index.php" class="btn btn-success w-100">Ver Viagens</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Solicitações</h5>
                    <p class="card-text">
                        Total: <?php echo $total_solicitacoes; ?><br>
                        Ativas: <?php echo $solicitacoes_ativas; ?><br>
                        Finalizadas: <?php echo $solicitacoes_finalizadas; ?>
                    </p>
                    <a href="../solicitacoes/index.php" class="btn btn-warning w-100">Visualizar</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Calendário de Agendamentos</h5>
                    <p class="card-text">Visualize graficamente os agendamentos de motoristas por data.</p>
                    <a href="calendario.php" class="btn btn-info w-100">Abrir Calendário</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gestão de Cidades</h5>
                    <p class="card-text">Cadastre e mantenha atualizada a tabela de cidades utilizadas no sistema.</p>
                    <a href="../cidades/index.php" class="btn btn-dark w-100">Gerenciar Cidades</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gestão de Promotorias</h5>
                    <p class="card-text">Cadastre e mantenha atualizada a tabela de Promotorias utilizadas no sistema.</p>
                    <a href="../promotorias/index.php" class="btn btn-dark w-100">Gerenciar Promotorias</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<?php include '../../includes/footer.php'; ?>
</html>
