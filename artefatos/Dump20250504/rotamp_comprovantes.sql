-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 192.168.72.75    Database: rotamp
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comprovantes`
--

DROP TABLE IF EXISTS `comprovantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comprovantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int NOT NULL,
  `assinado_motorista` tinyint(1) DEFAULT '0',
  `assinado_fiscal` tinyint(1) DEFAULT '0',
  `data_assinatura_motorista` datetime DEFAULT NULL,
  `data_assinatura_fiscal` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `assinado_solicitante` tinyint(1) DEFAULT '0',
  `data_assinatura_solicitante` datetime DEFAULT NULL,
  `id_solicitante_assinou` int DEFAULT NULL,
  `id_motorista_assinou` int DEFAULT NULL,
  `id_fiscal_assinou` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitacao_id` (`solicitacao_id`),
  CONSTRAINT `comprovantes_ibfk_1` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comprovantes`
--

LOCK TABLES `comprovantes` WRITE;
/*!40000 ALTER TABLE `comprovantes` DISABLE KEYS */;
INSERT INTO `comprovantes` VALUES (8,15,1,1,'2025-04-29 21:41:33','2025-04-29 21:21:16','2025-04-29 23:42:31',1,'2025-04-30 11:43:06',14,11,13),(9,16,1,1,'2025-04-29 21:35:47','2025-04-29 22:09:30','2025-04-30 00:27:03',1,'2025-04-30 11:43:01',14,12,13),(10,17,1,1,'2025-04-30 11:38:49','2025-04-30 11:39:15','2025-04-30 11:28:13',1,'2025-04-30 11:41:31',14,11,13),(11,18,1,1,'2025-04-30 11:45:07','2025-04-30 11:45:29','2025-04-30 11:45:01',1,'2025-04-30 11:45:16',14,11,13),(12,19,1,1,'2025-04-30 11:52:54','2025-04-30 11:59:15','2025-04-30 11:51:59',1,'2025-04-30 11:52:10',14,12,13),(13,20,1,1,'2025-04-30 12:00:52','2025-04-30 09:04:57','2025-04-30 12:00:47',1,'2025-04-30 09:04:09',14,11,13),(14,23,1,1,'2025-05-04 12:53:35','2025-05-04 12:54:25','2025-04-30 17:29:45',1,'2025-05-04 12:52:56',14,12,NULL);
/*!40000 ALTER TABLE `comprovantes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-04 18:12:32
