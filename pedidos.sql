-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/07/2026 às 00:54
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
-- Banco de dados: `pedidos`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `mesa_numero` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_produtos`
--

CREATE TABLE `pedido_produtos` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` float NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido_produtos`
--

INSERT INTO `pedido_produtos` (`id`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`, `created_at`, `updated_at`, `deleted_at`) VALUES
(21, 25, 1, 1, 5.9, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(22, 25, 2, 1, 18.5, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(23, 25, 3, 1, 7.2, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(24, 25, 4, 1, 9.5, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(25, 25, 5, 1, 34.9, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(26, 25, 6, 1, 6, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(27, 25, 7, 1, 8, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(28, 25, 8, 1, 9.9, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(29, 25, 12, 1, 12.5, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(30, 25, 11, 1, 16, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(31, 25, 10, 1, 22.9, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(32, 25, 9, 1, 11.5, '2026-06-30 22:15:08', '2026-06-30 22:15:08', NULL),
(33, 26, 1, 1, 5.9, '2026-06-30 19:29:43', '2026-06-30 19:29:43', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `imagem` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `categoria`, `preco`, `imagem`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Café Expresso', 'Café fresco e encorpado, servido quente.', 'Bebidas', 5.90, 'https://via.placeholder.com/260x180?text=Caf%C3%A9', '2026-06-12 17:48:22', NULL, NULL),
(2, 'Sanduíche Natural', 'Pão integral com peito de frango e salada.', 'Lanches', 18.50, 'https://via.placeholder.com/260x180?text=Sandu%C3%ADche', '2026-06-12 17:48:22', NULL, NULL),
(3, 'Suco de Laranja', 'Suco natural de laranja espremido na hora.', 'Bebidas', 7.20, 'https://via.placeholder.com/260x180?text=Suco', '2026-06-12 17:48:22', NULL, NULL),
(4, 'Porção de Batata Frita', 'Crocante e dourada, servida com molho especial.', 'Lanches', 14.00, 'https://via.placeholder.com/260x180?text=Batata+Frita', '2026-06-12 17:48:22', NULL, NULL),
(5, 'Bolo de Chocolate', 'Fatia macia com cobertura cremosa de chocolate.', 'Sobremesas', 12.90, 'https://via.placeholder.com/260x180?text=Bolo', '2026-06-12 17:48:22', NULL, NULL),
(6, 'Sundae de Morango', 'Sorvete com calda de morango e confeitos.', 'Sobremesas', 11.50, 'https://via.placeholder.com/260x180?text=Sundae', '2026-06-12 17:48:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `ativo` tinyint(4) DEFAULT 1,
  `api_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `role`, `ativo`, `api_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrador', 'admin@admin.com', '$2y$10$oPnZucFgeccqtYTHXRyhb.Y7QSKZWVK6N.B5WsZIwwDiweS/RTQs6', 'super_admin', 1, 'f2d2d48a78a1f57b282140c13ce9ab85743df7c4c2b675daa511794281a73c86', '2026-06-28 16:36:52', '2026-06-30 22:44:36', NULL),
(2, 'Pastel', 'pastel@pastel.com', '$2y$10$35a3vZG/hyIlBJ.4psm6yeby2.B5SLfrfQG.QVj4U.aPxF45eLk6i', 'user', 0, '9019b5f8200df7ab016f2d24234238132575c6a6e44c73bd43cfc010d1de8ba8', '2026-06-28 19:44:24', '2026-06-30 22:47:02', NULL),
(3, 'teste', 'teste@teste.com', '$2y$10$Xc0ZjDy/dCuPEScuRoCynOBGpEYKixO3vRo2MKWMF6CbMFQj/EHwa', 'user', 1, '390d23a0a9bf6cb437e03f49213642f9e2f8194f7efa71de313bfee87b0c9544', '2026-06-30 22:30:59', '2026-06-30 22:31:07', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedido_produtos`
--
ALTER TABLE `pedido_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
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
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `pedido_produtos`
--
ALTER TABLE `pedido_produtos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
