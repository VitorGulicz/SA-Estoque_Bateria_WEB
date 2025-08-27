-- Configurações iniciais
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS `trabalho_sa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `trabalho_sa`;

--
-- Estrutura da tabela `cliente`
--
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `idcliente` int NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `telefone` varchar(35) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idcliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Inserindo dados na tabela `cliente`
--
INSERT INTO `cliente` VALUES
(1,'João Silva','123.456.789-01','(11) 98765-4321','Av. Paulista, 1000'),
(2,'Maria Oliveira','234.567.890-12','(11) 98765-4322','Rua Augusta, 200'),
(3,'Carlos Souza','345.678.901-23','(11) 98765-4323','Rua da Consolação, 300'),
(4,'Ana Pereira','456.789.012-34','(11) 98765-4324','Alameda Santos, 400'),
(5,'Pedro Costa','567.890.123-45','(11) 98765-4325','Rua Oscar Freire, 500'),
(6,'Juliana Santos','678.901.234-56','(11) 98765-4326','Av. Brigadeiro Faria Lima, 600'),
(7,'Marcos Lima','789.012.345-67','(11) 98765-4327','Rua Haddock Lobo, 700'),
(8,'Fernanda Rocha','890.123.456-78','(11) 98765-4328','Av. Rebouças, 800'),
(9,'Ricardo Alves','901.234.567-89','(11) 98765-4329','Rua Pamplona, 900'),
(10,'Patrícia Nunes','012.345.678-90','(11) 98765-4330','Rua Bela Cintra, 100'),
(11,'Lucas Mendes','111.222.333-44','(11) 98765-4331','Av. Angélica, 1100'),
(12,'Amanda Ferreira','222.333.444-55','(11) 98765-4332','Rua Peixoto Gomide, 1200'),
(13,'Roberto Gomes','333.444.555-66','(11) 98765-4333','Alameda Jaú, 1300'),
(14,'Tatiana Dias','444.555.666-77','(11) 98765-4334','Rua Estados Unidos, 1400'),
(15,'Felipe Castro','555.666.777-88','(11) 98765-4335','Av. Europa, 1500'),
(16,'Vanessa Martins','666.777.888-99','(11) 98765-4336','Rua Dr. Melo Alves, 1600'),
(17,'Gustavo Barbosa','777.888.999-00','(11) 98765-4337','Av. Nove de Julho, 1700'),
(18,'Daniela Cardoso','888.999.000-11','(11) 98765-4338','Rua São Bento, 1800'),
(19,'Eduardo Ramos','999.000.111-22','(11) 98765-4339','Rua Líbero Badaró, 1900'),
(20,'Camila Teixeira','000.111.222-33','(11) 98765-4340','Av. Ipiranga, 2000'),
(21,'Rodrigo Azevedo','111.222.333-45','(11) 98765-4341','Rua Xavier de Toledo, 2100'),
(22,'Simone Carvalho','222.333.444-56','(11) 98765-4342','Av. São Luís, 2200'),
(23,'Hugo Pinto','333.444.555-67','(11) 98765-4343','Rua Barão de Itapetininga, 2300'),
(24,'Larissa Moura','444.555.666-78','(11) 98765-4344','Av. Juscelino Kubitschek, 2400'),
(25,'Alexandre Cunha','555.666.777-89','(11) 98765-4345','Rua Funchal, 2500'),
(26,'Bianca Freitas','666.777.888-90','(11) 98765-4346','Av. Presidente Vargas, 2600'),
(27,'Diego Andrade','777.888.999-01','(11) 98765-4347','Rua do Carmo, 2700'),
(28,'Renata Siqueira','888.999.000-12','(11) 98765-4348','Av. Rio Branco, 2800'),
(29,'Vitor Monteiro','999.000.111-23','(11) 98765-4349','Rua da Quitanda, 2900'),
(30,'Helena Borges','000.111.222-34','(11) 98765-4350','Av. Vieira Souto, 3000');

--
-- Estrutura da tabela `funcionario`
--
DROP TABLE IF EXISTS `funcionario`;
CREATE TABLE `funcionario` (
  `idfuncionario` int NOT NULL AUTO_INCREMENT,
  `cpf` varchar(14) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dataDeContratacao` date DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idfuncionario`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Inserindo dados na tabela `funcionario`
--
INSERT INTO `funcionario` VALUES
(1,'111.222.333-01','José Carlos','(11) 91234-5678','jose@bateriasauto.com.br','2020-01-15','Gerente',5000.00,'Rua Tutoia, 100'),
(2,'222.333.444-02','Ana Maria','(11) 92345-6789','ana@bateriasauto.com.br','2020-02-20','Vendedor',3000.00,'Rua Vergueiro, 200'),
(3,'333.444.555-03','Carlos Eduardo','(11) 93456-7890','carlos@bateriasauto.com.br','2020-03-10','Vendedor',3000.00,'Rua Cubatão, 300'),
(4,'444.555.666-04','Mariana Silva','(11) 94567-8901','mariana@bateriasauto.com.br','2020-04-05','Caixa',2500.00,'Av. Pacaembu, 400'),
(5,'555.666.777-05','Paulo Roberto','(11) 95678-9012','paulo@bateriasauto.com.br','2020-05-12','Técnico',3500.00,'Rua Cardoso de Almeida, 500'),
(6,'666.777.888-06','Fernanda Lima','(11) 96789-0123','fernanda@bateriasauto.com.br','2020-06-18','Vendedor',3000.00,'Av. Sumaré, 600'),
(7,'777.888.999-07','Ricardo Oliveira','(11) 97890-1234','ricardo@bateriasauto.com.br','2020-07-22','Vendedor',3000.00,'Rua Teodoro Sampaio, 700'),
(8,'888.999.000-08','Patrícia Souza','(11) 98901-2345','patricia@bateriasauto.com.br','2020-08-30','Caixa',2500.00,'Rua Piauí, 800'),
(9,'999.000.111-09','Marcos Antonio','(11) 99012-3456','marcos@bateriasauto.com.br','2020-09-14','Técnico',3500.00,'Rua dos Pinheiros, 900'),
(10,'000.111.222-10','Juliana Costa','(11) 90123-4567','juliana@bateriasauto.com.br','2020-10-05','Vendedor',3000.00,'Rua Wisard, 1000'),
(11,'111.222.333-11','Luciana Pereira','(11) 91234-5679','luciana@bateriasauto.com.br','2020-11-11','Vendedor',3000.00,'Rua Joaquim Floriano, 1100'),
(12,'222.333.444-12','Roberto Santos','(11) 92345-6780','roberto@bateriasauto.com.br','2020-12-03','Gerente',5000.00,'Rua Olimpíadas, 1200'),
(13,'333.444.555-13','Tatiane Almeida','(11) 93456-7891','tatiane@bateriasauto.com.br','2021-01-07','Caixa',2500.00,'Rua Hungria, 1300'),
(14,'444.555.666-14','Felipe Nunes','(11) 94567-8902','felipe@bateriasauto.com.br','2021-02-09','Técnico',3500.00,'Rua Groenlândia, 1400'),
(15,'555.666.777-15','Vanessa Mendes','(11) 95678-9013','vanessa@bateriasauto.com.br','2021-03-15','Vendedor',3000.00,'Rua Estados Unidos, 1500'),
(16,'666.777.888-16','Daniel Castro','(11) 96789-0124','daniel@bateriasauto.com.br','2021-04-20','Vendedor',3000.00,'Rua Bandeira Paulista, 1600'),
(17,'777.888.999-17','Camila Rocha','(11) 97890-1235','camila@bateriasauto.com.br','2021-05-25','Caixa',2500.00,'Rua Alvorada, 1700'),
(18,'888.999.000-18','Gustavo Dias','(11) 98901-2346','gustavo@bateriasauto.com.br','2021-06-30','Técnico',3500.00,'Rua Amauri, 1800'),
(19,'999.000.111-19','Amanda Teixeira','(11) 99012-3457','amanda@bateriasauto.com.br','2021-07-05','Vendedor',3000.00,'Rua Butantã, 1900'),
(20,'000.111.222-20','Rafael Martins','(11) 90123-4568','rafael@bateriasauto.com.br','2021-08-10','Vendedor',3000.00,'Rua Pirajussara, 2000'),
(21,'111.222.333-21','Larissa Gomes','(11) 91234-5670','larissa@bateriasauto.com.br','2021-09-15','Gerente',5000.00,'Rua Cerro Corá, 2100'),
(22,'222.333.444-22','Eduardo Barbosa','(11) 92345-6781','eduardo@bateriasauto.com.br','2021-10-20','Caixa',2500.00,'Rua Harmonia, 2200'),
(23,'333.444.555-23','Simone Cardoso','(11) 93456-7892','simone@bateriasauto.com.br','2021-11-25','Técnico',3500.00,'Rua Purpurina, 2300'),
(24,'444.555.666-24','Hugo Ramos','(11) 94567-8903','hugo@bateriasauto.com.br','2021-12-30','Vendedor',3000.00,'Rua Aspicuelta, 2400'),
(25,'555.666.777-25','Bianca Azevedo','(11) 95678-9014','bianca@bateriasauto.com.br','2022-01-05','Vendedor',3000.00,'Rua Fradique Coutinho, 2500'),
(26,'666.777.888-26','Diego Carvalho','(11) 96789-0125','diego@bateriasauto.com.br','2022-02-10','Caixa',2500.00,'Rua Mourato Coelho, 2600'),
(27,'777.888.999-27','Renata Pinto','(11) 97890-1236','renata@bateriasauto.com.br','2022-03-15','Técnico',3500.00,'Rua dos Coropés, 2700'),
(28,'888.999.000-28','Vitor Moura','(11) 98901-2347','vitor@bateriasauto.com.br','2022-04-20','Vendedor',3000.00,'Rua Heitor Penteado, 2800'),
(29,'999.000.111-29','Helena Cunha','(11) 99012-3458','helena@bateriasauto.com.br','2022-05-25','Vendedor',3000.00,'Rua Medeiros de Albuquerque, 2900'),
(30,'000.111.222-30','Rodrigo Freitas','(11) 90123-4569','rodrigo@bateriasauto.com.br','2022-06-30','Gerente',5000.00,'Rua Girassol, 3000');

--
-- Estrutura da tabela `fornecedor`
--
DROP TABLE IF EXISTS `fornecedor`;
CREATE TABLE `fornecedor` (
  `idfornecedor` int NOT NULL AUTO_INCREMENT,
  `fornecedores` varchar(100) DEFAULT NULL,
  `cpf` varchar(18) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `produto` varchar(100) DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  PRIMARY KEY (`idfornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Inserindo dados na tabela `fornecedor`
