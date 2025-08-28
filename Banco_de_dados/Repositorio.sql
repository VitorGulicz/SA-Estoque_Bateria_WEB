-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 14/05/2025 às 14:04
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `senai_login`
--
CREATE DATABASE IF NOT EXISTS `trabalho_sa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `trabalho_sa`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `cpf` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cliente`
--



-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `id_fornecedor` int NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contato` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_fornecedor`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `fornecedor`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

DROP TABLE IF EXISTS `funcionario`;
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id_funcionario` int NOT NULL AUTO_INCREMENT,
  `nome_funcionario` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dataDeContratacao` date DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_funcionario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `funcionario`
--



-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil`
--

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE IF NOT EXISTS `perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `nome_perfil` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_perfil`),
  UNIQUE KEY `nome_perfil` (`nome_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nome_perfil`) VALUES
(1, 'Adm'),
(3, 'Almoxarife'),
(4, 'Cliente'),
(2, 'Secretaria');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

DROP TABLE IF EXISTS `produto`;
CREATE TABLE IF NOT EXISTS `produto` (
  `id_produto` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `voltagem` varchar(10) DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `marca` varchar(50) DEFAULT NULL,
  `qtde` int DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `validade` date DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `fk_produto_id_fornecedor` (`id_fornecedor`),
  CONSTRAINT `fk_produto_id_fornecedor` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedor` (`id_fornecedor`)on delete cascade

) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produto`
--


-- --------------------------------------------------------
--
-- Estrutura da tabela `compra`
--
DROP TABLE IF EXISTS `compra`;
CREATE TABLE compra(
    cod_compra INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_cliente INTEGER NOT NULL,
    cod_produto INTEGER NOT NULL,
    cod_funcionario INTEGER NOT NULL,
    quantidade INTEGER NOT NULL,
    cod_fornecedor INTEGER NOT NULL,
    FOREIGN KEY (cod_cliente) REFERENCES cliente(id_cliente) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (cod_produto) REFERENCES produto(id_produto) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (cod_funcionario) REFERENCES funcionario(id_funcionario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (cod_fornecedor) REFERENCES fornecedor(id_fornecedor) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_perfil` int DEFAULT NULL,
  `senha_temporaria` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `id_perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `senha`, `email`, `id_perfil`, `senha_temporaria`) VALUES
(1, 'Administrador', '$2y$10$rIJhd7oXSRM1XbAdQCEsA.PF3n/rxNtIAUqCkcFybzE5J.mLBsq.q', 'admin@admin', 1, 0),
(2, 'Sergio Luiz da Silveira', '$2y$10$AKaq2b1ZyNzZs5u6ueiJq.t5xj02aj0aroz4IjHDPhdAsrhZL8MO.', 'sergio@sergio', 1, 1),
(6, 'Maria Souza', '$2y$10$RRDyLe.N/SHniQ03fG3mnuRN84K/D4wVS3BkftU7nUUFEqyOhwFDu', 'maria@empresa.com', 2, 0),
(7, 'Carlos Mendes', '$2y$10$RRDyLe.N/SHniQ03fG3mnuRN84K/D4wVS3BkftU7nUUFEqyOhwFDu', 'carlos@empresa.com', 3, 0),
(8, 'Ana Pereira', '$2y$10$xaWdXzOzYETic/DhbeHV2OZCAgBaOJzqo9j38DeAEKV2.grcV.L3u', 'ana@empresa.com', 4, 0),
(9, 'Joao Vitor', '$2y$10$2nzDym9SuKZba3OcGeUWKu7RB3CRhpVb1v.LXb9kYxBWVh1/dAG22', 'vitor@vitor', 1, 0),
(12, 'Grace Van Pelt', '$2y$10$g5h1LI20ufnY/p6062h5r.ezKU7eFlhhwRCSkuKTJiYUYulPIQjxq', 'grace@grace', 4, 0),
(13, 'Xavier', '$2y$10$ErMocH1x.avm4asmRnKzeOUF30fi4ZO33C/9H6D2opvlFZ6zEorR.', 'xavier@xavier', 1, 0);



--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
