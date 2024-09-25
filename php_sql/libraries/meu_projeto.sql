-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           8.0.37 - MySQL Community Server - GPL
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para meu_projeto
CREATE DATABASE IF NOT EXISTS `meu_projeto` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `meu_projeto`;

-- A despejar estrutura para tabela meu_projeto.utilizadores
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `apelido` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `funcao` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela meu_projeto.utilizadores: ~4 rows (aproximadamente)
INSERT INTO `utilizadores` (`id`, `nome`, `apelido`, `email`, `telefone`, `username`, `senha`, `funcao`, `created_at`, `updated_at`) VALUES
	(3, 'Admin', 'User', 'admin@example.com', '123456789', 'admin', '$2y$10$2IksHy5oNVPxgCfbyKC/5ubK9AR36Y5GOKIkDHnCrrY8SiNEdanFG', 'admin', NULL, NULL),
	(8, 'test', 'test', 'test@example.com', '987654321', 'teste', '$2y$10$2IksHy5oNVPxgCfbyKC/5ubK9AR36Y5GOKIkDHnCrrY8SiNEdanFG', 'user', NULL, '2024-08-25 18:51:15'),
	(22, 'Bea', 'araujo', 'beapinto@hotmail.com', '935868397', 'beapinto', '$2y$10$60hHmTzehFnNdgjpYSZQoOjVsXdNBO8k8YpwSlHV0WHExz8Dp12R6', 'user', '2024-09-04 16:00:38', '2024-09-04 16:00:38'),
	(23, 'wesley', 'araujo', 'wesleyaraujo@icloud.com', '931964105', 'wesleyaraujo', '$2y$10$p0q4IA793Vudb0/BfV5I8errGnhFGIRE7PyqfFGwdwQKg5YbcbLU.', 'user', '2024-09-04 16:04:08', '2024-09-04 16:04:08');


-- A despejar estrutura para tabela meu_projeto.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `apelido` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `id_utilizador` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `id_utilizador` (`id_utilizador`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Primeiro, inserindo os clientes
INSERT INTO `clientes` (`id`, `nome`, `apelido`, `email`, `telefone`, `id_utilizador`) VALUES
(22, 'Cliente 1', 'Sobrenome 1', 'cliente1@example.com', '123456789', NULL),
(23, 'Cliente 2', 'Sobrenome 2', 'cliente2@example.com', '987654321', NULL);

-- A despejar dados para tabela meu_projeto.clientes: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela meu_projeto.consultas
CREATE TABLE IF NOT EXISTS `consultas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `data_consulta` datetime DEFAULT NULL,
  `observacoes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_consultas_utilizadores` (`id_cliente`),
  CONSTRAINT `FK_consultas_utilizadores` FOREIGN KEY (`id_cliente`) REFERENCES `utilizadores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela meu_projeto.consultas: ~6 rows (aproximadamente)
INSERT INTO `consultas` (`id`, `id_cliente`, `data_consulta`, `observacoes`, `created_at`, `updated_at`) VALUES
	(59, 22, '2024-09-12 15:07:00', 'consulta com chefe executivo', '2024-09-04 16:02:20', '2024-09-04 16:02:20'),
	(60, 22, '2024-09-09 17:25:00', 'consulta sobre orçamentos!', '2024-09-04 16:02:57', '2024-09-04 16:02:57'),
	(61, 22, '2024-09-25 17:04:00', 'reunião com RH', '2024-09-04 16:03:20', '2024-09-04 16:03:20'),
	(62, 23, '2024-09-10 10:10:00', 'consulta apenas informativa', '2024-09-04 16:04:43', '2024-09-04 16:04:43'),
	(63, 23, '2024-09-12 17:13:00', 'consulta com setor de marketing', '2024-09-04 16:05:32', '2024-09-04 16:05:32'),
	(64, 23, '2024-09-13 17:08:00', 'consulta com setor de testes', '2024-09-04 16:06:07', '2024-09-04 16:06:07');

-- A despejar estrutura para tabela meu_projeto.noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `data_publicacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela meu_projeto.noticias: ~6 rows (aproximadamente)
INSERT INTO `noticias` (`id`, `titulo`, `conteudo`, `data_publicacao`) VALUES
	(1, 'Microsoft Portugal Partner of the Year 2023', 'Reconhecimento pela excelência na implementação de soluções Microsoft nas mais diversas indústrias.', '2023-05-28 00:00:00'),
	(2, 'Revista Human Resources 2022', 'A melhor empresa do setor de consultoria.', '2023-01-05 00:00:00'),
	(3, 'OnStrategy 2022', 'Empresa com melhor reputação em IT Services .', '2023-01-10 00:00:00'),
	(6, 'Revista Human Resources 2023', 'Melhor empresa de Tecnologia e Transformação Digital', '2024-08-27 14:17:02'),
	(7, 'Revista Human Resources 2023', 'Melhor empresa de Tecnologia e Transformação Digital', '2023-06-27 00:00:00'),
	(11, 'Revista Human Resources 2022', 'Melhor empresa em Inclusão & Diversidade', '2022-08-04 00:00:00');

-- A despejar estrutura para tabela meu_projeto.projetos
CREATE TABLE IF NOT EXISTS `projetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tecnologia` varchar(255) NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela meu_projeto.projetos: ~6 rows (aproximadamente)
INSERT INTO `projetos` (`id`, `titulo`, `descricao`, `tecnologia`, `data_inicio`, `data_fim`, `fotografia`, `status`, `criado_em`, `atualizado_em`) VALUES
	(16, 'Landing Page MSI', 'fazer um site simples para MSI com o formato de  Landing Page.', 'PHP, Javascript/AJAX, HTML e CSS', '2024-09-11', '2024-11-28', 'MSI_MPG.jpg', 'ativo', '2024-09-04 15:09:02', '2024-09-04 15:09:02'),
	(17, 'Landing Page BMW', 'Landing Page simples para BMW', 'php/javascrpt/html/css', '2024-09-26', '2024-12-19', 'bmw.webp', 'ativo', '2024-09-04 15:12:00', '2024-09-04 15:12:00'),
	(18, 'Site Básico para Mercedes', 'Site Básico para Mercedes', 'php/javascrpt/html/css', '2024-09-12', '2024-11-22', 'mercedez.jpg', 'ativo', '2024-09-04 15:13:53', '2024-09-04 15:13:53'),
	(19, 'Site Avançado para restaurante Mochi', 'Site Avançado para restaurante Mochi com sistema de Database para armazenar dados dos cliente para poder fazer um jornal dos descontos direto para o e-mail.', 'PHP/SQL/MYSQL/JAVASCRIPT/HTML/CSS', '2024-10-31', '2025-03-14', 'mochi.jpg', 'ativo', '2024-09-04 15:23:54', '2024-09-04 15:23:54'),
	(20, 'Site Avançado  D\'esquina', 'Site Avançado para restaurante D\'esquina com sistema de Database para armazenar dados dos cliente para poder fazer um jornal dos descontos direto para o e-mail.', 'php/sql/mysql/javascrpt/html/css', '2024-09-27', '2025-01-02', 'desquina.jpg', 'ativo', '2024-09-04 15:25:39', '2024-09-04 15:25:39'),
(21, 'eCommerce D''BEA', 'eCommerce para loja de roupas D''BEA com ferramenta de venda e compras direto pelo site usando api''s de pagamentos e banco de dados de clientes para históricos de compras e dados de faturação.', 'php/sql/mysql/APIS/javascrpt/html/css', '2024-09-30', '2025-11-27', 'OIP.jpg', 'ativo', '2024-09-04 15:30:06', '2024-09-04 15:30:06');


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
