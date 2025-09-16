-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Host: 127.0.0.1:3306
-- Banco de dados: `trabalho_sa`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `trabalho_sa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `trabalho_sa`;

-- --------------------------------------------------------
-- Tabela `cliente`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `fornecedor`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE `fornecedor` (
  `id_fornecedor` int NOT NULL AUTO_INCREMENT,
  `nome_fornecedor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contato` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_fornecedor`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `funcionario`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `funcionario`;
CREATE TABLE `funcionario` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `perfil`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `nome_perfil` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_perfil`),
  UNIQUE KEY `nome_perfil` (`nome_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `perfil` (`id_perfil`, `nome_perfil`) VALUES
(1, 'Adm'),
(2, 'Secretaria'),
(3, 'Almoxarife'),
(4, 'Cliente'),
(5, 'Funcionario');

-- --------------------------------------------------------
-- Tabela `produto`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `produto`;
CREATE TABLE `produto` (
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
  CONSTRAINT `fk_produto_id_fornecedor` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedor`(`id_fornecedor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `compra`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `cod_compra` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cod_cliente` int NOT NULL,
  `cod_produto` int NOT NULL,
  `cod_funcionario` int NOT NULL,
  `quantidade` int NOT NULL,
  `cod_fornecedor` int NOT NULL,
  `vlr_compra` decimal(10,2) DEFAULT NULL,
  FOREIGN KEY (`cod_cliente`) REFERENCES cliente(`id_cliente`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`cod_produto`) REFERENCES produto(`id_produto`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`cod_funcionario`) REFERENCES funcionario(`id_funcionario`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`cod_fornecedor`) REFERENCES fornecedor(`id_fornecedor`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabela `usuario`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_perfil` int DEFAULT NULL,
  `senha_temporaria` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES perfil(`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuario` (`id_usuario`, `nome`, `senha`, `email`, `id_perfil`, `senha_temporaria`) VALUES
(1, 'Administrador', '$2y$10$EdNKvnbs1ulqAuw0OorMbO8KvUlwyahixWbd5rC77B/VHxPeIHA5q', 'admin@admin', 1, 0),
(2, 'Sergio Luiz da Silveira', '$2y$10$AKaq2b1ZyNzZs5u6ueiJq.t5xj02aj0aroz4IjHDPhdAsrhZL8MO.', 'sergio@sergio', 1, 1),
(6, 'Maria Souza', '$2y$10$RRDyLe.N/SHniQ03fG3mnuRN84K/D4wVS3BkftU7nUUFEqyOhwFDu', 'maria@empresa.com', 2, 0),
(7, 'Carlos Mendes', '$2y$10$RRDyLe.N/SHniQ03fG3mnuRN84K/D4wVS3BkftU7nUUFEqyOhwFDu', 'carlos@empresa.com', 3, 0),
(8, 'Ana Pereira', '$2y$10$xaWdXzOzYETic/DhbeHV2OZCAgBaOJzqo9j38DeAEKV2.grcV.L3u', 'ana@empresa.com', 4, 0),
(9, 'Joao Vitor', '$2y$10$2nzDym9SuKZba3OcGeUWKu7RB3CRhpVb1v.LXb9kYxBWVh1/dAG22', 'vitor@vitor', 1, 0),
(12, 'Grace Van Pelt', '$2y$10$g5h1LI20ufnY/p6062h5r.ezKU7eFlhhwRCSkuKTJiYUYulPIQjxq', 'grace@grace', 4, 0),
(13, 'Xavier', '$2y$10$ErMocH1x.avm4asmRnKzeOUF30fi4ZO33C/9H6D2opvlFZ6zEorR.', 'xavier@xavier', 1, 0);

INSERT INTO cliente (nome_cliente, endereco, telefone, email, cpf) VALUES
('João Silva', 'Rua das Flores, 123', '(11) 99999-1111', 'joao.silva@email.com', '123.456.789-00'),
('Maria Oliveira', 'Av. Brasil, 456', '(11) 98888-2222', 'maria.oliveira@email.com', '987.654.321-00'),
('Carlos Santos', 'Rua Central, 789', '(11) 97777-3333', 'carlos.santos@email.com', '321.654.987-00');

INSERT INTO fornecedor (nome_fornecedor, cnpj, endereco, telefone, email, contato) VALUES
('Baterias Moura', '12.345.678/0001-99', 'Rodovia BR-101, Km 200', '(81) 3333-1111', 'contato@moura.com', 'Fernanda Moura'),
('Baterias Heliar', '98.765.432/0001-55', 'Av. das Indústrias, 555', '(41) 4444-2222', 'suporte@heliar.com', 'Roberto Lima'),
('Baterias Bosch', '11.222.333/0001-44', 'Rua Industrial, 88', '(11) 5555-3333', 'vendas@bosch.com', 'Cláudia Souza');

INSERT INTO funcionario (nome_funcionario, cpf, endereco, telefone, email, dataDeContratacao, cargo, salario) VALUES
('Ana Pereira', '123.456.789-10', 'Rua A, 123', '(11) 91234-5678', 'ana.pereira@empresa.com', '2022-01-15', 'Atendente', 2500.00),
('Lucas Martins', '987.654.321-00', 'Av. B, 456', '(11) 97654-3210', 'lucas.martins@empresa.com', '2021-05-20', 'Vendedor', 3000.00),
('Fernanda Costa', '111.222.333-44', 'Rua C, 789', '(11) 96543-2100', 'fernanda.costa@empresa.com', '2020-10-01', 'Gerente', 5000.00);

INSERT INTO produto (id_fornecedor, tipo, voltagem, descricao, marca, qtde, preco, validade) VALUES
(1, 'Bateria Automotiva', '12V', 'Bateria Moura 60Ah para veículos leves', 'Moura', 50, 480.00, '2026-12-31'),
(2, 'Bateria Automotiva', '12V', 'Bateria Heliar 70Ah com tecnologia EFB', 'Heliar', 40, 520.00, '2026-08-15'),
(3, 'Bateria Automotiva', '12V', 'Bateria Bosch 75Ah para carros premium', 'Bosch', 30, 600.00, '2027-01-10');

INSERT INTO usuario (nome, senha, email, id_perfil) VALUES
('Administrador', '123456', 'admin@empresa.com', 1),
('Secretária Maria', '123456', 'secretaria@empresa.com', 2),
('João Estoquista', '123456', 'almoxarife@empresa.com', 3),
('Cliente Carlos', '123456', 'cliente@empresa.com', 4);

ALTER TABLE compra ADD COLUMN data_compra DATE NOT NULL DEFAULT (CURRENT_DATE);


COMMIT;
