<?php
// Definir o base absoluto do projeto
$base_url = '/rotamp/public/';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $base_url; ?>dashboard.php">RotaMP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($_SESSION['usuario_tipo'] == 'solicitante'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>solicitacoes/index.php">Minhas Solicitações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>admin/calendario.php">Calendário</a>
                    </li>
                <?php elseif ($_SESSION['usuario_tipo'] == 'motorista'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>motorista/index.php">Minhas Viagens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>admin/calendario.php">Calendário</a>
                    </li>
                    <?php elseif ($_SESSION['usuario_tipo'] == 'fiscal'): ?>
                        <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>admin/index.php">Painel Administrativo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>fiscal/index.php">Painel do Fiscal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>fiscal/assinaturas.php">Assinar Comprovantes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>fiscal/relatorios.php">Relatórios</a>
                    </li>
                <?php elseif ($_SESSION['usuario_tipo'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>admin/index.php">Painel Administrativo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>usuarios/index.php">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>solicitacoes/index.php">Solicitações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url; ?>motorista/index.php">Viagens</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <span class="nav-link active">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