--
INSERT INTO `fornecedor` VALUES
(1,'Moura Baterias','12.345.678/0001-01','(11) 91234-5678','vendas@moura.com.br','Av. Industrial, 1000','Baterias automotivas',500),
(2,'Heliar','23.456.789/0001-02','(11) 92345-6789','contato@heliar.com.br','Rua das Indústrias, 200','Baterias automotivas',600),
(3,'ACDelco','34.567.890/0001-03','(11) 93456-7890','vendas@acdelco.com.br','Av. Automotiva, 300','Baterias para veículos',400),
(4,'Zetta','45.678.901/0001-04','(11) 94567-8901','contato@zetta.com.br','Rua dos Componentes, 400','Baterias seladas',300),
(5,'Tudor','56.789.012/0001-05','(11) 95678-9012','vendas@tudor.com.br','Av. dos Fornecedores, 500','Baterias de chumbo-ácido',350),
(6,'Cobat','67.890.123/0001-06','(11) 96789-0123','contato@cobat.com.br','Rua das Baterias, 600','Baterias recicladas',200),
(7,'Johnson Controls','78.901.234/0001-07','(11) 97890-1234','vendas@johnsoncontrols.com','Av. Internacional, 700','Baterias premium',250),
(8,'Bosch','89.012.345/0001-08','(11) 98901-2345','contato@bosch.com.br','Rua Tecnológica, 800','Baterias de alta performance',300),
(9,'Yuasa','90.123.456/0001-09','(11) 99012-3456','vendas@yuasa.com.br','Av. Japonesa, 900','Baterias importadas',180),
(10,'Hankook','01.234.567/0001-10','(11) 90123-4567','contato@hankook.com.br','Rua Coreana, 1000','Baterias para carros coreanos',220),
(11,'Baterias Ajax','12.345.678/0001-11','(11) 91234-5679','vendas@ajaxbaterias.com.br','Av. Nacional, 1100','Baterias nacionais',280),
(12,'Baterias Duran','23.456.789/0001-12','(11) 92345-6780','contato@duran.com.br','Rua das Peças, 1200','Baterias duráveis',240),
(13,'Baterias Top','34.567.890/0001-13','(11) 93456-7891','vendas@topbaterias.com.br','Av. Qualidade, 1300','Baterias premium',190),
(14,'Baterias Forte','45.678.901/0001-14','(11) 94567-8902','contato@fortebaterias.com.br','Rua Resistência, 1400','Baterias reforçadas',210),
(15,'Baterias Master','56.789.012/0001-15','(11) 95678-9013','vendas@masterbaterias.com.br','Av. Especializada, 1500','Baterias especiais',170),
(16,'Baterias Turbo','67.890.123/0001-16','(11) 96789-0124','contato@turbobaterias.com.br','Rua Veloz, 1600','Baterias de alta carga',160),
(17,'Baterias Power','78.901.234/0001-17','(11) 97890-1235','vendas@powerbaterias.com.br','Av. Energética, 1700','Baterias potentes',150),
(18,'Baterias Max','89.012.345/0001-18','(11) 98901-2346','contato@maxbaterias.com.br','Rua Máxima, 1800','Baterias de longa duração',140),
(19,'Baterias Ultra','90.123.456/0001-19','(11) 99012-3457','vendas@ultrabaterias.com.br','Av. Superior, 1900','Baterias ultra resistentes',130),
(20,'Baterias Gold','01.234.567/0001-20','(11) 90123-4568','contato@goldbaterias.com.br','Rua Premium, 2000','Baterias premium',120),
(21,'Baterias Silver','12.345.678/0001-21','(11) 91234-5670','vendas@silverbaterias.com.br','Av. Prata, 2100','Baterias intermediárias',110),
(22,'Baterias Bronze','23.456.789/0001-22','(11) 92345-6781','contato@bronzebaterias.com.br','Rua Básica, 2200','Baterias econômicas',100),
(23,'Baterias Eco','34.567.890/0001-23','(11) 93456-7892','vendas@ecobaterias.com.br','Av. Ecológica, 2300','Baterias ecológicas',90),
(24,'Baterias Plus','45.678.901/0001-24','(11) 94567-8903','contato@plusbaterias.com.br','Rua Adicional, 2400','Baterias com garantia estendida',80),
(25,'Baterias Extra','56.789.012/0001-25','(11) 95678-9014','vendas@extrabaterias.com.br','Av. Complementar, 2500','Baterias com tecnologia extra',70),
(26,'Baterias Super','67.890.123/0001-26','(11) 96789-0125','contato@superbaterias.com.br','Rua Excelência, 2600','Baterias superiores',60),
(27,'Baterias Mega','78.901.234/0001-27','(11) 97890-1236','vendas@megabaterias.com.br','Av. Gigante, 2700','Baterias de grande capacidade',50),
(28,'Baterias Giga','89.012.345/0001-28','(11) 98901-2347','contato@gigabaterias.com.br','Rua Colossal, 2800','Baterias de alta performance',40),
(29,'Baterias Tera','90.123.456/0001-29','(11) 99012-3458','vendas@terabaterias.com.br','Av. Monumental, 2900','Baterias de última geração',30),
(30,'Baterias Peta','01.234.567/0001-30','(11) 90123-4569','contato@petabaterias.com.br','Rua Suprema, 3000','Baterias tecnológicas',20);

