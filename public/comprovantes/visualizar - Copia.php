<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../includes/conexao.php';
// Verificar se é assinatura de motorista
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assinar_motorista']) && isset($_POST['solicitacao_id'])) {
    $solicitacao_id_post = intval($_POST['solicitacao_id']);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            UPDATE comprovantes
            SET assinado_motorista = TRUE, data_assinatura_motorista = NOW()
            WHERE solicitacao_id = :solicitacao_id
        ");
        $stmt->bindParam(':solicitacao_id', $solicitacao_id_post);
        $stmt->execute();

        $pdo->commit();

        // Recarrega os dados depois da assinatura
        header("Location: visualizar.php?id=" . $solicitacao_id_post);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao assinar comprovante como motorista: " . $e->getMessage();
    }
}


if (!isset($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$solicitacao_id = intval($_GET['id']);

// Buscar todos os dados da viagem
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

$stmt->bindParam(':id', $solicitacao_id);
$stmt->execute();
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    echo "Dados da solicitação não encontrados.";
    exit;
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

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <div class="relatorio-header">
        <div class="relatorio-logo">
            <img src="../../assets/img/logo-mppa.png" alt="Logo MPPA">
        </div>
        <h4>Ministério Público do Estado do Pará<br>Polo Regional de Altamira</h4>
        <small>Comprovante de Circulação de Veículo</small>
    </div>

    <div class="mb-4">
        <h5>Dados do Motorista e Veículo</h5>
        <p><strong>Motorista:</strong> <?php echo htmlspecialchars($dados['motorista_nome']); ?></p>
        <p><strong>Modelo do Veículo:</strong> <?php echo htmlspecialchars($dados['modelo']); ?></p>
        <p><strong>Placa do Veículo:</strong> <?php echo htmlspecialchars($dados['placa']); ?></p>
    </div>

    <div class="mb-4">
        <h5>Dados da Viagem</h5>
        <p><strong>Origem:</strong> <?php echo htmlspecialchars($dados['origem']); ?></p>
        <p><strong>Destino:</strong> <?php echo htmlspecialchars($dados['destino']); ?></p>
        <p><strong>Data de Ida:</strong> <?php echo date('d/m/Y', strtotime($dados['data_ida'])); ?></p>
        <p><strong>Data de Volta:</strong> <?php echo date('d/m/Y', strtotime($dados['data_volta'])); ?></p>
    </div>

    <div class="mb-4">
        <h5>Dados do Percurso</h5>
        <p><strong>Hora de Saída Real:</strong> <?php echo date('d/m/Y H:i', strtotime($dados['hora_saida_real'])); ?></p>
        <p><strong>Hora de Chegada Real:</strong> <?php echo date('d/m/Y H:i', strtotime($dados['hora_chegada_real'])); ?></p>
        <p><strong>Odômetro Inicial:</strong> <?php echo htmlspecialchars($dados['odometro_inicio']); ?> km</p>
        <p><strong>Odômetro Final:</strong> <?php echo htmlspecialchars($dados['odometro_fim']); ?> km</p>
        <p><strong>KM Rodados:</strong> <?php echo htmlspecialchars($dados['km_rodado']); ?> km</p>
        <p><strong>Tempo de Operação:</strong> <?php echo htmlspecialchars($dados['tempo_operacao']); ?></p>
    </div>

    <div class="mb-4">
        <h5>Solicitante</h5>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($dados['solicitante_nome']); ?></p>
    </div>

    <div class="mb-4">
    <h5>Assinaturas</h5>

    <p><strong>Motorista:</strong> 
        <?php if ($dados['assinado_motorista']): ?>
            Assinado em <?php echo date('d/m/Y H:i', strtotime($dados['data_assinatura_motorista'])); ?>
        <?php else: ?>
            <span class="text-danger">Pendente</span>
        <?php endif; ?>
    </p>

    <p><strong>Fiscal:</strong> 
        <?php if ($dados['assinado_fiscal']): ?>
            Assinado em <?php echo date('d/m/Y H:i', strtotime($dados['data_assinatura_fiscal'])); ?>
        <?php else: ?>
            <span class="text-danger">Pendente</span>
        <?php endif; ?>
    </p>
</div>


    <!--<div class="linha-assinatura">
        <span>Motorista</span>
        <span>Solicitante</span>
        <span>Fiscal do Contrato</span>
    </div>-->

    

    <div class="text-center mt-5">

    <?php if (!$dados['assinado_motorista'] && $_SESSION['usuario_tipo'] === 'motorista'): ?>
        <form method="POST">
    <input type="hidden" name="assinar_motorista" value="1">
    <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao_id; ?>">
    <button type="submit" class="btn btn-success mb-3">Assinar como Motorista</button>
</form>

    <?php endif; ?>

    <?php if ($dados['assinado_motorista'] && $dados['assinado_fiscal']): ?>
        <button class="btn btn-primary" onclick="window.print()">Imprimir Comprovante</button>
    <?php else: ?>
        <p class="text-muted">O comprovante só pode ser impresso após todas as assinaturas.</p>
    <?php endif; ?>

</div>
<div class="relatorio-footer mt-5">
                Ministério Público do Estado do Pará - Sudoeste I Altamira<br>
                Av. Brigadeiro Eduardo Gomes, nº 2785, B. Esplanada do Xingu - Altamira/PA<br>
                Telefone: (93) 3515-1744 | www.mppa.mp.br
                
            </div>
    

</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
