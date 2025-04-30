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

$comprovante_id = intval($_GET['id']);

// Buscar comprovante
$stmt = $pdo->prepare("
    SELECT 
        s.origem, s.destino, s.data_ida, s.data_volta,
        us.nome AS solicitante_nome,
        um.nome AS motorista_nome,
        v.modelo, v.placa,
        p.odometro_inicio, p.hora_saida_real, p.odometro_fim, p.hora_chegada_real, p.km_rodado, p.tempo_operacao,
        c.assinado_motorista, c.data_assinatura_motorista,
        c.assinado_fiscal, c.data_assinatura_fiscal
    FROM solicitacoes s
    JOIN usuarios us ON s.solicitante_id = us.id
    JOIN motoristas m ON s.motorista_id = m.id
    JOIN usuarios um ON m.usuario_id = um.id
    JOIN veiculos v ON m.veiculo_id = v.id
    JOIN percursos p ON p.solicitacao_id = s.id
    JOIN comprovantes c ON c.solicitacao_id = s.id
    WHERE s.id = :id
");
$stmt->bindParam(':id', $comprovante_id);
$stmt->execute();
$comprovante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comprovante) {
    echo "Comprovante não encontrado.";
    exit;
}

// Processar assinatura
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assinar'])) {
    $usuario_tipo = $_SESSION['usuario_tipo'];

    try {
        if ($usuario_tipo == 'motorista') {
            $stmt = $pdo->prepare("UPDATE comprovantes SET assinado_motorista = 1, data_assinatura_motorista = NOW() WHERE id = :id");
        } elseif ($usuario_tipo == 'fiscal') {
            $stmt = $pdo->prepare("UPDATE comprovantes SET assinado_fiscal = 1, data_assinatura_fiscal = NOW() WHERE id = :id");
        } elseif ($usuario_tipo == 'solicitante') {
            $stmt = $pdo->prepare("UPDATE comprovantes SET assinado_solicitante = 1, data_assinatura_solicitante = NOW() WHERE id = :id");
        } else {
            throw new Exception("Tipo de usuário inválido para assinatura.");
        }

        $stmt->bindParam(':id', $comprovante_id);
        $stmt->execute();

        header("Location: visualizar.php?id=" . $comprovante_id);
        exit;

    } catch (Exception $e) {
        $erro = "Erro ao registrar assinatura: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Visualizar Comprovante - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .assinaturas {
            margin-top: 20px;
        }

        .assinaturas ul {
            list-style: none;
            padding: 0;
        }

        .assinaturas li {
            margin-bottom: 5px;
        }

        .relatorio-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .relatorio-logo img {
            max-height: 80px;
            margin-bottom: 10px;
        }

        .relatorio-footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-light">

<div class="no-print">
    <?php include '../menu.php'; ?>
</div>


    <div class="container mt-5">
        <div class="relatorio-header">
            <div class="relatorio-logo">
                <img src="../../assets/img/logo-mppa.png" alt="Logo MPPA">
            </div>
            <h4>Ministério Público do Estado do Pará<br>Polo Regional de Altamira</h4>
            <small>Comprovante de Circulação de Veículo</small>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <table class="table table-bordered">
            <tr>
                <th>Solicitante</th>
                <td><?php echo htmlspecialchars($comprovante['solicitante_nome']); ?></td>
            </tr>
            <tr>
                <th>Motorista</th>
                <td><?php echo htmlspecialchars($comprovante['motorista_nome']); ?></td>
            </tr>
            <tr>
                <th>Veículo</th>
                <td><?php echo htmlspecialchars($comprovante['modelo'] . ' - ' . $comprovante['placa']); ?></td>
            </tr>
            <tr>
                <th>Origem</th>
                <td><?php echo htmlspecialchars($comprovante['origem']); ?></td>
            </tr>
            <tr>
                <th>Destino</th>
                <td><?php echo htmlspecialchars($comprovante['destino']); ?></td>
            </tr>
            <tr>
                <th>Data IDA/Hora Saída</th>
                <td><?php echo date('d/m/Y H:i', strtotime($comprovante['hora_saida_real'])); ?></td>
            </tr>
            <tr>
                <th>Data VOLTA/Hora Chegada</th>
                <td><?php echo date('d/m/Y H:i', strtotime($comprovante['hora_chegada_real'])); ?></td>
            </tr>
            <tr>
                <th>Odômetro Início</th>
                <td><?php echo htmlspecialchars($comprovante['odometro_inicio']) . ' km'; ?></td>
            </tr>
            <tr>
                <th>Odômetro Fim</th>
                <td><?php echo htmlspecialchars($comprovante['odometro_fim']) . ' km'; ?></td>
            </tr>
            <tr>
                <th>Km Rodado</th>
                <td><?php echo htmlspecialchars($comprovante['km_rodado']) . ' km'; ?></td>
            </tr>
            <tr>
                <th>Tempo de Operação</th>
                <td><?php echo htmlspecialchars($comprovante['tempo_operacao']); ?></td>
            </tr>
        </table>

        <div class="assinaturas">
            <h5>Assinaturas:</h5>
            <ul>
                <li>Motorista:
                    <?php echo $comprovante['assinado_motorista'] ? 'Assinado em ' . date('d/m/Y H:i', strtotime($comprovante['data_assinatura_motorista'])) : 'Pendente'; ?>
                </li>
                <!--<li>Solicitante: <?php echo $comprovante['assinado_solicitante'] ? 'Assinado em ' . date('d/m/Y H:i', strtotime($comprovante['data_assinatura_solicitante'])) : 'Pendente'; ?></li>-->
                <li>Fiscal:
                    <?php echo $comprovante['assinado_fiscal'] ? 'Assinado em ' . date('d/m/Y H:i', strtotime($comprovante['data_assinatura_fiscal'])) : 'Pendente'; ?>
                </li>
            </ul>
        </div>

        <div class="no-print text-center mt-5">
    <?php if (
        ($_SESSION['usuario_tipo'] == 'motorista' && !$comprovante['assinado_motorista']) ||
        ($_SESSION['usuario_tipo'] == 'fiscal' && $comprovante['assinado_motorista'] && !$comprovante['assinado_fiscal'])
    ): ?>
        <form method="POST">
            <input type="hidden" name="assinar" value="1">
            <button type="submit" class="btn btn-success mb-3">
                Assinar Comprovante
            </button>
        </form>
    <?php endif; ?>

    <?php if ($comprovante['assinado_motorista'] && $comprovante['assinado_fiscal']): ?>
        <button class="btn btn-primary" onclick="window.print()">Imprimir Comprovante</button>
    <?php else: ?>
        <p class="text-muted">O comprovante só pode ser impresso após todas as assinaturas.</p>
    <?php endif; ?>
</div>


            <div class="relatorio-footer">
                Ministério Público do Estado do Pará - Sudoeste I - Altamira<br>
                Av. Brigadeiro Eduardo Gomes, nº 2785, Esplanada do Xingu Altamira/PA<br>
                Telefone: (93) 3515-1744 | www.mppa.mp.br
            </div>

     

        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>