-- MariaDB dump 10.19  Distrib 10.6.17-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: fluensys
-- ------------------------------------------------------
-- Server version	10.6.17-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `information_system_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `default_contact_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7D3656A46E192A27` (`information_system_id`),
  KEY `IDX_7D3656A4AF827129` (`default_contact_id`),
  KEY `IDX_7D3656A49033212A` (`tenant_id`),
  CONSTRAINT `FK_7D3656A46E192A27` FOREIGN KEY (`information_system_id`) REFERENCES `information_system` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_7D3656A49033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`),
  CONSTRAINT `FK_7D3656A4AF827129` FOREIGN KEY (`default_contact_id`) REFERENCES `contact` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (4,4,'Solaris','Spécialisée dans l’énergie photovoltaïque, Solaris accompagne ses clients agriculteurs, entreprises du secteur tertiaire, industriels, bureaux d’études et collectivités dans la conception, la réalisation et le suivi de leurs projets solaires.',10,1),(6,NULL,'Compte de TEST','Utiliser pour faire pleins de tests moches',NULL,2);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calculated_variable`
--

DROP TABLE IF EXISTS `calculated_variable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calculated_variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capture_element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `technical_name` varchar(255) NOT NULL,
  `expression` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_76EE2621DE152EAB` (`capture_element_id`),
  CONSTRAINT `FK_76EE2621DE152EAB` FOREIGN KEY (`capture_element_id`) REFERENCES `capture_element` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calculated_variable`
--

