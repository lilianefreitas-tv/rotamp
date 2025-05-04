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
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo` enum('solicitante','motorista','fiscal','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotoria_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (10,'Administrador do Sistema','admin@rotamp.local.com','$2y$10$cmjh1qB.8PEIcYcF.8NyV.IfkJFNkCXxaNdkgkqeU0VPU1.wGvOHa','admin','2025-04-29 23:04:34',5),(11,'Motorista 01','motorista01@mppa.mp.br','$2y$10$R11UwaGnvHwLxDomct.u0e4BefYfZ4r7E92OqEtDRX5MbMq.QZA7m','motorista','2025-04-29 23:24:47',5),(12,'Motorista 02','motorista02@mppa.mp.br','$2y$10$CdbGywq1FVNtZHIinD8rte20OKRkU8za5AT4rLyEZikIWk0rAx9ja','motorista','2025-04-29 23:25:21',5),(13,'Fiscal de Testes','fiscal@mppa.mp.br','$2y$10$Tm/ypiqBef/gKPGtRLF0lODpgwGpyO3yUmq.CzYoj.UhC.p0ePDD6','fiscal','2025-04-29 23:25:44',5),(14,'Liliane de Freitas Terra Vieira','lilianefreitas@mppa.mp.br','$2y$10$6CtF6CNyj/8pJrN/hsiNJuEJOX5sfkC34wguyvVigWITlxhyAVkju','solicitante','2025-04-29 23:26:04',5),(15,'Administrador de Testes','admin@mppa.mp.br','$2y$10$4BZ2.IzU/habxTzVJz7jeeYgo./Sx5.JV0XlStCrYiNcgb2CzSUbK','admin','2025-04-29 23:40:10',5);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-04 18:12:31
