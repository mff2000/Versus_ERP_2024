-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: erp03
-- ------------------------------------------------------
-- Server version	5.7.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agendamento_rateio`
--

DROP TABLE IF EXISTS `agendamento_rateio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agendamento_rateio` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plano_conta_id` int(11) NOT NULL,
  `centro_resultado_id` int(11) NOT NULL,
  `projeto_id` int(11) NOT NULL,
  `porcentagem` decimal(5,2) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `ordem` smallint(6) NOT NULL,
  `agendamento_id` int(11) DEFAULT NULL,
  `lancamento_id` int(11) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_agendamento_rateio_idx` (`agendamento_id`),
  KEY `fk_rateio_plano_idx` (`plano_conta_id`),
  KEY `fk_rateio_centro_idx` (`centro_resultado_id`),
  KEY `fk_rateio_projeto_idx` (`projeto_id`),
  CONSTRAINT `fk_agendamento_rateio` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rateio_centro` FOREIGN KEY (`centro_resultado_id`) REFERENCES `centros_resultado` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_rateio_plano` FOREIGN KEY (`plano_conta_id`) REFERENCES `planos_contas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_rateio_projeto` FOREIGN KEY (`projeto_id`) REFERENCES `projetos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1074 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agendamentos`
--

DROP TABLE IF EXISTS `agendamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `numero_titulo` varchar(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `tipo_movimento` varchar(3) DEFAULT NULL,
  `historico` varchar(200) NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `valor_titulo` decimal(20,2) NOT NULL DEFAULT '0.00',
  `valor_saldo` decimal(20,2) NOT NULL DEFAULT '0.00',
  `data_competencia` date NOT NULL,
  `data_vencimento` date NOT NULL,
  `codigo_link` varchar(20) DEFAULT NULL,
  `item_link` varchar(3) DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `contrato` bigint(20) DEFAULT NULL,
  `sequencia` bigint(20) DEFAULT NULL,
  `correcao_financeira_id` int(11) DEFAULT NULL,
  `favorecido_id` int(11) NOT NULL,
  `bordero_id` int(11) DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_agendamentos_chave_titulo` (`codigo_empresa`,`codigo_filial`,`numero_titulo`,`numero_parcela`,`favorecido_id`,`codigo_link`,`item_link`),
  KEY `fk_agendamento_bordero_idx` (`bordero_id`),
  CONSTRAINT `fk_agendamento_bordero` FOREIGN KEY (`bordero_id`) REFERENCES `borderos` (`id`),
  CONSTRAINT `fk_agendamentos_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1576 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `armazens`
--

DROP TABLE IF EXISTS `armazens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `armazens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_armazens_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_armazens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bancos`
--

DROP TABLE IF EXISTS `bancos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(3) DEFAULT NULL,
  `agencia` varchar(5) DEFAULT NULL,
  `dv_agencia` varchar(1) DEFAULT NULL,
  `numero_conta` varchar(20) DEFAULT NULL,
  `dv_conta` varchar(1) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `tel_fixo1` varchar(20) DEFAULT NULL,
  `tel_fixo2` varchar(20) DEFAULT NULL,
  `tel_movel1` varchar(20) DEFAULT NULL,
  `tel_movel2` varchar(20) DEFAULT NULL,
  `nome_gerente` varchar(80) DEFAULT NULL,
  `email_geral` varchar(80) DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `saldo_atual` decimal(20,2) DEFAULT '0.00',
  `limite` decimal(20,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bancos_cod_agencia_conta` (`codigo_empresa`,`codigo_filial`,`codigo`,`agencia`,`numero_conta`),
  CONSTRAINT `fk_bancos_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `borderos`
--

DROP TABLE IF EXISTS `borderos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `borderos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` bigint(20) NOT NULL,
  `tipo_bordero` varchar(3) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor` decimal(20,2) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `observacoes` varchar(200) DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `data_liquidacao` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_borderos_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `centros_resultado`
--

DROP TABLE IF EXISTS `centros_resultado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centros_resultado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `classe` varchar(1) NOT NULL,
  `conta_superior` varchar(20) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `codigo_devra` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_centros_resultado_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_centros_resultado_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cfop`
--

DROP TABLE IF EXISTS `cfop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(50) NOT NULL,
  `descricao` longtext NOT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1109 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `tipo_pessoa` varchar(1) DEFAULT NULL,
  `cnpj` varchar(14) DEFAULT NULL,
  `nome_empresarial` varchar(80) DEFAULT NULL,
  `nome_fantasia` varchar(60) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `tel_fixo1` varchar(20) DEFAULT NULL,
  `tel_fixo2` varchar(20) DEFAULT NULL,
  `tel_movel1` varchar(20) DEFAULT NULL,
  `tel_movel2` varchar(20) DEFAULT NULL,
  `email_geral` varchar(80) DEFAULT NULL,
  `email_nfe` varchar(80) DEFAULT NULL,
  `email_financ` varchar(80) DEFAULT NULL,
  `contato_financ` varchar(80) DEFAULT NULL,
  `contato_geral` varchar(80) DEFAULT NULL,
  `contato_fiscal` varchar(80) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_clientes_cnpj` (`codigo_empresa`,`codigo_filial`,`cnpj`),
  CONSTRAINT `fk_clientes_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `condicoes_pagamento`
--

DROP TABLE IF EXISTS `condicoes_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `condicoes_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `descricao` varchar(200) NOT NULL,
  `tipo` varchar(3) NOT NULL,
  `quantidade_parcelas` smallint(6) NOT NULL,
  `dias_intervalo` smallint(6) NOT NULL,
  `dias_carencia` smallint(6) NOT NULL,
  `percentuais` varchar(80) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_condicoes_pagamento_codigo` (`codigo_empresa`,`codigo_filial`),
  CONSTRAINT `fk_condicoes_pagamento_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contas_pagar`
--

DROP TABLE IF EXISTS `contas_pagar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contas_pagar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `numero_titulo` varchar(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `fornecedor_id` bigint(20) DEFAULT NULL,
  `conta_financeira` varchar(20) DEFAULT NULL,
  `centro_resultado` varchar(20) DEFAULT NULL,
  `historico` varchar(200) DEFAULT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `valor_titulo` decimal(20,2) DEFAULT NULL,
  `valor_saldo` decimal(20,2) DEFAULT NULL,
  `data_digitacao` date DEFAULT NULL,
  `data_competencia` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contas_pagar_chave_titulo` (`codigo_empresa`,`codigo_filial`,`numero_titulo`,`numero_parcela`,`fornecedor_id`),
  CONSTRAINT `fk_contas_pagar_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contas_receber`
--

DROP TABLE IF EXISTS `contas_receber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contas_receber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `numero_titulo` varchar(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `conta_financeira` varchar(20) DEFAULT NULL,
  `centro_resultado` varchar(20) DEFAULT NULL,
  `historico` varchar(200) DEFAULT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `valor_titulo` decimal(20,2) DEFAULT NULL,
  `valor_saldo` decimal(20,2) DEFAULT NULL,
  `data_digitacao` date DEFAULT NULL,
  `data_competencia` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contas_receber_chave_titulo` (`codigo_empresa`,`codigo_filial`,`numero_titulo`,`numero_parcela`,`cliente_id`),
  CONSTRAINT `fk_contas_receber_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contratos_cabec`
--

DROP TABLE IF EXISTS `contratos_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratos_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `dia_vencimento` smallint(6) DEFAULT NULL,
  `dia_fechamento` smallint(6) DEFAULT NULL,
  `tipo_contrato` varchar(3) DEFAULT NULL,
  `conta_financeira` varchar(20) DEFAULT NULL,
  `centro_custo` varchar(20) DEFAULT NULL,
  `observacoes` longtext,
  `ativo` int(11) DEFAULT '1',
  `data_vigencia_inicio` date DEFAULT NULL,
  `data_vigencia_fim` date DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contratos_cabec_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_contratos_cabec_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contratos_fornecimento_cabec`
--

DROP TABLE IF EXISTS `contratos_fornecimento_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratos_fornecimento_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` bigint(20) DEFAULT NULL,
  `sequencia` bigint(20) DEFAULT NULL,
  `favorecido_id` int(11) NOT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `etapa` varchar(3) NOT NULL,
  `condicao_id` int(11) DEFAULT NULL,
  `pec_cliente` decimal(20,2) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `data_vigencia_inicio` date NOT NULL,
  `data_vigencia_fim` date NOT NULL,
  `valor` decimal(20,2) DEFAULT NULL,
  `log_agendamento` varchar(50) DEFAULT NULL,
  `observacoes` longtext,
  `vendedor1_id` int(11) NOT NULL,
  `vendedor2_id` int(11) DEFAULT NULL,
  `vendedor3_id` int(11) DEFAULT NULL,
  `tipo_transacao_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contratos_fornecimento_cabec_codigo_sequencia` (`codigo_empresa`,`codigo_filial`,`codigo`,`sequencia`),
  KEY `fk_contrato_fornecimento_favorecido_idx` (`favorecido_id`),
  KEY `fk_contrato_fornecimento_condcao_idx` (`condicao_id`),
  KEY `fk_contratos_fornecimento_tipo_transacao_idx` (`tipo_transacao_id`),
  KEY `fk_contratos_fornecimentos_vendedor1_idx` (`vendedor1_id`),
  KEY `fk_contratos_fornecimentos_vendedor2_idx` (`vendedor2_id`),
  KEY `fk_contratos_fornecimentos_vendedor3_idx` (`vendedor3_id`),
  CONSTRAINT `fk_contrato_fornecimento_condicao` FOREIGN KEY (`condicao_id`) REFERENCES `condicoes_pagamento` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_contrato_fornecimento_favorecido` FOREIGN KEY (`favorecido_id`) REFERENCES `favorecidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_contratos_fornecimento_cabec_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_fornecimento_tipo_transacao` FOREIGN KEY (`tipo_transacao_id`) REFERENCES `tipos_transacao` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_contratos_fornecimentos_vendedor1` FOREIGN KEY (`vendedor1_id`) REFERENCES `vendedores` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_contratos_fornecimentos_vendedor2` FOREIGN KEY (`vendedor2_id`) REFERENCES `vendedores` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_contratos_fornecimentos_vendedor3` FOREIGN KEY (`vendedor3_id`) REFERENCES `vendedores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contratos_fornecimento_itens`
--

DROP TABLE IF EXISTS `contratos_fornecimento_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratos_fornecimento_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `sequencia` bigint(20) DEFAULT NULL,
  `item` bigint(20) DEFAULT NULL,
  `codigo` bigint(20) DEFAULT NULL,
  `quantidade` decimal(20,5) DEFAULT NULL,
  `preco_unitario` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `pec_cliente` decimal(20,2) DEFAULT NULL,
  `saldo_quantidade` decimal(20,5) DEFAULT NULL,
  `saldo_valor` decimal(20,2) DEFAULT NULL,
  `observacoes` longtext,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `contrato_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contratos_fornecimento_itens_contrato_id_item` (`codigo_empresa`,`codigo_filial`,`contrato_id`,`sequencia`,`item`),
  KEY `fk_contratos_fornecimento_itens_contrato_idx` (`contrato_id`),
  CONSTRAINT `fk_contratos_fornecimento_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_fornecimento_itens_contrato` FOREIGN KEY (`contrato_id`) REFERENCES `contratos_fornecimento_cabec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contratos_itens`
--

DROP TABLE IF EXISTS `contratos_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contratos_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `contrato_id` bigint(20) DEFAULT NULL,
  `item` bigint(20) DEFAULT NULL,
  `codigo_servico` varchar(8) DEFAULT NULL,
  `quantidade` bigint(20) DEFAULT NULL,
  `observacoes` longtext,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contratos_itens_contrato_id_item` (`codigo_empresa`,`codigo_filial`,`contrato_id`,`item`),
  CONSTRAINT `fk_contratos_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `correcao_financeiras`
--

DROP TABLE IF EXISTS `correcao_financeiras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `correcao_financeiras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `aliquota_juros` decimal(5,2) NOT NULL,
  `periodo_juros` tinyint(4) NOT NULL,
  `aliquota_multa` decimal(5,2) NOT NULL,
  `periodo_multa` tinyint(4) NOT NULL,
  `limite_multa` decimal(10,0) NOT NULL,
  `plano_conta_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_correcao_plano_conta_idx` (`plano_conta_id`),
  CONSTRAINT `fk_correcao_plano_conta` FOREIGN KEY (`plano_conta_id`) REFERENCES `planos_contas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `descontos`
--

DROP TABLE IF EXISTS `descontos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `descontos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `plano_conta_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`descricao`),
  KEY `fk_desconto_plano_conta_idx` (`plano_conta_id`),
  CONSTRAINT `fk_desconto_plano_conta` FOREIGN KEY (`plano_conta_id`) REFERENCES `planos_contas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `nome_filial` varchar(30) DEFAULT NULL,
  `nome_empresarial` varchar(80) DEFAULT NULL,
  `nome_fantasia` varchar(60) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `tel_fixo1` varchar(20) DEFAULT NULL,
  `tel_fixo2` varchar(20) DEFAULT NULL,
  `tel_movel1` varchar(20) DEFAULT NULL,
  `tel_movel2` varchar(20) DEFAULT NULL,
  `email_geral` varchar(80) DEFAULT NULL,
  `email_nfe` varchar(80) DEFAULT NULL,
  `email_financ` varchar(80) DEFAULT NULL,
  `contato_financ` varchar(80) DEFAULT NULL,
  `contato_geral` varchar(80) DEFAULT NULL,
  `contato_fiscal` varchar(80) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `codigo_autorizacao` varchar(200) DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `param_multa` decimal(4,2) DEFAULT NULL,
  `param_juros` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_empresas_cnpj` (`cnpj`),
  UNIQUE KEY `uk_empresas_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  UNIQUE KEY `uk_empresas_nome_filial` (`nome_filial`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favorecidos`
--

DROP TABLE IF EXISTS `favorecidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorecidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `tipo_pessoa` varchar(1) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `inscricao_estadual` varchar(20) DEFAULT NULL,
  `nome_empresarial` varchar(80) NOT NULL,
  `nome_fantasia` varchar(60) NOT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `entrega_cep` varchar(10) DEFAULT NULL,
  `entrega_endereco` varchar(60) DEFAULT NULL,
  `entrega_numero` varchar(10) DEFAULT NULL,
  `entrega_complemento` varchar(60) DEFAULT NULL,
  `entrega_bairro` varchar(60) DEFAULT NULL,
  `entrega_cidade` varchar(60) DEFAULT NULL,
  `entrega_uf` varchar(2) DEFAULT NULL,
  `cobranca_cep` varchar(10) DEFAULT NULL,
  `cobranca_endereco` varchar(60) DEFAULT NULL,
  `cobranca_numero` varchar(10) DEFAULT NULL,
  `cobranca_complemento` varchar(60) DEFAULT NULL,
  `cobranca_bairro` varchar(60) DEFAULT NULL,
  `cobranca_cidade` varchar(60) DEFAULT NULL,
  `cobranca_uf` varchar(2) DEFAULT NULL,
  `tel_fixo1` varchar(20) DEFAULT NULL,
  `tel_fixo2` varchar(20) DEFAULT NULL,
  `tel_movel1` varchar(20) DEFAULT NULL,
  `tel_movel2` varchar(20) DEFAULT NULL,
  `email_geral` varchar(80) DEFAULT NULL,
  `email_nfe` varchar(80) DEFAULT NULL,
  `email_financ` varchar(80) DEFAULT NULL,
  `contato_financ` varchar(80) DEFAULT NULL,
  `contato_geral` varchar(80) DEFAULT NULL,
  `contato_fiscal` varchar(80) DEFAULT NULL,
  `limite_credito` decimal(20,2) DEFAULT NULL,
  `limite_desconto` decimal(10,2) DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `risco_credito` varchar(1) DEFAULT NULL,
  `tabela_preco_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favorecidos_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  KEY `fk_favorecidos_tabela_preco_idx` (`tabela_preco_id`),
  CONSTRAINT `fk_favorecidos_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6170 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favorecidos_has_condicoes_pagamento`
--

DROP TABLE IF EXISTS `favorecidos_has_condicoes_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorecidos_has_condicoes_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `favorecido_id` int(11) NOT NULL,
  `condicao_pagamento_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favorecido_idx` (`favorecido_id`),
  KEY `fk_condicoes_idx` (`condicao_pagamento_id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favorecidos_has_desconto`
--

DROP TABLE IF EXISTS `favorecidos_has_desconto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorecidos_has_desconto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `percentual` decimal(5,2) NOT NULL,
  `tabela_preco_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `favorecido_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_desconto_produto_idx` (`produto_id`),
  KEY `fk_desconto_favorecido_idx` (`favorecido_id`),
  CONSTRAINT `fk_desconto_favorecido` FOREIGN KEY (`favorecido_id`) REFERENCES `favorecidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_desconto_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favorecidos_has_vendedores`
--

DROP TABLE IF EXISTS `favorecidos_has_vendedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorecidos_has_vendedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `favorecido_id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `comissao` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favorecido_table_idx` (`favorecido_id`),
  KEY `fk_vendedor_table_idx` (`vendedor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favorecidos_obs`
--

DROP TABLE IF EXISTS `favorecidos_obs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorecidos_obs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obs` text NOT NULL,
  `usuario` varchar(45) DEFAULT NULL,
  `favorecido_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favorecidos_obs_favorecido_idx` (`favorecido_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6174 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forma_financeiras`
--

DROP TABLE IF EXISTS `forma_financeiras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forma_financeiras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  `liquida` varchar(1) NOT NULL,
  `ativo` smallint(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `tipo_pessoa` varchar(1) DEFAULT NULL,
  `cnpj` varchar(14) DEFAULT NULL,
  `nome_empresarial` varchar(80) DEFAULT NULL,
  `nome_fantasia` varchar(60) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `tel_fixo1` varchar(20) DEFAULT NULL,
  `tel_fixo2` varchar(20) DEFAULT NULL,
  `tel_movel1` varchar(20) DEFAULT NULL,
  `tel_movel2` varchar(20) DEFAULT NULL,
  `email_geral` varchar(80) DEFAULT NULL,
  `email_nfe` varchar(80) DEFAULT NULL,
  `email_financ` varchar(80) DEFAULT NULL,
  `contato_financ` varchar(80) DEFAULT NULL,
  `contato_geral` varchar(80) DEFAULT NULL,
  `contato_fiscal` varchar(80) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fornecedores_cnpj` (`codigo_empresa`,`codigo_filial`,`cnpj`),
  CONSTRAINT `fk_fornecedores_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupos_produto`
--

DROP TABLE IF EXISTS `grupos_produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_grupos_produto_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_grupos_produto_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(8) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_jobs_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_jobs_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lancamentos_bancarios`
--

DROP TABLE IF EXISTS `lancamentos_bancarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lancamentos_bancarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `numero_titulo` varchar(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `favorecido_id` int(11) NOT NULL,
  `tipo_movimento` varchar(3) NOT NULL,
  `tipo_baixa` char(3) DEFAULT NULL,
  `sequencia_baixa` mediumint(9) DEFAULT NULL,
  `numero_cheque` varchar(20) DEFAULT NULL,
  `historico` varchar(200) NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `banco_id` int(11) NOT NULL,
  `valor_lancamento` decimal(20,2) NOT NULL DEFAULT '0.00',
  `valor_multa` decimal(20,2) DEFAULT '0.00',
  `valor_juros` decimal(20,2) DEFAULT '0.00',
  `valor_desconto` decimal(20,2) DEFAULT '0.00',
  `data_lancamento` date NOT NULL,
  `data_liquidacao` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `agendamento_id` int(11) NOT NULL,
  `agendamento_relacionado_id` int(11) DEFAULT NULL,
  `lancamento_relacionado_id` int(11) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `bordero_id` int(11) DEFAULT NULL,
  `baixa_id` varchar(45) DEFAULT NULL,
  `forma_financeira_id` int(11) NOT NULL,
  `desconto_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lancamento_forma_financeira_idx` (`forma_financeira_id`),
  KEY `fk_lancamento_banco_idx` (`banco_id`),
  KEY `fk_lancamento_favorecido_idx` (`favorecido_id`),
  KEY `fk_lancamentos_bancarios_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  KEY `fk_lancamento_bancario_agendamento_idx` (`agendamento_id`),
  CONSTRAINT `fk_lancamento_agendamento` FOREIGN KEY (`agendamento_id`) REFERENCES `agendamentos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lancamento_bancario_favorecidos` FOREIGN KEY (`favorecido_id`) REFERENCES `favorecidos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_lancamento_bancario_forma` FOREIGN KEY (`forma_financeira_id`) REFERENCES `forma_financeiras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lancamento_banco` FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_lancamentos_bancarios_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lancamentos_gerenciais`
--

DROP TABLE IF EXISTS `lancamentos_gerenciais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lancamentos_gerenciais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `numero_titulo` varchar(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `favorecido_id` int(11) NOT NULL,
  `historico` varchar(200) NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `valor_lancamento` decimal(20,2) NOT NULL DEFAULT '0.00',
  `data_lancamento` date NOT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `centro_resultado_credito_id` int(11) NOT NULL,
  `centro_resultado_debito_id` int(11) NOT NULL,
  `plano_conta_credito_id` int(11) NOT NULL,
  `plano_conta_debito_id` int(11) NOT NULL,
  `projeto_credito_id` int(11) NOT NULL,
  `projeto_debito_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lancamentos_gerenciais_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  KEY `fk_lancamento_favorecido_idx` (`favorecido_id`),
  KEY `fk_lancamento_plano_credito_idx` (`plano_conta_credito_id`),
  KEY `fk_lancamento_plano_debito_idx` (`plano_conta_debito_id`),
  KEY `fk_lancamento_centro_credito_idx` (`centro_resultado_credito_id`),
  KEY `fk_lancamento_centro_debito_idx` (`centro_resultado_debito_id`),
  KEY `fk_lancamento_projeto_credito_idx` (`projeto_credito_id`),
  KEY `fk_lancamento_projeto_debito_idx` (`projeto_debito_id`),
  CONSTRAINT `fk_lancamento_centro_credito` FOREIGN KEY (`centro_resultado_credito_id`) REFERENCES `centros_resultado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamento_centro_debito` FOREIGN KEY (`centro_resultado_debito_id`) REFERENCES `centros_resultado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamento_favorecido` FOREIGN KEY (`favorecido_id`) REFERENCES `favorecidos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamento_plano_credito` FOREIGN KEY (`plano_conta_credito_id`) REFERENCES `planos_contas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamento_plano_debito` FOREIGN KEY (`plano_conta_debito_id`) REFERENCES `planos_contas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamento_projeto_credito` FOREIGN KEY (`projeto_credito_id`) REFERENCES `projetos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamento_projeto_debito` FOREIGN KEY (`projeto_debito_id`) REFERENCES `projetos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lancamentos_gerenciais_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lancamentos_orcamento`
--

DROP TABLE IF EXISTS `lancamentos_orcamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lancamentos_orcamento` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `tipo_movimento` varchar(3) NOT NULL,
  `historico` varchar(200) NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `valor_lancamento` decimal(20,2) NOT NULL DEFAULT '0.00',
  `data_competencia` date NOT NULL,
  `data_vencimento` date NOT NULL,
  `plano_conta_id` int(11) NOT NULL,
  `centro_resultado_id` int(11) NOT NULL,
  `projeto_id` int(11) NOT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_orcamento_projeto_idx` (`projeto_id`),
  KEY `fk_orcamento_plano_conta_idx` (`plano_conta_id`),
  KEY `fk_orcamento_centro_resultado_idx` (`centro_resultado_id`),
  CONSTRAINT `fk_orcamento_centro_resultado` FOREIGN KEY (`centro_resultado_id`) REFERENCES `centros_resultado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orcamento_plano_conta` FOREIGN KEY (`plano_conta_id`) REFERENCES `planos_contas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orcamento_projeto` FOREIGN KEY (`projeto_id`) REFERENCES `projetos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=265 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_titulos_cabec`
--

DROP TABLE IF EXISTS `link_titulos_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_titulos_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` bigint(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_link_titulos_cabec_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_link_titulos_cabec_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_titulos_itens`
--

DROP TABLE IF EXISTS `link_titulos_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_titulos_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo_link` bigint(20) DEFAULT NULL,
  `numero_item` bigint(20) DEFAULT NULL,
  `favorecido_id` bigint(20) DEFAULT NULL,
  `tipo_movimento` varchar(3) DEFAULT NULL,
  `conta_financeira` varchar(20) DEFAULT NULL,
  `centro_resultado` varchar(20) DEFAULT NULL,
  `historico` varchar(200) DEFAULT NULL,
  `valor_titulo` decimal(20,2) DEFAULT '0.00',
  `percentual` decimal(20,2) DEFAULT '0.00',
  `data_digitacao` date DEFAULT NULL,
  `data_competencia` date DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `dia_vencimento` smallint(6) DEFAULT NULL,
  `mes_vencimento` varchar(1) DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_link_titulos_itens_codigo_item` (`codigo_empresa`,`codigo_filial`,`codigo_link`,`numero_item`),
  CONSTRAINT `fk_link_titulos_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_link_titulos_itens_codigo_link` FOREIGN KEY (`codigo_empresa`, `codigo_filial`, `codigo_link`) REFERENCES `link_titulos_cabec` (`codigo_empresa`, `codigo_filial`, `codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensagens_sistema`
--

DROP TABLE IF EXISTS `mensagens_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensagens_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `descricao` varchar(80) DEFAULT NULL,
  `situacao` varchar(200) DEFAULT NULL,
  `acao` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_mensagens_sistema_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notas_saida_cabec`
--

DROP TABLE IF EXISTS `notas_saida_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas_saida_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `contrato` bigint(20) DEFAULT NULL,
  `vendedor1_id` int(11) DEFAULT NULL,
  `vendedor2_id` int(11) DEFAULT NULL,
  `vendedor3_id` int(11) DEFAULT NULL,
  `tipo_transacao` bigint(20) DEFAULT NULL,
  `mensagens_danfe` longtext,
  `valor` decimal(20,2) DEFAULT NULL,
  `data_emissao` date DEFAULT NULL,
  `data_entrega` date DEFAULT NULL,
  `data_faturamento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_notas_saida_nfe_serie_numero` (`codigo_empresa`,`codigo_filial`,`nfe_serie`,`nfe_numero`),
  CONSTRAINT `fk_notas_saida_cabec_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notas_saida_itens`
--

DROP TABLE IF EXISTS `notas_saida_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas_saida_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `nota_saida_id` int(11) DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `nfe_item` bigint(20) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `pedido_item` bigint(20) DEFAULT NULL,
  `produto` bigint(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `quantidade` decimal(20,5) DEFAULT NULL,
  `preco_unitario` decimal(20,2) DEFAULT NULL,
  `total_item` decimal(20,2) DEFAULT NULL,
  `armazem` varchar(20) DEFAULT NULL,
  `observacoes` longtext,
  `saldo_romaneio` decimal(20,2) DEFAULT NULL,
  `quantidade_pecas` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_notas_saida_nfe_serie_numero_item` (`codigo_empresa`,`codigo_filial`,`nfe_serie`,`nfe_numero`,`nfe_item`),
  CONSTRAINT `fk_notas_saida_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parametros`
--

DROP TABLE IF EXISTS `parametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `nome` varchar(30) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `tipo` varchar(3) DEFAULT NULL,
  `conteudo_int` int(11) DEFAULT NULL,
  `conteudo_numeric` decimal(27,9) DEFAULT NULL,
  `conteudo_date` date DEFAULT NULL,
  `conteudo_varchar` varchar(200) DEFAULT NULL,
  `proprietario` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_contrato_cabec`
--

DROP TABLE IF EXISTS `pedidos_contrato_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos_contrato_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `contrato_id` int(11) NOT NULL,
  `condicao_id` int(11) NOT NULL,
  `mensagens_danfe` longtext,
  `observacoes` longtext,
  `pedido_favorecido` varchar(20) DEFAULT NULL,
  `etapa` varchar(3) NOT NULL,
  `valor` decimal(20,2) DEFAULT NULL,
  `pec_cliente` decimal(20,2) DEFAULT NULL,
  `pec_fabrica` decimal(20,2) DEFAULT NULL,
  `log_credito` varchar(50) DEFAULT NULL,
  `log_estoque` varchar(50) DEFAULT NULL,
  `log_faturado` varchar(50) DEFAULT NULL,
  `log_entregue` varchar(50) DEFAULT NULL,
  `data_emissao` date NOT NULL,
  `data_entrega` date DEFAULT NULL,
  `data_faturamento` date DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `romaneio` bigint(20) DEFAULT NULL,
  `peso_liquido` decimal(20,2) DEFAULT NULL,
  `peso_bruto` decimal(20,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pedidos_contrato_cabec_id` (`codigo_empresa`,`codigo_filial`,`id`),
  KEY `fk_pedido_favorecido_idx` (`contrato_id`),
  CONSTRAINT `fk_pedido_favorecido` FOREIGN KEY (`contrato_id`) REFERENCES `contratos_fornecimento_cabec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_contrato_cab_contrato` FOREIGN KEY (`contrato_id`) REFERENCES `contratos_fornecimento_cabec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_contrato_cabec_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_contrato_itens`
--

DROP TABLE IF EXISTS `pedidos_contrato_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos_contrato_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `item` bigint(20) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `quantidade_pecas` bigint(20) DEFAULT NULL,
  `altura` bigint(20) DEFAULT NULL,
  `largura` bigint(20) DEFAULT NULL,
  `area_real` decimal(20,2) DEFAULT NULL,
  `pec_cliente` decimal(20,2) DEFAULT NULL,
  `pec_fabrica` decimal(20,2) DEFAULT NULL,
  `area_bruta_cliente` decimal(20,2) DEFAULT NULL,
  `area_bruta_fabrica` decimal(20,2) DEFAULT NULL,
  `quantidade_fabrica` decimal(20,5) DEFAULT NULL,
  `quantidade_cliente` decimal(20,5) DEFAULT NULL,
  `preco_unitario` decimal(20,2) DEFAULT NULL,
  `total_item` decimal(20,2) DEFAULT NULL,
  `armazem_id` int(11) DEFAULT NULL,
  `observacoes` longtext,
  `altura_corte` decimal(20,2) DEFAULT NULL,
  `largura_corte` decimal(20,2) DEFAULT NULL,
  `preco_tabela` decimal(20,2) DEFAULT NULL,
  `romaneio` bigint(20) DEFAULT NULL,
  `saldo_liberar` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pedidos_contrato_itens_pedido_item` (`codigo_empresa`,`codigo_filial`,`item`),
  KEY `fk_pedido_item_pedido_idx` (`pedido_id`),
  KEY `fk_pedido_item_produto_idx` (`produto_id`),
  KEY `fk_pedido_item_armazem_idx` (`armazem_id`),
  CONSTRAINT `fk_pedido_item_armazem` FOREIGN KEY (`armazem_id`) REFERENCES `armazens` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_pedido_item_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_contrato_cabec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pedido_item_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_pedidos_contrato_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_contrato_vencto`
--

DROP TABLE IF EXISTS `pedidos_contrato_vencto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos_contrato_vencto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `valor` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pedidos_contrato_vencto_pedido_numero_parcela` (`codigo_empresa`,`codigo_filial`,`pedido`,`numero_parcela`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_venda_itens_liberados`
--

DROP TABLE IF EXISTS `pedidos_venda_itens_liberados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos_venda_itens_liberados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `pedido_item_id` int(11) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `item` bigint(20) DEFAULT NULL,
  `produto` bigint(20) DEFAULT NULL,
  `nota_saida_id` int(11) DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `nfe_item` bigint(20) DEFAULT NULL,
  `quantidade_pecas` bigint(20) DEFAULT NULL,
  `altura` bigint(20) DEFAULT NULL,
  `largura` bigint(20) DEFAULT NULL,
  `area_real` decimal(20,2) DEFAULT NULL,
  `pec_cliente` decimal(20,2) DEFAULT NULL,
  `pec_fabrica` decimal(20,2) DEFAULT NULL,
  `area_bruta_cliente` decimal(20,2) DEFAULT NULL,
  `area_bruta_fabrica` decimal(20,2) DEFAULT NULL,
  `quantidade_fabrica` decimal(20,5) DEFAULT NULL,
  `quantidade_cliente` decimal(20,5) DEFAULT NULL,
  `preco_unitario` decimal(20,2) DEFAULT NULL,
  `total_item` decimal(20,2) DEFAULT NULL,
  `armazem` varchar(20) DEFAULT NULL,
  `observacoes` longtext,
  `altura_corte` decimal(20,2) DEFAULT NULL,
  `largura_corte` decimal(20,2) DEFAULT NULL,
  `preco_tabela` decimal(20,2) DEFAULT NULL,
  `romaneio` bigint(20) DEFAULT NULL,
  `saldo_faturar` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pedidos_venda_itens_liberados_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  CONSTRAINT `fk_pedidos_venda_itens_liberados_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil_usuarios`
--

DROP TABLE IF EXISTS `perfil_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) DEFAULT NULL,
  `descricao` varchar(5000) DEFAULT NULL,
  `tipo_acesso` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_perfil_usuarios_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perguntas_filtro`
--

DROP TABLE IF EXISTS `perguntas_filtro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perguntas_filtro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `grupo` varchar(30) DEFAULT NULL,
  `sequencia` int(11) DEFAULT NULL,
  `label` varchar(60) DEFAULT NULL,
  `tipo_input` varchar(60) DEFAULT NULL,
  `class` varchar(60) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `value` varchar(8000) DEFAULT NULL,
  `placeholder` varchar(60) DEFAULT NULL,
  `onkeyup` varchar(60) DEFAULT NULL,
  `sql_source` varchar(8000) DEFAULT NULL,
  `required` char(1) DEFAULT NULL,
  `preselected` int(11) DEFAULT NULL,
  `text_option1` varchar(20) DEFAULT NULL,
  `text_option2` varchar(20) DEFAULT NULL,
  `text_option3` varchar(20) DEFAULT NULL,
  `text_option4` varchar(20) DEFAULT NULL,
  `text_option5` varchar(20) DEFAULT NULL,
  `value_option1` varchar(20) DEFAULT NULL,
  `value_option2` varchar(20) DEFAULT NULL,
  `value_option3` varchar(20) DEFAULT NULL,
  `value_option4` varchar(20) DEFAULT NULL,
  `value_option5` varchar(20) DEFAULT NULL,
  `proprietario` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planos_contas`
--

DROP TABLE IF EXISTS `planos_contas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planos_contas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `classe` varchar(1) DEFAULT NULL,
  `natureza` varchar(1) NOT NULL,
  `conta_contabil_ext` varchar(45) DEFAULT NULL,
  `conta_superior` varchar(20) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contas_contabeis_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=1143 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `descricao` varchar(200) NOT NULL,
  `ncm` varchar(10) NOT NULL,
  `altura` decimal(20,2) NOT NULL,
  `largura` decimal(20,2) NOT NULL,
  `espessura` decimal(20,2) NOT NULL,
  `fator_multiplo` decimal(20,2) NOT NULL,
  `lapidado` varchar(1) NOT NULL DEFAULT 'N',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `grupo_id` int(11) NOT NULL,
  `unidade_id` int(11) NOT NULL,
  `armazem_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_produtos_codigo` (`codigo_empresa`,`codigo_filial`),
  KEY `fk_produtos_unidade_idx` (`unidade_id`),
  KEY `fk_produtos_grupo_idx` (`grupo_id`),
  KEY `fk_produtos_armazem_idx` (`armazem_id`),
  CONSTRAINT `fk_produtos_armazem` FOREIGN KEY (`armazem_id`) REFERENCES `armazens` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_produtos_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos_produto` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_produtos_unidade` FOREIGN KEY (`unidade_id`) REFERENCES `unidades_medida` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projetos`
--

DROP TABLE IF EXISTS `projetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projetos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `classe` varchar(1) DEFAULT NULL,
  `natureza` varchar(1) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `conta_superior` int(11) DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contas_financeiras_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_contas_financeiras_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `romaneios_cabec`
--

DROP TABLE IF EXISTS `romaneios_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `romaneios_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `codigo` bigint(20) DEFAULT NULL,
  `observacoes` longtext,
  `data_emissao` date DEFAULT NULL,
  `data_entrega` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_romaneios_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_romaneios_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `romaneios_itens`
--

DROP TABLE IF EXISTS `romaneios_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `romaneios_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `romaneio_id` int(11) DEFAULT NULL,
  `romaneio` bigint(20) DEFAULT NULL,
  `romaneio_item` bigint(20) DEFAULT NULL,
  `nota_saida_id` int(11) DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `nfe_item` bigint(20) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `pedido_item` bigint(20) DEFAULT NULL,
  `produto` bigint(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `quantidade` decimal(20,5) DEFAULT NULL,
  `armazem` varchar(20) DEFAULT NULL,
  `observacoes` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_romaneios_itens_romaneio_item` (`codigo_empresa`,`codigo_filial`,`romaneio`,`romaneio_item`),
  CONSTRAINT `fk_romaneios_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sequencias`
--

DROP TABLE IF EXISTS `sequencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sequencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `tabela` varchar(100) DEFAULT NULL,
  `ultimo` bigint(20) DEFAULT NULL,
  `proximo` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sequencias_tabela` (`codigo_empresa`,`codigo_filial`,`tabela`),
  CONSTRAINT `fk_sequencias_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servicos`
--

DROP TABLE IF EXISTS `servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(8) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `valor_unitario` decimal(20,2) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_servicos_cliente_codigo` (`codigo_empresa`,`codigo_filial`,`cliente_id`,`codigo`),
  CONSTRAINT `fk_servicos_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `supertab_cabec`
--

DROP TABLE IF EXISTS `supertab_cabec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supertab_cabec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_supertab_cabec_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_supertab_cabec_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `supertab_itens`
--

DROP TABLE IF EXISTS `supertab_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supertab_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo_tabela` varchar(20) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_supertab_itens_codigo` (`codigo_empresa`,`codigo_filial`,`codigo_tabela`,`codigo`),
  CONSTRAINT `fk_supertab_itens_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sysdiagrams`
--

DROP TABLE IF EXISTS `sysdiagrams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sysdiagrams` (
  `name` varchar(160) NOT NULL,
  `principal_id` int(11) NOT NULL,
  `diagram_id` int(11) NOT NULL AUTO_INCREMENT,
  `version` int(11) DEFAULT NULL,
  `definition` longblob,
  PRIMARY KEY (`diagram_id`),
  UNIQUE KEY `UK_principal_name` (`principal_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tabela_has_produto`
--

DROP TABLE IF EXISTS `tabela_has_produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tabela_has_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela_preco_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produto_idx` (`produto_id`),
  KEY `fk_tabela_idx` (`tabela_preco_id`),
  CONSTRAINT `fk_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_tabela` FOREIGN KEY (`tabela_preco_id`) REFERENCES `tabelas_preco` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tabelas_preco`
--

DROP TABLE IF EXISTS `tabelas_preco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tabelas_preco` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tb220`
--

DROP TABLE IF EXISTS `tb220`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb220` (
  `codigo` varchar(50) DEFAULT NULL,
  `descricao` longtext,
  `igno1` varchar(50) DEFAULT NULL,
  `igno2` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timesheet`
--

DROP TABLE IF EXISTS `timesheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timesheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` bigint(20) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `data_registro` date DEFAULT NULL,
  `mes_competencia` varchar(6) DEFAULT NULL,
  `contrato_id` bigint(20) DEFAULT NULL,
  `projeto_id` bigint(20) DEFAULT NULL,
  `codigo_servico` varchar(8) DEFAULT NULL,
  `hora_inicial` time(6) DEFAULT NULL,
  `hora_final` time(6) DEFAULT NULL,
  `horas_total` decimal(20,2) DEFAULT NULL,
  `horas_intervalo` decimal(20,2) DEFAULT NULL,
  `horas_abono` decimal(20,2) DEFAULT NULL,
  `horas_traslado` decimal(20,2) DEFAULT NULL,
  `horas_faturadas` decimal(20,2) DEFAULT NULL,
  `motivo_abono` varchar(200) DEFAULT NULL,
  `observacoes` longtext,
  `ativo` int(11) DEFAULT '1',
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_timesheet_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_timesheet_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_transacao`
--

DROP TABLE IF EXISTS `tipos_transacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_transacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `descricao` varchar(200) NOT NULL,
  `tipo` varchar(1) NOT NULL,
  `integra_financeiro` varchar(1) NOT NULL,
  `integra_estoque` varchar(1) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `plano_conta_debito_id` int(11) NOT NULL,
  `centro_resultado_debito_id` int(11) NOT NULL,
  `plano_conta_credito_id` int(11) NOT NULL,
  `centro_resultado_credito_id` int(11) NOT NULL,
  `cfop_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tipos_transacao_codigo` (`codigo_empresa`,`codigo_filial`),
  KEY `fk_tipos_transacao_cfop_idx` (`cfop_id`),
  CONSTRAINT `fk_tipos_transacao_cfop` FOREIGN KEY (`cfop_id`) REFERENCES `cfop` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_tipos_transacao_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tmp_detalhe`
--

DROP TABLE IF EXISTS `tmp_detalhe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_detalhe` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `fav_nome_fantasia` varchar(60) DEFAULT NULL,
  `contrato` bigint(20) DEFAULT NULL,
  `ccab_codigo` bigint(20) DEFAULT NULL,
  `ccab_descricao` varchar(200) DEFAULT NULL,
  `vendedor_id` int(11) DEFAULT NULL,
  `ven_nome_fantasia` varchar(60) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `conta_financeira` varchar(20) DEFAULT NULL,
  `cfin_descricao` varchar(200) DEFAULT NULL,
  `centro_resultado` varchar(20) DEFAULT NULL,
  `cres_descricao` varchar(200) DEFAULT NULL,
  `mensagens_danfe` longtext NOT NULL,
  `cab_observacoes` longtext NOT NULL,
  `pedido_favorecido` varchar(20) DEFAULT NULL,
  `etapa` varchar(3) DEFAULT NULL,
  `eta_descricao` varchar(200) DEFAULT NULL,
  `cab_log_credito` varchar(50) NOT NULL,
  `cab_log_estoque` varchar(50) NOT NULL,
  `cab_log_faturado` varchar(50) NOT NULL,
  `cab_log_entregue` varchar(50) NOT NULL,
  `ativo` int(11) DEFAULT NULL,
  `data_emissao` date DEFAULT NULL,
  `data_entrega` date DEFAULT NULL,
  `data_faturamento` date DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `nfe_serie` smallint(6) DEFAULT NULL,
  `nfe_numero` bigint(20) DEFAULT NULL,
  `c_valor` decimal(20,2) NOT NULL,
  `i_id` int(11) DEFAULT NULL,
  `i_codigo_empresa` varchar(4) DEFAULT NULL,
  `i_codigo_filial` varchar(4) DEFAULT NULL,
  `i_pedido` bigint(20) DEFAULT NULL,
  `i_item` bigint(20) DEFAULT NULL,
  `i_produto` bigint(20) DEFAULT NULL,
  `p_descricao` varchar(200) DEFAULT NULL,
  `p_unidade` varchar(3) DEFAULT NULL,
  `i_quantidade_pecas` decimal(20,2) DEFAULT NULL,
  `i_altura` bigint(20) DEFAULT NULL,
  `i_largura` bigint(20) DEFAULT NULL,
  `i_area_real` decimal(20,2) DEFAULT NULL,
  `i_area_bruta_cliente` decimal(20,2) DEFAULT NULL,
  `i_area_bruta_fabrica` decimal(20,2) DEFAULT NULL,
  `i_pec_cliente` decimal(20,2) DEFAULT NULL,
  `i_pec_fabrica` decimal(20,2) DEFAULT NULL,
  `i_quantidade` decimal(20,5) DEFAULT NULL,
  `i_quantidade_cliente` decimal(20,5) DEFAULT NULL,
  `i_quantidade_fabrica` decimal(20,5) DEFAULT NULL,
  `i_preco_unitario` decimal(20,2) DEFAULT NULL,
  `i_total` decimal(20,2) DEFAULT NULL,
  `i_tipo_transacao` varchar(20) DEFAULT NULL,
  `i_armazem` varchar(20) DEFAULT NULL,
  `i_eficiencia_corte` decimal(20,2) NOT NULL,
  `i_observacoes` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tmp_grupofiltro`
--

DROP TABLE IF EXISTS `tmp_grupofiltro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_grupofiltro` (
  `id` int(11) NOT NULL,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `grupo` varchar(30) DEFAULT NULL,
  `sequencia` int(11) DEFAULT NULL,
  `label` varchar(60) DEFAULT NULL,
  `tipo_input` varchar(60) DEFAULT NULL,
  `class` varchar(60) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `value` varchar(8000) DEFAULT NULL,
  `placeholder` varchar(60) DEFAULT NULL,
  `onkeyup` varchar(60) DEFAULT NULL,
  `sql_source` varchar(8000) DEFAULT NULL,
  `required` char(1) DEFAULT NULL,
  `preselected` int(11) DEFAULT NULL,
  `text_option1` varchar(20) DEFAULT NULL,
  `text_option2` varchar(20) DEFAULT NULL,
  `text_option3` varchar(20) DEFAULT NULL,
  `text_option4` varchar(20) DEFAULT NULL,
  `text_option5` varchar(20) DEFAULT NULL,
  `value_option1` varchar(20) DEFAULT NULL,
  `value_option2` varchar(20) DEFAULT NULL,
  `value_option3` varchar(20) DEFAULT NULL,
  `value_option4` varchar(20) DEFAULT NULL,
  `value_option5` varchar(20) DEFAULT NULL,
  `proprietario` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tmp_vencto`
--

DROP TABLE IF EXISTS `tmp_vencto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_vencto` (
  `id` int(11) NOT NULL,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `favorecido_id` int(11) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `pedido` bigint(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `valor` decimal(20,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transferencias_bancarias`
--

DROP TABLE IF EXISTS `transferencias_bancarias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transferencias_bancarias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `numero_titulo` varchar(20) DEFAULT NULL,
  `numero_parcela` varchar(3) DEFAULT NULL,
  `tipo_movimento` varchar(3) DEFAULT NULL,
  `numero_cheque` varchar(20) DEFAULT NULL,
  `historico` varchar(200) NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `banco_origem_id` int(11) NOT NULL,
  `banco_destino_id` int(11) NOT NULL,
  `valor_lancamento` decimal(20,2) NOT NULL DEFAULT '0.00',
  `data_lancamento` date NOT NULL,
  `log_ins` varchar(50) DEFAULT NULL,
  `log_upd` varchar(50) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transferencias_bancarias_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  KEY `fk_transferencia_origem_idx` (`banco_origem_id`),
  KEY `fk_transferencia_destino_idx` (`banco_destino_id`),
  CONSTRAINT `fk_transferencia_destino` FOREIGN KEY (`banco_destino_id`) REFERENCES `bancos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_transferencia_origem` FOREIGN KEY (`banco_origem_id`) REFERENCES `bancos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_transferencias_bancarias_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unidades_medida`
--

DROP TABLE IF EXISTS `unidades_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidades_medida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_unidades_medida_codigo` (`codigo_empresa`,`codigo_filial`,`codigo`),
  CONSTRAINT `fk_unidades_medida_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `username` varchar(80) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `ativo` int(11) DEFAULT '1',
  `perfil_usuario_id` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `empresa_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_email` (`email`),
  UNIQUE KEY `uk_usuarios_username` (`username`),
  KEY `fki_usuarsios_perfil_usuario_id` (`perfil_usuario_id`),
  CONSTRAINT `fk_usuarios_perfil_usuario_id` FOREIGN KEY (`perfil_usuario_id`) REFERENCES `perfil_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vendedores`
--

DROP TABLE IF EXISTS `vendedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_empresa` varchar(4) DEFAULT NULL,
  `codigo_filial` varchar(4) DEFAULT NULL,
  `tipo_pessoa` varchar(1) NOT NULL,
  `categoria` varchar(20) NOT NULL,
  `cnpj` varchar(14) DEFAULT NULL,
  `nome_empresarial` varchar(80) NOT NULL,
  `nome_fantasia` varchar(60) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `tel_fixo1` varchar(20) DEFAULT NULL,
  `tel_fixo2` varchar(20) DEFAULT NULL,
  `tel_movel1` varchar(20) DEFAULT NULL,
  `tel_movel2` varchar(20) DEFAULT NULL,
  `email_geral` varchar(80) DEFAULT NULL,
  `contato_geral` varchar(80) DEFAULT NULL,
  `percentual_comissao` decimal(20,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vendedores_codigo_empresa_filial` (`codigo_empresa`,`codigo_filial`),
  CONSTRAINT `fk_vendedores_codigo_empresa_filial` FOREIGN KEY (`codigo_empresa`, `codigo_filial`) REFERENCES `empresas` (`codigo_empresa`, `codigo_filial`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-03 15:09:48
