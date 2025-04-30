<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../../includes/conexao.php';

// Buscar usuários com nome da Promotoria
$stmt = $pdo->query("
    SELECT u.*, p.nome AS nome_promotoria
    FROM usuarios u
    LEFT JOIN promotorias p ON u.promotoria_id = p.id
    ORDER BY u.nome ASC
");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Usuários - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include '../menu.php'; ?>

    <div class="container mt-5">
        <h1>Usuários</h1>
        <a href="cadastrar.php" class="btn btn-success mb-3">Novo Usuário</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Tipo</th>
                    <th>Promotoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['tipo']); ?></td>
                        <td>
                            <?php echo $usuario['nome_promotoria'] ? htmlspecialchars($usuario['nome_promotoria']) : '<span class="text-muted">Não vinculada</span>'; ?>
                        </td>

                        <td>
                            <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="excluir.php?id=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
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