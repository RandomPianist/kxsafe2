-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/10/2024 às 10:49
-- Versão do servidor: 8.0.39-0ubuntu0.22.04.1
-- Versão do PHP: 8.1.2-1ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `kxsafe_`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atribuicoes`
--

CREATE TABLE `atribuicoes` (
  `id` int NOT NULL,
  `funcionario_ou_setor_chave` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `funcionario_ou_setor_valor` int DEFAULT NULL,
  `produto_ou_referencia_chave` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `produto_ou_referencia_valor` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `qtd` decimal(10,5) DEFAULT NULL,
  `validade` int DEFAULT NULL,
  `obrigatorio` tinyint DEFAULT '0',
  `lixeira` tinyint DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `baixas`
--

CREATE TABLE `baixas` (
  `id` int NOT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `acrescimo` decimal(8,2) DEFAULT NULL,
  `desconto` decimal(8,2) DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `id_titulo` int DEFAULT NULL,
  `id_forma` int DEFAULT NULL,
  `id_tpbxa` int DEFAULT NULL,
  `id_plano` int DEFAULT NULL,
  `id_conta` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bancos`
--

CREATE TABLE `bancos` (
  `id` int NOT NULL,
  `cod` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bancos`
--

INSERT INTO `bancos` (`id`, `cod`, `descr`, `created_at`, `updated_at`) VALUES
(1, '341', 'ITAÚ', '2024-10-11 19:34:15', '2024-10-11 19:34:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cep`
--

CREATE TABLE `cep` (
  `id` int NOT NULL,
  `cod` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_tipo` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_tipo_abv` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_intervalo_min` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_intervalo_max` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_ibge_uf` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_ibge_cidade` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cidade` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bairro` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uf` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cfop`
--

CREATE TABLE `cfop` (
  `id` int NOT NULL,
  `cfop` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descr` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cfop`
--

INSERT INTO `cfop` (`id`, `cfop`, `descr`, `created_at`, `updated_at`) VALUES
(1, '5551', 'Venda de bem do ativo imobilizado no mesmo estado', '2024-10-11 19:41:54', '2024-10-11 19:41:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `concessoes`
--

CREATE TABLE `concessoes` (
  `id` int NOT NULL,
  `inicio` decimal(8,2) DEFAULT NULL,
  `id_franqueadora` int DEFAULT NULL,
  `id_franquia` int DEFAULT NULL,
  `id_maquina` int DEFAULT NULL,
  `id_nota` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `condicoes`
--

CREATE TABLE `condicoes` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas`
--

CREATE TABLE `contas` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agencia` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `conta` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pix` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedente` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nossnum` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_banco` int DEFAULT NULL,
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int NOT NULL,
  `razao_social` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nome_fantasia` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cnpj` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ie` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo_contribuicao` int DEFAULT NULL,
  `tipo` int DEFAULT NULL,
  `royalties` decimal(8,2) DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_grupo` int DEFAULT NULL,
  `id_segmento` int DEFAULT NULL,
  `id_matriz` int DEFAULT NULL,
  `id_criadora` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas_usuarios`
--

CREATE TABLE `empresas_usuarios` (
  `id` int NOT NULL,
  `id_empresa` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int NOT NULL,
  `numero` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complemento` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `referencia` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_empresa` int DEFAULT NULL,
  `id_cep` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int NOT NULL,
  `qtd` decimal(10,5) DEFAULT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `es` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_li` int DEFAULT NULL,
  `id_ni` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `faturas`
--

CREATE TABLE `faturas` (
  `id` int NOT NULL,
  `rp` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `emissao` date DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `ndoc` int DEFAULT NULL,
  `parcela` int DEFAULT NULL,
  `pago` decimal(8,2) DEFAULT NULL,
  `id_tpdoc` int DEFAULT NULL,
  `id_nota` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas`
--

CREATE TABLE `formas` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `intervalo` int DEFAULT NULL,
  `vtaxa` decimal(6,2) DEFAULT NULL,
  `ptaxa` decimal(8,5) DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_condicao` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int NOT NULL,
  `nome` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cpf` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `funcao` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admissao` date DEFAULT NULL,
  `senha` int DEFAULT NULL,
  `foto` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pis` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supervisor` tinyint DEFAULT '0',
  `rosto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `id_setor` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos`
--

CREATE TABLE `grupos` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `grupos`
--

INSERT INTO `grupos` (`id`, `descr`, `lixeira`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Grupo Target', 0, 1, '2024-10-11 18:54:33', '2024-10-11 18:54:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `impostos`
--

CREATE TABLE `impostos` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sigla` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `porcentagem` decimal(10,5) DEFAULT NULL,
  `valor` decimal(10,5) DEFAULT NULL,
  `id_ni` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int NOT NULL,
  `descr` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `preco` decimal(8,2) DEFAULT NULL,
  `referencia` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tamanho` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validade` int DEFAULT NULL,
  `ca` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validade_ca` date DEFAULT NULL,
  `detalhes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `foto` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_externo` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_ou_id` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `consumo` tinyint DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_categoria` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `locais`
--

CREATE TABLE `locais` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `locais`
--

INSERT INTO `locais` (`id`, `descr`, `lixeira`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Camboriú', 0, 1, '2024-10-11 19:50:49', '2024-10-11 19:50:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `locais_itens`
--

CREATE TABLE `locais_itens` (
  `id` int NOT NULL,
  `descr` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `minimo` decimal(10,5) DEFAULT NULL,
  `maximo` decimal(10,5) DEFAULT NULL,
  `id_local` int DEFAULT NULL,
  `id_item` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log`
--

CREATE TABLE `log` (
  `id` int NOT NULL,
  `tabela` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nome` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `acao` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fk` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `maquinas`
--

CREATE TABLE `maquinas` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_local` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ordem` int DEFAULT NULL,
  `id_pai` int DEFAULT NULL,
  `id_modulo` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `menu`
--

INSERT INTO `menu` (`id`, `descr`, `url`, `ordem`, `id_pai`, `id_modulo`, `created_at`, `updated_at`) VALUES
(1, 'Franqueadoras', 'franqueadoras/grupo/0', 1, 0, 1, '2024-10-09 10:52:49', '2024-10-10 13:43:13'),
(2, 'Franquias', 'franquias/grupo/0', 2, 0, 1, '2024-10-09 10:52:49', '2024-10-10 13:43:21'),
(3, 'Clientes', 'clientes/grupo/0', 3, 0, 1, '2024-10-09 10:52:49', '2024-10-10 13:43:27'),
(4, 'Fornecedores', 'fornecedores/grupo/0', 4, 0, 1, '2024-10-09 10:52:49', '2024-10-10 13:43:35'),
(5, 'Grupo empresarial', 'grupos', 5, 0, 1, '2024-10-09 10:52:49', '2024-10-11 18:13:33'),
(6, 'Segmentos', 'segmentos', 6, 0, 1, '2024-10-09 10:52:49', '2024-10-11 18:13:24'),
(7, 'Setores', 'setores', 7, 0, 1, '2024-10-09 10:52:49', '2024-10-11 18:13:37'),
(8, 'Funcionários', 'funcionarios', 8, 0, 1, '2024-10-09 10:52:49', '2024-10-14 18:02:02'),
(9, 'Supervisores', NULL, 9, 0, 1, '2024-10-09 10:52:49', '2024-10-09 10:52:49'),
(10, 'Logradouros', NULL, 10, 0, 1, '2024-10-09 10:52:49', '2024-10-09 14:17:03'),
(11, 'Locais de estoque', 'locais', 1, 0, 2, '2024-10-09 10:55:28', '2024-10-11 18:13:46'),
(12, 'Itens por local', NULL, 2, 0, 2, '2024-10-09 10:55:28', '2024-10-09 10:55:28'),
(13, 'Máquinas', NULL, 3, 0, 2, '2024-10-09 10:55:28', '2024-10-09 10:55:28'),
(14, 'Itens', 'itens', 4, 0, 2, '2024-10-09 10:55:28', '2024-10-14 17:26:45'),
(15, 'Naturezas do documento', 'naturezas', 1, 0, 3, '2024-10-09 10:56:43', '2024-10-14 11:15:10'),
(16, 'Operações', NULL, 2, 0, 3, '2024-10-09 10:56:43', '2024-10-09 10:56:43'),
(17, 'Condições de pagamento', NULL, 3, 0, 3, '2024-10-09 10:56:43', '2024-10-09 10:56:43'),
(18, 'Tipos de documento', 'tpdoc', 1, 0, 4, '2024-10-09 10:59:11', '2024-10-11 18:13:59'),
(19, 'Tipos de baixa', NULL, 2, 0, 4, '2024-10-09 10:59:11', '2024-10-09 10:59:11'),
(20, 'Formas de pagamento', NULL, 3, 0, 4, '2024-10-09 10:59:11', '2024-10-09 10:59:11'),
(21, 'Plano de contas', NULL, 4, 0, 4, '2024-10-09 10:59:11', '2024-10-09 10:59:11'),
(22, 'Contas bancárias', NULL, 5, 0, 4, '2024-10-09 10:59:11', '2024-10-09 10:59:11'),
(24, 'Concessões', NULL, 1, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(25, 'Baixa de documento', NULL, 2, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(26, 'Movimento de caixa', NULL, 3, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(27, 'Emissão de nota', NULL, 4, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(28, 'Retirada avulsa', NULL, 5, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(29, 'Movimento de estoque', NULL, 6, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(30, 'Titulo avulso', NULL, 7, 0, 5, '2024-10-09 11:05:39', '2024-10-09 11:05:39'),
(31, 'Novo', 'franqueadoras/crud?id=0&id_matriz=0&id_grupo=0', NULL, 1, 1, '2024-10-09 12:31:47', '2024-10-10 12:15:51'),
(32, 'Novo', 'franquias/crud?id=0&id_matriz=0&id_grupo=0', NULL, 2, 1, '2024-10-09 12:31:47', '2024-10-10 12:16:05'),
(33, 'Novo', 'clientes/crud?id=0&id_matriz=0&id_grupo=0', NULL, 3, 1, '2024-10-09 12:31:47', '2024-10-10 12:16:21'),
(34, 'Novo', 'fornecedores/crud?id=0&id_matriz=0&id_grupo=0', NULL, 4, 1, '2024-10-09 12:31:47', '2024-10-10 12:16:35'),
(35, 'Novo', 'grupos/crud/0', NULL, 5, 1, '2024-10-09 12:31:47', '2024-10-11 18:33:30'),
(36, 'Novo', 'segmentos/crud/0', NULL, 6, 1, '2024-10-09 12:31:47', '2024-10-11 18:34:24'),
(37, 'Novo', 'setores/crud/0', NULL, 7, 1, '2024-10-09 12:31:47', '2024-10-11 18:34:59'),
(38, 'Novo', 'funcionarios/crud/0', NULL, 8, 1, '2024-10-09 12:31:47', '2024-10-14 18:02:18'),
(39, 'Novo', NULL, NULL, 9, 1, '2024-10-09 12:31:47', '2024-10-09 12:32:15'),
(40, 'Novo', NULL, NULL, 10, 1, '2024-10-09 12:31:47', '2024-10-09 12:32:15'),
(46, 'Usuários', 'usuarios', 11, 0, 1, '2024-10-09 14:15:57', '2024-10-09 14:15:57'),
(47, 'Novo', 'usuarios/crud/0', NULL, 46, 1, '2024-10-09 14:16:22', '2024-10-11 17:47:22'),
(48, 'Categoria', 'categorias', 12, 0, 1, '2024-10-09 19:53:17', '2024-10-11 17:47:10'),
(49, 'Novo', 'categorias/crud/0', NULL, 48, 1, '2024-10-10 12:07:13', '2024-10-11 17:47:19'),
(50, 'Novo', 'locais/crud/0', NULL, 11, 2, '2024-10-11 18:36:10', '2024-10-11 18:39:35'),
(51, 'Novo', 'naturezas/crud/0', NULL, 15, 3, '2024-10-11 18:37:35', '2024-10-11 18:39:50'),
(52, 'Novo', 'tpdoc/crud/0', NULL, 18, 4, '2024-10-11 18:40:37', '2024-10-11 18:41:17'),
(53, 'Bancos', 'bancos', 13, 0, 1, '2024-10-11 19:21:22', '2024-10-11 19:21:22'),
(54, 'CFOP', 'cfop', 14, 0, 1, '2024-10-11 19:21:55', '2024-10-11 19:21:55'),
(55, NULL, 'itens/crud/0', NULL, 14, 2, '2024-10-14 17:27:34', '2024-10-14 17:27:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu_perfis`
--

CREATE TABLE `menu_perfis` (
  `id` int NOT NULL,
  `id_menu` int DEFAULT NULL,
  `tipo` int DEFAULT NULL,
  `admin` tinyint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `menu_perfis`
--

INSERT INTO `menu_perfis` (`id`, `id_menu`, `tipo`, `admin`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(2, 2, 1, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(3, 3, 2, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(4, 4, 1, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(5, 5, 1, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(6, 5, 2, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(7, 6, 1, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(8, 6, 2, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(9, 7, 3, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(10, 8, 3, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(11, 9, 3, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(12, 10, 1, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(13, 10, 2, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(14, 10, 3, 0, '2024-10-09 11:10:08', '2024-10-11 17:25:37'),
(15, 11, 1, 0, '2024-10-09 11:11:08', '2024-10-11 17:25:37'),
(16, 11, 2, 0, '2024-10-09 11:11:08', '2024-10-11 17:25:37'),
(17, 12, 1, 0, '2024-10-09 11:11:08', '2024-10-11 17:25:37'),
(18, 12, 2, 0, '2024-10-09 11:11:08', '2024-10-11 17:25:37'),
(19, 13, 1, 0, '2024-10-09 11:11:08', '2024-10-11 17:25:37'),
(20, 14, 1, 0, '2024-10-09 11:11:08', '2024-10-11 17:25:37'),
(21, 15, 1, 0, '2024-10-09 11:11:52', '2024-10-11 17:25:37'),
(22, 15, 2, 0, '2024-10-09 11:11:52', '2024-10-11 17:25:37'),
(23, 16, 1, 0, '2024-10-09 11:11:52', '2024-10-11 17:25:37'),
(24, 17, 1, 0, '2024-10-09 11:11:52', '2024-10-11 17:25:37'),
(25, 17, 2, 0, '2024-10-09 11:11:52', '2024-10-11 17:25:37'),
(26, 18, 1, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(27, 18, 2, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(28, 19, 1, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(29, 19, 2, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(30, 20, 1, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(31, 20, 2, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(32, 21, 1, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(33, 21, 2, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(34, 22, 1, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(35, 22, 2, 0, '2024-10-09 11:13:20', '2024-10-11 17:25:37'),
(37, 24, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(38, 25, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(39, 25, 2, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(40, 26, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(41, 26, 2, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(42, 27, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(43, 27, 2, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(44, 28, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(45, 28, 2, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(46, 29, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(47, 29, 2, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(48, 30, 1, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(49, 30, 2, 0, '2024-10-09 11:14:43', '2024-10-11 17:25:37'),
(50, 31, 1, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(51, 32, 1, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(52, 33, 2, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(53, 34, 1, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(54, 35, 1, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(55, 35, 2, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(56, 36, 1, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(57, 36, 2, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(58, 37, 3, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(59, 38, 3, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(60, 39, 3, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(61, 40, 1, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(62, 40, 2, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(63, 40, 3, 0, '2024-10-09 12:34:41', '2024-10-11 17:25:37'),
(65, 46, 1, 0, '2024-10-09 14:44:53', '2024-10-11 17:25:37'),
(66, 46, 2, 0, '2024-10-09 14:44:53', '2024-10-11 17:25:37'),
(67, 47, 1, 0, '2024-10-09 14:45:02', '2024-10-11 17:25:37'),
(68, 47, 2, 0, '2024-10-09 14:45:02', '2024-10-11 17:25:37'),
(69, 48, 1, 1, '2024-10-09 19:54:09', '2024-10-11 17:25:37'),
(70, 49, 1, 1, '2024-10-11 17:24:34', '2024-10-11 17:25:37'),
(71, 48, 2, 1, '2024-10-11 18:12:17', '2024-10-11 18:12:17'),
(72, 49, 2, 1, '2024-10-11 18:12:17', '2024-10-11 18:12:17'),
(73, 48, 3, 1, '2024-10-11 18:12:50', '2024-10-11 18:12:50'),
(74, 49, 3, 1, '2024-10-11 18:12:50', '2024-10-11 18:12:50'),
(75, 53, 1, 0, '2024-10-11 19:23:21', '2024-10-11 19:23:21'),
(76, 53, 2, 0, '2024-10-11 19:23:21', '2024-10-11 19:23:21'),
(77, 54, 1, 0, '2024-10-11 19:23:21', '2024-10-11 19:23:21'),
(78, 54, 2, 0, '2024-10-11 19:23:21', '2024-10-11 19:23:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `modulos`
--

CREATE TABLE `modulos` (
  `id` int NOT NULL,
  `descr` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ordem` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `modulos`
--

INSERT INTO `modulos` (`id`, `descr`, `ordem`, `created_at`, `updated_at`) VALUES
(1, 'Cadastros', 1, '2024-10-09 10:28:28', '2024-10-09 10:28:28'),
(2, 'Estoque', 2, '2024-10-09 10:28:28', '2024-10-09 10:28:28'),
(3, 'Vendas', 3, '2024-10-09 10:28:28', '2024-10-09 10:28:28'),
(4, 'Financeiro', 4, '2024-10-09 10:28:28', '2024-10-09 10:28:28'),
(5, 'Processos', 5, '2024-10-09 10:28:28', '2024-10-09 10:28:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentos`
--

CREATE TABLE `movimentos` (
  `id` int NOT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `consolidado` tinyint DEFAULT NULL,
  `acrescimo` decimal(8,2) DEFAULT NULL,
  `desconto` decimal(8,2) DEFAULT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `id_baixa` int DEFAULT NULL,
  `id_plano` int DEFAULT NULL,
  `id_conta` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `naturezas`
--

CREATE TABLE `naturezas` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `naturezas`
--

INSERT INTO `naturezas` (`id`, `descr`, `lixeira`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Alimentação', 0, 1, '2024-10-11 18:52:50', '2024-10-11 18:52:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas`
--

CREATE TABLE `notas` (
  `id` int NOT NULL,
  `es` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `produtos` decimal(8,2) DEFAULT NULL,
  `servicos` decimal(8,2) DEFAULT NULL,
  `acrescimo` decimal(8,2) DEFAULT NULL,
  `desconto` decimal(8,2) DEFAULT NULL,
  `impostos` decimal(8,2) DEFAULT NULL,
  `liquido` decimal(8,2) DEFAULT NULL,
  `id_natureza` int DEFAULT NULL,
  `id_emitente` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota_itens`
--

CREATE TABLE `nota_itens` (
  `id` int NOT NULL,
  `qtd` decimal(10,5) DEFAULT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `acrescimo` decimal(8,2) DEFAULT NULL,
  `desconto` decimal(8,2) DEFAULT NULL,
  `impostos` decimal(8,2) DEFAULT NULL,
  `liquido` decimal(8,2) DEFAULT NULL,
  `id_cfop` int DEFAULT NULL,
  `id_item` int DEFAULT NULL,
  `id_nota` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `paginas`
--

CREATE TABLE `paginas` (
  `id` int NOT NULL,
  `nome` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rp` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `id_pai` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `precos`
--

CREATE TABLE `precos` (
  `id` int NOT NULL,
  `preco` decimal(8,2) DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_item` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `retiradas`
--

CREATE TABLE `retiradas` (
  `id` int NOT NULL,
  `qtd` decimal(10,5) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `id_local` int DEFAULT NULL,
  `id_maquina` int DEFAULT NULL,
  `id_produto` int DEFAULT NULL,
  `id_atribuicao` int DEFAULT NULL,
  `id_nota` int DEFAULT NULL,
  `id_funcionario` int DEFAULT NULL,
  `id_supervisor` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `segmentos`
--

CREATE TABLE `segmentos` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `segmentos`
--

INSERT INTO `segmentos` (`id`, `descr`, `lixeira`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Agropecuário', 0, 1, '2024-10-11 18:55:44', '2024-10-11 18:55:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `titulos`
--

CREATE TABLE `titulos` (
  `id` int NOT NULL,
  `rp` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `emissao` date DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `ndoc` int DEFAULT NULL,
  `parcela` int DEFAULT NULL,
  `pago` decimal(8,2) DEFAULT NULL,
  `obs` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nossnum` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `digitavel` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_tpdoc` int DEFAULT NULL,
  `id_fatura` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tpbxa`
--

CREATE TABLE `tpbxa` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caixa` tinyint DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tpdoc`
--

CREATE TABLE `tpdoc` (
  `id` int NOT NULL,
  `descr` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tpdoc`
--

INSERT INTO `tpdoc` (`id`, `descr`, `lixeira`, `id_empresa`, `created_at`, `updated_at`) VALUES
(1, 'Pix', 0, 1, '2024-10-11 18:53:29', '2024-10-11 18:53:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `id_empresa` int DEFAULT NULL,
  `id_aux` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atribuicoes`
--
ALTER TABLE `atribuicoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `baixas`
--
ALTER TABLE `baixas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cep`
--
ALTER TABLE `cep`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cfop`
--
ALTER TABLE `cfop`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `concessoes`
--
ALTER TABLE `concessoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `condicoes`
--
ALTER TABLE `condicoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `empresas_usuarios`
--
ALTER TABLE `empresas_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices de tabela `faturas`
--
ALTER TABLE `faturas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `formas`
--
ALTER TABLE `formas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `impostos`
--
ALTER TABLE `impostos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `locais`
--
ALTER TABLE `locais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `locais_itens`
--
ALTER TABLE `locais_itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `menu_perfis`
--
ALTER TABLE `menu_perfis`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimentos`
--
ALTER TABLE `movimentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `naturezas`
--
ALTER TABLE `naturezas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `nota_itens`
--
ALTER TABLE `nota_itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `precos`
--
ALTER TABLE `precos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `retiradas`
--
ALTER TABLE `retiradas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `segmentos`
--
ALTER TABLE `segmentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `titulos`
--
ALTER TABLE `titulos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tpbxa`
--
ALTER TABLE `tpbxa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tpdoc`
--
ALTER TABLE `tpdoc`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atribuicoes`
--
ALTER TABLE `atribuicoes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `baixas`
--
ALTER TABLE `baixas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bancos`
--
ALTER TABLE `bancos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cep`
--
ALTER TABLE `cep`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cfop`
--
ALTER TABLE `cfop`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `concessoes`
--
ALTER TABLE `concessoes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `condicoes`
--
ALTER TABLE `condicoes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas_usuarios`
--
ALTER TABLE `empresas_usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `faturas`
--
ALTER TABLE `faturas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `formas`
--
ALTER TABLE `formas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `impostos`
--
ALTER TABLE `impostos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `locais`
--
ALTER TABLE `locais`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `locais_itens`
--
ALTER TABLE `locais_itens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log`
--
ALTER TABLE `log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de tabela `menu_perfis`
--
ALTER TABLE `menu_perfis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `movimentos`
--
ALTER TABLE `movimentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `naturezas`
--
ALTER TABLE `naturezas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `nota_itens`
--
ALTER TABLE `nota_itens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `precos`
--
ALTER TABLE `precos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `retiradas`
--
ALTER TABLE `retiradas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `segmentos`
--
ALTER TABLE `segmentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `titulos`
--
ALTER TABLE `titulos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tpbxa`
--
ALTER TABLE `tpbxa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tpdoc`
--
ALTER TABLE `tpdoc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
