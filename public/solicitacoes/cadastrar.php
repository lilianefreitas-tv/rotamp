<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';

// Buscar cidades para os selects
$stmt = $pdo->query("SELECT id, nome, uf FROM cidades ORDER BY nome ASC");
$cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Buscar motoristas disponíveis
$stmt = $pdo->query("SELECT m.id, u.nome FROM motoristas m JOIN usuarios u ON m.usuario_id = u.id ORDER BY u.nome ASC");
$motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $solicitante_id = $_SESSION['usuario_id'];
    $descricao = trim($_POST['descricao']);
    $data_ida = $_POST['data_ida'];
    $data_volta = $_POST['data_volta'];
    $hora_saida = $_POST['hora_saida'];
    $hora_chegada = $_POST['hora_chegada'];
    $origem = trim($_POST['origem']);
    $destino = trim($_POST['destino']);
    $motorista_id = intval($_POST['motorista_id']);

    // Validação básica
    if (empty($descricao) || empty($data_ida) || empty($data_volta) || empty($hora_saida) || empty($hora_chegada) || empty($origem) || empty($destino) || empty($motorista_id)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Montar datetime completo
        $saida_inicio = $data_ida . ' ' . $hora_saida;
        $chegada_fim = $data_volta . ' ' . $hora_chegada;

        // Verificação de conflito de agenda do motorista (data + hora)
        $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM solicitacoes
        WHERE motorista_id = :motorista_id
          AND status != 'cancelado'
          AND (
            (CONCAT(data_ida, ' ', hora_saida) < :chegada_fim)
            AND (CONCAT(data_volta, ' ', hora_chegada) > :saida_inicio)
          )
    ");
        $stmt->bindParam(':motorista_id', $motorista_id);
        $stmt->bindParam(':saida_inicio', $saida_inicio);
        $stmt->bindParam(':chegada_fim', $chegada_fim);
        $stmt->execute();
        $conflito = $stmt->fetchColumn();

        if ($conflito > 0) {
            $erro = "O motorista selecionado já possui agendamento nesse intervalo. Escolha outro horário ou motorista.";
        } else {
            // Inserir solicitação
            $stmt = $pdo->prepare("INSERT INTO solicitacoes (solicitante_id, motorista_id, descricao, data_ida, data_volta, hora_saida, hora_chegada, origem, destino, status) 
                                   VALUES (:solicitante_id, :motorista_id, :descricao, :data_ida, :data_volta, :hora_saida, :hora_chegada, :origem, :destino, 'pendente')");
            $stmt->bindParam(':solicitante_id', $solicitante_id);
            $stmt->bindParam(':motorista_id', $motorista_id);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':data_ida', $data_ida);
            $stmt->bindParam(':data_volta', $data_volta);
            $stmt->bindParam(':hora_saida', $hora_saida);
            $stmt->bindParam(':hora_chegada', $hora_chegada);
            $stmt->bindParam(':origem', $origem);
            $stmt->bindParam(':destino', $destino);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                $erro = "Erro ao registrar a solicitação.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Nova Solicitação - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include '../menu.php'; ?>

    <div class="container mt-5">
        <h1>Nova Solicitação de Viagem</h1>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Solicitante</label>
                <input type="text" class="form-control"
                    value="<?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label>Motorista Desejado</label>
                <select name="motorista_id" class="form-control" required>
                    <option value="">Selecione um motorista</option>
                    <?php foreach ($motoristas as $motorista): ?>
                        <option value="<?php echo $motorista['id']; ?>"><?php echo htmlspecialchars($motorista['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Descrição da Missão</label>
                <textarea name="descricao" class="form-control" rows="3" required></textarea>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label>Data de Ida</label>
                    <input type="date" name="data_ida" class="form-control" required>
                </div>
                <div class="col">
                    <label>Data de Volta</label>
                    <input type="date" name="data_volta" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label>Hora de Saída</label>
                    <input type="time" name="hora_saida" class="form-control" required>
                </div>
                <div class="col">
                    <label>Hora de Chegada</label>
                    <input type="time" name="hora_chegada" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Origem</label>
                <select name="origem" class="form-control" required>
                    <option value="">Selecione a cidade de origem</option>
                    <?php foreach ($cidades as $cidade): ?>
                        <option value="<?php echo htmlspecialchars($cidade['nome'] . '/' . $cidade['uf']); ?>">
                            <?php echo htmlspecialchars($cidade['nome'] . '/' . $cidade['uf']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Destino</label>
                <select name="destino" class="form-control" required>
                    <option value="">Selecione a cidade de destino</option>
                    <?php foreach ($cidades as $cidade): ?>
                        <option value="<?php echo htmlspecialchars($cidade['nome'] . '/' . $cidade['uf']); ?>">
                            <?php echo htmlspecialchars($cidade['nome'] . '/' . $cidade['uf']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Registrar Solicitação</button>
        </form>
    </div>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<?php include '../../includes/footer.php'; ?>

</html>