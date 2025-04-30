<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'fiscal') {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Buscar dados para filtros
$motoristas = $pdo->query("SELECT m.id, u.nome FROM motoristas m JOIN usuarios u ON m.usuario_id = u.id ORDER BY u.nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$promotorias = $pdo->query("SELECT id, nome FROM promotorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

// Processar filtros
$where = [];
$params = [];

if (!empty($_GET['motorista_id'])) {
    $where[] = 's.motorista_id = :motorista_id';
    $params[':motorista_id'] = $_GET['motorista_id'];
}
if (!empty($_GET['promotoria_id'])) {
    $where[] = 'u.promotoria_id = :promotoria_id';
    $params[':promotoria_id'] = $_GET['promotoria_id'];
}
if (!empty($_GET['status'])) {
    $where[] = 's.status = :status';
    $params[':status'] = $_GET['status'];
}
if (!empty($_GET['data_inicio']) && !empty($_GET['data_fim'])) {
    $where[] = '(s.data_ida BETWEEN :data_inicio AND :data_fim)';
    $params[':data_inicio'] = $_GET['data_inicio'];
    $params[':data_fim'] = $_GET['data_fim'];
}

$sql = "
    SELECT s.*, u.nome AS solicitante_nome, um.nome AS motorista_nome, v.modelo, v.placa
    FROM solicitacoes s
    JOIN usuarios u ON s.solicitante_id = u.id
    JOIN motoristas m ON s.motorista_id = m.id
    JOIN usuarios um ON m.usuario_id = um.id
    JOIN veiculos v ON m.veiculo_id = v.id
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY s.data_ida ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_viagens = count($solicitacoes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .relatorio-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .relatorio-logo img {
        max-height: 80px;
        margin-bottom: 10px;
    }
    .relatorio-footer {
        text-align: center;
        font-size: 12px;
        margin-top: 30px;
        border-top: 1px solid #ccc;
        padding-top: 10px;
    }
    @media print {
        .no-print {
            display: none;
        }
    }
    </style>
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <div class="no-print">
        <h1>Relatório de Solicitações</h1>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label>Motorista</label>
                <select name="motorista_id" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach ($motoristas as $m): ?>
                        <option value="<?php echo $m['id']; ?>" <?php if ($_GET['motorista_id'] ?? '' == $m['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($m['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Promotoria</label>
                <select name="promotoria_id" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($promotorias as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php if ($_GET['promotoria_id'] ?? '' == $p['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($p['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendente" <?php if ($_GET['status'] ?? '' == 'pendente') echo 'selected'; ?>>Pendente</option>
                    <option value="em andamento" <?php if ($_GET['status'] ?? '' == 'em andamento') echo 'selected'; ?>>Em Andamento</option>
                    <option value="finalizado" <?php if ($_GET['status'] ?? '' == 'finalizado') echo 'selected'; ?>>Finalizado</option>
                    <option value="cancelado" <?php if ($_GET['status'] ?? '' == 'cancelado') echo 'selected'; ?>>Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Data Início</label>
                <input type="date" name="data_inicio" class="form-control" value="<?php echo $_GET['data_inicio'] ?? ''; ?>">
            </div>
            <div class="col-md-2">
                <label>Data Fim</label>
                <input type="date" name="data_fim" class="form-control" value="<?php echo $_GET['data_fim'] ?? ''; ?>">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <button type="button" class="btn btn-success" onclick="window.print()">Imprimir</button>
            </div>
        </form>
    </div>

    <div class="relatorio-header">
        <div class="relatorio-logo">
            <!-- Coloque sua imagem de logo aqui -->
            <img src="../../assets/img/logo-mppa2.png" alt="Logo MPPA">

        </div>
        <h4>Ministério Público do Estado do Pará<br>Polo Regional de Altamira</h4>
        <small>Relatório de Solicitações de Viagens</small>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Solicitante</th>
                <th>Motorista</th>
                <th>Veículo</th>
                <th>Data Ida</th>
                <th>Data Volta</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitacoes as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['solicitante_nome']); ?></td>
                    <td><?php echo htmlspecialchars($s['motorista_nome']); ?></td>
                    <td><?php echo htmlspecialchars($s['modelo'] . ' - ' . $s['placa']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($s['data_ida'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($s['data_volta'])); ?></td>
                    <td><?php echo ucfirst($s['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <strong>Total de Viagens: </strong> <?php echo $total_viagens; ?>
    </div>

    <div class="relatorio-footer">
        Ministério Público do Estado do Pará - Sudoeste I - Altamira<br>
        Av. Brigadeiro Eduardo Gomes, nº 2785, Esplanada do Xingu Altamira/PA<br>
        Telefone: (93) 3515-1744 | www.mppa.mp.br
    </div>

</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
