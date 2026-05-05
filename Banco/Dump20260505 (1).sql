-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: barbearia
-- ------------------------------------------------------
-- Server version	8.0.41

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
-- Table structure for table `agendamento`
--

DROP TABLE IF EXISTS `agendamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agendamento` (
  `idagendamento` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idbarbeiro` int DEFAULT NULL,
  `idservico` int DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `horario` time DEFAULT NULL,
  `status` enum('pendente','confirmado','cancelado') DEFAULT 'pendente',
  `valor_final` decimal(10,2) DEFAULT NULL,
  `plano_ativo` int DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idagendamento`),
  KEY `iduser` (`iduser`),
  KEY `idbarbeiro` (`idbarbeiro`),
  KEY `idservico` (`idservico`),
  KEY `plano_ativo` (`plano_ativo`),
  CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`),
  CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`idbarbeiro`) REFERENCES `barbeiro` (`idbarbeiro`),
  CONSTRAINT `agendamento_ibfk_3` FOREIGN KEY (`idservico`) REFERENCES `servico` (`idservico`),
  CONSTRAINT `agendamento_ibfk_4` FOREIGN KEY (`plano_ativo`) REFERENCES `plano_ativo` (`idplano_ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=598 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agendamento`
--

LOCK TABLES `agendamento` WRITE;
/*!40000 ALTER TABLE `agendamento` DISABLE KEYS */;
INSERT INTO `agendamento` VALUES (110,15,5,13,'2025-11-27 00:00:00','09:00:00','confirmado',20.00,NULL,'2025-11-24 23:37:10'),(111,15,5,14,'2025-11-23 00:00:00','13:00:00','confirmado',15.00,NULL,'2025-11-24 23:38:23'),(113,15,5,12,'2025-12-03 00:00:00','09:00:00','confirmado',20.00,NULL,'2025-12-03 23:10:02'),(150,1,5,16,NULL,'09:00:00','confirmado',65.00,NULL,'2025-12-07 02:10:05'),(151,1,5,9,NULL,'10:30:00','confirmado',35.00,NULL,'2025-12-07 02:10:05'),(158,1,5,10,NULL,'09:00:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(159,1,5,20,NULL,'10:30:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(166,1,5,19,NULL,'09:00:00','confirmado',80.00,NULL,'2025-12-07 02:10:05'),(167,1,5,13,NULL,'10:30:00','confirmado',40.00,NULL,'2025-12-07 02:10:05'),(174,1,5,20,NULL,'09:00:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(175,1,5,15,NULL,'10:30:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(182,1,5,9,NULL,'09:00:00','confirmado',35.00,NULL,'2025-12-07 02:10:05'),(183,1,5,11,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(190,1,5,15,NULL,'09:00:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(191,1,5,17,NULL,'10:30:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(198,1,5,15,NULL,'09:00:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(199,1,5,16,NULL,'10:30:00','confirmado',65.00,NULL,'2025-12-07 02:10:05'),(206,1,5,11,NULL,'09:00:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(207,1,5,21,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(214,1,5,20,NULL,'09:00:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(215,1,5,11,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(222,1,5,11,NULL,'09:00:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(223,1,5,20,NULL,'10:30:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(230,1,5,13,NULL,'09:00:00','confirmado',40.00,NULL,'2025-12-07 02:10:05'),(231,1,5,18,NULL,'10:30:00','confirmado',55.00,NULL,'2025-12-07 02:10:05'),(238,1,5,10,NULL,'09:00:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(239,1,5,21,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(246,1,5,20,NULL,'09:00:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(247,1,5,9,NULL,'10:30:00','confirmado',35.00,NULL,'2025-12-07 02:10:05'),(254,1,5,9,NULL,'09:00:00','confirmado',35.00,NULL,'2025-12-07 02:10:05'),(255,1,5,20,NULL,'10:30:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(262,1,5,18,NULL,'09:00:00','confirmado',55.00,NULL,'2025-12-07 02:10:05'),(263,1,5,9,NULL,'10:30:00','confirmado',35.00,NULL,'2025-12-07 02:10:05'),(270,1,5,14,NULL,'09:00:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(271,1,5,21,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(278,1,5,14,NULL,'09:00:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(279,1,5,20,NULL,'10:30:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(286,1,5,10,NULL,'09:00:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(287,1,5,16,NULL,'10:30:00','confirmado',65.00,NULL,'2025-12-07 02:10:05'),(294,1,5,10,NULL,'09:00:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(295,1,5,11,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(302,1,5,21,NULL,'09:00:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(303,1,5,17,NULL,'10:30:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(310,1,5,18,NULL,'09:00:00','confirmado',55.00,NULL,'2025-12-07 02:10:05'),(311,1,5,15,NULL,'10:30:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(318,1,5,11,NULL,'09:00:00','confirmado',25.00,NULL,'2025-12-07 02:10:05'),(319,1,5,12,NULL,'10:30:00','confirmado',40.00,NULL,'2025-12-07 02:10:05'),(326,1,5,19,NULL,'09:00:00','confirmado',80.00,NULL,'2025-12-07 02:10:05'),(327,1,5,14,NULL,'10:30:00','confirmado',30.00,NULL,'2025-12-07 02:10:05'),(334,1,5,12,NULL,'09:00:00','confirmado',40.00,NULL,'2025-12-07 02:10:05'),(335,1,5,13,NULL,'10:30:00','confirmado',40.00,NULL,'2025-12-07 02:10:05'),(342,1,5,17,NULL,'09:00:00','confirmado',60.00,NULL,'2025-12-07 02:10:05'),(343,1,5,20,NULL,'10:30:00','confirmado',20.00,NULL,'2025-12-07 02:10:05'),(350,1,5,12,NULL,'09:00:00','confirmado',40.00,NULL,'2025-12-07 02:10:06'),(351,1,5,21,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:06'),(358,1,5,17,NULL,'09:00:00','confirmado',60.00,NULL,'2025-12-07 02:10:06'),(359,1,5,21,NULL,'10:30:00','confirmado',25.00,NULL,'2025-12-07 02:10:06'),(366,1,5,19,NULL,'09:00:00','confirmado',80.00,NULL,'2025-12-07 02:10:06'),(367,1,5,19,NULL,'10:30:00','confirmado',80.00,NULL,'2025-12-07 02:10:06'),(374,1,5,11,NULL,'09:00:00','confirmado',25.00,NULL,'2025-12-07 02:10:06'),(375,1,5,16,NULL,'10:30:00','confirmado',65.00,NULL,'2025-12-07 02:10:06'),(382,1,5,21,NULL,'09:00:00','confirmado',25.00,NULL,'2025-12-07 02:10:06'),(383,1,5,12,NULL,'10:30:00','confirmado',40.00,NULL,'2025-12-07 02:10:06'),(390,15,5,13,'2025-12-07 00:00:00','09:00:00','confirmado',20.00,NULL,'2025-12-07 02:11:17'),(391,15,5,12,'2025-12-06 00:00:00','09:00:00','confirmado',20.00,NULL,'2025-12-07 02:13:09'),(392,15,5,13,'2025-12-09 00:00:00','09:00:00','confirmado',20.00,NULL,'2025-12-07 19:39:37'),(393,15,5,12,'2025-12-08 00:00:00','12:00:00','confirmado',20.00,NULL,'2025-12-07 19:55:27'),(394,15,5,12,'2025-12-07 00:00:00','10:00:00','confirmado',20.00,NULL,'2025-12-07 19:56:31'),(396,15,5,12,'2025-12-10 00:00:00','10:00:00','cancelado',20.00,NULL,'2025-12-10 02:47:22'),(397,15,5,12,'2025-12-10 00:00:00','09:00:00','cancelado',20.00,NULL,'2025-12-10 05:00:38'),(398,15,5,13,'2025-12-10 00:00:00','09:00:00','cancelado',20.00,NULL,'2025-12-10 05:02:43'),(444,1,5,12,'2025-12-09 00:00:00','09:00:00','pendente',50.00,NULL,'2025-12-10 05:11:28'),(445,2,28,9,'2025-12-09 00:00:00','09:30:00','pendente',60.00,NULL,'2025-12-10 05:11:28'),(446,3,29,13,'2025-12-09 00:00:00','10:00:00','pendente',45.00,NULL,'2025-12-10 05:11:28'),(447,4,5,14,'2025-12-09 00:00:00','10:30:00','pendente',55.00,NULL,'2025-12-10 05:11:28'),(448,1,5,12,'2025-12-09 00:00:00','09:00:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(449,2,28,9,'2025-12-09 00:00:00','09:30:00','pendente',60.00,NULL,'2025-12-10 05:14:21'),(450,3,29,13,'2025-12-09 00:00:00','10:00:00','pendente',45.00,NULL,'2025-12-10 05:14:21'),(451,4,5,14,'2025-12-09 00:00:00','10:30:00','pendente',55.00,NULL,'2025-12-10 05:14:21'),(452,1,28,15,'2025-12-09 00:00:00','11:00:00','pendente',40.00,NULL,'2025-12-10 05:14:21'),(453,2,29,16,'2025-12-09 00:00:00','11:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(454,3,5,17,'2025-12-10 00:00:00','09:00:00','pendente',80.00,NULL,'2025-12-10 05:14:21'),(455,4,28,18,'2025-12-10 00:00:00','09:30:00','pendente',70.00,NULL,'2025-12-10 05:14:21'),(456,1,29,19,'2025-12-10 00:00:00','10:00:00','pendente',90.00,NULL,'2025-12-10 05:14:21'),(457,2,5,20,'2025-12-10 00:00:00','10:30:00','pendente',30.00,NULL,'2025-12-10 05:14:21'),(458,3,28,21,'2025-12-10 00:00:00','11:00:00','pendente',35.00,NULL,'2025-12-10 05:14:21'),(459,4,29,12,'2025-12-10 00:00:00','11:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(460,1,5,13,'2025-12-11 00:00:00','09:00:00','pendente',40.00,NULL,'2025-12-10 05:14:21'),(461,2,28,14,'2025-12-11 00:00:00','09:30:00','pendente',55.00,NULL,'2025-12-10 05:14:21'),(462,3,29,15,'2025-12-11 00:00:00','10:00:00','pendente',45.00,NULL,'2025-12-10 05:14:21'),(463,4,5,16,'2025-12-11 00:00:00','10:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(464,1,28,17,'2025-12-11 00:00:00','11:00:00','pendente',80.00,NULL,'2025-12-10 05:14:21'),(465,2,29,18,'2025-12-11 00:00:00','11:30:00','pendente',70.00,NULL,'2025-12-10 05:14:21'),(466,3,5,19,'2025-12-12 00:00:00','09:00:00','pendente',90.00,NULL,'2025-12-10 05:14:21'),(467,4,28,20,'2025-12-12 00:00:00','09:30:00','pendente',30.00,NULL,'2025-12-10 05:14:21'),(468,1,29,21,'2025-12-12 00:00:00','10:00:00','pendente',35.00,NULL,'2025-12-10 05:14:21'),(469,2,5,12,'2025-12-12 00:00:00','10:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(470,3,28,13,'2025-12-12 00:00:00','11:00:00','pendente',40.00,NULL,'2025-12-10 05:14:21'),(471,4,29,14,'2025-12-12 00:00:00','11:30:00','pendente',55.00,NULL,'2025-12-10 05:14:21'),(472,1,5,15,'2025-12-13 00:00:00','09:00:00','pendente',45.00,NULL,'2025-12-10 05:14:21'),(473,2,28,16,'2025-12-13 00:00:00','09:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(474,3,29,17,'2025-12-13 00:00:00','10:00:00','pendente',80.00,NULL,'2025-12-10 05:14:21'),(475,4,5,18,'2025-12-13 00:00:00','10:30:00','pendente',70.00,NULL,'2025-12-10 05:14:21'),(476,1,28,19,'2025-12-13 00:00:00','11:00:00','pendente',90.00,NULL,'2025-12-10 05:14:21'),(477,2,29,20,'2025-12-13 00:00:00','11:30:00','pendente',30.00,NULL,'2025-12-10 05:14:21'),(478,3,5,21,'2025-12-14 00:00:00','09:00:00','pendente',35.00,NULL,'2025-12-10 05:14:21'),(479,4,28,12,'2025-12-14 00:00:00','09:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(480,1,29,13,'2025-12-14 00:00:00','10:00:00','pendente',40.00,NULL,'2025-12-10 05:14:21'),(481,2,5,14,'2025-12-14 00:00:00','10:30:00','pendente',55.00,NULL,'2025-12-10 05:14:21'),(482,3,28,15,'2025-12-14 00:00:00','11:00:00','pendente',45.00,NULL,'2025-12-10 05:14:21'),(483,4,29,16,'2025-12-14 00:00:00','11:30:00','pendente',50.00,NULL,'2025-12-10 05:14:21'),(484,1,5,12,'2025-12-09 00:00:00','09:00:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(485,2,5,13,'2025-12-09 00:00:00','09:30:00','pendente',45.00,NULL,'2025-12-10 05:16:20'),(486,3,5,14,'2025-12-09 00:00:00','10:00:00','pendente',55.00,NULL,'2025-12-10 05:16:20'),(487,4,5,15,'2025-12-09 00:00:00','10:30:00','pendente',40.00,NULL,'2025-12-10 05:16:20'),(488,1,5,16,'2025-12-09 00:00:00','11:00:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(489,2,5,17,'2025-12-09 00:00:00','11:30:00','pendente',80.00,NULL,'2025-12-10 05:16:20'),(490,3,5,18,'2025-12-09 00:00:00','12:00:00','pendente',70.00,NULL,'2025-12-10 05:16:20'),(491,4,5,19,'2025-12-09 00:00:00','12:30:00','pendente',90.00,NULL,'2025-12-10 05:16:20'),(492,1,5,20,'2025-12-09 00:00:00','13:00:00','pendente',30.00,NULL,'2025-12-10 05:16:20'),(493,2,5,21,'2025-12-09 00:00:00','13:30:00','pendente',35.00,NULL,'2025-12-10 05:16:20'),(494,3,5,9,'2025-12-09 00:00:00','14:00:00','pendente',60.00,NULL,'2025-12-10 05:16:20'),(495,4,5,10,'2025-12-09 00:00:00','14:30:00','pendente',70.00,NULL,'2025-12-10 05:16:20'),(496,1,5,11,'2025-12-09 00:00:00','15:00:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(497,2,5,12,'2025-12-09 00:00:00','15:30:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(498,3,5,13,'2025-12-09 00:00:00','16:00:00','pendente',45.00,NULL,'2025-12-10 05:16:20'),(499,4,5,14,'2025-12-09 00:00:00','16:30:00','pendente',55.00,NULL,'2025-12-10 05:16:20'),(500,1,5,15,'2025-12-09 00:00:00','17:00:00','pendente',40.00,NULL,'2025-12-10 05:16:20'),(501,2,5,16,'2025-12-09 00:00:00','17:30:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(502,3,5,17,'2025-12-10 00:00:00','09:00:00','pendente',80.00,NULL,'2025-12-10 05:16:20'),(503,4,5,18,'2025-12-10 00:00:00','09:30:00','pendente',70.00,NULL,'2025-12-10 05:16:20'),(504,1,5,19,'2025-12-10 00:00:00','10:00:00','pendente',90.00,NULL,'2025-12-10 05:16:20'),(505,2,5,20,'2025-12-10 00:00:00','10:30:00','pendente',30.00,NULL,'2025-12-10 05:16:20'),(506,3,5,21,'2025-12-10 00:00:00','11:00:00','pendente',35.00,NULL,'2025-12-10 05:16:20'),(507,4,5,9,'2025-12-10 00:00:00','11:30:00','pendente',60.00,NULL,'2025-12-10 05:16:20'),(508,1,5,10,'2025-12-10 00:00:00','12:00:00','pendente',70.00,NULL,'2025-12-10 05:16:20'),(509,2,5,11,'2025-12-10 00:00:00','12:30:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(510,3,5,12,'2025-12-10 00:00:00','13:00:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(511,4,5,13,'2025-12-10 00:00:00','13:30:00','pendente',45.00,NULL,'2025-12-10 05:16:20'),(512,1,5,14,'2025-12-10 00:00:00','14:00:00','pendente',55.00,NULL,'2025-12-10 05:16:20'),(513,2,5,15,'2025-12-10 00:00:00','14:30:00','pendente',40.00,NULL,'2025-12-10 05:16:20'),(514,3,5,16,'2025-12-10 00:00:00','15:00:00','pendente',50.00,NULL,'2025-12-10 05:16:20'),(515,4,5,17,'2025-12-10 00:00:00','15:30:00','pendente',80.00,NULL,'2025-12-10 05:16:20'),(516,1,5,18,'2025-12-10 00:00:00','16:00:00','pendente',70.00,NULL,'2025-12-10 05:16:20'),(517,2,5,19,'2025-12-10 00:00:00','16:30:00','pendente',90.00,NULL,'2025-12-10 05:16:20'),(518,3,5,20,'2025-12-10 00:00:00','17:00:00','pendente',30.00,NULL,'2025-12-10 05:16:20'),(519,4,5,21,'2025-12-10 00:00:00','17:30:00','pendente',35.00,NULL,'2025-12-10 05:16:20'),(520,1,5,12,'2025-12-09 00:00:00','09:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(521,2,5,13,'2025-12-09 00:00:00','09:30:00','pendente',45.00,NULL,'2025-12-10 05:18:30'),(522,3,5,14,'2025-12-09 00:00:00','10:00:00','pendente',55.00,NULL,'2025-12-10 05:18:30'),(523,4,5,15,'2025-12-09 00:00:00','10:30:00','pendente',40.00,NULL,'2025-12-10 05:18:30'),(524,1,5,16,'2025-12-09 00:00:00','11:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(525,2,5,17,'2025-12-09 00:00:00','11:30:00','pendente',80.00,NULL,'2025-12-10 05:18:30'),(526,3,5,18,'2025-12-09 00:00:00','12:00:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(527,4,5,19,'2025-12-09 00:00:00','12:30:00','pendente',90.00,NULL,'2025-12-10 05:18:30'),(528,1,5,20,'2025-12-09 00:00:00','13:00:00','pendente',30.00,NULL,'2025-12-10 05:18:30'),(529,2,5,21,'2025-12-09 00:00:00','13:30:00','pendente',35.00,NULL,'2025-12-10 05:18:30'),(530,3,5,9,'2025-12-09 00:00:00','14:00:00','pendente',60.00,NULL,'2025-12-10 05:18:30'),(531,4,5,10,'2025-12-09 00:00:00','14:30:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(532,1,5,11,'2025-12-09 00:00:00','15:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(533,2,5,12,'2025-12-09 00:00:00','15:30:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(534,3,5,13,'2025-12-09 00:00:00','16:00:00','pendente',45.00,NULL,'2025-12-10 05:18:30'),(535,4,5,14,'2025-12-09 00:00:00','16:30:00','pendente',55.00,NULL,'2025-12-10 05:18:30'),(536,1,5,15,'2025-12-09 00:00:00','17:00:00','pendente',40.00,NULL,'2025-12-10 05:18:30'),(537,2,5,16,'2025-12-09 00:00:00','17:30:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(538,3,5,17,'2025-12-10 00:00:00','09:00:00','pendente',80.00,NULL,'2025-12-10 05:18:30'),(539,4,5,18,'2025-12-10 00:00:00','09:30:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(540,1,5,19,'2025-12-10 00:00:00','10:00:00','pendente',90.00,NULL,'2025-12-10 05:18:30'),(541,2,5,20,'2025-12-10 00:00:00','10:30:00','pendente',30.00,NULL,'2025-12-10 05:18:30'),(542,3,5,21,'2025-12-10 00:00:00','11:00:00','pendente',35.00,NULL,'2025-12-10 05:18:30'),(543,4,5,9,'2025-12-10 00:00:00','11:30:00','pendente',60.00,NULL,'2025-12-10 05:18:30'),(544,1,5,10,'2025-12-10 00:00:00','12:00:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(545,2,5,11,'2025-12-10 00:00:00','12:30:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(546,3,5,12,'2025-12-10 00:00:00','13:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(547,4,5,13,'2025-12-10 00:00:00','13:30:00','pendente',45.00,NULL,'2025-12-10 05:18:30'),(548,1,5,14,'2025-12-10 00:00:00','14:00:00','pendente',55.00,NULL,'2025-12-10 05:18:30'),(549,2,5,15,'2025-12-10 00:00:00','14:30:00','pendente',40.00,NULL,'2025-12-10 05:18:30'),(550,3,5,16,'2025-12-10 00:00:00','15:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(551,4,5,17,'2025-12-10 00:00:00','15:30:00','pendente',80.00,NULL,'2025-12-10 05:18:30'),(552,1,5,18,'2025-12-10 00:00:00','16:00:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(553,2,5,19,'2025-12-10 00:00:00','16:30:00','pendente',90.00,NULL,'2025-12-10 05:18:30'),(554,3,5,20,'2025-12-10 00:00:00','17:00:00','pendente',30.00,NULL,'2025-12-10 05:18:30'),(555,4,5,21,'2025-12-10 00:00:00','17:30:00','pendente',35.00,NULL,'2025-12-10 05:18:30'),(556,1,5,12,'2025-12-11 00:00:00','09:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(557,2,5,13,'2025-12-11 00:00:00','09:30:00','pendente',45.00,NULL,'2025-12-10 05:18:30'),(558,3,5,14,'2025-12-11 00:00:00','10:00:00','pendente',55.00,NULL,'2025-12-10 05:18:30'),(559,4,5,15,'2025-12-11 00:00:00','10:30:00','pendente',40.00,NULL,'2025-12-10 05:18:30'),(560,1,5,16,'2025-12-11 00:00:00','11:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(561,2,5,17,'2025-12-11 00:00:00','11:30:00','pendente',80.00,NULL,'2025-12-10 05:18:30'),(562,3,5,18,'2025-12-11 00:00:00','12:00:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(563,4,5,19,'2025-12-11 00:00:00','12:30:00','pendente',90.00,NULL,'2025-12-10 05:18:30'),(564,1,5,20,'2025-12-11 00:00:00','13:00:00','pendente',30.00,NULL,'2025-12-10 05:18:30'),(565,2,5,21,'2025-12-11 00:00:00','13:30:00','pendente',35.00,NULL,'2025-12-10 05:18:30'),(566,3,5,9,'2025-12-11 00:00:00','14:00:00','pendente',60.00,NULL,'2025-12-10 05:18:30'),(567,4,5,10,'2025-12-11 00:00:00','14:30:00','pendente',70.00,NULL,'2025-12-10 05:18:30'),(568,1,5,11,'2025-12-11 00:00:00','15:00:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(569,2,5,12,'2025-12-11 00:00:00','15:30:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(570,3,5,13,'2025-12-11 00:00:00','16:00:00','pendente',45.00,NULL,'2025-12-10 05:18:30'),(571,4,5,14,'2025-12-11 00:00:00','16:30:00','pendente',55.00,NULL,'2025-12-10 05:18:30'),(572,1,5,15,'2025-12-11 00:00:00','17:00:00','pendente',40.00,NULL,'2025-12-10 05:18:30'),(573,2,5,16,'2025-12-11 00:00:00','17:30:00','pendente',50.00,NULL,'2025-12-10 05:18:30'),(574,1,5,10,'2025-12-10 00:00:00','08:30:00','pendente',70.00,NULL,'2025-12-10 05:20:39'),(575,2,5,11,'2025-12-10 00:00:00','08:30:00','pendente',50.00,NULL,'2025-12-10 05:20:39'),(576,3,5,12,'2025-12-10 00:00:00','12:00:00','pendente',50.00,NULL,'2025-12-10 05:20:39'),(577,4,5,13,'2025-12-10 00:00:00','12:30:00','pendente',45.00,NULL,'2025-12-10 05:20:39'),(578,1,5,14,'2025-12-10 00:00:00','13:00:00','pendente',55.00,NULL,'2025-12-10 05:20:39'),(579,2,5,15,'2025-12-10 00:00:00','13:30:00','pendente',40.00,NULL,'2025-12-10 05:20:39'),(580,3,5,16,'2025-12-10 00:00:00','14:00:00','pendente',50.00,NULL,'2025-12-10 05:20:39'),(581,4,5,17,'2025-12-10 00:00:00','14:30:00','pendente',80.00,NULL,'2025-12-10 05:20:39'),(582,1,5,18,'2025-12-10 00:00:00','15:00:00','pendente',70.00,NULL,'2025-12-10 05:20:39'),(583,2,5,19,'2025-12-10 00:00:00','15:30:00','pendente',90.00,NULL,'2025-12-10 05:20:39'),(584,3,5,20,'2025-12-10 00:00:00','16:00:00','pendente',30.00,NULL,'2025-12-10 05:20:39'),(585,4,5,21,'2025-12-10 00:00:00','16:30:00','pendente',35.00,NULL,'2025-12-10 05:20:39'),(586,1,5,9,'2025-12-10 00:00:00','17:00:00','pendente',60.00,NULL,'2025-12-10 05:20:39'),(587,2,5,10,'2025-12-10 00:00:00','17:30:00','pendente',70.00,NULL,'2025-12-10 05:20:39'),(588,15,5,12,'2025-12-13 00:00:00','10:00:00','cancelado',20.00,NULL,'2025-12-10 21:58:12'),(589,15,5,17,'2025-12-13 00:00:00','10:00:00','pendente',30.00,NULL,'2025-12-10 21:58:12'),(590,1,28,12,'2026-03-12 00:00:00','16:00:00','pendente',40.00,NULL,'2026-03-04 01:30:18'),(591,1,29,12,'2026-03-13 00:00:00','09:00:00','pendente',40.00,NULL,'2026-03-04 01:53:22'),(592,1,29,11,'2026-03-13 00:00:00','09:00:00','pendente',25.00,NULL,'2026-03-04 01:53:22'),(593,1,29,20,'2026-03-13 00:00:00','09:00:00','pendente',20.00,NULL,'2026-03-04 01:53:22'),(594,1,29,18,'2026-03-13 00:00:00','09:00:00','pendente',55.00,NULL,'2026-03-04 01:53:22'),(595,1,29,9,'2026-03-23 00:00:00','13:00:00','pendente',35.00,NULL,'2026-03-04 01:56:38'),(596,1,28,12,'2026-03-09 00:00:00','09:00:00','pendente',40.00,NULL,'2026-03-04 02:07:00'),(597,1,28,12,'2026-03-09 00:00:00','10:00:00','pendente',40.00,NULL,'2026-03-04 02:08:40');
/*!40000 ALTER TABLE `agendamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbearia`
--

DROP TABLE IF EXISTS `barbearia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbearia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `local` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbearia`
--

LOCK TABLES `barbearia` WRITE;
/*!40000 ALTER TABLE `barbearia` DISABLE KEYS */;
INSERT INTO `barbearia` VALUES (1,'Rua-H. 158, Qd. 320, Lt.5','62992380175','bielteste@gmail.com','Aparecida de Goiania'),(2,'123 Anywhere St., Any City','+123-456-7890','hello@reallygreatsite.com','www.reallygreatsite.com');
/*!40000 ALTER TABLE `barbearia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbeiro`
--

DROP TABLE IF EXISTS `barbeiro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbeiro` (
  `idbarbeiro` int NOT NULL AUTO_INCREMENT,
  `nome_barbeiro` varchar(100) DEFAULT NULL,
  `descricao` longtext,
  `foto` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idbarbeiro`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbeiro`
--

LOCK TABLES `barbeiro` WRITE;
/*!40000 ALTER TABLE `barbeiro` DISABLE KEYS */;
INSERT INTO `barbeiro` VALUES (5,'alan','Profissional em cortes','2024-01-24.png','alangabryel17092002@gmail.com','66666'),(28,'João Sliva','Profissional em cortes','image (12).png','jon@gmail.com','barber123'),(29,'Carlos Souza','5 anos de exeperiencia','img barber.jpg','carlos@gmail.com','barber123'),(30,'alan ','vadiar','Captura de Tela (1).png','alan@email.com','agl1308');
/*!40000 ALTER TABLE `barbeiro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Hardware'),(2,'Periféricos'),(3,'Software'),(4,'Cabelo'),(5,'Barba'),(6,'Skincare'),(7,'Acessórios');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Ana Silva','SP'),(2,'Bruno Costa','RJ'),(3,'Carla Dias','MG'),(4,'Daniel Farias','SP'),(5,'Eduarda Lima','BA');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `duracao_plano`
--

