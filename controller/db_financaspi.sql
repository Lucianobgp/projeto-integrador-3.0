-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/08/2025 às 02:33
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_financaspi`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_banco`
--

CREATE TABLE `tb_cad_banco` (
  `id_cad_banco` int(11) NOT NULL,
  `id_cad_usuario` int(11) NOT NULL,
  `nome_banco` varchar(50) NOT NULL,
  `num_agencia` varchar(30) NOT NULL,
  `num_conta` varchar(30) NOT NULL,
  `saldo_inicial` decimal(10,2) DEFAULT 0.00,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_banco`
--

INSERT INTO `tb_cad_banco` (`id_cad_banco`, `id_cad_usuario`, `nome_banco`, `num_agencia`, `num_conta`, `saldo_inicial`, `ativo`) VALUES
(1, 1, 'NUBANK', '0001', '00011', 0.00, 1),
(2, 1, 'BANCO DO BRASIL', '0002', '00022', 0.00, 1),
(3, 1, 'CAIXA ECONOMICA', '0003', '00033', 0.00, 1),
(4, 1, 'INTER', '0004', '00044', 0.00, 1),
(5, 1, 'C6 BANK', '0005', '00055', 0.00, 1),
(6, 1, 'NAO INFORMADO', '0000', '00000', 0.00, 1),
(8, 1, 'TESTE LUCIANO FARIAS', '0005', '00022', 0.00, 1),
(10, 1, 'SMART', '1', '2', 0.00, 1),
(11, 1, 'SMART', '1', '2', 0.00, 1),
(12, 1, 'SMART', '1', '2', 0.00, 1),
(13, 1, 'SAFRA ', '09990', 'O00967', 0.00, 1),
(15, 1, 'BANCO DO BRASIL', '0004', '123456', 0.00, 1),
(16, 1, 'BANCOS DO BRASIL', '0004', '11110', 0.00, 1),
(18, 1, 'BANCO PACHECO', '0000', '0000', 0.00, 1),
(19, 1, 'TESTE', '1111', '1111', 0.00, 1),
(20, 1, 'TESTE', '2212', '123456', 0.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_bandeira`
--

CREATE TABLE `tb_cad_bandeira` (
  `id_cad_band` int(11) NOT NULL,
  `nome_band` varchar(30) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_bandeira`
--

INSERT INTO `tb_cad_bandeira` (`id_cad_band`, `nome_band`, `ativo`) VALUES
(1, 'VISA', 1),
(2, 'MASTERCARD', 1),
(3, 'ELLO', 1),
(4, 'NAO INFORMADO', 1),
(9, 'LUBANK', 1),
(10, 'MASTERLU', 1),
(11, 'PACHECO', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_cartao`
--

CREATE TABLE `tb_cad_cartao` (
  `id_cad_cartao` int(11) NOT NULL,
  `id_cad_usuario` int(11) NOT NULL,
  `id_cad_band` int(11) NOT NULL,
  `nome_cartao` varchar(50) NOT NULL,
  `num_cartao` varchar(19) NOT NULL,
  `limite` decimal(10,2) DEFAULT 0.00,
  `data_vencimento` date DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_cartao`
--

INSERT INTO `tb_cad_cartao` (`id_cad_cartao`, `id_cad_usuario`, `id_cad_band`, `nome_cartao`, `num_cartao`, `limite`, `data_vencimento`, `ativo`) VALUES
(1, 1, 3, 'C6', '0001-0002-0003-0004', 0.00, NULL, 1),
(2, 1, 2, 'NUBANK', '1000-2000-3000-4000', 0.00, NULL, 1),
(3, 1, 2, 'BRASILIA', '1111-2222-3333-4444', 0.00, NULL, 1),
(4, 1, 4, 'NAO INFORMADO', '0000-0000-0000-0000', 0.00, NULL, 1),
(6, 1, 9, 'BRUNO G LAURENTINO', '12345678910112', 0.00, NULL, 1),
(7, 1, 4, 'JOSE LAURENTINO FILHO', 'ABCD1234', 0.00, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_forma`
--

CREATE TABLE `tb_cad_forma` (
  `id_cad_forma` int(11) NOT NULL,
  `id_cad_usuario` int(11) NOT NULL,
  `desc_forma` varchar(50) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_forma`
--

INSERT INTO `tb_cad_forma` (`id_cad_forma`, `id_cad_usuario`, `desc_forma`, `ativo`) VALUES
(1, 1, 'PIX', 1),
(2, 1, 'DINHEIRO', 1),
(3, 1, 'DEBITO', 1),
(4, 1, 'TRANSFERENCIA', 1),
(6, 1, 'TESTE LUCIANO', 1),
(8, 1, 'DINHEIRO', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_plano`
--

CREATE TABLE `tb_cad_plano` (
  `id_cad_plano` int(11) NOT NULL,
  `id_cad_usuario` int(11) NOT NULL,
  `desc_plano` varchar(50) NOT NULL,
  `cor` varchar(7) DEFAULT '#007bff',
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_plano`
--

INSERT INTO `tb_cad_plano` (`id_cad_plano`, `id_cad_usuario`, `desc_plano`, `cor`, `ativo`) VALUES
(1, 1, 'DESPESAS FIXAS', '#007bff', 1),
(2, 1, 'VEICULO', '#007bff', 1),
(3, 1, 'MORADIA', '#007bff', 1),
(4, 1, 'FOLHA DE PAGAMENTO', '#007bff', 1),
(5, 1, 'VESTUARIO', '#007bff', 1),
(8, 1, 'MEDICAMENTOS DIVERSOS', '#007bff', 1),
(11, 1, 'MANUTENCAO DO VEICULO', '#007bff', 1),
(12, 1, 'PLANO', '#007bff', 1),
(13, 1, 'CONTA DE ÃGUAA', '#007bff', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_tipo`
--

CREATE TABLE `tb_cad_tipo` (
  `id_cad_tipo` int(11) NOT NULL,
  `desc_tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_tipo`
--

INSERT INTO `tb_cad_tipo` (`id_cad_tipo`, `desc_tipo`) VALUES
(1, 'RECEBIMENTO'),
(2, 'PAGAMENTO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cad_usuario`
--

CREATE TABLE `tb_cad_usuario` (
  `id_cad_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_cad_usuario`
--

INSERT INTO `tb_cad_usuario` (`id_cad_usuario`, `nome_usuario`, `email`, `senha`, `data_cadastro`, `ativo`) VALUES
(0, 'bruno', 'bruno@teste.com.br', '$2y$10$//LhrdPEMsmthfHp5qCEPeP/O1VRg69qg/ElpoibDVLpHY2DRoZQ6', '2025-08-19 00:07:26', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_lancamento`
--

CREATE TABLE `tb_lancamento` (
  `id_lanc` int(11) NOT NULL,
  `id_cad_usuario` int(11) NOT NULL,
  `id_cad_tipo` int(11) NOT NULL,
  `id_cad_plano` int(11) NOT NULL,
  `desc_lanc` varchar(150) NOT NULL,
  `data_venc` date NOT NULL,
  `valor_lanc` decimal(10,2) NOT NULL,
  `id_cad_forma` int(11) NOT NULL,
  `id_cad_banco` int(11) NOT NULL,
  `id_cad_cartao` int(11) NOT NULL,
  `data_rec_pag` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_lancamento`
--

INSERT INTO `tb_lancamento` (`id_lanc`, `id_cad_usuario`, `id_cad_tipo`, `id_cad_plano`, `desc_lanc`, `data_venc`, `valor_lanc`, `id_cad_forma`, `id_cad_banco`, `id_cad_cartao`, `data_rec_pag`, `observacoes`, `ativo`, `data_cadastro`) VALUES
(2, 0, 2, 4, 'INSS', '2025-06-10', 150.23, 3, 6, 4, NULL, NULL, 1, '2025-08-18 23:58:41'),
(3, 0, 2, 3, 'ALUGUEL', '2025-06-10', 250.00, 2, 6, 4, NULL, NULL, 1, '2025-08-18 23:58:41'),
(4, 0, 2, 5, 'CALCA JEANS', '2025-06-15', 150.25, 3, 4, 3, NULL, NULL, 1, '2025-08-18 23:58:41'),
(5, 0, 2, 3, 'ALUGUEL', '2025-07-02', 80000.00, 2, 12, 3, '0000-00-00', NULL, 1, '2025-08-18 23:58:41'),
(6, 0, 1, 1, 'DIVIDA DO LUCIANO', '2025-06-24', 8000.00, 3, 4, 2, '2025-06-24', NULL, 1, '2025-08-18 23:58:41'),
(7, 0, 1, 5, 'PRESENTE DE ANIVERSARIO DE 15 ANOS DA ELEONORA LEOPOLDINA SOFIA DE CASTRO', '2025-06-24', 80000.00, 1, 1, 2, '2025-06-24', NULL, 1, '2025-08-18 23:58:41'),
(77, 0, 1, 4, 'ALUGUEL', '2025-07-01', 18000.00, 2, 3, 3, '2025-07-01', NULL, 1, '2025-08-18 23:58:41'),
(78, 0, 1, 4, 'PAGAMENTO DO MÃªS DE JULHO - SERGIO', '2025-07-01', 20000.00, 4, 16, 1, '2025-07-01', NULL, 1, '2025-08-18 23:58:41'),
(79, 0, 2, 3, 'PAGAMENTO DE ENERGIA', '2025-08-30', 180.00, 1, 4, 4, '2025-07-01', NULL, 1, '2025-08-18 23:58:41'),
(80, 0, 2, 3, 'PAGAMENTO DE ALUGUEL', '2025-08-30', 1800.00, 1, 4, 4, '2025-07-01', NULL, 1, '2025-08-18 23:58:41'),
(81, 0, 2, 13, 'MES DE JULHO', '2025-07-08', 452.00, 1, 3, 1, '2025-07-10', NULL, 1, '2025-08-18 23:58:41'),
(94, 0, 1, 4, 'SALÃ¡RIO', '2025-07-03', 18000.00, 2, 15, 3, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(95, 0, 1, 4, 'SALÃ¡RIO', '2025-07-03', 18000.00, 2, 15, 3, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(96, 0, 1, 4, 'SALÃ¡RIO', '2025-07-03', 5000.00, 3, 4, 6, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(97, 0, 1, 4, 'SALÃ¡RIO', '2025-07-03', 5000.00, 3, 4, 6, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(98, 0, 1, 4, 'SALÃ¡RIO', '2025-07-03', 5000.00, 3, 4, 6, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(99, 0, 1, 4, 'BRUNO GOMES S2', '2025-07-03', 2800.00, 6, 8, 4, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(100, 0, 1, 4, 'BRUNO GOMES S2', '2025-07-03', 2800.00, 6, 8, 4, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(101, 0, 2, 13, 'BRUNO', '2025-07-03', 1800.00, 6, 8, 2, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(102, 0, 2, 13, 'BRUNO', '2025-07-03', 1800.00, 6, 8, 2, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(103, 0, 2, 13, 'CONTA DE AGUA', '2025-07-03', 2800.00, 3, 8, 3, '2025-07-17', NULL, 1, '2025-08-18 23:58:41'),
(105, 0, 1, 4, 'SALARIO', '2025-07-03', 2800.00, 2, 5, 6, '2025-07-03', NULL, 1, '2025-08-18 23:58:41'),
(107, 0, 2, 4, 'EMERGÊNCIA', '2025-07-03', 548.00, 1, 1, 1, NULL, NULL, 1, '2025-08-18 23:58:41'),
(108, 0, 1, 4, 'SALARIO', '2025-08-30', 5500.00, 4, 3, 4, '2025-07-04', NULL, 1, '2025-08-18 23:58:41'),
(109, 0, 2, 2, 'COMPRA DE VEÃ­CULO', '2025-07-04', 50000.00, 1, 3, 4, '2025-07-04', NULL, 1, '2025-08-18 23:58:41'),
(110, 0, 2, 3, 'ALUGUEL', '2025-07-07', 7400.00, 3, 2, 3, NULL, NULL, 1, '2025-08-18 23:58:41'),
(111, 0, 2, 3, 'ALUGUEL', '2025-07-07', 7400.00, 3, 2, 3, NULL, NULL, 1, '2025-08-18 23:58:41'),
(112, 0, 2, 3, 'ALUGUEL', '2025-07-07', 7400.00, 3, 2, 3, NULL, NULL, 1, '2025-08-18 23:58:41'),
(113, 0, 1, 13, 'AGUA', '1985-11-26', 1000.00, 3, 3, 4, '2025-07-10', NULL, 1, '2025-08-18 23:58:41'),
(114, 0, 1, 4, 'SALÃ¡RIO MÃªS DE JULHO', '2025-07-10', 18700.00, 3, 3, 4, '2025-07-15', NULL, 1, '2025-08-18 23:58:41'),
(115, 0, 1, 4, 'SALÃ¡RIO MÃªS DE JULHO', '2025-07-10', 18700.00, 3, 3, 4, '2025-07-15', NULL, 1, '2025-08-18 23:58:41'),
(116, 0, 1, 4, 'SALARIO', '2025-06-05', 2800.00, 3, 3, 4, '2025-08-06', NULL, 1, '2025-08-18 23:58:41');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_pagamento_mes_ano_atual`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_pagamento_mes_ano_atual` (
`desc_plano` varchar(50)
,`desc_lanc` varchar(150)
,`data_venc` date
,`valor_lanc` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_pagamento_soma_mes_ano_atual`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_pagamento_soma_mes_ano_atual` (
`total_pagamento` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_recebimento_mes_ano_atual`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_recebimento_mes_ano_atual` (
`desc_plano` varchar(50)
,`desc_lanc` varchar(150)
,`data_venc` date
,`valor_lanc` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_recebimento_soma_mes_ano_atual`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_recebimento_soma_mes_ano_atual` (
`total_recebimento` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estrutura para view `view_pagamento_mes_ano_atual`
--
DROP TABLE IF EXISTS `view_pagamento_mes_ano_atual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`db_financaspi`@`%` SQL SECURITY DEFINER VIEW `view_pagamento_mes_ano_atual`  AS SELECT `pc`.`desc_plano` AS `desc_plano`, `lc`.`desc_lanc` AS `desc_lanc`, `lc`.`data_venc` AS `data_venc`, `lc`.`valor_lanc` AS `valor_lanc` FROM ((`tb_lancamento` `lc` join `tb_cad_plano` `pc` on(`lc`.`id_cad_plano` = `pc`.`id_cad_plano`)) join `tb_cad_tipo` `tt` on(`lc`.`id_cad_tipo` = `tt`.`id_cad_tipo`)) WHERE `tt`.`desc_tipo` = 'PAGAMENTO' AND year(`lc`.`data_venc`) = year(curdate()) AND month(`lc`.`data_venc`) = month(curdate()) ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_pagamento_soma_mes_ano_atual`
--
DROP TABLE IF EXISTS `view_pagamento_soma_mes_ano_atual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`db_financaspi`@`%` SQL SECURITY DEFINER VIEW `view_pagamento_soma_mes_ano_atual`  AS SELECT sum(`lc`.`valor_lanc`) AS `total_pagamento` FROM (`tb_lancamento` `lc` join `tb_cad_tipo` `tt` on(`lc`.`id_cad_tipo` = `tt`.`id_cad_tipo`)) WHERE `tt`.`desc_tipo` = 'PAGAMENTO' AND year(`lc`.`data_venc`) = year(curdate()) AND month(`lc`.`data_venc`) = month(curdate()) ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_recebimento_mes_ano_atual`
--
DROP TABLE IF EXISTS `view_recebimento_mes_ano_atual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`db_financaspi`@`%` SQL SECURITY DEFINER VIEW `view_recebimento_mes_ano_atual`  AS SELECT `pc`.`desc_plano` AS `desc_plano`, `lc`.`desc_lanc` AS `desc_lanc`, `lc`.`data_venc` AS `data_venc`, `lc`.`valor_lanc` AS `valor_lanc` FROM ((`tb_lancamento` `lc` join `tb_cad_plano` `pc` on(`lc`.`id_cad_plano` = `pc`.`id_cad_plano`)) join `tb_cad_tipo` `tt` on(`lc`.`id_cad_tipo` = `tt`.`id_cad_tipo`)) WHERE `tt`.`desc_tipo` = 'RECEBIMENTO' AND year(`lc`.`data_venc`) = year(curdate()) AND month(`lc`.`data_venc`) = month(curdate()) ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_recebimento_soma_mes_ano_atual`
--
DROP TABLE IF EXISTS `view_recebimento_soma_mes_ano_atual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`db_financaspi`@`%` SQL SECURITY DEFINER VIEW `view_recebimento_soma_mes_ano_atual`  AS SELECT sum(`lc`.`valor_lanc`) AS `total_recebimento` FROM (`tb_lancamento` `lc` join `tb_cad_tipo` `tt` on(`lc`.`id_cad_tipo` = `tt`.`id_cad_tipo`)) WHERE `tt`.`desc_tipo` = 'RECEBIMENTO' AND year(`lc`.`data_venc`) = year(curdate()) AND month(`lc`.`data_venc`) = month(curdate()) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_cad_banco`
--
ALTER TABLE `tb_cad_banco`
  ADD PRIMARY KEY (`id_cad_banco`),
  ADD KEY `fk_tb_cad_banco_usuario_idx` (`id_cad_usuario`);

--
-- Índices de tabela `tb_cad_bandeira`
--
ALTER TABLE `tb_cad_bandeira`
  ADD PRIMARY KEY (`id_cad_band`);

--
-- Índices de tabela `tb_cad_cartao`
--
ALTER TABLE `tb_cad_cartao`
  ADD PRIMARY KEY (`id_cad_cartao`),
  ADD KEY `fk_tb_cad_cartao_tb_cad_bandeira1_idx` (`id_cad_band`),
  ADD KEY `fk_tb_cad_cartao_usuario_idx` (`id_cad_usuario`);

--
-- Índices de tabela `tb_cad_forma`
--
ALTER TABLE `tb_cad_forma`
  ADD PRIMARY KEY (`id_cad_forma`),
  ADD KEY `fk_tb_cad_forma_usuario_idx` (`id_cad_usuario`);

--
-- Índices de tabela `tb_cad_plano`
--
ALTER TABLE `tb_cad_plano`
  ADD PRIMARY KEY (`id_cad_plano`),
  ADD KEY `fk_tb_cad_plano_usuario_idx` (`id_cad_usuario`);

--
-- Índices de tabela `tb_cad_tipo`
--
ALTER TABLE `tb_cad_tipo`
  ADD PRIMARY KEY (`id_cad_tipo`);

--
-- Índices de tabela `tb_cad_usuario`
--
ALTER TABLE `tb_cad_usuario`
  ADD PRIMARY KEY (`id_cad_usuario`);

--
-- Índices de tabela `tb_lancamento`
--
ALTER TABLE `tb_lancamento`
  ADD PRIMARY KEY (`id_lanc`),
  ADD KEY `fk_tb_lancamento_usuario_idx` (`id_cad_usuario`),
  ADD KEY `fk_tb_lancamento_tipo_idx` (`id_cad_tipo`),
  ADD KEY `fk_tb_lancamento_plano_idx` (`id_cad_plano`),
  ADD KEY `fk_tb_lancamento_forma_idx` (`id_cad_forma`),
  ADD KEY `fk_tb_lancamento_banco_idx` (`id_cad_banco`),
  ADD KEY `fk_tb_lancamento_cartao_idx` (`id_cad_cartao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_cad_banco`
--
ALTER TABLE `tb_cad_banco`
  MODIFY `id_cad_banco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tb_cad_bandeira`
--
ALTER TABLE `tb_cad_bandeira`
  MODIFY `id_cad_band` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `tb_cad_cartao`
--
ALTER TABLE `tb_cad_cartao`
  MODIFY `id_cad_cartao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
