<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Listar cidades
$stmt = $pdo->query("SELECT * FROM cidades ORDER BY nome ASC");
$cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Cidades - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Gestão de Cidades</h1>
    <a href="cadastrar.php" class="btn btn-success mb-3">+ Nova Cidade</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>UF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cidades as $cidade): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cidade['nome']); ?></td>
                    <td><?php echo htmlspecialchars($cidade['uf']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $cidade['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="excluir.php?id=<?php echo $cidade['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?');">Excluir</a>
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
