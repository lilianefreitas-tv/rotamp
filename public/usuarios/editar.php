<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../../includes/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']); // Corrigi aqui para usar o id corretamente!

// Busca usuário atual
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit;
}

// Busca todas as promotorias
$stmt = $pdo->query("SELECT id, nome FROM promotorias ORDER BY nome ASC");
$promotorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se for motorista, buscar também dados do veículo
$veiculo = null;
if ($usuario['tipo'] === 'motorista') {
    $stmt = $pdo->prepare("
        SELECT v.id, v.modelo, v.placa 
        FROM motoristas m 
        JOIN veiculos v ON m.veiculo_id = v.id 
        WHERE m.usuario_id = :usuario_id
    ");
    $stmt->bindParam(':usuario_id', $usuario['id']);
    $stmt->execute();
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Atualizar informações
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];
    $promotoria_id = !empty($_POST['promotoria_id']) ? intval($_POST['promotoria_id']) : null;

    try {
        $pdo->beginTransaction();

        // Atualizar usuário incluindo promotoria
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nome = :nome, email = :email, tipo = :tipo, promotoria_id = :promotoria_id 
            WHERE id = :id
        ");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':promotoria_id', $promotoria_id);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($tipo === 'motorista') {
            $modelo_veiculo = trim($_POST['modelo_veiculo']);
            $placa_veiculo = trim($_POST['placa_veiculo']);

            if ($veiculo) {
                // Atualizar o veículo existente
                $stmt = $pdo->prepare("UPDATE veiculos SET modelo = :modelo, placa = :placa WHERE id = :veiculo_id");
                $stmt->bindParam(':modelo', $modelo_veiculo);
                $stmt->bindParam(':placa', $placa_veiculo);
                $stmt->bindParam(':veiculo_id', $veiculo['id']);
                $stmt->execute();
            } else {
                // Criar veículo se não existir
                $stmt = $pdo->prepare("INSERT INTO veiculos (modelo, placa) VALUES (:modelo, :placa)");
                $stmt->bindParam(':modelo', $modelo_veiculo);
                $stmt->bindParam(':placa', $placa_veiculo);
                $stmt->execute();
                $veiculo_id = $pdo->lastInsertId();

                $stmt = $pdo->prepare("INSERT INTO motoristas (usuario_id, veiculo_id) VALUES (:usuario_id, :veiculo_id)");
                $stmt->bindParam(':usuario_id', $id);
                $stmt->bindParam(':veiculo_id', $veiculo_id);
                $stmt->execute();
            }
        }

        $pdo->commit();

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao atualizar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function exibirCamposVeiculo() {
        var tipo = document.getElementById('tipo').value;
        var camposVeiculo = document.getElementById('campos-veiculo');
        if (tipo === 'motorista') {
            camposVeiculo.style.display = 'block';
        } else {
            camposVeiculo.style.display = 'none';
        }
    }
    </script>
</head>
<body class="bg-light" onload="exibirCamposVeiculo()">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1>Editar Usuário</h1>
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label>E-mail</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label>Tipo</label>
            <select name="tipo" id="tipo" class="form-control" onchange="exibirCamposVeiculo()" required>
                <option value="">Selecione</option>
                <option value="solicitante" <?php if ($usuario['tipo'] == 'solicitante') echo 'selected'; ?>>Solicitante</option>
                <option value="motorista" <?php if ($usuario['tipo'] == 'motorista') echo 'selected'; ?>>Motorista</option>
                <option value="fiscal" <?php if ($usuario['tipo'] == 'fiscal') echo 'selected'; ?>>Fiscal</option>
                <option value="admin" <?php if ($usuario['tipo'] == 'admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Promotoria</label>
            <select name="promotoria_id" class="form-control">
                <option value="">Selecione uma promotoria</option>
                <?php foreach ($promotorias as $promotoria): ?>
                    <option value="<?php echo $promotoria['id']; ?>" <?php if ($usuario['promotoria_id'] == $promotoria['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($promotoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="campos-veiculo" style="display:none;">
            <hr>
            <h5>Dados do Veículo</h5>
            <div class="form-group mb-3">
                <label>Modelo do Veículo</label>
                <input type="text" name="modelo_veiculo" class="form-control" value="<?php echo htmlspecialchars($veiculo['modelo'] ?? ''); ?>">
            </div>
            <div class="form-group mb-3">
                <label>Placa do Veículo</label>
                <input type="text" name="placa_veiculo" class="form-control" value="<?php echo htmlspecialchars($veiculo['placa'] ?? ''); ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
