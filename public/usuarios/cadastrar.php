<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $promotoria_id = !empty($_POST['promotoria_id']) ? intval($_POST['promotoria_id']) : null;

    try {
        $pdo->beginTransaction();

        // Inserir usuário (agora incluindo a Promotoria)
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nome, email, senha, tipo, promotoria_id) 
            VALUES (:nome, :email, :senha, :tipo, :promotoria_id)
        ");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':promotoria_id', $promotoria_id);
        $stmt->execute();

        $usuario_id = $pdo->lastInsertId();

        if ($tipo === 'motorista') {
            $modelo_veiculo = trim($_POST['modelo_veiculo']);
            $placa_veiculo = trim($_POST['placa_veiculo']);

            // Inserir veículo
            $stmt = $pdo->prepare("INSERT INTO veiculos (modelo, placa) VALUES (:modelo, :placa)");
            $stmt->bindParam(':modelo', $modelo_veiculo);
            $stmt->bindParam(':placa', $placa_veiculo);
            $stmt->execute();

            $veiculo_id = $pdo->lastInsertId();

            // Vincular motorista ao veículo
            $stmt = $pdo->prepare("INSERT INTO motoristas (usuario_id, veiculo_id) VALUES (:usuario_id, :veiculo_id)");
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':veiculo_id', $veiculo_id);
            $stmt->execute();
        }

        $pdo->commit();

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário - RotaMP</title>
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

<body class="bg-light">

    <?php include '../menu.php'; ?>

    <div class="container mt-5">
        <h1>Cadastrar Novo Usuário</h1>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Tipo</label>
                <select name="tipo" id="tipo" class="form-control" onchange="exibirCamposVeiculo()" required>
                    <option value="">Selecione</option>
                    <option value="solicitante">Solicitante</option>
                    <option value="motorista">Motorista</option>
                    <option value="fiscal">Fiscal</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div id="campos-veiculo" style="display:none;">
                <hr>
                <h5>Dados do Veículo</h5>
                <div class="form-group mb-3">
                    <label>Modelo do Veículo</label>
                    <input type="text" name="modelo_veiculo" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Placa do Veículo</label>
                    <input type="text" name="placa_veiculo" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label>Promotoria</label>
                <select name="promotoria_id" class="form-control">
                    <option value="">Selecione uma promotoria</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, nome FROM promotorias ORDER BY nome ASC");
                    $promotorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($promotorias as $promotoria):
                        ?>
                        <option value="<?php echo $promotoria['id']; ?>">
                            <?php echo htmlspecialchars($promotoria['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Cadastrar</button>
        </form>
    </div>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>