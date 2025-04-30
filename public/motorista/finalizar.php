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

// Buscar percurso correspondente
$stmt = $pdo->prepare("SELECT * FROM percursos WHERE solicitacao_id = :solicitacao_id");
$stmt->bindParam(':solicitacao_id', $solicitacao_id);
$stmt->execute();
$percurso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$percurso) {
    echo "Percurso não iniciado.";
    exit;
}

// Processar finalização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $odometro_fim = intval($_POST['odometro_fim']);
    $hora_chegada_real = $_POST['hora_chegada_real'];

    // Calcula os KM rodados
    $km_rodado = $odometro_fim - $percurso['odometro_inicio'];

    // Calcula o tempo de operação
    $inicio = new DateTime($percurso['hora_saida_real']);
    $fim = new DateTime($hora_chegada_real);
    $tempo_operacao = $inicio->diff($fim)->format('%H:%I:%S');

    try {
        $pdo->beginTransaction();

        // Atualizar percurso
        $stmt = $pdo->prepare("
            UPDATE percursos 
            SET odometro_fim = :odometro_fim, 
                hora_chegada_real = :hora_chegada_real, 
                km_rodado = :km_rodado, 
                tempo_operacao = :tempo_operacao
            WHERE id = :id
        ");
        $stmt->bindParam(':odometro_fim', $odometro_fim);
        $stmt->bindParam(':hora_chegada_real', $hora_chegada_real);
        $stmt->bindParam(':km_rodado', $km_rodado);
        $stmt->bindParam(':tempo_operacao', $tempo_operacao);
        $stmt->bindParam(':id', $percurso['id']);
        $stmt->execute();

        // Atualizar status da solicitação
        $stmt = $pdo->prepare("UPDATE solicitacoes SET status = 'finalizado' WHERE id = :id");
        $stmt->bindParam(':id', $solicitacao_id);
        $stmt->execute();

        // Inserir Comprovante automático
        $stmt = $pdo->prepare("
            INSERT INTO comprovantes (solicitacao_id)
            VALUES (:solicitacao_id)
        ");
        $stmt->bindParam(':solicitacao_id', $solicitacao_id);
        $stmt->execute();

        $pdo->commit();

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao finalizar percurso: " . $e->getMessage();
    }

}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Finalizar Percurso - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include '../menu.php'; ?>

    <div class="container mt-5">
        <h1>Finalizar Percurso</h1>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Odômetro Final</label>
                <input type="number" name="odometro_fim" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Hora de Chegada Real</label>
                <input type="datetime-local" name="hora_chegada_real" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Finalizar Percurso</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>