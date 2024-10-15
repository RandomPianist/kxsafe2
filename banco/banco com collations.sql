-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/10/2024 às 09:48
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
-- Banco de dados: `teste`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atribuicoes`
--

CREATE TABLE `atribuicoes` (
  `id` int NOT NULL,
  `funcionario_ou_setor_chave` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `funcionario_ou_setor_valor` int DEFAULT NULL,
  `produto_ou_referencia_chave` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `produto_ou_referencia_valor` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `cod` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cep`
--

CREATE TABLE `cep` (
  `id` int NOT NULL,
  `cod` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_tipo` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_tipo_abv` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_intervalo_min` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logradouro_intervalo_max` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_ibge_uf` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_ibge_cidade` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cidade` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bairro` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uf` varchar(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cfop`
--

CREATE TABLE `cfop` (
  `id` int NOT NULL,
  `cfop` varchar(4) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descr` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agencia` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `conta` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pix` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cedente` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nossnum` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `razao_social` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nome_fantasia` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cnpj` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ie` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefone` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `numero` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `complemento` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `referencia` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `es` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `uuid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `faturas`
--

CREATE TABLE `faturas` (
  `id` int NOT NULL,
  `rp` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `nome` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cpf` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `funcao` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admissao` date DEFAULT NULL,
  `senha` int DEFAULT NULL,
  `foto` varchar(512) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefone` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pis` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supervisor` tinyint DEFAULT '0',
  `rosto` text COLLATE utf8mb4_general_ci,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `impostos`
--

CREATE TABLE `impostos` (
  `id` int NOT NULL,
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sigla` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `preco` decimal(8,2) DEFAULT NULL,
  `referencia` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tamanho` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validade` int DEFAULT NULL,
  `ca` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validade_ca` date DEFAULT NULL,
  `detalhes` text COLLATE utf8mb4_general_ci,
  `foto` varchar(512) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_externo` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_ou_id` varchar(8) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `locais_itens`
--

CREATE TABLE `locais_itens` (
  `id` int NOT NULL,
  `descr` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `tabela` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nome` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `acao` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ordem` int DEFAULT NULL,
  `id_pai` int DEFAULT NULL,
  `id_modulo` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
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
  `descr` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ordem` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas`
--

CREATE TABLE `notas` (
  `id` int NOT NULL,
  `es` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
-- Estrutura para tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_general_ci,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rp` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int NOT NULL,
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `rp` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor` decimal(8,2) DEFAULT NULL,
  `emissao` date DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `ndoc` int DEFAULT NULL,
  `parcela` int DEFAULT NULL,
  `pago` decimal(8,2) DEFAULT NULL,
  `obs` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nossnum` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `digitavel` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `descr` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lixeira` tinyint DEFAULT '0',
  `id_empresa` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `id_aux` int DEFAULT NULL,
  `id_empresa` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `menu_perfis`
--
ALTER TABLE `menu_perfis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `movimentos`
--
ALTER TABLE `movimentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `naturezas`
--
ALTER TABLE `naturezas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
