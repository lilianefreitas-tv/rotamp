<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Listar promotorias
$stmt = $pdo->query("SELECT * FROM promotorias ORDER BY nome ASC");
$promotorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Promotorias - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Gestão de Promotorias</h1>
    <a href="cadastrar.php" class="btn btn-success mb-3">+ Nova Promotoria</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promotorias as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nome']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="excluir.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir esta promotoria?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<?php include '../../includes/footer.php'; ?>
</html>
