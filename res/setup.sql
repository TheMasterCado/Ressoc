-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: info10.cegepthetford.ca    Database: alecado
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
-- Table structure for table `publication`
--
use alecado;

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication` (
  `pk_publication` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texte` varchar(21844) NOT NULL,
  `fk_publication` int(10) unsigned DEFAULT NULL,
  `fk_type_publication` int(10) unsigned NOT NULL,
  `fk_utilisateur` int(10) unsigned NOT NULL,
  `fk_specialite` int(10) unsigned DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_publication`),
  KEY `fk_publication` (`fk_publication`),
  KEY `fk_type_publication` (`fk_type_publication`),
  KEY `fk_utilisateur` (`fk_utilisateur`),
  KEY `fk_specialite` (`fk_specialite`),
  CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`fk_publication`) REFERENCES `publication` (`pk_publication`) ON DELETE CASCADE,
  CONSTRAINT `publication_ibfk_2` FOREIGN KEY (`fk_type_publication`) REFERENCES `type_publication` (`pk_type_publication`),
  CONSTRAINT `publication_ibfk_3` FOREIGN KEY (`fk_utilisateur`) REFERENCES `utilisateur` (`pk_utilisateur`) ON DELETE CASCADE,
  CONSTRAINT `publication_ibfk_4` FOREIGN KEY (`fk_specialite`) REFERENCES `specialite` (`pk_specialite`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`alecado`@`%`*/ /*!50003 TRIGGER trigger_name
AFTER INSERT
   ON publication FOR EACH ROW

BEGIN
	INSERT INTO vote (vote.fk_publication, vote.fk_utilisateur, vote.valeur)
    VALUES (NEW.pk_publication, NEW.fk_utilisateur, 1);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `specialite`
--

DROP TABLE IF EXISTS `specialite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialite` (
  `pk_specialite` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  PRIMARY KEY (`pk_specialite`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `type_publication`
--

DROP TABLE IF EXISTS `type_publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_publication` (
  `pk_type_publication` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`pk_type_publication`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_publication`
--

LOCK TABLES `type_publication` WRITE;
/*!40000 ALTER TABLE `type_publication` DISABLE KEYS */;
INSERT INTO `type_publication` VALUES (1,'Question'),(2,'Texte'),(3,'BonneReponse'),(4,'QuestionRepondue');
/*!40000 ALTER TABLE `type_publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `pk_utilisateur` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `nb_session` int(11) NOT NULL,
  `loginID` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `image` varchar(300) NOT NULL,
  `fk_specialite` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`pk_utilisateur`),
  UNIQUE KEY `loginID_UNIQUE` (`loginID`),
  KEY `fk_specialite` (`fk_specialite`),
  CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`fk_specialite`) REFERENCES `specialite` (`pk_specialite`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote` (
  `fk_publication` int(10) unsigned NOT NULL,
  `fk_utilisateur` int(10) unsigned NOT NULL,
  `valeur` int(11) NOT NULL CHECK (valeur>=-1 AND valeur<=1),
  PRIMARY KEY (`fk_publication`,`fk_utilisateur`),
  KEY `fk_utilisateur` (`fk_utilisateur`),
  CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`fk_publication`) REFERENCES `publication` (`pk_publication`) ON DELETE CASCADE,
  CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`fk_utilisateur`) REFERENCES `utilisateur` (`pk_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Dumping events for database 'alecado'
--

--
-- Dumping routines for database 'alecado'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-11 13:13:25