DROP TABLE IF EXISTS `duracao_plano`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `duracao_plano` (
  `idduracao_plano` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idduracao_plano`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duracao_plano`
--

LOCK TABLES `duracao_plano` WRITE;
/*!40000 ALTER TABLE `duracao_plano` DISABLE KEYS */;
/*!40000 ALTER TABLE `duracao_plano` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itens_pedido`
--

DROP TABLE IF EXISTS `itens_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `itens_pedido` (
  `pedido_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` int DEFAULT NULL,
  PRIMARY KEY (`pedido_id`,`produto_id`),
  KEY `itens_pedido_ibfk_2` (`produto_id`),
  CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itens_pedido`
--

LOCK TABLES `itens_pedido` WRITE;
/*!40000 ALTER TABLE `itens_pedido` DISABLE KEYS */;
INSERT INTO `itens_pedido` VALUES (101,3,2),(101,5,1),(102,2,1),(103,7,1),(104,8,1),(104,9,2),(105,1,1),(105,4,1),(106,10,1),(107,6,1),(108,5,1),(109,3,2),(110,2,1),(114,1,1),(115,1,1),(116,3,1),(117,1,1),(118,1,1);
/*!40000 ALTER TABLE `itens_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamento`
--

DROP TABLE IF EXISTS `pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagamento` (
  `idpagamento` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `tipo_pagamento` enum('plano','agendamento') DEFAULT 'plano',
  `idplano_ativo` int DEFAULT NULL,
  `idagendamento` int DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `metodo_pagamento` varchar(50) DEFAULT NULL,
  `status` enum('pendente','pago','falhou') DEFAULT 'pendente',
  `data_pagamento` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpagamento`),
  KEY `iduser` (`iduser`),
  KEY `idplano_ativo` (`idplano_ativo`),
  KEY `idagendamento` (`idagendamento`),
  CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`),
  CONSTRAINT `pagamento_ibfk_2` FOREIGN KEY (`idplano_ativo`) REFERENCES `plano_ativo` (`idplano_ativo`),
  CONSTRAINT `pagamento_ibfk_3` FOREIGN KEY (`idagendamento`) REFERENCES `agendamento` (`idagendamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamento`
--

LOCK TABLES `pagamento` WRITE;
/*!40000 ALTER TABLE `pagamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `data_pedido` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (101,1,'2024-01-10','pendente'),(102,2,'2024-01-12','pendente'),(103,1,'2024-02-15','pendente'),(104,3,'2024-02-20','pendente'),(105,4,'2024-03-05','pendente'),(106,5,'2024-03-10','pendente'),(107,2,'2024-03-18','pendente'),(108,1,'2024-04-01','pendente'),(109,3,'2024-04-05','pendente'),(110,4,'2024-04-22','pendente'),(111,1,'2026-04-22','pendente'),(112,1,'2026-04-22','pendente'),(113,1,'2026-04-22','pendente'),(114,1,'2026-04-22','pendente'),(115,1,'2026-04-22','pendente'),(116,1,'2026-04-22','pendente'),(117,1,'2026-04-22','pendente'),(118,1,'2026-04-22','pendente');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plano_ativo`
--

DROP TABLE IF EXISTS `plano_ativo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plano_ativo` (
  `idplano_ativo` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idplano` int DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `status` enum('ativo','expirado','cancelado') DEFAULT 'ativo',
  PRIMARY KEY (`idplano_ativo`),
  KEY `iduser` (`iduser`),
  KEY `idplano` (`idplano`),
  CONSTRAINT `plano_ativo_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`),
  CONSTRAINT `plano_ativo_ibfk_2` FOREIGN KEY (`idplano`) REFERENCES `planos` (`idplanos`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plano_ativo`
--

LOCK TABLES `plano_ativo` WRITE;
/*!40000 ALTER TABLE `plano_ativo` DISABLE KEYS */;
INSERT INTO `plano_ativo` VALUES (2,15,4,'2025-11-10','2025-12-10','ativo');
/*!40000 ALTER TABLE `plano_ativo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planos`
--

DROP TABLE IF EXISTS `planos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `planos` (
  `idplanos` int NOT NULL AUTO_INCREMENT,
  `nome_plano` varchar(100) DEFAULT NULL,
  `duracao` varchar(50) DEFAULT NULL,
  `descricao_plano` longtext,
  `preco_plano` decimal(10,2) DEFAULT NULL,
  `desconto` int DEFAULT NULL,
  PRIMARY KEY (`idplanos`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planos`
--

LOCK TABLES `planos` WRITE;
/*!40000 ALTER TABLE `planos` DISABLE KEYS */;
INSERT INTO `planos` VALUES (4,'BASIC','Mensal','Corte o cabelo quantas vezes quiser!\nPresentes exclusivos.\nDesconto em produtos e serviços.\nDesconto consumo barbearia (cerveja, café, água e etc)',39.90,50),(5,'PLUS','Mensal','Faça a barba quantas vezes quiser!\nPresentes exclusivos.\nDesconto em produtos e serviços.\nDesconto consumo barbearia (cerveja, café, água e etc)',69.90,75),(6,'PREMIUM','Mensal','Faça a barba quantas vezes quiser!\nPresentes exclusivos.\nDesconto em produtos e serviços.\nDesconto consumo barbearia (cerveja, café, água e etc)',109.90,100);
/*!40000 ALTER TABLE `planos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (1,'Processador Core i9',200.00,1,NULL,NULL),(2,'Placa de Vídeo RTX 4080',7500.00,1,NULL,NULL),(3,'Memória RAM 16GB DDR5',650.00,1,NULL,NULL),(4,'SSD NVMe 1TB',800.00,1,NULL,NULL),(5,'Mouse Gamer Pro',450.00,2,NULL,NULL),(6,'Teclado Mecânico RGB',580.00,2,NULL,NULL),(7,'Monitor 4K 27\"',2800.00,2,NULL,NULL),(8,'Licença Windows 11 Pro',950.00,3,NULL,NULL),(9,'Licença Antivírus Premium',150.00,3,NULL,NULL),(10,'Suite de Edição de Vídeo',1200.00,3,NULL,NULL),(11,'Pomada',35.00,NULL,NULL,NULL),(12,'Pomada',40.00,NULL,NULL,NULL),(13,'alan',10.00,NULL,NULL,NULL),(14,'Pomada Matte Premium',89.90,4,'https://images.unsplash.com/photo-1621607512214-68297480165e?w=120&q=70','Fixação forte com acabamento natural.'),(15,'Óleo para Barba',69.90,5,'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=120&q=70','Hidrata e amacia os fios.'),(16,'Balm Modelador',59.90,5,'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=120&q=70','Modela e nutre a barba.'),(17,'Shampoo Antiqueda',54.90,4,'https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=120&q=70','Fortalece os fios.'),(18,'Hidratante Facial',79.90,6,'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=120&q=70','Hidratação profunda.'),(19,'Kit Pentes Premium',129.90,7,'https://images.unsplash.com/photo-1621607512022-6aecc4fed814?w=120&q=70','3 pentes de acetato.'),(22,'Pente',20.00,4,'uploads/69f29c274f502_Captura de Tela (1).png','Pente'),(23,'Notebook',20.00,1,'uploads/69f2a7c148652_Captura de Tela (1).png','new'),(24,'HDMI',1.00,1,'uploads/69f2a7f7c1803_Captura de Tela (1).png','sei la'),(25,'alan',20.00,1,'uploads/69f2aa06982c9_Captura de Tela (1).png','jihj8rg');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servico`
--

DROP TABLE IF EXISTS `servico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servico` (
  `idservico` int NOT NULL AUTO_INCREMENT,
  `nome_servico` varchar(100) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `descricao` longtext,
  `tipo` varchar(50) NOT NULL,
  `duracao` int DEFAULT NULL,
  PRIMARY KEY (`idservico`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servico`
--

LOCK TABLES `servico` WRITE;
/*!40000 ALTER TABLE `servico` DISABLE KEYS */;
INSERT INTO `servico` VALUES (9,'BARBA COMPLETA',35.00,'Barba completa com navalha e acabamento','barba',30),(10,'BARBA DESENHADA',30.00,'Barba desenhada com precisão e acabamento','barba',25),(11,'SÓ NAVALHA',25.00,'Apenas limpeza com navalha','barba',20),(12,'CORTE CLÁSSICO',40.00,'Corte de cabelo tradicional e elegante','corte',30),(13,'CORTE INFANTIL',40.00,'Corte infantil com todo o cuidado','corte',30),(14,'CORTE DEGRADÊ',30.00,'Corte com degradê moderno','corte',30),(15,'CORTE AMERICANO',60.00,'Estilo americano com acabamento premium','corte',40),(16,'CORTE LOW FADE',65.00,'Corte moderno com low fade','corte',40),(17,'CORTE + BARBA',60.00,'Combo de corte de cabelo e barba','combo',50),(18,'CORTE + SOBRANCELHA',55.00,'Combo de corte de cabelo e sobrancelha','combo',45),(19,'COMPLETO (C/B/S)',80.00,'Corte, barba e sobrancelha completo','combo',60),(20,'SOBRANCELHA NAVALHA',20.00,'Sobrancelha feita com navalha','sobrancelha',15),(21,'SOBRANCELHA PINÇA',25.00,'Sobrancelha feita com pinça','sobrancelha',15),(22,'Barba + Corte',45.00,'','Combo',20);
/*!40000 ALTER TABLE `servico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `nome_user` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_user` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tipo` enum('cliente','barbeiro','admin') DEFAULT 'cliente',
  `foto_perfil` text,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `email_user` (`email_user`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Biel','62998888888','bielteste@gmail.com','$2y$10$pe/.3Wtr9eYEgtYZkcfr1..nMDI7BY5LE4RfEVhWMo./3ZM/gI8wu','cliente','img/uploads/perfil_1_68af964f1cfa0.png'),(2,'João Silva','11987654321','joao.silva@email.com','12345678hashed','cliente','foto_joao.png'),(3,'Maria Oliveira','21988887777','maria.oliveira@email.com','senhaSegura123hash','cliente','foto_maria.jpg'),(4,'Carlos Santos','31999998888','carlos.santos@email.com','outraSenha456hash','admin','foto_carlos.png'),(9,'João Barbeiro','11999990001','joao@barbearia.com','123456','barbeiro','joao.jpg'),(10,'Carlos Barbeiro','11999990002','carlos@barbearia.com','123456','barbeiro','carlos.jpg'),(11,'alan','11999990002','alan@barbearia.com','1308','barbeiro','carlos.jpg'),(12,'Joaquin','62933338888','joaca@gmail.com','$2y$10$cJeSXqDcxvzFJQka3Fhupuw7xlZzZwUeYhEa2/S9rzGPb5HVgfyuW','cliente',NULL),(13,'Pedro','62977779999','pedro@gmail.com','$2y$10$oN/YsFJ./ZV9J6XXS6V2W.JMn.Iw7QM78UulvUIBOf7UKV5BwQkbS','cliente',NULL),(14,'Simão','62900001111','Simas@gmail.com','$2y$10$dsMssm/3hlTte73dROdN6ui5dmGLaPx7bM/WpNfCEnd2.VD/2XhXe','cliente',NULL),(15,'alan','62933338888','alangabryel17092002@gmail.com','$2y$10$2aJ7DFx5m3aBIxW0OUcQ9OrXv1.TR7E62vkfAUrmqHB.g8U/f7fJC','cliente','img/uploads/perfil_15_6934b5851bbfb.jpg'),(18,'Pedro','62933338888','alberto@gmail.com','$2y$10$50YRkxN7lLNutg6i9znxbeOtyEbGoq/09m17IFVgG9R4otDa.l9ZW','cliente',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-05 19:30:49
