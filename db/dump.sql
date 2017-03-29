-- MySQL dump 10.13  Distrib 5.7.16, for Linux (x86_64)
--
-- Host: localhost    Database: board
-- ------------------------------------------------------
-- Server version	5.7.16-0ubuntu0.16.04.1

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
-- Table structure for table `boardroom`
--

DROP TABLE IF EXISTS `boardroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boardroom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boardroom`
--

LOCK TABLES `boardroom` WRITE;
/*!40000 ALTER TABLE `boardroom` DISABLE KEYS */;
INSERT INTO `boardroom` VALUES (1,'Boardroom 1','2017-03-28 15:32:29','2017-03-28 15:32:29'),(2,'Boardroom 2','2017-03-28 17:11:41','2017-03-28 17:11:41'),(3,'Boardroom 3','2017-03-28 17:11:50','2017-03-28 17:11:50');
/*!40000 ALTER TABLE `boardroom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `access` int(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (6,'Abbey Road','email@example.com','$2y$10$z5p6mD0yM6CM5kOQPgiVJ.hfpJUBh9ovVEZwDVIKgmbVrzc3gJYxu',3,'2017-03-26 16:40:40','2017-03-29 15:05:17'),(7,'Enema of the State','email2@example.com','$2y$10$zR3YugblDFKiEDOXLAeC7eAllwXcJAD55BcIS1ZrYYD03Ph/z5YSu',-1,'2017-03-26 16:59:19','2017-03-29 15:05:10'),(8,'Sound & Color','email3@example.com','$2y$10$fWwqUCgbV6iAIo3n8CFSX.OEka6oMhHep.q5.wYyCVcMSOMn8KCsq',-1,'2017-03-29 15:04:44','2017-03-29 15:04:44');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recurrent_appointment`
--

DROP TABLE IF EXISTS `recurrent_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recurrent_appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `day_start` date NOT NULL,
  `day_end` date NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `recurrent_appointment_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `employee` (`id`),
  CONSTRAINT `recurrent_appointment_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `boardroom` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recurrent_appointment`
--

LOCK TABLES `recurrent_appointment` WRITE;
/*!40000 ALTER TABLE `recurrent_appointment` DISABLE KEYS */;
INSERT INTO `recurrent_appointment` VALUES (1,6,1,'bi-week','12:00:00','14:00:00','2017-03-02','2017-04-30','','2017-03-28 19:20:24','2017-03-28 19:20:24'),(2,6,2,'week','12:00:00','13:30:00','2017-03-01','2017-05-01','','2017-03-29 14:06:04','2017-03-29 14:06:04');
/*!40000 ALTER TABLE `recurrent_appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recurrent_except`
--

DROP TABLE IF EXISTS `recurrent_except`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recurrent_except` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  `for_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `for_id` (`for_id`),
  CONSTRAINT `recurrent_except_ibfk_1` FOREIGN KEY (`for_id`) REFERENCES `recurrent_appointment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recurrent_except`
--

LOCK TABLES `recurrent_except` WRITE;
/*!40000 ALTER TABLE `recurrent_except` DISABLE KEYS */;
INSERT INTO `recurrent_except` VALUES (1,'2017-03-16',1,'2017-03-29 13:19:13','2017-03-29 13:19:13'),(2,'2017-03-15',2,'2017-03-29 14:08:05','2017-03-29 14:08:05');
/*!40000 ALTER TABLE `recurrent_except` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simple_appointment`
--

DROP TABLE IF EXISTS `simple_appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simple_appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `day` date NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `simple_appointment_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `employee` (`id`),
  CONSTRAINT `simple_appointment_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `boardroom` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simple_appointment`
--

LOCK TABLES `simple_appointment` WRITE;
/*!40000 ALTER TABLE `simple_appointment` DISABLE KEYS */;
INSERT INTO `simple_appointment` VALUES (2,6,2,'12:00:00','14:30:00','2017-03-15','','2017-03-29 12:53:35','2017-03-29 14:08:05'),(3,6,1,'11:00:00','12:00:00','2017-03-28','','2017-03-29 14:13:26','2017-03-29 14:13:26');
/*!40000 ALTER TABLE `simple_appointment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-29 22:23:51
