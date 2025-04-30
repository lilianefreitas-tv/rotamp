-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/04/2025 às 03:47
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rotamp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidades`
--

CREATE TABLE `cidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `uf` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cidades`
--

INSERT INTO `cidades` (`id`, `nome`, `uf`) VALUES
(4, 'Altamira', 'PA'),
(5, 'Anapu', 'PA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comprovantes`
--

CREATE TABLE `comprovantes` (
  `id` int(11) NOT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `assinado_motorista` tinyint(1) DEFAULT 0,
  `assinado_fiscal` tinyint(1) DEFAULT 0,
  `data_assinatura_motorista` datetime DEFAULT NULL,
  `data_assinatura_fiscal` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `assinado_solicitante` tinyint(1) DEFAULT 0,
  `data_assinatura_solicitante` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comprovantes`
--

INSERT INTO `comprovantes` (`id`, `solicitacao_id`, `assinado_motorista`, `assinado_fiscal`, `data_assinatura_motorista`, `data_assinatura_fiscal`, `created_at`, `assinado_solicitante`, `data_assinatura_solicitante`) VALUES
(8, 15, 1, 1, '2025-04-29 21:41:33', '2025-04-29 21:21:16', '2025-04-29 23:42:31', 0, NULL),
(9, 16, 1, 1, '2025-04-29 21:35:47', '2025-04-29 22:09:30', '2025-04-30 00:27:03', 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `motoristas`
--

CREATE TABLE `motoristas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `veiculo_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `motoristas`
--

INSERT INTO `motoristas` (`id`, `usuario_id`, `veiculo_id`, `created_at`) VALUES
(4, 11, 3, '2025-04-29 23:24:47'),
(5, 12, 4, '2025-04-29 23:25:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `percursos`
--

CREATE TABLE `percursos` (
  `id` int(11) NOT NULL,
  `solicitacao_id` int(11) NOT NULL,
  `odometro_inicio` int(11) DEFAULT NULL,
  `hora_saida_real` datetime DEFAULT NULL,
  `odometro_fim` int(11) DEFAULT NULL,
  `hora_chegada_real` datetime DEFAULT NULL,
  `km_rodado` int(11) DEFAULT NULL,
  `tempo_operacao` time DEFAULT NULL,
  `assinatura_motorista` tinyint(1) DEFAULT 0,
  `assinatura_fiscal` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `percursos`
--

INSERT INTO `percursos` (`id`, `solicitacao_id`, `odometro_inicio`, `hora_saida_real`, `odometro_fim`, `hora_chegada_real`, `km_rodado`, `tempo_operacao`, `assinatura_motorista`, `assinatura_fiscal`, `created_at`) VALUES
(12, 15, 1111, '2025-04-30 08:00:00', 2222, '2025-04-30 12:00:00', 1111, '04:00:00', 0, 0, '2025-04-29 23:42:21'),
(13, 16, 1111, '2025-04-30 08:00:00', 2222, '2025-04-30 12:00:00', 1111, '04:00:00', 0, 0, '2025-04-30 00:26:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `promotorias`
--

CREATE TABLE `promotorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `promotorias`
--

INSERT INTO `promotorias` (`id`, `nome`) VALUES
(5, 'Promotoria de Justiça de Altamira'),
(6, '1ª PJ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes`
--

CREATE TABLE `solicitacoes` (
  `id` int(11) NOT NULL,
  `solicitante_id` int(11) NOT NULL,
  `motorista_id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `data_ida` date NOT NULL,
  `data_volta` date NOT NULL,
  `hora_saida` time NOT NULL,
  `hora_chegada` time NOT NULL,
  `origem` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `status` enum('pendente','em andamento','finalizado') DEFAULT 'pendente',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `solicitacoes`
--

INSERT INTO `solicitacoes` (`id`, `solicitante_id`, `motorista_id`, `descricao`, `data_ida`, `data_volta`, `hora_saida`, `hora_chegada`, `origem`, `destino`, `status`, `created_at`) VALUES
(15, 14, 4, 'Nova Solicitação de Viagem', '2025-04-30', '2025-04-30', '08:00:00', '12:00:00', 'Altamira/PA', 'Anapu/PA', 'finalizado', '2025-04-29 23:41:04'),
(16, 14, 5, 'Nova Solicitação de Viagem', '2025-04-30', '2025-04-30', '08:00:00', '12:00:00', 'Altamira/PA', 'Anapu/PA', 'finalizado', '2025-04-29 23:41:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('solicitante','motorista','fiscal','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `promotoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `created_at`, `promotoria_id`) VALUES
(10, 'Administrador do Sistema', 'admin@rotamp.local.com', '$2y$10$cmjh1qB.8PEIcYcF.8NyV.IfkJFNkCXxaNdkgkqeU0VPU1.wGvOHa', 'admin', '2025-04-29 23:04:34', 5),
(11, 'Motorista 01', 'motorista01@mppa.mp.br', '$2y$10$R11UwaGnvHwLxDomct.u0e4BefYfZ4r7E92OqEtDRX5MbMq.QZA7m', 'motorista', '2025-04-29 23:24:47', 5),
(12, 'Motorista 02', 'motorista02@mppa.mp.br', '$2y$10$CdbGywq1FVNtZHIinD8rte20OKRkU8za5AT4rLyEZikIWk0rAx9ja', 'motorista', '2025-04-29 23:25:21', 5),
(13, 'Fiscal de Testes', 'fiscal@mppa.mp.br', '$2y$10$Tm/ypiqBef/gKPGtRLF0lODpgwGpyO3yUmq.CzYoj.UhC.p0ePDD6', 'fiscal', '2025-04-29 23:25:44', 5),
(14, 'Liliane de Freitas Terra Vieira', 'lilianefreitas@mppa.mp.br', '$2y$10$6CtF6CNyj/8pJrN/hsiNJuEJOX5sfkC34wguyvVigWITlxhyAVkju', 'solicitante', '2025-04-29 23:26:04', 5),
(15, 'Administrador de Testes', 'admin@mppa.mp.br', '$2y$10$4BZ2.IzU/habxTzVJz7jeeYgo./Sx5.JV0XlStCrYiNcgb2CzSUbK', 'admin', '2025-04-29 23:40:10', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id`, `modelo`, `placa`, `created_at`) VALUES
(3, 'GOL', '123ABC', '2025-04-29 23:24:47'),
(4, 'CHEVET', '456ASD', '2025-04-29 23:25:21');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cidades`
--
ALTER TABLE `cidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `comprovantes`
--
ALTER TABLE `comprovantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitacao_id` (`solicitacao_id`);

--
-- Índices de tabela `motoristas`
--
ALTER TABLE `motoristas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD UNIQUE KEY `veiculo_id` (`veiculo_id`);

--
-- Índices de tabela `percursos`
--
ALTER TABLE `percursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitacao_id` (`solicitacao_id`);

--
-- Índices de tabela `promotorias`
--
ALTER TABLE `promotorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitante_id` (`solicitante_id`),
  ADD KEY `motorista_id` (`motorista_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cidades`
--
ALTER TABLE `cidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `comprovantes`
--
ALTER TABLE `comprovantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `motoristas`
--
ALTER TABLE `motoristas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `percursos`
--
ALTER TABLE `percursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `promotorias`
--
ALTER TABLE `promotorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comprovantes`
--
ALTER TABLE `comprovantes`
  ADD CONSTRAINT `comprovantes_ibfk_1` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `motoristas`
--
ALTER TABLE `motoristas`
  ADD CONSTRAINT `motoristas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `motoristas_ibfk_2` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`);

--
-- Restrições para tabelas `percursos`
--
ALTER TABLE `percursos`
  ADD CONSTRAINT `percursos_ibfk_1` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes` (`id`);

--
-- Restrições para tabelas `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD CONSTRAINT `solicitacoes_ibfk_1` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `solicitacoes_ibfk_2` FOREIGN KEY (`motorista_id`) REFERENCES `motoristas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
