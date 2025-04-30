<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Buscar solicitações do usuário logado
$usuario_id = $_SESSION['usuario_id'];

// Se quiser deixar futuramente diferenciado: motorista vê todas, solicitante vê só as dele.

$stmt = $pdo->prepare("
    SELECT s.*, u.nome AS motorista_nome
    FROM solicitacoes s
    JOIN motoristas m ON s.motorista_id = m.id
    JOIN usuarios u ON m.usuario_id = u.id
    WHERE s.solicitante_id = :usuario_id
    ORDER BY s.created_at DESC
");
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Minhas Solicitações - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include '../menu.php'; ?>

    <div class="container mt-5">
        <h1>Minhas Solicitações de Viagem</h1>
        <a href="cadastrar.php" class="btn btn-success mb-3">Nova Solicitação</a>

        <?php if (empty($solicitacoes)): ?>
            <div class="alert alert-info">Nenhuma solicitação encontrada.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Motorista</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data Ida</th>
                        <th>Hora Saída</th>
                        <th>Data Volta</th>
                        <th>Hora Chegada</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitacoes as $solicitacao): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($solicitacao['motorista_nome']); ?></td>
                            <td><?php echo htmlspecialchars($solicitacao['origem']); ?></td>
                            <td><?php echo htmlspecialchars($solicitacao['destino']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($solicitacao['data_ida'])); ?></td>
                            <td><?php echo htmlspecialchars($solicitacao['hora_saida']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($solicitacao['data_volta'])); ?></td>
                            <td><?php echo htmlspecialchars($solicitacao['hora_chegada']); ?></td>
                            <td>
                                <?php
                                $badgeClass = match ($solicitacao['status']) {
                                    'pendente' => 'warning',
                                    'em andamento' => 'primary',
                                    'finalizado' => 'success',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $badgeClass; ?>">
                                    <?php echo ucfirst($solicitacao['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($solicitacao['status'] === 'pendente'): ?>
                                    <a href="editar.php?id=<?php echo $solicitacao['id']; ?>"
                                        class="btn btn-sm btn-warning">Editar</a>
                                    <a href="cancelar.php?id=<?php echo $solicitacao['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Tem certeza que deseja cancelar esta solicitação?');">
                                        Cancelar
                                    </a>
                                <?php elseif ($solicitacao['status'] === 'finalizado'): ?>
                                    <a href="../comprovantes/visualizar.php?id=<?php echo $solicitacao['id']; ?>"
                                        class="btn btn-sm btn-info" target="_blank">
                                        Ver Comprovante
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Em andamento</span>
                                <?php endif; ?>
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