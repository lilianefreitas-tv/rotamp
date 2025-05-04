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
-- Table structure for table `solicitacoes`
--

DROP TABLE IF EXISTS `solicitacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `solicitante_id` int NOT NULL,
  `motorista_id` int NOT NULL,
  `descricao` text COLLATE utf8mb4_general_ci NOT NULL,
  `data_ida` date NOT NULL,
  `data_volta` date NOT NULL,
  `hora_saida` time NOT NULL,
  `hora_chegada` time NOT NULL,
  `origem` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `destino` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `solicitante_id` (`solicitante_id`),
  KEY `motorista_id` (`motorista_id`),
  CONSTRAINT `solicitacoes_ibfk_1` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `solicitacoes_ibfk_2` FOREIGN KEY (`motorista_id`) REFERENCES `motoristas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitacoes`
--

LOCK TABLES `solicitacoes` WRITE;
/*!40000 ALTER TABLE `solicitacoes` DISABLE KEYS */;
INSERT INTO `solicitacoes` VALUES (15,14,4,'Nova Solicitação de Viagem','2025-04-30','2025-04-30','08:00:00','12:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-29 23:41:04'),(16,14,5,'Nova Solicitação de Viagem','2025-04-30','2025-04-30','08:00:00','12:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-29 23:41:25'),(17,14,4,'Nova Solicitação de Viagem - teste de validação 30/04/2025','2025-05-05','2025-05-05','08:00:00','12:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-30 11:25:48'),(18,14,4,'teste','2025-05-02','2025-05-02','08:00:00','15:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-30 11:44:29'),(19,14,5,'teste','2025-05-03','2025-05-03','08:00:00','12:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-30 11:51:32'),(20,14,4,'teste','2025-05-08','2025-05-08','09:00:00','23:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-30 12:00:12'),(21,14,4,'teste','2025-05-07','2025-05-07','08:00:00','12:00:00','Altamira/PA','Altamira/PA','cancelado','2025-04-30 15:20:03'),(22,14,5,'teste','2025-05-01','2025-05-01','08:00:00','12:00:00','Altamira/PA','Anapu/PA','cancelado','2025-04-30 15:40:34'),(23,14,5,'teste','2025-05-01','2025-05-01','08:00:00','18:00:00','Altamira/PA','Anapu/PA','finalizado','2025-04-30 15:45:51');
/*!40000 ALTER TABLE `solicitacoes` ENABLE KEYS */;
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
