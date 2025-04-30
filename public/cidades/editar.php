<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM cidades WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$cidade = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cidade) {
    echo "Cidade não encontrada.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $uf = strtoupper(trim($_POST['uf']));

    if (empty($nome) || empty($uf)) {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare("UPDATE cidades SET nome = :nome, uf = :uf WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':uf', $uf);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cidade</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Editar Cidade</h1>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nome da Cidade</label>
            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($cidade['nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label>UF</label>
            <input type="text" name="uf" class="form-control" maxlength="2" value="<?php echo htmlspecialchars($cidade['uf']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
