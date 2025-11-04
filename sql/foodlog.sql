-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 03:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodlog`
--

-- --------------------------------------------------------

--
-- Table structure for table `doacoes`
--

CREATE TABLE `doacoes` (
  `id_doacao` int(11) NOT NULL,
  `id_estabelecimento` int(11) NOT NULL,
  `nome_alimento` varchar(100) NOT NULL,
  `unidade_medida` varchar(50) NOT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `validade` date DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('disponivel','doado','expirado') NOT NULL DEFAULT 'disponivel',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doacoes`
--

INSERT INTO `doacoes` (`id_doacao`, `id_estabelecimento`, `nome_alimento`, `unidade_medida`, `quantidade`, `validade`, `descricao`, `status`, `criado_em`) VALUES
(10001, 1, 'arroz', 'kg', 10.00, '2025-11-11', 'Arroz integral buriti', 'disponivel', '2025-11-01 03:00:00'),
(10002, 1, 'feijão', 'kg', 20.00, '2025-11-11', 'Feijão caricoa', 'expirado', '2025-11-01 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `enderecos`
--

CREATE TABLE `enderecos` (
  `id_endereco` int(11) NOT NULL,
  `id_estabelecimento` int(11) DEFAULT NULL,
  `id_ong` int(11) DEFAULT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enderecos`
--

INSERT INTO `enderecos` (`id_endereco`, `id_estabelecimento`, `id_ong`, `cep`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`) VALUES
(1, 1, NULL, '123456789', 'Rua Bacalhau', '1444', 'Barigüi', 'Curitiba', 'PR');

-- --------------------------------------------------------

--
-- Table structure for table `estabelecimentos`
--

CREATE TABLE `estabelecimentos` (
  `id_estabelecimento` int(11) NOT NULL,
  `nome_estabelecimento` varchar(100) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estabelecimentos`
--

INSERT INTO `estabelecimentos` (`id_estabelecimento`, `nome_estabelecimento`, `cnpj`, `telefone`, `criado_em`) VALUES
(1, 'pizza do pierre', '238158743543', '4191234567', '2025-11-03 01:20:03'),
(2, 'Tabacaria do Guilherme', '4567834543', '473278954319', '2025-11-03 01:27:09'),
(3, 'Linguiçaria do JP', '12334654765', '4791283543', '2025-11-03 01:27:32');

-- --------------------------------------------------------

--
-- Table structure for table `ongs`
--

CREATE TABLE `ongs` (
  `id_ong` int(11) NOT NULL,
  `nome_ong` varchar(100) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requisicoes`
--

CREATE TABLE `requisicoes` (
  `id` int(11) NOT NULL,
  `id_ong` int(11) NOT NULL,
  `id_doacao` int(11) NOT NULL,
  `observacao` text DEFAULT NULL,
  `data_requisicao` timestamp NOT NULL DEFAULT current_timestamp(),
  `reposta` enum('pendente','aceito','recusado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('estabelecimento','ong','admin') NOT NULL,
  `id_estabelecimento` int(11) DEFAULT NULL,
  `id_ong` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pendente','aprovado','recusado') NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_enderecos_estabelecimentos_ongs`
-- (See below for the actual view)
--
CREATE TABLE `vw_enderecos_estabelecimentos_ongs` (
`id_endereco` int(11)
,`cep` varchar(9)
,`logradouro` varchar(255)
,`numero` varchar(20)
,`bairro` varchar(100)
,`cidade` varchar(100)
,`estado` varchar(2)
,`id_origem` int(11)
,`nome` varchar(100)
,`tipo` varchar(15)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_enderecos_estabelecimentos_ongs`
--
DROP TABLE IF EXISTS `vw_enderecos_estabelecimentos_ongs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_enderecos_estabelecimentos_ongs`  AS SELECT `e`.`id_endereco` AS `id_endereco`, `e`.`cep` AS `cep`, `e`.`logradouro` AS `logradouro`, `e`.`numero` AS `numero`, `e`.`bairro` AS `bairro`, `e`.`cidade` AS `cidade`, `e`.`estado` AS `estado`, `est`.`id_estabelecimento` AS `id_origem`, `est`.`nome_estabelecimento` AS `nome`, 'estabelecimento' AS `tipo` FROM (`enderecos` `e` join `estabelecimentos` `est` on(`e`.`id_estabelecimento` = `est`.`id_estabelecimento`))union all select `e`.`id_endereco` AS `id_endereco`,`e`.`cep` AS `cep`,`e`.`logradouro` AS `logradouro`,`e`.`numero` AS `numero`,`e`.`bairro` AS `bairro`,`e`.`cidade` AS `cidade`,`e`.`estado` AS `estado`,`o`.`id_ong` AS `id_origem`,`o`.`nome_ong` AS `nome`,'ong' AS `tipo` from (`enderecos` `e` join `ongs` `o` on(`e`.`id_ong` = `o`.`id_ong`))  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doacoes`
--
ALTER TABLE `doacoes`
  ADD PRIMARY KEY (`id_doacao`),
  ADD KEY `id_estabelecimento` (`id_estabelecimento`);

--
-- Indexes for table `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `id_ong` (`id_ong`),
  ADD KEY `id_estabelecimento` (`id_estabelecimento`);

--
-- Indexes for table `estabelecimentos`
--
ALTER TABLE `estabelecimentos`
  ADD PRIMARY KEY (`id_estabelecimento`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Indexes for table `ongs`
--
ALTER TABLE `ongs`
  ADD PRIMARY KEY (`id_ong`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Indexes for table `requisicoes`
--
ALTER TABLE `requisicoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ong` (`id_ong`),
  ADD KEY `id_doacao` (`id_doacao`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_estabelecimento` (`id_estabelecimento`),
  ADD KEY `fk_usuario_ong` (`id_ong`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doacoes`
--
ALTER TABLE `doacoes`
  MODIFY `id_doacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10003;

--
-- AUTO_INCREMENT for table `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ongs`
--
ALTER TABLE `ongs`
  MODIFY `id_ong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10001;

--
-- AUTO_INCREMENT for table `requisicoes`
--
ALTER TABLE `requisicoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10001;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10001;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doacoes`
--
ALTER TABLE `doacoes`
  ADD CONSTRAINT `doacoes_ibfk_1` FOREIGN KEY (`id_estabelecimento`) REFERENCES `estabelecimentos` (`id_estabelecimento`) ON DELETE CASCADE;

--
-- Constraints for table `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `enderecos_ibfk_1` FOREIGN KEY (`id_ong`) REFERENCES `ongs` (`id_ong`) ON DELETE CASCADE,
  ADD CONSTRAINT `enderecos_ibfk_2` FOREIGN KEY (`id_estabelecimento`) REFERENCES `estabelecimentos` (`id_estabelecimento`) ON DELETE CASCADE;

--
-- Constraints for table `requisicoes`
--
ALTER TABLE `requisicoes`
  ADD CONSTRAINT `requisicoes_ibfk_1` FOREIGN KEY (`id_ong`) REFERENCES `ongs` (`id_ong`) ON DELETE CASCADE,
  ADD CONSTRAINT `requisicoes_ibfk_2` FOREIGN KEY (`id_doacao`) REFERENCES `doacoes` (`id_doacao`) ON DELETE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_estabelecimento` FOREIGN KEY (`id_estabelecimento`) REFERENCES `estabelecimentos` (`id_estabelecimento`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario_ong` FOREIGN KEY (`id_ong`) REFERENCES `ongs` (`id_ong`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
