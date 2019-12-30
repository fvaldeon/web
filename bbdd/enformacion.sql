CREATE DATABASE  IF NOT EXISTS `enformacion` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `enformacion`;
-- MySQL dump 10.16  Distrib 10.1.41-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: enformacion
-- ------------------------------------------------------
-- Server version	10.1.41-MariaDB-0+deb9u1

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
-- Table structure for table `gimnasios`
--

DROP TABLE IF EXISTS `gimnasios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gimnasios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `mixto` tinyint(1) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gimnasios`
--

LOCK TABLES `gimnasios` WRITE;
/*!40000 ALTER TABLE `gimnasios` DISABLE KEYS */;
INSERT INTO `gimnasios` VALUES (1,'calabozo',0,'2010-10-10'),(2,'bulderland',1,'2018-05-05');
/*!40000 ALTER TABLE `gimnasios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taquillas`
--

DROP TABLE IF EXISTS `taquillas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taquillas` (
  `id_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id_taquilla`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taquillas`
--

LOCK TABLES `taquillas` WRITE;
/*!40000 ALTER TABLE `taquillas` DISABLE KEYS */;
INSERT INTO `taquillas` VALUES (1),(2),(3),(4),(5),(6),(7);
/*!40000 ALTER TABLE `taquillas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_gimnasio`
--

DROP TABLE IF EXISTS `usuario_gimnasio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_gimnasio` (
  `id_usuario` int(11) NOT NULL DEFAULT '0',
  `id_gimnasio` int(11) NOT NULL DEFAULT '0',
  `fecha_matriculacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`,`id_gimnasio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_gimnasio`
--

LOCK TABLES `usuario_gimnasio` WRITE;
/*!40000 ALTER TABLE `usuario_gimnasio` DISABLE KEYS */;
INSERT INTO `usuario_gimnasio` VALUES (1,1,'2019-12-31 13:13:13'),(2,1,'2019-12-30 13:02:30'),(2,2,'2019-12-30 13:02:30');
/*!40000 ALTER TABLE `usuario_gimnasio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nif` varchar(15) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `telefono` varchar(40) DEFAULT NULL,
  `id_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_taquilla` (`id_taquilla`),
  UNIQUE KEY `nif` (`nif`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (4,'1234R','Juan','Perez','23452345',2),(5,'4567T','Laura','Garcia','2452354',4),(6,'753567T','Pablo','Lopez','2436347',1);
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

-- Dump completed on 2019-12-30 14:03:47
