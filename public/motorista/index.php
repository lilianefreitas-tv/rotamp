<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Só motoristas podem acessar essa área
if ($_SESSION['usuario_tipo'] !== 'motorista') {
    echo "Acesso não autorizado.";
    exit;
}

require_once '../../includes/conexao.php';

// Pegar o id do motorista
$stmt = $pdo->prepare("SELECT id FROM motoristas WHERE usuario_id = :usuario_id");
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();
$motorista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$motorista) {
    echo "Motorista não encontrado.";
    exit;
}

$motorista_id = $motorista['id'];

// Buscar viagens atribuídas a este motorista
$stmt = $pdo->prepare("
    SELECT s.*, u.nome AS solicitante_nome
    FROM solicitacoes s
    JOIN usuarios u ON s.solicitante_id = u.id
    WHERE s.motorista_id = :motorista_id
    ORDER BY s.created_at DESC
");

$stmt->bindParam(':motorista_id', $motorista_id);
$stmt->execute();
$viagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Minhas Viagens - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include '../menu.php'; ?>

    <div class="container mt-5">
        <h1>Minhas Viagens</h1>

        <?php if (empty($viagens)): ?>
            <div class="alert alert-info">Nenhuma viagem atribuída no momento.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data Ida</th>
                        <th>Hora Saída</th>
                        <th>Data Volta</th>
                        <th>Hora Chegada</th>
                        <th>Solicitante</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viagens as $viagem): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($viagem['origem']); ?></td>
                            <td><?php echo htmlspecialchars($viagem['destino']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($viagem['data_ida'])); ?></td>
                            <td><?php echo htmlspecialchars($viagem['hora_saida']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($viagem['data_volta'])); ?></td>
                            <td><?php echo htmlspecialchars($viagem['hora_chegada']); ?></td>
                            <td><?php echo htmlspecialchars($viagem['solicitante_nome']); ?></td>
                            <td>
                                <?php
                                $badgeClass = match ($viagem['status']) {
                                    'pendente' => 'warning',
                                    'em andamento' => 'primary',
                                    'finalizado' => 'success',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $badgeClass; ?>">
                                    <?php echo ucfirst($viagem['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($viagem['status'] === 'pendente'): ?>
                                    <a href="iniciar.php?id=<?php echo $viagem['id']; ?>" class="btn btn-sm btn-success">Iniciar
                                        Percurso</a>
                                <?php elseif ($viagem['status'] === 'em andamento'): ?>
                                    <a href="finalizar.php?id=<?php echo $viagem['id']; ?>" class="btn btn-sm btn-primary">Finalizar
                                        Percurso</a>
                                <?php elseif ($viagem['status'] === 'finalizado'): ?>
                                    <a href="../comprovantes/visualizar.php?id=<?php echo $viagem['id']; ?>"
                                        class="btn btn-sm btn-info" target="_blank">Ver Comprovante</a>
                                <?php else: ?>
                                    <span class="text-muted">Sem ação</span>
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