<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $uf = strtoupper(trim($_POST['uf']));

    if (empty($nome) || empty($uf)) {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO cidades (nome, uf) VALUES (:nome, :uf)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':uf', $uf);
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
    <title>Cadastrar Cidade</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Cadastrar Cidade</h1>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nome da Cidade</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>UF</label>
            <input type="text" name="uf" class="form-control" maxlength="2" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
