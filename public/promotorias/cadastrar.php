<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);

    if (empty($nome)) {
        $erro = "Informe o nome da promotoria.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO promotorias (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
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
    <title>Cadastrar Promotoria</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Cadastrar Promotoria</h1>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Nome da Promotoria</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
