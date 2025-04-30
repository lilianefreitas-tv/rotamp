<?php
session_start();
require_once '../includes/conexao.php';

// Processa o login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // Redireciona para a página inicial
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <style>
        .relatorio-header {
            text-align: center;
            margin-bottom: 0px;
        }

        .relatorio-logo img {
            max-height: 180px;
            margin-bottom: 0px;
        }
    </style>

    <meta charset="UTF-8">
    <div class="relatorio-header">
        <div class="relatorio-logo">
            <!-- Coloque sua imagem de logo aqui -->
            <br><br>
            <img src="../assets/img/rotamp-logo2.png" alt="Logo MPPA">
        </div>
    </div>
    <title>Login - RotaMP</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h4>Login - RotaMP</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($erro)): ?>
                            <div class="alert alert-danger"><?php echo $erro; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group mb-3">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" required autofocus>
                            </div>
                            <div class="form-group mb-4">
                                <label for="senha">Senha</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
<br>
<?php include '../includes/footer.php'; ?>

</html>