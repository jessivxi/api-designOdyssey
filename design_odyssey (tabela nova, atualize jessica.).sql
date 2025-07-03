-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/07/2025 às 22:01
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
-- Banco de dados: `design_odyssey`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` enum('suporte','moderador','superadmin') DEFAULT 'suporte',
  `ultimo_login` datetime DEFAULT NULL,
  `ip_acesso` varchar(45) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administradores`
--

INSERT INTO `administradores` (`id`, `nome`, `email`, `senha`, `nivel_acesso`, `ultimo_login`, `ip_acesso`, `status`) VALUES
(1, 'Administrador', 'admin@designodyssey.com', '10888aa951354acf196622be997551360cda76e0e4a62bf4bc282e1628f9009c', 'superadmin', NULL, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nota` tinyint(1) NOT NULL CHECK (`nota` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp(),
  `resposta_designer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `descricao` text NOT NULL,
  `preco_base` decimal(10,2) DEFAULT NULL,
  `icone` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome`, `descricao`, `preco_base`, `icone`) VALUES
(1, 'Logotipo Empresarial Minimalista', 'Design de logotipos clean e profissionais para empresas', NULL, ''),
(2, 'Logotipo', 'Design de logotipos profissionais', 150.00, 'logo.png'),
(3, 'Logotipo', 'Design de logotipos profissionais', 250.00, 'logo.png'),
(4, 'Logo', 'Design de logotipos divertidas', 500.00, 'logo.png'),
(5, 'Logotipo', 'Design de logotipos profissionais', 250.00, 'logo.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfis`
--

CREATE TABLE `perfis` (
  `id_perfil` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `nome_exibicao` varchar(50) DEFAULT NULL,
  `foto` varchar(100) NOT NULL,
  `tipo` enum('cliente','designer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfis`
--

INSERT INTO `perfis` (`id_perfil`, `id_usuarios`, `nome_exibicao`, `foto`, `tipo`) VALUES
(1, 0, 'Criação de Logotipo Profissional', 'logotipo.png', ''),
(2, 0, 'Maria', 'logotipo.png', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `rl_usuarios_perfis`
--

CREATE TABLE `rl_usuarios_perfis` (
  `id` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `id_perfis` int(11) NOT NULL,
  `tipo` enum('designer','cliente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `id_freelancer` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL COMMENT 'Ex: "Criarei um logotipo minimalista"',
  `descricao` text NOT NULL,
  `categoria` enum('web','grafico','ux_ui','arte_digital') NOT NULL,
  `preco_base` decimal(10,2) NOT NULL COMMENT 'Ex: 270.00 (valor mostrado como "A partir de")',
  `pacotes` text DEFAULT NULL COMMENT 'JSON com pacotes de serviço',
  `data_publicacao` datetime DEFAULT current_timestamp(),
  `destaque` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `id_freelancer`, `id_categoria`, `titulo`, `descricao`, `categoria`, `preco_base`, `pacotes`, `data_publicacao`, `destaque`) VALUES
(1, 1, 0, 'Criarei um logotipo empresarial minimalista premium', 'Pacote de logotipo inicial com 1 conceito + arquivos PNG/JPEG + 3 revisões', 'grafico', 270.00, '{\"basico\": {\"preco\": 277.22, \"descricao\": \"1 conceito incluído + 3 revisões\", \"detalhes\": [\"Arquivos PNG/JPEG\", \"Transparência\", \"Entrega em 3 dias\"]}}', '2025-05-27 13:45:29', 0),
(2, 3, 0, 'Design de Logotipo', 'Criação de logo profissional', 'grafico', 399.99, '[{\"basico\":{\"preco\":\"299.99\",\"descricao\":\"Pacote b\\u00e1sico\"}}]', '2025-07-02 05:13:57', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('designer','cliente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`) VALUES
(1, 'Luisa M.', 'luisa@exemplo.com', '55a5e9e78207b4df8699d60886fa070079463547b095d1a05bc719bb4e6cd251', 'designer'),
(2, 'cliente', 'lucas@exemplo.com', '$2y$10$SpI6fv4qDX.hRuXGNXrQpuGI86GNqbq1UQ67A9sXgSkfLA7EpgXJO', 'cliente'),
(3, 'Julia m.', 'ju1lia@exemplo.com', '$2y$10$8YXEyKdLqpaXup3XKrvIyeiuKe7mBAQWZl3LYzzB/03GCxW.EynRO', 'designer');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_servico` (`id_servico`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id_perfil`) USING BTREE,
  ADD KEY `id_usuarios` (`id_usuarios`);

--
-- Índices de tabela `rl_usuarios_perfis`
--
ALTER TABLE `rl_usuarios_perfis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuarios` (`id_usuarios`,`id_perfis`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_freelancer` (`id_freelancer`) USING BTREE,
  ADD KEY `id_categoria` (`id_categoria`);

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
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `rl_usuarios_perfis`
--
ALTER TABLE `rl_usuarios_perfis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`id_freelancer`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