LOCK TABLES `calculated_variable` WRITE;
/*!40000 ALTER TABLE `calculated_variable` DISABLE KEYS */;
/*!40000 ALTER TABLE `calculated_variable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `capture`
--

DROP TABLE IF EXISTS `capture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `capture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `responsible_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `owner_project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8BFEA6E5A9F87BD` (`title_id`),
  KEY `IDX_8BFEA6E59B6B5FBA` (`account_id`),
  KEY `IDX_8BFEA6E5602AD315` (`responsible_id`),
  KEY `IDX_8BFEA6E59033212A` (`tenant_id`),
  KEY `IDX_8BFEA6E5440786D9` (`owner_project_id`),
  CONSTRAINT `FK_8BFEA6E5440786D9` FOREIGN KEY (`owner_project_id`) REFERENCES `project` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_8BFEA6E5602AD315` FOREIGN KEY (`responsible_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8BFEA6E59033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`),
  CONSTRAINT `FK_8BFEA6E59B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  CONSTRAINT `FK_8BFEA6E5A9F87BD` FOREIGN KEY (`title_id`) REFERENCES `title` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capture`
--

LOCK TABLES `capture` WRITE;
/*!40000 ALTER TABLE `capture` DISABLE KEYS */;
INSERT INTO `capture` VALUES (17,52,NULL,'Compte rendu','Support de comptes rendus',NULL,1,'template',NULL),(139,336,NULL,'COPIL / COPROJ','Support de COPIL / COPROJ',NULL,1,'template',NULL),(140,345,NULL,'Spécification fonctionnelle','Spécification fonctionnelle',NULL,1,'template',NULL),(141,344,NULL,'Chiffrage','Chiffrage',NULL,1,'template',NULL),(142,346,4,'Interface MS Dynamics <-> Atrium','Spécification fonctionnelle',20,1,'validated',NULL),(146,366,4,'SPEC : Interface SAP ByDesign <-> Hubspot','Spécification fonctionnelle',20,1,'validated',27),(147,373,4,'Chiffrage : Interface SAP ByDesign <-> Hubspot','Chiffrage',20,1,'pending',27),(148,375,4,'Spécification fonctionnelle','Spécification fonctionnelle',22,1,'ready',28),(149,382,4,'Chiffrage','Chiffrage',22,1,'ready',28),(150,384,4,'Spécification fonctionnelle','Spécification fonctionnelle',22,1,'ready',NULL),(151,NULL,NULL,'dsgsdg','sgsgsgsg',NULL,1,'template',NULL);
/*!40000 ALTER TABLE `capture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `capture_condition`
--

DROP TABLE IF EXISTS `capture_condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `capture_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_element_id` int(11) NOT NULL,
  `target_element_id` int(11) NOT NULL,
  `source_field_id` int(11) NOT NULL,
  `capture_id` int(11) NOT NULL,
  `expected_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D6CFAC7D63AB5B00` (`source_element_id`),
  KEY `IDX_D6CFAC7D2DF3F2B5` (`target_element_id`),
  KEY `IDX_D6CFAC7D7173162` (`source_field_id`),
  KEY `IDX_D6CFAC7D6B301384` (`capture_id`),
  CONSTRAINT `FK_D6CFAC7D2DF3F2B5` FOREIGN KEY (`target_element_id`) REFERENCES `capture_element` (`id`),
  CONSTRAINT `FK_D6CFAC7D63AB5B00` FOREIGN KEY (`source_element_id`) REFERENCES `capture_element` (`id`),
  CONSTRAINT `FK_D6CFAC7D6B301384` FOREIGN KEY (`capture_id`) REFERENCES `capture` (`id`),
  CONSTRAINT `FK_D6CFAC7D7173162` FOREIGN KEY (`source_field_id`) REFERENCES `field` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capture_condition`
--

LOCK TABLES `capture_condition` WRITE;
/*!40000 ALTER TABLE `capture_condition` DISABLE KEYS */;
/*!40000 ALTER TABLE `capture_condition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `capture_element`
--

DROP TABLE IF EXISTS `capture_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `capture_element` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `validator_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `capture_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_33ED8BFF579F4768` (`chapter_id`),
  KEY `IDX_33ED8BFFB0644AEC` (`validator_id`),
  KEY `IDX_33ED8BFF6B301384` (`capture_id`),
  KEY `IDX_33ED8BFF7A19A357` (`contributor_id`),
  KEY `IDX_33ED8BFF9033212A` (`tenant_id`),
  CONSTRAINT `FK_33ED8BFF579F4768` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id`),
  CONSTRAINT `FK_33ED8BFF6B301384` FOREIGN KEY (`capture_id`) REFERENCES `capture` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_33ED8BFF7A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `participant_role` (`id`),
  CONSTRAINT `FK_33ED8BFF9033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`),
  CONSTRAINT `FK_33ED8BFFB0644AEC` FOREIGN KEY (`validator_id`) REFERENCES `participant_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=362 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capture_element`
--

LOCK TABLES `capture_element` WRITE;
/*!40000 ALTER TABLE `capture_element` DISABLE KEYS */;
INSERT INTO `capture_element` VALUES (33,NULL,33,17,'Introduction','Introduction',1,0,'flex',NULL,'template',1),(34,NULL,34,17,'Points abordés / résumé','Points abordés / résumé',1,1,'flex',NULL,'template',1),(35,NULL,35,17,'Etapes suivantes','Etapes suivantes',1,2,'flex',NULL,'template',1),(309,39,240,139,'Compte rendu (COPIL / COPROJ)','Compte rendu de COPIL (ou COPROJ)',1,0,'flex',NULL,'template',1),(310,NULL,241,140,'Contexte et objectifs','Contexte et objectifs',1,0,'flex',NULL,'template',1),(311,NULL,242,140,'Périmètre','Périmètre',1,1,'flex',NULL,'template',1),(312,NULL,243,140,'Acteurs / Rôles','Acteurs / Rôles',1,2,'flex',NULL,'template',1),(313,NULL,NULL,140,'Besoins fonctionnels','Besoins fonctionnels',1,3,'flex',NULL,'template',1),(314,NULL,244,140,'Règles de gestion','Règles de gestion',1,4,'flex',NULL,'template',1),(315,NULL,245,140,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,'flex',NULL,'template',1),(316,NULL,246,140,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,'flex',NULL,'template',1),(318,39,247,141,'Chiffrage','Chiffrage',1,0,'flex',52,'template',1),(319,NULL,248,142,'Contexte et objectifs','Contexte et objectifs',1,0,'flex',NULL,'validated',1),(320,NULL,249,142,'Périmètre','Périmètre',1,1,'flex',NULL,'validated',1),(321,NULL,250,142,'Acteurs / Rôles','Acteurs / Rôles',1,2,'flex',NULL,'validated',1),(322,NULL,NULL,142,'Besoins fonctionnels','Besoins fonctionnels',1,3,'flex',NULL,'validated',1),(323,NULL,251,142,'Règles de gestion','Règles de gestion',1,4,'flex',NULL,'validated',1),(324,NULL,252,142,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,'flex',NULL,'validated',1),(325,NULL,253,142,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,'flex',NULL,'validated',1),(337,NULL,264,146,'Contexte et objectifs','Contexte et objectifs',1,0,'flex',NULL,'validated',1),(338,NULL,265,146,'Périmètre','Périmètre',1,1,'flex',NULL,'validated',1),(339,NULL,266,146,'Acteurs / Rôles','Acteurs / Rôles',1,2,'flex',NULL,'validated',1),(340,NULL,NULL,146,'Besoins fonctionnels','Besoins fonctionnels',1,3,'flex',NULL,'validated',1),(341,NULL,267,146,'Règles de gestion','Règles de gestion',1,4,'flex',NULL,'validated',1),(342,NULL,268,146,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,'flex',NULL,'validated',1),(343,NULL,269,146,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,'flex',NULL,'validated',1),(344,39,270,147,'Chiffrage','Chiffrage',1,0,'flex',52,'pending',1),(345,NULL,271,148,'Contexte et objectifs','Contexte et objectifs',1,0,'flex',NULL,'ready',1),(346,NULL,272,148,'Périmètre','Périmètre',1,1,'flex',NULL,'ready',1),(347,NULL,273,148,'Acteurs / Rôles','Acteurs / Rôles',1,2,'flex',NULL,'ready',1),(348,NULL,NULL,148,'Besoins fonctionnels','Besoins fonctionnels',1,3,'flex',NULL,'ready',1),(349,NULL,274,148,'Règles de gestion','Règles de gestion',1,4,'flex',NULL,'ready',1),(350,NULL,275,148,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,'flex',NULL,'ready',1),(351,NULL,276,148,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,'flex',NULL,'ready',1),(352,39,277,149,'Chiffrage','Chiffrage',1,0,'flex',52,'ready',1),(353,NULL,278,150,'Contexte et objectifs','Contexte et objectifs',1,0,'flex',NULL,'validated',1),(354,NULL,279,150,'Périmètre','Périmètre',1,1,'flex',NULL,'validated',1),(355,NULL,280,150,'Acteurs / Rôles','Acteurs / Rôles',1,2,'flex',NULL,'ready',1),(356,NULL,NULL,150,'Besoins fonctionnels','Besoins fonctionnels',1,3,'flex',NULL,'ready',1),(357,NULL,281,150,'Règles de gestion','Règles de gestion',1,4,'flex',NULL,'ready',1),(358,NULL,282,150,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,'flex',NULL,'ready',1),(359,NULL,283,150,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,'flex',NULL,'ready',1),(360,NULL,NULL,151,'dgsdgs','gsgsgs',1,0,'flex',NULL,'template',1);
/*!40000 ALTER TABLE `capture_element` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chapter`
--

DROP TABLE IF EXISTS `chapter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_id` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F981B52EA9F87BD` (`title_id`),
  CONSTRAINT `FK_F981B52EA9F87BD` FOREIGN KEY (`title_id`) REFERENCES `title` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=284 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapter`
--

LOCK TABLES `chapter` WRITE;
/*!40000 ALTER TABLE `chapter` DISABLE KEYS */;
INSERT INTO `chapter` VALUES (33,49,'text'),(34,50,'text'),(35,51,'text'),(240,335,'text'),(241,337,'text'),(242,338,'text'),(243,339,'text'),(244,340,'text'),(245,341,'text'),(246,342,'text'),(247,343,'text'),(248,347,'text'),(249,348,'text'),(250,349,'text'),(251,350,'text'),(252,351,'text'),(253,352,'text'),(264,367,'text'),(265,368,'text'),(266,369,'text'),(267,370,'text'),(268,371,'text'),(269,372,'text'),(270,374,'text'),(271,376,'text'),(272,377,'text'),(273,378,'text'),(274,379,'text'),(275,380,'text'),(276,381,'text'),(277,383,'text'),(278,385,'text'),(279,386,'text'),(280,387,'text'),(281,388,'text'),(282,389,'text'),(283,390,'text');
/*!40000 ALTER TABLE `chapter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist_field`
--

DROP TABLE IF EXISTS `checklist_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist_field` (
  `id` int(11) NOT NULL,
  `choices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`choices`)),
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`value`)),
  `unique_response` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_651DC286BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_field`
--

LOCK TABLES `checklist_field` WRITE;
/*!40000 ALTER TABLE `checklist_field` DISABLE KEYS */;
INSERT INTO `checklist_field` VALUES (633,'[{\"label\":\"COPIL\",\"value\":\"COPIL\"},{\"label\":\"COPROJ\",\"value\":\"COPROJ\"}]',NULL,1);
/*!40000 ALTER TABLE `checklist_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C62E6389B6B5FBA` (`account_id`),
  KEY `IDX_4C62E6389033212A` (`tenant_id`),
  CONSTRAINT `FK_4C62E6389033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`),
  CONSTRAINT `FK_4C62E6389B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (10,4,'j.dupont@gmail.com','jean Dupont','Sponsor',1),(11,4,'e.durand@gmail.com','Elodie Durand','Responsable métier',1);
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_participant_role`
--

DROP TABLE IF EXISTS `contact_participant_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_participant_role` (
  `contact_id` int(11) NOT NULL,
  `participant_role_id` int(11) NOT NULL,
  PRIMARY KEY (`contact_id`,`participant_role_id`),
  KEY `IDX_422792C0E7A1254A` (`contact_id`),
  KEY `IDX_422792C04C0EEDA4` (`participant_role_id`),
  CONSTRAINT `FK_422792C04C0EEDA4` FOREIGN KEY (`participant_role_id`) REFERENCES `participant_role` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_422792C0E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_participant_role`
--

LOCK TABLES `contact_participant_role` WRITE;
/*!40000 ALTER TABLE `contact_participant_role` DISABLE KEYS */;
INSERT INTO `contact_participant_role` VALUES (10,37),(10,38),(11,49),(11,50),(11,53);
/*!40000 ALTER TABLE `contact_participant_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `date_field`
--

DROP TABLE IF EXISTS `date_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `date_field` (
  `id` int(11) NOT NULL,
  `value` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_E105ADD4BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `date_field`
--

LOCK TABLES `date_field` WRITE;
/*!40000 ALTER TABLE `date_field` DISABLE KEYS */;
INSERT INTO `date_field` VALUES (169,NULL),(626,NULL);
/*!40000 ALTER TABLE `date_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `decimal_field`
--

DROP TABLE IF EXISTS `decimal_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `decimal_field` (
  `id` int(11) NOT NULL,
  `value` decimal(14,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_87FDAF1BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `decimal_field`
--

LOCK TABLES `decimal_field` WRITE;
/*!40000 ALTER TABLE `decimal_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `decimal_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20251221124726','2025-12-22 23:39:06',1962),('DoctrineMigrations\\Version20251222175506','2025-12-22 23:39:08',129),('DoctrineMigrations\\Version20251222233019','2025-12-22 23:39:08',325),('DoctrineMigrations\\Version20251222234341','2025-12-22 23:43:48',45),('DoctrineMigrations\\Version20251223110823','2025-12-23 11:08:30',139),('DoctrineMigrations\\Version20251223132424','2025-12-23 13:24:30',24),('DoctrineMigrations\\Version20251223135827','2025-12-23 13:58:33',134),('DoctrineMigrations\\Version20251224173554','2025-12-24 17:36:09',250),('DoctrineMigrations\\Version20251224204633','2025-12-24 20:46:36',200),('DoctrineMigrations\\Version20251224214524','2025-12-24 21:45:53',302),('DoctrineMigrations\\Version20251225102140','2025-12-25 10:21:51',41),('DoctrineMigrations\\Version20251225155652','2025-12-25 15:56:59',27),('DoctrineMigrations\\Version20251225222045','2025-12-25 22:20:55',452),('DoctrineMigrations\\Version20251228100054',NULL,NULL),('DoctrineMigrations\\Version20251228104045','2025-12-28 10:40:58',122),('DoctrineMigrations\\Version20251228122935',NULL,NULL),('DoctrineMigrations\\Version20251228123730','2025-12-28 12:37:33',101),('DoctrineMigrations\\Version20251228163504',NULL,NULL),('DoctrineMigrations\\Version20251228164051','2025-12-28 16:40:57',113),('DoctrineMigrations\\Version20251228164506',NULL,NULL),('DoctrineMigrations\\Version20251228164634','2025-12-28 16:46:38',98),('DoctrineMigrations\\Version20251228164727',NULL,NULL),('DoctrineMigrations\\Version20251228164820','2025-12-28 16:48:26',95),('DoctrineMigrations\\Version20251228165021',NULL,NULL),('DoctrineMigrations\\Version20251228165132','2025-12-28 16:51:37',92),('DoctrineMigrations\\Version20251228172057','2025-12-28 17:21:23',26),('DoctrineMigrations\\Version20251230111731','2025-12-30 11:17:50',91),('DoctrineMigrations\\Version20251230121029','2025-12-30 12:10:34',123),('DoctrineMigrations\\Version20251230125658','2025-12-30 12:57:02',88),('DoctrineMigrations\\Version20251230153755','2025-12-30 15:37:59',377),('DoctrineMigrations\\Version20260108104748','2026-01-08 10:48:04',358),('DoctrineMigrations\\Version20260108154528','2026-01-08 15:45:32',109),('DoctrineMigrations\\Version20260109112302','2026-01-09 11:23:07',75),('DoctrineMigrations\\Version20260109150519','2026-01-09 15:05:23',117),('DoctrineMigrations\\Version20260111112211','2026-01-11 11:22:15',47),('DoctrineMigrations\\Version20260111120442','2026-01-11 12:04:49',129),('DoctrineMigrations\\Version20260111121511','2026-01-11 12:15:16',124),('DoctrineMigrations\\Version20260112093425','2026-01-12 09:53:50',116),('DoctrineMigrations\\Version20260112101614','2026-01-12 10:16:20',62),('DoctrineMigrations\\Version20260112114713','2026-01-12 11:47:19',133),('DoctrineMigrations\\Version20260114154652','2026-01-14 15:47:02',167);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_field`
--

DROP TABLE IF EXISTS `email_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_field` (
  `id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_C6B804D7BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_field`
--

LOCK TABLES `email_field` WRITE;
/*!40000 ALTER TABLE `email_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field`
--

DROP TABLE IF EXISTS `field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capture_element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `technical_name` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `help` varchar(255) DEFAULT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5BF54558DE152EAB` (`capture_element_id`),
  CONSTRAINT `FK_5BF54558DE152EAB` FOREIGN KEY (`capture_element_id`) REFERENCES `capture_element` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=688 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field`
--

LOCK TABLES `field` WRITE;
/*!40000 ALTER TABLE `field` DISABLE KEYS */;
INSERT INTO `field` VALUES (157,33,'Objet','OBJET',0,'text','Objet (ex. “Atelier #2 – Parcours utilisateur / besoins”)',NULL,1),(159,33,'Ordre du jour','ORDREDUJOUR',1,'textarea','Ordre du jour',NULL,1),(169,33,'Date','DATE',3,'date','Date',NULL,1),(176,33,'Participants','PARTICIPANTS',4,'listable_field','Participants',NULL,1),(626,309,'Date COPIL','DATECOPIL',1,'date','Date',NULL,1),(627,309,'Participants COPIL','PARTICIPANTSCOPIL',2,'listable_field','Participants',NULL,1),(628,309,'Avancement COPIL','AVANCEMENTCOPIL',3,'textarea','Avancement',NULL,1),(629,309,'Décisions COPIL','DECISIONSCOPIL',4,'textarea','Décisions',NULL,1),(630,309,'Actions COPIL','ACTIONSCOPIL',5,'listable_field','Actions / Prochaines étapes',NULL,1),(631,34,'Points abordés','POINTSABORDES',1,'listable_field','Listez les points abordés',NULL,1),(632,35,'Actions','ACTIONS',1,'listable_field','Listez les prochaines étapes / Actions à mener',NULL,1),(633,309,'COPIL / COPROJ','COPILCOPROJ',0,'checklist','COPIL / COPROJ',NULL,1),(634,310,'Contexte et objectifs','CONTEXTEETOBJECTIFS',1,'textarea','Contexte et objectifs',NULL,1),(635,311,'Périmètre','PERIMETRE',1,'textarea','Périmètre',NULL,1),(636,312,'Acteurs / Rôles','ACTEURSROLES',1,'listable_field','Acteurs / Rôles',NULL,1),(637,313,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(638,314,'Règles de gestion','REGLESDEGESTION',1,'listable_field','Règles de gestion',NULL,1),(639,315,'Parcours / cas d’usage','PARCOURSCASDUSAGE',1,'textarea','Parcours / cas d’usage',NULL,1),(640,316,'Definition of Done','DEFINITIONOFDONE',1,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(641,318,'Chiffrage','CHIFFRAGE',0,'listable_field','Listez les tâches et indiquez le nombre de jours',NULL,1),(642,319,'Contexte et objectifs','CONTEXTEETOBJECTIFS',1,'textarea','Contexte et objectifs',NULL,1),(643,320,'Périmètre','PERIMETRE',1,'textarea','Périmètre',NULL,1),(644,321,'Acteurs / Rôles','ACTEURSROLES',1,'listable_field','Acteurs / Rôles',NULL,1),(645,322,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(646,323,'Règles de gestion','REGLESDEGESTION',1,'listable_field','Règles de gestion',NULL,1),(647,324,'Parcours / cas d’usage','PARCOURSCASDUSAGE',1,'textarea','Parcours / cas d’usage',NULL,1),(648,325,'Definition of Done','DEFINITIONOFDONE',1,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(663,337,'Contexte et objectifs','CONTEXTEETOBJECTIFS',1,'textarea','Contexte et objectifs',NULL,1),(664,338,'Périmètre','PERIMETRE',1,'textarea','Périmètre',NULL,1),(665,339,'Acteurs / Rôles','ACTEURSROLES',1,'listable_field','Acteurs / Rôles',NULL,1),(666,340,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(667,341,'Règles de gestion','REGLESDEGESTION',1,'listable_field','Règles de gestion',NULL,1),(668,342,'Parcours / cas d’usage','PARCOURSCASDUSAGE',1,'textarea','Parcours / cas d’usage',NULL,1),(669,343,'Definition of Done','DEFINITIONOFDONE',1,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(670,344,'Chiffrage','CHIFFRAGE',0,'listable_field','Listez les tâches et indiquez le nombre de jours',NULL,1),(671,345,'Contexte et objectifs','CONTEXTEETOBJECTIFS',1,'textarea','Contexte et objectifs',NULL,1),(672,346,'Périmètre','PERIMETRE',1,'textarea','Périmètre',NULL,1),(673,347,'Acteurs / Rôles','ACTEURSROLES',1,'listable_field','Acteurs / Rôles',NULL,1),(674,348,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(675,349,'Règles de gestion','REGLESDEGESTION',1,'listable_field','Règles de gestion',NULL,1),(676,350,'Parcours / cas d’usage','PARCOURSCASDUSAGE',1,'textarea','Parcours / cas d’usage',NULL,1),(677,351,'Definition of Done','DEFINITIONOFDONE',1,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(678,352,'Chiffrage','CHIFFRAGE',0,'listable_field','Listez les tâches et indiquez le nombre de jours',NULL,1),(679,353,'Contexte et objectifs','CONTEXTEETOBJECTIFS',1,'textarea','Contexte et objectifs',NULL,1),(680,354,'Périmètre','PERIMETRE',1,'textarea','Périmètre',NULL,1),(681,355,'Acteurs / Rôles','ACTEURSROLES',1,'listable_field','Acteurs / Rôles',NULL,1),(682,356,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(683,357,'Règles de gestion','REGLESDEGESTION',1,'listable_field','Règles de gestion',NULL,1),(684,358,'Parcours / cas d’usage','PARCOURSCASDUSAGE',1,'textarea','Parcours / cas d’usage',NULL,1),(685,359,'Definition of Done','DEFINITIONOFDONE',1,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(686,360,'sdfsf','SDFSF',1,'textarea','sdfsdfsdfsdfsdf',NULL,1);
/*!40000 ALTER TABLE `field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flex_capture_element`
--

DROP TABLE IF EXISTS `flex_capture_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flex_capture_element` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_CAF7302BF396750` FOREIGN KEY (`id`) REFERENCES `capture_element` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flex_capture_element`
--

LOCK TABLES `flex_capture_element` WRITE;
/*!40000 ALTER TABLE `flex_capture_element` DISABLE KEYS */;
INSERT INTO `flex_capture_element` VALUES (33),(34),(35),(309),(310),(311),(312),(313),(314),(315),(316),(318),(319),(320),(321),(322),(323),(324),(325),(337),(338),(339),(340),(341),(342),(343),(344),(345),(346),(347),(348),(349),(350),(351),(352),(353),(354),(355),(356),(357),(358),(359),(360);
/*!40000 ALTER TABLE `flex_capture_element` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `information_system`
--

DROP TABLE IF EXISTS `information_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `information_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `information_system`
--

LOCK TABLES `information_system` WRITE;
/*!40000 ALTER TABLE `information_system` DISABLE KEYS */;
INSERT INTO `information_system` VALUES (4,'SI');
/*!40000 ALTER TABLE `information_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `integer_field`
--

DROP TABLE IF EXISTS `integer_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integer_field` (
  `id` int(11) NOT NULL,
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_85B54CB0BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `integer_field`
--

LOCK TABLES `integer_field` WRITE;
/*!40000 ALTER TABLE `integer_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `integer_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listable_field`
--

DROP TABLE IF EXISTS `listable_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `listable_field` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_91AFDDBDBF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listable_field`
--

LOCK TABLES `listable_field` WRITE;
/*!40000 ALTER TABLE `listable_field` DISABLE KEYS */;
INSERT INTO `listable_field` VALUES (627),(630),(631),(632),(636),(638),(640),(641),(644),(646),(648),(665),(667),(669),(670),(673),(675),(677),(678),(681),(683),(685);
/*!40000 ALTER TABLE `listable_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listable_field_capture_element`
--

DROP TABLE IF EXISTS `listable_field_capture_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `listable_field_capture_element` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_7F1995CBF396750` FOREIGN KEY (`id`) REFERENCES `capture_element` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listable_field_capture_element`
--

LOCK TABLES `listable_field_capture_element` WRITE;
/*!40000 ALTER TABLE `listable_field_capture_element` DISABLE KEYS */;
/*!40000 ALTER TABLE `listable_field_capture_element` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listable_field_text_item`
--

DROP TABLE IF EXISTS `listable_field_text_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `listable_field_text_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listable_field_id` int(11) NOT NULL,
  `value` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F16BD479D938ADC9` (`listable_field_id`),
  CONSTRAINT `FK_F16BD479D938ADC9` FOREIGN KEY (`listable_field_id`) REFERENCES `listable_field` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listable_field_text_item`
--

LOCK TABLES `listable_field_text_item` WRITE;
/*!40000 ALTER TABLE `listable_field_text_item` DISABLE KEYS */;
INSERT INTO `listable_field_text_item` VALUES (7,644,'Tous les utilisateurs de Microsoft Dynamics'),(8,646,'Un seul type de temps devra être créer dans MS Dynamics pour gérer tous les types d\'absences'),(9,648,'Lecture des temps effectifs dans MS Dynamics'),(10,648,'Création d\'une absence dans MS Dynamics'),(11,648,'Modification d\'une absence dans MS Dynamics'),(12,648,'Suppression d\'une absence dans MS Dynamics'),(13,665,'Tous les utilisateurs de SAP ByDesign'),(14,669,'La base article est synchronisée avec SAP en maître de la donnée'),(15,669,'La base client est synchronisée avec Hubspot en maître'),(16,669,'Les commandes sont créées automatiquement dans SAP ByDesign quand le statut de celle ci passe en \"closed won\" dans Hubspot'),(17,667,'Tous les articles au statut \"Actif\" dans SAP ByDesign doivent être synchronisés dans Hubspot (création / modification) : toutes les 30min'),(18,667,'Tous les clients dans Hubspot qui ont au moins une commande au statut \"Closed won\" doivent être synchronisés dans SAP ByDesign (création / modification): toutes les 30min'),(19,667,'Quand une commande passe au statut \"Closed won\" dans Hubspot, elle est répliquée dans SAP ByDesign après vérification / création du compte client dans SAP ByDesign');
/*!40000 ALTER TABLE `listable_field_text_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participant_assignment`
--

DROP TABLE IF EXISTS `participant_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participant_assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `capture_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `purpose` varchar(20) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_capture_role_purpose` (`capture_id`,`role_id`,`purpose`),
  KEY `IDX_C05A2A4D60322AC` (`role_id`),
  KEY `IDX_C05A2A46B301384` (`capture_id`),
  KEY `IDX_C05A2A4A76ED395` (`user_id`),
  KEY `IDX_C05A2A4E7A1254A` (`contact_id`),
  KEY `IDX_C05A2A4166D1F9C` (`project_id`),
  CONSTRAINT `FK_C05A2A4166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C05A2A46B301384` FOREIGN KEY (`capture_id`) REFERENCES `capture` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C05A2A4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_C05A2A4D60322AC` FOREIGN KEY (`role_id`) REFERENCES `participant_role` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C05A2A4E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant_assignment`
--

LOCK TABLES `participant_assignment` WRITE;
/*!40000 ALTER TABLE `participant_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `participant_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participant_role`
--

DROP TABLE IF EXISTS `participant_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participant_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `internal` tinyint(1) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_83DD98549033212A` (`tenant_id`),
  CONSTRAINT `FK_83DD98549033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participant_role`
--

LOCK TABLES `participant_role` WRITE;
/*!40000 ALTER TABLE `participant_role` DISABLE KEYS */;
INSERT INTO `participant_role` VALUES (37,'Sponsor client','Porte la vision, arbitre les priorités et valide les décisions structurantes (budget, périmètre, arbitrages majeurs).',0,1),(38,'Chef de projet client','Pilote côté client (planning, ressources, arbitrages), coordonne les équipes internes et sécurise la disponibilité des métiers.',0,1),(39,'Chef de projet','Pilote le delivery (méthode, jalons, risques), organise la production et assure l’atteinte des objectifs coût/délai/qualité.',1,1),(40,'Architecte','Définit l’architecture cible, les patterns d’intégration, la sécurité et la cohérence globale de la solution et valide les choix d’implémentation.',1,2),(41,'Consultant Finance','Conçoit les processus finance (GL/AP/AR/FA/Trésorerie), paramètre l’ERP, prépare la reprise et accompagne les tests.',1,2),(42,'Consultant Achats / Approvisionnement','Couvre Procure-to-Pay, référentiels, workflows, contrats, intégrations fournisseurs et scénarios de validation.',1,2),(43,'Consultant Ventes / Distribution','Couvre Order-to-Cash, tarification, conditions, facturation, logistique de sortie et cas d’exception.',1,2),(44,'Consultant Supply Chain / Logistique','Conçoit les flux stock/entrepôt, MRP/planification, inventaires, traçabilité, et règles d’allocation.',1,2),(45,'Consultant Production / Industrie','Couvre nomenclatures/gammes, ordonnancement, suivi atelier, qualité, coûts de revient et pilotage industriel.',1,2),(46,'Consultant RH / Paie','Couvre Core HR, paie, temps/activités, interfaces, conformité et cycles de recette spécifiques.',1,2),(47,'Consultant Data / BI','Définition du modèle analytique, datamarts, KPI, tableaux de bord, qualité des données et alignement métier.',1,2),(48,'Développeur','Réalise les spécifiques, extensions, formulaires, jobs, et corrections techniques sous contrôle d’architecture.',1,1),(49,'Key User Finance','Référent métier finance, valide les processus, anime les tests métiers et la conduite du changement sur son périmètre.',0,2),(50,'Key User Opérations','Référent métier opérations (achats/ventes/logistique/production), valide les scénarios, forme et relaye les besoins terrain.',0,2),(51,'consultant expert','consultant expert',1,2),(52,'Lead Dev','Lead Dev',1,1),(53,'Responsable métier client','Responsable métier / PO client',0,1);
/*!40000 ALTER TABLE `participant_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `account_id` int(11) DEFAULT NULL,
  `responsible_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE9033212A` (`tenant_id`),
  KEY `IDX_2FB3D0EE9B6B5FBA` (`account_id`),
  KEY `IDX_2FB3D0EE602AD315` (`responsible_id`),
  CONSTRAINT `FK_2FB3D0EE602AD315` FOREIGN KEY (`responsible_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2FB3D0EE9033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`),
  CONSTRAINT `FK_2FB3D0EE9B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (25,1,'Projet de développement','Projet de développement','template',NULL,NULL),(27,1,'Interface SAP ByDesign <-> Hubspot','Projet de développement','pending',4,20),(28,1,'Site web','Projet de développement','ready',4,22);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_capture`
--

DROP TABLE IF EXISTS `project_capture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_capture` (
  `project_id` int(11) NOT NULL,
  `capture_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`capture_id`),
  KEY `IDX_1DE0AA80166D1F9C` (`project_id`),
  KEY `IDX_1DE0AA806B301384` (`capture_id`),
  CONSTRAINT `FK_1DE0AA80166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_1DE0AA806B301384` FOREIGN KEY (`capture_id`) REFERENCES `capture` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_capture`
--

LOCK TABLES `project_capture` WRITE;
/*!40000 ALTER TABLE `project_capture` DISABLE KEYS */;
INSERT INTO `project_capture` VALUES (25,140),(25,141),(27,146),(27,147),(28,148),(28,149);
/*!40000 ALTER TABLE `project_capture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_recurring_capture`
--

DROP TABLE IF EXISTS `project_recurring_capture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_recurring_capture` (
  `project_id` int(11) NOT NULL,
  `capture_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`capture_id`),
  KEY `IDX_67DB6F53166D1F9C` (`project_id`),
  KEY `IDX_67DB6F536B301384` (`capture_id`),
  CONSTRAINT `FK_67DB6F53166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_67DB6F536B301384` FOREIGN KEY (`capture_id`) REFERENCES `capture` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_recurring_capture`
--

LOCK TABLES `project_recurring_capture` WRITE;
/*!40000 ALTER TABLE `project_recurring_capture` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_recurring_capture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_recurring_capture_templates`
--

DROP TABLE IF EXISTS `project_recurring_capture_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_recurring_capture_templates` (
  `project_id` int(11) NOT NULL,
  `capture_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`capture_id`),
  KEY `IDX_E62BB78C166D1F9C` (`project_id`),
  KEY `IDX_E62BB78C6B301384` (`capture_id`),
  CONSTRAINT `FK_E62BB78C166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_E62BB78C6B301384` FOREIGN KEY (`capture_id`) REFERENCES `capture` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_recurring_capture_templates`
--

LOCK TABLES `project_recurring_capture_templates` WRITE;
/*!40000 ALTER TABLE `project_recurring_capture_templates` DISABLE KEYS */;
INSERT INTO `project_recurring_capture_templates` VALUES (25,17),(25,139),(27,17),(27,139),(28,17),(28,139);
/*!40000 ALTER TABLE `project_recurring_capture_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_component`
--

DROP TABLE IF EXISTS `system_component`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_component` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `information_system_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4A21A9EA6E192A27` (`information_system_id`),
  CONSTRAINT `FK_4A21A9EA6E192A27` FOREIGN KEY (`information_system_id`) REFERENCES `information_system` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_component`
--

LOCK TABLES `system_component` WRITE;
/*!40000 ALTER TABLE `system_component` DISABLE KEYS */;
INSERT INTO `system_component` VALUES (10,4,'Salesforce','application'),(11,4,'Make','application'),(12,4,'Microsoft Dynamics','application'),(13,4,'Teams','application');
/*!40000 ALTER TABLE `system_component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant`
--

DROP TABLE IF EXISTS `tenant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant`
--

LOCK TABLES `tenant` WRITE;
/*!40000 ALTER TABLE `tenant` DISABLE KEYS */;
INSERT INTO `tenant` VALUES (1,'Idev4U'),(2,'ERP Logic - Intégrateur multi technos');
/*!40000 ALTER TABLE `tenant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_area_field`
--

DROP TABLE IF EXISTS `text_area_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_area_field` (
  `id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_4AC50418BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_area_field`
--

LOCK TABLES `text_area_field` WRITE;
/*!40000 ALTER TABLE `text_area_field` DISABLE KEYS */;
INSERT INTO `text_area_field` VALUES (159,NULL),(628,NULL),(629,NULL),(634,NULL),(635,NULL),(637,NULL),(639,NULL),(642,'Solaris a développé sont propre outil de gestion de projet interne et souhaite maintenant l\'interfacer avec son ERP Microsoft Dynamics'),(643,'Ne concerne que l\'entité Solaris France'),(645,'L\'application de gestion de projet interne Atrium doit être en mesure de lire les temps effectifs passés sur projet dans MS Dynamics et venir y ajouter des absences'),(647,'Vérifier les temps effectifs dans MS Dynamics\r\nCréer une absence dans MS Dynamics\r\nModifier cette absence dans MS Dynamics\r\nSupprimer cette absence dans MS Dynamics'),(663,'La société Solaris souhaite interfacer l\'ERP SAP ByDesign avec le CRM Hubspot pour éviter toute ressaisie'),(664,'Toutes les entités sont concernées'),(666,'Le programme devra permettre de synchroniser la base article (SAP maître), la base client (Hbspot maître)et de créer des commandes clients dans SAP ByDesign quand celles ci sont au statut \"closed won\" dans Hubspot'),(668,'Création du commande dans Hubspot pour un compte client non synchronisé\r\nPassage du statut de la commande en \"Closed won\"\r\nLe compte client est créé dans SAP ByDesign et \"Actif\"\r\nLa commande est créée dans SAP ByDesign au staut \"En cours\"'),(671,NULL),(672,NULL),(674,NULL),(676,NULL),(679,'kjgdskjgfkdsgkjggsdg'),(680,'odhdkjdhkjbksdbbsd'),(682,NULL),(684,NULL),(686,NULL);
/*!40000 ALTER TABLE `text_area_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_chapter`
--

DROP TABLE IF EXISTS `text_chapter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_chapter` (
  `id` int(11) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_DD209A11BF396750` FOREIGN KEY (`id`) REFERENCES `chapter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_chapter`
--

LOCK TABLES `text_chapter` WRITE;
/*!40000 ALTER TABLE `text_chapter` DISABLE KEYS */;
INSERT INTO `text_chapter` VALUES (33,'Objet : [OBJET]\r\nDate : [DATE]\r\n\r\nOrdre du jour :\r\n[ORDREDUJOUR]\r\n\r\nParticipant :\r\n[PARTICIPANTS]'),(34,'[POINTSABORDES]'),(35,'[NEXTSTEPS]'),(240,'Objet : [COPILCOPROJ]\r\nDate : [DATECOPIL]\r\n\r\nParticipants :\r\n[PARTICIPANTSCOPIL]\r\n\r\nAvancement :\r\n[AVANCEMENTCOPIL]\r\n\r\nDécisions : \r\n[DECISIONSCOPIL]\r\n\r\nProchaines étapes :\r\n[ACTIONSCOPIL]'),(241,'[CONTEXTEETOBJECTIFS]'),(242,'[PERIMETRE]'),(243,'[ACTEURSROLES]'),(244,'[REGLESDEGESTION]'),(245,'[PARCOURSCASDUSAGE]'),(246,'[DEFINITIONOFDONE]'),(247,'[CHIFFRAGE]'),(248,'[CONTEXTEETOBJECTIFS]'),(249,'[PERIMETRE]'),(250,'[ACTEURSROLES]'),(251,'[REGLESDEGESTION]'),(252,'[PARCOURSCASDUSAGE]'),(253,'[DEFINITIONOFDONE]'),(264,'[CONTEXTEETOBJECTIFS]'),(265,'[PERIMETRE]'),(266,'[ACTEURSROLES]'),(267,'[REGLESDEGESTION]'),(268,'[PARCOURSCASDUSAGE]'),(269,'[DEFINITIONOFDONE]'),(270,'[CHIFFRAGE]'),(271,'[CONTEXTEETOBJECTIFS]'),(272,'[PERIMETRE]'),(273,'[ACTEURSROLES]'),(274,'[REGLESDEGESTION]'),(275,'[PARCOURSCASDUSAGE]'),(276,'[DEFINITIONOFDONE]'),(277,'[CHIFFRAGE]'),(278,'[CONTEXTEETOBJECTIFS]'),(279,'[PERIMETRE]'),(280,'[ACTEURSROLES]'),(281,'[REGLESDEGESTION]'),(282,'[PARCOURSCASDUSAGE]'),(283,'[DEFINITIONOFDONE]');
/*!40000 ALTER TABLE `text_chapter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_field`
--

DROP TABLE IF EXISTS `text_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_field` (
  `id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_D41FF05BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_field`
--

LOCK TABLES `text_field` WRITE;
/*!40000 ALTER TABLE `text_field` DISABLE KEYS */;
INSERT INTO `text_field` VALUES (157,NULL);
/*!40000 ALTER TABLE `text_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `title`
--

DROP TABLE IF EXISTS `title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `title`
--

LOCK TABLES `title` WRITE;
/*!40000 ALTER TABLE `title` DISABLE KEYS */;
INSERT INTO `title` VALUES (49,'Contexte',2),(50,'Points abordés',2),(51,'Prochaines étapes',2),(52,'Compte rendu d\'atelier',1),(335,NULL,1),(336,'Compte rendu de COPIL',1),(337,'Contexte et objectifs',2),(338,'Périmètre',2),(339,'Acteurs / Rôles',2),(340,'Règles de gestion',2),(341,'Parcours / cas d’usage',2),(342,'Critères d’acceptation (Definition of Done)',2),(343,NULL,1),(344,'Chiffrage',1),(345,'Spécification fonctionnelle',1),(346,'Spécification fonctionnelle',1),(347,'Contexte et objectifs',2),(348,'Périmètre',2),(349,'Acteurs / Rôles',2),(350,'Règles de gestion',2),(351,'Parcours / cas d’usage',2),(352,'Critères d’acceptation (Definition of Done)',2),(366,'Spécification fonctionnelle',1),(367,'Contexte et objectifs',2),(368,'Périmètre',2),(369,'Acteurs / Rôles',2),(370,'Règles de gestion',2),(371,'Parcours / cas d’usage',2),(372,'Critères d’acceptation (Definition of Done)',2),(373,'Chiffrage',1),(374,NULL,1),(375,'Spécification fonctionnelle',1),(376,'Contexte et objectifs',2),(377,'Périmètre',2),(378,'Acteurs / Rôles',2),(379,'Règles de gestion',2),(380,'Parcours / cas d’usage',2),(381,'Critères d’acceptation (Definition of Done)',2),(382,'Chiffrage',1),(383,NULL,1),(384,'Spécification fonctionnelle',1),(385,'Contexte et objectifs',2),(386,'Périmètre',2),(387,'Acteurs / Rôles',2),(388,'Règles de gestion',2),(389,'Parcours / cas d’usage',2),(390,'Critères d’acceptation (Definition of Done)',2);
/*!40000 ALTER TABLE `title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `url_field`
--

DROP TABLE IF EXISTS `url_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `url_field` (
  `id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_9E512A2FBF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `url_field`
--

LOCK TABLES `url_field` WRITE;
/*!40000 ALTER TABLE `url_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `function` varchar(255) DEFAULT NULL,
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  KEY `IDX_8D93D6499033212A` (`tenant_id`),
  CONSTRAINT `FK_8D93D6499033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (20,'admin@idev4u.fr','[\"ROLE_ADMIN\"]','$2y$13$zie7v1hijaXUpwHlHCXG6OyUsY.I.tPL1HVRjELj2bP5vSj7tlpde','Denis Palot','Chef de projet',1),(21,'dev@idev4u.fr','[\"ROLE_USER\"]','$2y$13$cStQv5i5TXCdbpUCaChvB.6ZgyfzhYxG50EgpP9WrS/L1fXtwShFi','Rémy Maillard','Développeur Junior',1),(22,'leaddev@idev4u.fr','[\"ROLE_USER\"]','$2y$13$uL9Zq3NmEXGXoOkAExZX7eF1CjYcHSjsfmraztQ.hKGa8NWzG.cUW','Maëlle Guiyot','Développeur sénior',1),(24,'admin@fluensys.fr','[\"ROLE_ADMIN\"]','$2y$13$gk8NWmOb0EZCqde/wL50..o8CAIyGbzG9Q5nVWKFufdX4/Y8Ca0DO','admin','admin',2);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_participant_role`
--

DROP TABLE IF EXISTS `user_participant_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_participant_role` (
  `user_id` int(11) NOT NULL,
  `participant_role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`participant_role_id`),
  KEY `IDX_FEC8DA89A76ED395` (`user_id`),
  KEY `IDX_FEC8DA894C0EEDA4` (`participant_role_id`),
  CONSTRAINT `FK_FEC8DA894C0EEDA4` FOREIGN KEY (`participant_role_id`) REFERENCES `participant_role` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FEC8DA89A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_participant_role`
--

LOCK TABLES `user_participant_role` WRITE;
/*!40000 ALTER TABLE `user_participant_role` DISABLE KEYS */;
INSERT INTO `user_participant_role` VALUES (20,39),(21,42),(21,43),(21,44),(21,45),(21,48),(22,41),(22,46),(22,52);
/*!40000 ALTER TABLE `user_participant_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'fluensys'
--

--
-- Dumping routines for database 'fluensys'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-14 21:27:56
