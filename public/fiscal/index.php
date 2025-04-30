<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar se é fiscal
if ($_SESSION['usuario_tipo'] !== 'fiscal') {
    echo "Acesso não autorizado.";
    exit;
}

require_once '../../includes/conexao.php';

// Buscar todas as solicitações finalizadas
$stmt = $pdo->prepare("
    SELECT s.id, s.origem, s.destino, s.data_ida, s.data_volta,
           u.nome AS solicitante_nome, um.nome AS motorista_nome,
           p.km_rodado, p.tempo_operacao,
           c.assinado_fiscal
    FROM solicitacoes s
    JOIN usuarios u ON s.solicitante_id = u.id
    JOIN motoristas m ON s.motorista_id = m.id
    JOIN usuarios um ON m.usuario_id = um.id
    JOIN percursos p ON p.solicitacao_id = s.id
    LEFT JOIN comprovantes c ON c.solicitacao_id = s.id
    WHERE s.status = 'finalizado'
    ORDER BY s.data_ida DESC
");
$stmt->execute();
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Fiscal - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Painel do Fiscal</h1>
    <div class="mb-3">
        <a href="relatorios.php" class="btn btn-primary">Gerar Relatórios</a>
    </div>

    <?php if (empty($solicitacoes)): ?>
        <div class="alert alert-info">Nenhuma solicitação finalizada encontrada.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Motorista</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Data Ida</th>
                    <th>Data Volta</th>
                    <th>KM Rodados</th>
                    <th>Tempo Operação</th>
                    <th>Status Assinatura</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitacao['solicitante_nome']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['motorista_nome']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['origem']); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['destino']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($solicitacao['data_ida'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($solicitacao['data_volta'])); ?></td>
                        <td><?php echo htmlspecialchars($solicitacao['km_rodado']); ?> km</td>
                        <td><?php echo htmlspecialchars($solicitacao['tempo_operacao']); ?></td>
                        <td>
                            <?php if ($solicitacao['assinado_fiscal']): ?>
                                <span class="badge bg-success">Assinado</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="../comprovantes/visualizar.php?id=<?php echo $solicitacao['id']; ?>" class="btn btn-sm btn-info" target="_blank">Ver Comprovante</a>
                            <!-- Depois botão de Assinar -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<?php include '../../includes/footer.php'; ?>
</html>
