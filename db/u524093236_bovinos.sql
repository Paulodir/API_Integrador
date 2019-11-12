-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.15 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para u524093236_bovinos
CREATE DATABASE IF NOT EXISTS `u524093236_bovinos` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `u524093236_bovinos`;

-- Copiando estrutura para tabela u524093236_bovinos.alimentacao
CREATE TABLE IF NOT EXISTS `alimentacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `minimo` float NOT NULL DEFAULT '0',
  `maximo` float NOT NULL DEFAULT '0',
  `racao` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.bovino
CREATE TABLE IF NOT EXISTS `bovino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `raca_id` int(11) NOT NULL,
  `brinco` int(15) NOT NULL,
  `nome` varchar(25) DEFAULT NULL,
  `nascimento` date NOT NULL,
  `peso` float(4,3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_bovino_raca` (`raca_id`),
  CONSTRAINT `FK_bovino_raca` FOREIGN KEY (`raca_id`) REFERENCES `raca` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.ci_sessions
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.inseminacao
CREATE TABLE IF NOT EXISTS `inseminacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bovino_id` int(11) NOT NULL,
  `raca_id` int(11) NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_natalidade_bovino` (`bovino_id`),
  KEY `FK_natalidade_raca` (`raca_id`),
  CONSTRAINT `FK_natalidade_bovino` FOREIGN KEY (`bovino_id`) REFERENCES `bovino` (`id`),
  CONSTRAINT `FK_natalidade_raca` FOREIGN KEY (`raca_id`) REFERENCES `raca` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=464 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.ordenha
CREATE TABLE IF NOT EXISTS `ordenha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bovino_id` int(11) NOT NULL,
  `leite` float(3,3) NOT NULL,
  `descarte` float(3,3) NOT NULL,
  `coleta` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ordenha_bovino` (`bovino_id`),
  CONSTRAINT `FK_ordenha_bovino` FOREIGN KEY (`bovino_id`) REFERENCES `bovino` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.partos
CREATE TABLE IF NOT EXISTS `partos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bovino_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `nascido` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_partos_bovino` (`bovino_id`),
  CONSTRAINT `FK_partos_bovino` FOREIGN KEY (`bovino_id`) REFERENCES `bovino` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.raca
CREATE TABLE IF NOT EXISTS `raca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.token
CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apikey` varchar(40) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_token_usuario` (`usuario_id`),
  CONSTRAINT `FK_token_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela u524093236_bovinos.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
usuario