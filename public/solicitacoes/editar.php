<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Verifica se veio um ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

// Busca a solicitação (só se for do solicitante e estiver pendente)
$stmt = $pdo->prepare("
    SELECT * FROM solicitacoes 
    WHERE id = :id AND solicitante_id = :usuario_id AND status = 'pendente'
");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitacao) {
    echo "Solicitação não encontrada ou não pode ser editada.";
    exit;
}

// Buscar motoristas para seleção
$stmt = $pdo->query("
    SELECT m.id, u.nome 
    FROM motoristas m 
    JOIN usuarios u ON m.usuario_id = u.id 
    ORDER BY u.nome ASC
");
$motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $data_ida = $_POST['data_ida'];
    $data_volta = $_POST['data_volta'];
    $hora_saida = $_POST['hora_saida'];
    $hora_chegada = $_POST['hora_chegada'];
    $origem = $_POST['origem'];
    $destino = $_POST['destino'];
    $motorista_id = $_POST['motorista_id'];

    // Validação de conflito de horário
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM solicitacoes
        WHERE motorista_id = :motorista_id
          AND id != :id
          AND (
              (data_ida = :data_ida AND data_volta = :data_volta)
              AND (
                  (hora_saida < :hora_chegada AND hora_chegada > :hora_saida)
              )
          )
          AND status != 'cancelado'
    ");
    $stmt->bindParam(':motorista_id', $motorista_id);
    $stmt->bindParam(':id', $id); // Ignora a própria solicitação atual
    $stmt->bindParam(':data_ida', $data_ida);
    $stmt->bindParam(':data_volta', $data_volta);
    $stmt->bindParam(':hora_saida', $hora_saida);
    $stmt->bindParam(':hora_chegada', $hora_chegada);
    $stmt->execute();
    $conflito = $stmt->fetchColumn();

    if ($conflito > 0) {
        $erro = "Este motorista já possui agendamento neste intervalo de horário.";
    } else {
        // Atualiza se não houver conflito
        $stmt = $pdo->prepare("
            UPDATE solicitacoes 
            SET descricao = :descricao, data_ida = :data_ida, data_volta = :data_volta,
                hora_saida = :hora_saida, hora_chegada = :hora_chegada,
                origem = :origem, destino = :destino, motorista_id = :motorista_id
            WHERE id = :id AND solicitante_id = :usuario_id
        ");
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data_ida', $data_ida);
        $stmt->bindParam(':data_volta', $data_volta);
        $stmt->bindParam(':hora_saida', $hora_saida);
        $stmt->bindParam(':hora_chegada', $hora_chegada);
        $stmt->bindParam(':origem', $origem);
        $stmt->bindParam(':destino', $destino);
        $stmt->bindParam(':motorista_id', $motorista_id);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $erro = "Erro ao atualizar solicitação.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Solicitação - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Editar Solicitação</h1>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Descrição da Missão</label>
            <textarea name="descricao" class="form-control" required><?php echo htmlspecialchars($solicitacao['descricao']); ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>Data de Ida</label>
                <input type="date" name="data_ida" class="form-control" value="<?php echo $solicitacao['data_ida']; ?>" required>
            </div>
            <div class="col">
                <label>Data de Volta</label>
                <input type="date" name="data_volta" class="form-control" value="<?php echo $solicitacao['data_volta']; ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>Hora de Saída</label>
                <input type="time" name="hora_saida" class="form-control" value="<?php echo $solicitacao['hora_saida']; ?>" required>
            </div>
            <div class="col">
                <label>Hora de Chegada</label>
                <input type="time" name="hora_chegada" class="form-control" value="<?php echo $solicitacao['hora_chegada']; ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Origem</label>
            <input type="text" name="origem" class="form-control" value="<?php echo htmlspecialchars($solicitacao['origem']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Destino</label>
            <input type="text" name="destino" class="form-control" value="<?php echo htmlspecialchars($solicitacao['destino']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Motorista</label>
            <select name="motorista_id" class="form-control" required>
                <?php foreach ($motoristas as $motorista): ?>
                    <option value="<?php echo $motorista['id']; ?>" <?php if ($motorista['id'] == $solicitacao['motorista_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($motorista['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
