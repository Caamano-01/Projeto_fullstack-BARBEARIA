-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/02/2026 às 16:03
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
-- Banco de dados: `senai_barbearia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `profissional_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `status` enum('pendente','confirmado','concluido','cancelado') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `usuario_id`, `servico_id`, `profissional_id`, `data`, `hora`, `status`, `criado_em`) VALUES
(2, 2, 1, 5, '2026-02-28', '10:00:00', 'confirmado', '2026-02-23 11:47:18'),
(3, 2, 3, 5, '2026-02-24', '11:00:00', 'cancelado', '2026-02-23 12:50:42'),
(4, 2, 3, 5, '2026-03-03', '09:00:00', 'confirmado', '2026-02-23 13:34:54'),
(5, 2, 2, 6, '2026-02-23', '19:00:00', 'confirmado', '2026-02-23 14:02:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bloqueios_horarios`
--

CREATE TABLE `bloqueios_horarios` (
  `id` int(11) NOT NULL,
  `profissional_id` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bloqueios_horarios`
--

INSERT INTO `bloqueios_horarios` (`id`, `profissional_id`, `data`, `hora_inicio`, `hora_fim`, `motivo`) VALUES
(1, 4, '2026-02-28', '09:00:00', '12:00:00', 'Consulta ao médico');

-- --------------------------------------------------------

--
-- Estrutura para tabela `profissionais`
--

CREATE TABLE `profissionais` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `profissionais`
--

INSERT INTO `profissionais` (`id`, `nome`, `contato`, `foto_url`, `ativo`, `usuario_id`) VALUES
(4, 'Márcio', '(51) 9976-55321', 'https://res.cloudinary.com/dqsodebo9/image/upload/v1771607517/gn579ohzekzgeb6xrn9h.jpg', 1, 5),
(5, 'Daniel', '(51)1234-56789', 'https://res.cloudinary.com/dqsodebo9/image/upload/v1771607804/ydq3pnvvbbnuuzuqpofa.jpg', 1, 6),
(6, 'André', '(51) 9876-54321', 'https://res.cloudinary.com/dqsodebo9/image/upload/v1771608555/xruo9n5jnoydqnkpco28.jpg', 1, 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `profissional_servico`
--

CREATE TABLE `profissional_servico` (
  `id` int(11) NOT NULL,
  `profissional_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `profissional_servico`
--

INSERT INTO `profissional_servico` (`id`, `profissional_id`, `servico_id`) VALUES
(10, 4, 1),
(11, 4, 2),
(12, 4, 4),
(13, 5, 1),
(14, 5, 3),
(15, 6, 1),
(16, 6, 2),
(17, 6, 3),
(18, 6, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao_minutos` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `descricao`, `preco`, `duracao_minutos`, `ativo`) VALUES
(1, 'Corte', 'Corte de cabelo clássico para todas as idades', 40.00, 30, 1),
(2, 'Barba com Hidratação', 'Modelagem e tratamento de barba com produto hidratante', 50.00, 25, 1),
(3, 'Corte + Sombrancelha', 'Corte de cabelo clássico e sombrancelha', 55.00, 40, 1),
(4, 'Corte + Barba com Hidratação', 'Corte de cabelo clássico e barba feita com hidratação', 80.00, 50, 1),
(19, 'Sombrancelha', 'Sobrancelha feita com cera', 20.00, 20, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `tipo` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `telefone`, `tipo`, `data_cadastro`) VALUES
(1, 'Administrador', 'admin@teste.com', '$2y$10$P9HGK8ZYr.MAwYAQV9cGDeQkJwgO8m6aMWbesL0QDOjmhjNf8bNze', '51888888888', 'admin', '2026-02-13 18:15:49'),
(2, 'Usuário Teste', 'usuario@teste.com', '$2y$10$7qYtZ5LlKZDMcKq6V8pqqOSlyiZ8u.NaKb3BEqfJGH4O/o0dsEq8u', '51999999999', 'cliente', '2026-02-13 18:15:09'),
(5, 'Márcio', 'marcio-barber@teste.com', '$2y$10$h.dMbB/poCPmihLHJ5pY2.JkDLmWJAeuR85YqJKYFXknu/HVIWAJO', '(51) 9976-55321', 'admin', '2026-02-20 17:11:57'),
(6, 'Daniel', 'daniel-barber@teste.com', '$2y$10$Lh6zok1dswfkYMp4wgmxyu1MnXKCeaRaNut4b0NbOHe7k/QThPIfa', '(51)1234-56789', 'admin', '2026-02-20 17:16:44'),
(8, 'André', 'andre-barber@teste.com', '$2y$10$zOy.kRIHT7r6SLcLtqS.C.bVYBN9DN7M4T4988TIbMSSvGjVXn65q', '(51) 9876-54321', 'admin', '2026-02-20 17:29:15');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `servico_id` (`servico_id`),
  ADD KEY `profissional_id` (`profissional_id`);

--
-- Índices de tabela `bloqueios_horarios`
--
ALTER TABLE `bloqueios_horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profissional_id` (`profissional_id`);

--
-- Índices de tabela `profissionais`
--
ALTER TABLE `profissionais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_profissional` (`usuario_id`);

--
-- Índices de tabela `profissional_servico`
--
ALTER TABLE `profissional_servico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profissional_id` (`profissional_id`),
  ADD KEY `servico_id` (`servico_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `bloqueios_horarios`
--
ALTER TABLE `bloqueios_horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `profissionais`
--
ALTER TABLE `profissionais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `profissional_servico`
--
ALTER TABLE `profissional_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`),
  ADD CONSTRAINT `agendamentos_ibfk_3` FOREIGN KEY (`profissional_id`) REFERENCES `profissionais` (`id`);

--
-- Restrições para tabelas `bloqueios_horarios`
--
ALTER TABLE `bloqueios_horarios`
  ADD CONSTRAINT `bloqueios_horarios_ibfk_1` FOREIGN KEY (`profissional_id`) REFERENCES `profissionais` (`id`);

--
-- Restrições para tabelas `profissionais`
--
ALTER TABLE `profissionais`
  ADD CONSTRAINT `fk_usuario_profissional` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `profissional_servico`
--
ALTER TABLE `profissional_servico`
  ADD CONSTRAINT `profissional_servico_ibfk_1` FOREIGN KEY (`profissional_id`) REFERENCES `profissionais` (`id`),
  ADD CONSTRAINT `profissional_servico_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
