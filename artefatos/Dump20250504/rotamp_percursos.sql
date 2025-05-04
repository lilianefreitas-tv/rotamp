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
-- Table structure for table `percursos`
--

DROP TABLE IF EXISTS `percursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `percursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int NOT NULL,
  `odometro_inicio` int DEFAULT NULL,
  `hora_saida_real` datetime DEFAULT NULL,
  `odometro_fim` int DEFAULT NULL,
  `hora_chegada_real` datetime DEFAULT NULL,
  `km_rodado` int DEFAULT NULL,
  `tempo_operacao` time DEFAULT NULL,
  `assinatura_motorista` tinyint(1) DEFAULT '0',
  `assinatura_fiscal` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `solicitacao_id` (`solicitacao_id`),
  CONSTRAINT `percursos_ibfk_1` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacoes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `percursos`
--

LOCK TABLES `percursos` WRITE;
/*!40000 ALTER TABLE `percursos` DISABLE KEYS */;
INSERT INTO `percursos` VALUES (12,15,1111,'2025-04-30 08:00:00',2222,'2025-04-30 12:00:00',1111,'04:00:00',0,0,'2025-04-29 23:42:21'),(13,16,1111,'2025-04-30 08:00:00',2222,'2025-04-30 12:00:00',1111,'04:00:00',0,0,'2025-04-30 00:26:54'),(14,17,4567,'2025-05-05 08:15:00',4587,'2025-05-05 15:00:00',20,'06:45:00',0,0,'2025-04-30 11:26:48'),(15,18,5555,'2025-05-02 15:00:00',6666,'2025-05-02 17:00:00',1111,'02:00:00',0,0,'2025-04-30 11:44:53'),(16,19,3333,'2025-05-03 08:00:00',4444,'2025-05-03 15:00:00',1111,'07:00:00',0,0,'2025-04-30 11:51:51'),(17,20,6666,'2025-05-08 08:00:00',6666,'2025-05-08 17:00:00',0,'09:00:00',0,0,'2025-04-30 12:00:39'),(18,23,2222,'2025-05-01 06:00:00',3333,'2025-05-06 21:00:00',1111,'15:00:00',0,0,'2025-04-30 17:26:00');
/*!40000 ALTER TABLE `percursos` ENABLE KEYS */;
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
