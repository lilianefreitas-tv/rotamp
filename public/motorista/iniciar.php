<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Verificar se é motorista
if ($_SESSION['usuario_tipo'] !== 'motorista') {
    echo "Acesso não autorizado.";
    exit;
}

require_once '../../includes/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$solicitacao_id = intval($_GET['id']);

// Pegar id do motorista
$stmt = $pdo->prepare("SELECT id FROM motoristas WHERE usuario_id = :usuario_id");
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();
$motorista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$motorista) {
    echo "Motorista não encontrado.";
    exit;
}

$motorista_id = $motorista['id'];

// Verificar se a solicitação pertence a este motorista
$stmt = $pdo->prepare("SELECT * FROM solicitacoes WHERE id = :id AND motorista_id = :motorista_id");
$stmt->bindParam(':id', $solicitacao_id);
$stmt->bindParam(':motorista_id', $motorista_id);
$stmt->execute();
$solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitacao) {
    echo "Solicitação não encontrada ou não atribuída a você.";
    exit;
}

// Processar formulário de início de percurso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $odometro_inicio = intval($_POST['odometro_inicio']);
    $hora_saida_real = $_POST['hora_saida_real'];

    try {
        $pdo->beginTransaction();

        // Inserir registro de percurso
        $stmt = $pdo->prepare("
            INSERT INTO percursos (solicitacao_id, odometro_inicio, hora_saida_real)
            VALUES (:solicitacao_id, :odometro_inicio, :hora_saida_real)
        ");
        $stmt->bindParam(':solicitacao_id', $solicitacao_id);
        $stmt->bindParam(':odometro_inicio', $odometro_inicio);
        $stmt->bindParam(':hora_saida_real', $hora_saida_real);
        $stmt->execute();

        // Atualizar status da solicitação
        $stmt = $pdo->prepare("UPDATE solicitacoes SET status = 'em andamento' WHERE id = :id");
        $stmt->bindParam(':id', $solicitacao_id);
        $stmt->execute();

        $pdo->commit();

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao iniciar percurso: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Percurso - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Iniciar Percurso</h1>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Odômetro Inicial</label>
            <input type="number" name="odometro_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Hora de Saída Real</label>
            <input type="datetime-local" name="hora_saida_real" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Iniciar Percurso</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