--
-- Estrutura da tabela `produto`
--
DROP TABLE IF EXISTS `produto`;
CREATE TABLE `produto` (
  `idproduto` int NOT NULL AUTO_INCREMENT,
  `idfornecedor` int NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `voltagem` varchar(10) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`idproduto`),
  KEY `fk_produto_idfornecedor` (`idfornecedor`),
  CONSTRAINT `fk_produto_idfornecedor` FOREIGN KEY (`idfornecedor`) REFERENCES `fornecedor` (`idfornecedor`)on delete cascade
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Inserindo dados na tabela `produto`
--
INSERT INTO `produto` VALUES
(1,1,'Bateria Automotiva','12V','Moura',50,350.00,'2023-01-10'),
(2,2,'Bateria Automotiva','12V','Heliar',60,320.00,'2023-01-15'),
(3,3,'Bateria para Caminhões','24V','ACDelco',30,650.00,'2023-01-20'),
(4,4,'Bateria Selada','12V','Zetta',40,400.00,'2023-02-05'),
(5,5,'Bateria de Chumbo-Ácido','12V','Tudor',45,280.00,'2023-02-10'),
(6,6,'Bateria Reciclada','12V','Cobat',35,220.00,'2023-02-15'),
(7,7,'Bateria Premium','12V','Johnson Controls',25,500.00,'2023-03-01'),
(8,8,'Bateria de Alta Performance','12V','Bosch',30,450.00,'2023-03-05'),
(9,9,'Bateria Importada','12V','Yuasa',20,480.00,'2023-03-10'),
(10,10,'Bateria para Carros Coreanos','12V','Hankook',25,380.00,'2023-03-15'),
(11,11,'Bateria Nacional','12V','Ajax',40,300.00,'2023-04-01'),
(12,12,'Bateria Durável','12V','Duran',35,350.00,'2023-04-05'),
(13,13,'Bateria Premium','12V','Top',30,420.00,'2023-04-10'),
(14,14,'Bateria Reforçada','12V','Forte',25,370.00,'2023-04-15'),
(15,15,'Bateria Especial','12V','Master',20,400.00,'2023-05-01'),
(16,16,'Bateria de Alta Carga','12V','Turbo',18,450.00,'2023-05-05'),
(17,17,'Bateria Potente','12V','Power',22,380.00,'2023-05-10'),
(18,18,'Bateria de Longa Duração','12V','Max',24,420.00,'2023-05-15'),
(19,19,'Bateria Ultra Resistente','12V','Ultra',20,460.00,'2023-06-01'),
(20,20,'Bateria Premium','12V','Gold',15,520.00,'2023-06-05'),
(21,21,'Bateria Intermediária','12V','Silver',28,340.00,'2023-06-10'),
(22,22,'Bateria Econômica','12V','Bronze',32,280.00,'2023-06-15'),
(23,23,'Bateria Ecológica','12V','Eco',25,360.00,'2023-07-01'),
(24,24,'Bateria com Garantia Estendida','12V','Plus',20,390.00,'2023-07-05'),
(25,25,'Bateria com Tecnologia Extra','12V','Extra',18,410.00,'2023-07-10'),
(26,26,'Bateria Superior','12V','Super',16,440.00,'2023-07-15'),
(27,27,'Bateria de Grande Capacidade','12V','Mega',14,470.00,'2023-08-01'),
(28,28,'Bateria de Alta Performance','12V','Giga',12,500.00,'2023-08-05'),
(29,29,'Bateria de Última Geração','12V','Tera',10,550.00,'2023-08-10'),
(30,30,'Bateria Tecnológica','12V','Peta',8,600.00,'2023-08-15');



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
    FOREIGN KEY (cod_cliente) REFERENCES cliente(idcliente) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (cod_produto) REFERENCES produto(idproduto) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (cod_funcionario) REFERENCES funcionario(idfuncionario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (cod_fornecedor) REFERENCES fornecedor(idfornecedor) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;


--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `COD_USER` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
   PRIMARY KEY (`COD_USER`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`COD_USER`, `usuario`, `senha`) VALUES
(1, 'user1', 'senha1'),
(2, 'user2', 'senha2'),
(3, 'user3', 'senha3'),
(4, 'user4', 'senha4'),
(5, 'user5', 'senha5'),





CREATE TABLE `adm` (
  `COD_ADM` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
   PRIMARY KEY (`COD_ADM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `adm` (`COD_ADM`, `usuario`, `senha`) VALUES
(1, 'admin1', 'senha1');
