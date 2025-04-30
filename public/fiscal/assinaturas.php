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

// Processar assinatura se enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprovante_id'])) {
    $comprovante_id = intval($_POST['comprovante_id']);

    try {
        $pdo->beginTransaction();

        // Atualizar assinatura do fiscal
        $stmt = $pdo->prepare("
            UPDATE comprovantes
            SET assinado_fiscal = TRUE, data_assinatura_fiscal = NOW()
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $comprovante_id);
        $stmt->execute();

        $pdo->commit();
        $mensagem = "Comprovante assinado com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao assinar comprovante: " . $e->getMessage();
    }
}

// Buscar todos os comprovantes
$stmt = $pdo->query("
    SELECT c.id AS comprovante_id, s.id AS solicitacao_id, 
           u.nome AS solicitante_nome, um.nome AS motorista_nome, 
           v.modelo, v.placa, p.km_rodado, p.tempo_operacao,
           c.assinado_fiscal, c.data_assinatura_fiscal
    FROM comprovantes c
    JOIN solicitacoes s ON c.solicitacao_id = s.id
    JOIN usuarios u ON s.solicitante_id = u.id
    JOIN motoristas m ON s.motorista_id = m.id
    JOIN usuarios um ON m.usuario_id = um.id
    JOIN veiculos v ON m.veiculo_id = v.id
    JOIN percursos p ON p.solicitacao_id = s.id
    ORDER BY s.data_ida DESC
");
$comprovantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Assinaturas - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Painel de Assinaturas de Comprovantes</h1>

    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-success"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <?php if (empty($comprovantes)): ?>
        <div class="alert alert-warning">Nenhum comprovante encontrado.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Solicitante</th>
                        <th>Motorista</th>
                        <th>Veículo</th>
                        <th>KM Rodados</th>
                        <th>Tempo de Operação</th>
                        <th>Status Assinatura</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comprovantes as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['solicitante_nome']); ?></td>
                            <td><?php echo htmlspecialchars($c['motorista_nome']); ?></td>
                            <td><?php echo htmlspecialchars($c['modelo']) . " - " . htmlspecialchars($c['placa']); ?></td>
                            <td><?php echo htmlspecialchars($c['km_rodado']); ?> km</td>
                            <td><?php echo htmlspecialchars($c['tempo_operacao']); ?></td>
                            <td>
                                <?php if ($c['assinado_fiscal']): ?>
                                    <span class="badge bg-success">Assinado em <?php echo date('d/m/Y H:i', strtotime($c['data_assinatura_fiscal'])); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$c['assinado_fiscal']): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="comprovante_id" value="<?php echo $c['comprovante_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Assinar</button>
                                    </form>
                                <?php else: ?>
                                    <a href="../comprovantes/visualizar.php?id=<?php echo $c['solicitacao_id']; ?>" class="btn btn-sm btn-info">Ver Comprovante</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<?php include '../../includes/footer.php'; ?>
</html>
