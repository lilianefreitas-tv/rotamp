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

$stmt = $pdo->prepare("SELECT * FROM promotorias WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$promotoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$promotoria) {
    echo "Promotoria não encontrada.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);

    if (empty($nome)) {
        $erro = "Preencha o nome.";
    } else {
        $stmt = $pdo->prepare("UPDATE promotorias SET nome = :nome WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
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
    <title>Editar Promotoria</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Editar Promotoria</h1>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Nome da Promotoria</label>
            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($promotoria['nome']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
