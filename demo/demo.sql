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
  `active` tinyint(1) NOT NULL DEFAULT 1,
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
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capture`
--

LOCK TABLES `capture` WRITE;
/*!40000 ALTER TABLE `capture` DISABLE KEYS */;
INSERT INTO `capture` VALUES (17,52,NULL,'Compte rendu','Support de comptes rendus',NULL,1,'template',NULL,1),(139,336,NULL,'COPIL / COPROJ','Support de COPIL / COPROJ',NULL,1,'template',NULL,1),(140,345,NULL,'Spécification fonctionnelle','Spécification fonctionnelle',NULL,1,'template',NULL,1),(141,344,NULL,'Chiffrage','Chiffrage',NULL,1,'template',NULL,1),(153,392,NULL,'Capture de TEST','Capture de TEST',NULL,1,'template',NULL,1),(162,417,4,'Capture de TEST de rendu','Capture de TEST',20,1,'validated',NULL,1),(164,425,4,'Spécification fonctionnelle v2','Spécification fonctionnelle',20,1,'ready',NULL,1),(165,432,4,'Chiffrage','Chiffrage',20,1,'pending',NULL,1),(168,440,4,'Spécification fonctionnelle','Spécification fonctionnelle',20,1,'ready',30,1),(169,447,4,'Chiffrage','Chiffrage',20,1,'pending',30,1),(170,449,4,'Compte rendu','Support de comptes rendus',20,1,'submitted',NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=418 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capture_element`
--

LOCK TABLES `capture_element` WRITE;
/*!40000 ALTER TABLE `capture_element` DISABLE KEYS */;
INSERT INTO `capture_element` VALUES (33,NULL,33,17,'Introduction','Introduction',1,0,NULL,'template',1),(34,NULL,34,17,'Points abordés / Décisions','Points abordés / Décisions',1,1,NULL,'template',1),(35,NULL,35,17,'Etapes suivantes','Etapes suivantes',1,2,NULL,'template',1),(309,39,240,139,'Compte rendu (COPIL / COPROJ)','Compte rendu de COPIL (ou COPROJ)',1,0,NULL,'template',1),(310,NULL,241,140,'Contexte et objectifs','Contexte et objectifs',1,0,NULL,'template',1),(311,NULL,242,140,'Périmètre','Périmètre',1,1,NULL,'template',1),(312,NULL,243,140,'Acteurs / Rôles','Acteurs / Rôles',1,2,NULL,'template',1),(313,NULL,NULL,140,'Besoins fonctionnels','Besoins fonctionnels',1,3,NULL,'template',1),(314,NULL,244,140,'Règles de gestion','Règles de gestion',1,4,NULL,'template',1),(315,NULL,245,140,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,NULL,'template',1),(316,NULL,246,140,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,NULL,'template',1),(318,39,247,141,'Chiffrage','Chiffrage',1,0,52,'template',1),(367,NULL,290,153,'Table field test','Table field test',1,0,NULL,'template',1),(368,NULL,291,153,'Listable field test','Listable field test',1,0,NULL,'template',1),(380,NULL,294,153,'Autres champs','Autres champs',1,0,NULL,'template',1),(387,NULL,301,162,'Table field test','Table field test',1,0,NULL,'validated',1),(388,NULL,302,162,'Listable field test','Listable field test',1,0,NULL,'validated',1),(389,NULL,303,162,'Autres champs','Autres champs',1,0,NULL,'validated',1),(393,NULL,307,164,'Contexte et objectifs','Contexte et objectifs',1,0,NULL,'ready',1),(394,NULL,308,164,'Périmètre','Périmètre',1,1,NULL,'submitted',1),(395,NULL,309,164,'Acteurs / Rôles','Acteurs / Rôles',1,2,NULL,'ready',1),(396,NULL,NULL,164,'Besoins fonctionnels','Besoins fonctionnels',1,3,NULL,'ready',1),(397,NULL,310,164,'Règles de gestion','Règles de gestion',1,4,NULL,'ready',1),(398,NULL,311,164,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,NULL,'ready',1),(399,NULL,312,164,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,NULL,'ready',1),(400,39,313,165,'Chiffrage','Chiffrage',1,0,52,'pending',1),(407,NULL,318,168,'Contexte et objectifs','Contexte et objectifs',1,0,NULL,'submitted',1),(408,NULL,319,168,'Périmètre','Périmètre',1,1,NULL,'submitted',1),(409,NULL,320,168,'Acteurs / Rôles','Acteurs / Rôles',1,2,NULL,'ready',1),(410,NULL,NULL,168,'Besoins fonctionnels','Besoins fonctionnels',1,3,NULL,'ready',1),(411,NULL,321,168,'Règles de gestion','Règles de gestion',1,4,NULL,'submitted',1),(412,NULL,322,168,'Parcours / cas d’usage','Parcours / cas d’usage',1,5,NULL,'submitted',1),(413,NULL,323,168,'Critères d’acceptation (Definition of Done)','Critères d’acceptation (Definition of Done)',1,6,NULL,'ready',1),(414,39,324,169,'Chiffrage','Chiffrage',1,0,52,'pending',1),(415,NULL,325,170,'Introduction','Introduction',1,0,NULL,'submitted',1),(416,NULL,326,170,'Points abordés / Décisions','Points abordés / Décisions',1,1,NULL,'submitted',1),(417,NULL,327,170,'Etapes suivantes','Etapes suivantes',1,2,NULL,'submitted',1);
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
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F981B52EA9F87BD` (`title_id`),
  CONSTRAINT `FK_F981B52EA9F87BD` FOREIGN KEY (`title_id`) REFERENCES `title` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapter`
--

LOCK TABLES `chapter` WRITE;
/*!40000 ALTER TABLE `chapter` DISABLE KEYS */;
INSERT INTO `chapter` VALUES (33,49,'Objet : [OBJET]\r\nDate : [DATE]\r\n\r\nParticipants :\r\n[PARTICIPANTS]'),(34,50,'[POINTSABORDESDECISIONS]'),(35,51,'[ACTIONS]'),(240,335,'Date : [DATECOPIL]\r\n\r\nParticipants : \r\n[PARTICIPANTSCOPIL]\r\n\r\n[POINTSABORDESDECISIONS]\r\n\r\nProchaines étapes : \r\n[PROCHAINESETAPES]'),(241,337,'[CONTEXTEETOBJECTIFS]'),(242,338,'[PERIMETRE]'),(243,339,'[ACTEURSROLES]'),(244,340,'[REGLESDEGESTION]'),(245,341,'[CASDUSAGE]\r\n\r\n[JEUXDEDONNEES]'),(246,342,'[DEFINITIONOFDONE]'),(247,343,'[LISTEDETACHES]'),(290,403,'[TABLE]'),(291,404,'[LISTE]'),(294,408,'Texte long :\r\n[TEXTELONG]\r\n\r\nTexte court :\r\n[TEXTECOURT]\r\n\r\nInteger :\r\n[INTEGER]\r\n\r\nDecimal :\r\n[DECIMAL]\r\n\r\nDate :\r\n[DATE]\r\n\r\nReponse multiple :\r\n[CHOIXMULTIPLE]\r\n\r\nReponse unique :\r\n[REPONSEUNIQUE]\r\n\r\nLien :\r\n[LINK]\r\n\r\nEmail :\r\n[EMAIL]\r\n\r\nFichier :\r\n[FILE]\r\n\r\nImage :\r\n[IMAGE]'),(301,418,'[TABLE]'),(302,419,'[LISTE]'),(303,420,'Texte long :\r\n[TEXTELONG]\r\n\r\nTexte court :\r\n[TEXTECOURT]\r\n\r\nInteger :\r\n[INTEGER]\r\n\r\nDecimal :\r\n[DECIMAL]\r\n\r\nDate :\r\n[DATE]\r\n\r\nReponse multiple :\r\n[CHOIXMULTIPLE]\r\n\r\nReponse unique :\r\n[REPONSEUNIQUE]\r\n\r\nLien :\r\n[LINK]\r\n\r\nEmail :\r\n[EMAIL]\r\n\r\nFichier :\r\n[FILE]\r\n\r\nImage :\r\n[IMAGE]'),(307,426,NULL),(308,427,NULL),(309,428,NULL),(310,429,NULL),(311,430,NULL),(312,431,NULL),(313,433,NULL),(318,441,'[CONTEXTEETOBJECTIFS]'),(319,442,'[PERIMETRE]'),(320,443,'[ACTEURSROLES]'),(321,444,'[REGLESDEGESTION]'),(322,445,'[CASDUSAGE]\r\n\r\n[JEUXDEDONNEES]'),(323,446,'[DEFINITIONOFDONE]'),(324,448,'[LISTEDETACHES]'),(325,450,'Objet : [OBJET]\r\nDate : [DATE]\r\n\r\nParticipants :\r\n[PARTICIPANTS]'),(326,451,'[POINTSABORDESDECISIONS]'),(327,452,'[ACTIONS]');
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
INSERT INTO `checklist_field` VALUES (633,'[{\"label\":\"COPIL\",\"value\":\"COPIL\"},{\"label\":\"COPROJ\",\"value\":\"COPROJ\"}]',NULL,1),(788,'[{\"label\":\"Option A\",\"value\":\"Option A\"},{\"label\":\"Option B\",\"value\":\"Option B\"},{\"label\":\"Option C\",\"value\":\"Option C\"}]',NULL,0),(789,'[{\"label\":\"Option A\",\"value\":\"Option A\"},{\"label\":\"Option B\",\"value\":\"Option B\"},{\"label\":\"Option C\",\"value\":\"Option C\"}]',NULL,1),(823,'[{\"label\":\"Option A\",\"value\":\"Option A\"},{\"label\":\"Option B\",\"value\":\"Option B\"},{\"label\":\"Option C\",\"value\":\"Option C\"}]','[\"Option B\",\"Option C\"]',0),(824,'[{\"label\":\"Option A\",\"value\":\"Option A\"},{\"label\":\"Option B\",\"value\":\"Option B\"},{\"label\":\"Option C\",\"value\":\"Option C\"}]','[\"Option A\"]',1);
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
INSERT INTO `date_field` VALUES (169,NULL),(626,NULL),(787,NULL),(822,'2026-01-15'),(887,'2026-01-08');
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
INSERT INTO `decimal_field` VALUES (786,NULL),(821,10.3500);
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
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20251221124726','2025-12-22 23:39:06',1962),('DoctrineMigrations\\Version20251222175506','2025-12-22 23:39:08',129),('DoctrineMigrations\\Version20251222233019','2025-12-22 23:39:08',325),('DoctrineMigrations\\Version20251222234341','2025-12-22 23:43:48',45),('DoctrineMigrations\\Version20251223110823','2025-12-23 11:08:30',139),('DoctrineMigrations\\Version20251223132424','2025-12-23 13:24:30',24),('DoctrineMigrations\\Version20251223135827','2025-12-23 13:58:33',134),('DoctrineMigrations\\Version20251224173554','2025-12-24 17:36:09',250),('DoctrineMigrations\\Version20251224204633','2025-12-24 20:46:36',200),('DoctrineMigrations\\Version20251224214524','2025-12-24 21:45:53',302),('DoctrineMigrations\\Version20251225102140','2025-12-25 10:21:51',41),('DoctrineMigrations\\Version20251225155652','2025-12-25 15:56:59',27),('DoctrineMigrations\\Version20251225222045','2025-12-25 22:20:55',452),('DoctrineMigrations\\Version20251228100054',NULL,NULL),('DoctrineMigrations\\Version20251228104045','2025-12-28 10:40:58',122),('DoctrineMigrations\\Version20251228122935',NULL,NULL),('DoctrineMigrations\\Version20251228123730','2025-12-28 12:37:33',101),('DoctrineMigrations\\Version20251228163504',NULL,NULL),('DoctrineMigrations\\Version20251228164051','2025-12-28 16:40:57',113),('DoctrineMigrations\\Version20251228164506',NULL,NULL),('DoctrineMigrations\\Version20251228164634','2025-12-28 16:46:38',98),('DoctrineMigrations\\Version20251228164727',NULL,NULL),('DoctrineMigrations\\Version20251228164820','2025-12-28 16:48:26',95),('DoctrineMigrations\\Version20251228165021',NULL,NULL),('DoctrineMigrations\\Version20251228165132','2025-12-28 16:51:37',92),('DoctrineMigrations\\Version20251228172057','2025-12-28 17:21:23',26),('DoctrineMigrations\\Version20251230111731','2025-12-30 11:17:50',91),('DoctrineMigrations\\Version20251230121029','2025-12-30 12:10:34',123),('DoctrineMigrations\\Version20251230125658','2025-12-30 12:57:02',88),('DoctrineMigrations\\Version20251230153755','2025-12-30 15:37:59',377),('DoctrineMigrations\\Version20260108104748','2026-01-08 10:48:04',358),('DoctrineMigrations\\Version20260108154528','2026-01-08 15:45:32',109),('DoctrineMigrations\\Version20260109112302','2026-01-09 11:23:07',75),('DoctrineMigrations\\Version20260109150519','2026-01-09 15:05:23',117),('DoctrineMigrations\\Version20260111112211','2026-01-11 11:22:15',47),('DoctrineMigrations\\Version20260111120442','2026-01-11 12:04:49',129),('DoctrineMigrations\\Version20260111121511','2026-01-11 12:15:16',124),('DoctrineMigrations\\Version20260112093425','2026-01-12 09:53:50',116),('DoctrineMigrations\\Version20260112101614','2026-01-12 10:16:20',62),('DoctrineMigrations\\Version20260112114713','2026-01-12 11:47:19',133),('DoctrineMigrations\\Version20260114154652','2026-01-14 15:47:02',167),('DoctrineMigrations\\Version20260114235806','2026-01-14 23:58:14',47),('DoctrineMigrations\\Version20260115000120','2026-01-15 00:01:34',114),('DoctrineMigrations\\Version20260115000254','2026-01-15 00:02:57',82),('DoctrineMigrations\\Version20260115000447','2026-01-15 00:04:56',111),('DoctrineMigrations\\Version20260115010619','2026-01-15 01:06:22',55),('DoctrineMigrations\\Version20260115102633','2026-01-15 10:26:38',55),('DoctrineMigrations\\Version20260116152619','2026-01-16 15:26:35',232),('DoctrineMigrations\\Version20260118114958','2026-01-18 11:50:05',41),('DoctrineMigrations\\Version20260118124554','2026-01-18 12:45:58',85),('DoctrineMigrations\\Version20260118145149','2026-01-18 14:51:56',164),('DoctrineMigrations\\Version20260118150004','2026-01-18 15:00:09',33),('DoctrineMigrations\\Version20260118153939','2026-01-18 15:39:44',84),('DoctrineMigrations\\Version20260118171727','2026-01-18 17:17:31',38),('DoctrineMigrations\\Version20260118213543','2026-01-18 21:36:04',62),('DoctrineMigrations\\Version20260118221638','2026-01-18 22:16:42',134);
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
INSERT INTO `email_field` VALUES (791,NULL),(826,'max@fluensys.io');
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
) ENGINE=InnoDB AUTO_INCREMENT=891 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field`
--

LOCK TABLES `field` WRITE;
/*!40000 ALTER TABLE `field` DISABLE KEYS */;
INSERT INTO `field` VALUES (157,33,'Objet','OBJET',0,'text','Objet (ex. “Atelier #2 – Parcours utilisateur / besoins”)',NULL,1),(169,33,'Date','DATE',1,'date','Date',NULL,1),(176,33,'Participants','PARTICIPANTS',2,'listable_field','Participants',NULL,1),(626,309,'Date COPIL','DATECOPIL',1,'date','Date',NULL,1),(627,309,'Participants COPIL','PARTICIPANTSCOPIL',2,'listable_field','Participants',NULL,1),(633,309,'COPIL / COPROJ','COPILCOPROJ',0,'checklist','COPIL / COPROJ',NULL,1),(634,310,'Contexte et objectifs','CONTEXTEETOBJECTIFS',0,'textarea','Contexte et objectifs',NULL,1),(635,311,'Périmètre','PERIMETRE',0,'textarea','Périmètre',NULL,1),(636,312,'Acteurs / Rôles','ACTEURSROLES',0,'listable_field','Acteurs / Rôles',NULL,1),(637,313,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(640,316,'Definition of Done','DEFINITIONOFDONE',0,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(743,368,'Liste','LISTE',0,'listable_field','Liste',NULL,0),(744,367,'Table','TABLE',0,'table_field','Table',NULL,0),(783,380,'Texte long','TEXTELONG',0,'textarea','Texte long',NULL,0),(784,380,'Texte court','TEXTECOURT',1,'text','Texte court',NULL,0),(785,380,'Integer','INTEGER',2,'integer','Integer',NULL,0),(786,380,'Decimal','DECIMAL',3,'decimal','Decimal',NULL,0),(787,380,'Date','DATE',4,'date','Date',NULL,0),(788,380,'Choix multiple','CHOIXMULTIPLE',5,'checklist','Choix multiple',NULL,0),(789,380,'Reponse unique','REPONSEUNIQUE',6,'checklist','Reponse unique',NULL,0),(790,380,'Link','LINK',7,'url','Link',NULL,0),(791,380,'Email','EMAIL',8,'email','Email',NULL,0),(814,380,'File','FILE',9,'file','File',NULL,0),(815,380,'Image','IMAGE',10,'image','Image',NULL,0),(816,387,'Table','TABLE',0,'table_field','Table',NULL,0),(817,388,'Liste','LISTE',0,'listable_field','Liste',NULL,0),(818,389,'Texte long','TEXTELONG',0,'textarea','Texte long',NULL,0),(819,389,'Texte court','TEXTECOURT',1,'text','Texte court',NULL,0),(820,389,'Integer','INTEGER',2,'integer','Integer',NULL,0),(821,389,'Decimal','DECIMAL',3,'decimal','Decimal',NULL,0),(822,389,'Date','DATE',4,'date','Date',NULL,0),(823,389,'Choix multiple','CHOIXMULTIPLE',5,'checklist','Choix multiple',NULL,0),(824,389,'Reponse unique','REPONSEUNIQUE',6,'checklist','Reponse unique',NULL,0),(825,389,'Link','LINK',7,'url','Link',NULL,0),(826,389,'Email','EMAIL',8,'email','Email',NULL,0),(827,389,'File','FILE',9,'file','File',NULL,0),(828,389,'Image','IMAGE',10,'image','Image',NULL,0),(842,393,'Contexte et objectifs','CONTEXTEETOBJECTIFS',1,'textarea','Contexte et objectifs',NULL,1),(843,394,'Périmètre','PERIMETRE',1,'textarea','Périmètre',NULL,1),(844,395,'Acteurs / Rôles','ACTEURSROLES',1,'listable_field','Acteurs / Rôles',NULL,1),(845,396,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(846,397,'Règles de gestion','REGLESDEGESTION',1,'listable_field','Règles de gestion',NULL,1),(847,398,'Parcours / cas d’usage','PARCOURSCASDUSAGE',1,'textarea','Parcours / cas d’usage',NULL,1),(848,399,'Definition of Done','DEFINITIONOFDONE',1,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(849,400,'Chiffrage','CHIFFRAGE',0,'listable_field','Listez les tâches et indiquez le nombre de jours',NULL,1),(865,314,'Règles de gestion','REGLESDEGESTION',0,'table_field','Règles de gestion',NULL,1),(866,315,'Cas d\'usage','CASDUSAGE',0,'table_field','Cas d\'usage',NULL,0),(869,318,'Liste de tâches','LISTEDETACHES',0,'table_field','Listez les tâches à effectuer et indiquez un nombre d\'heure',NULL,0),(871,35,'Actions','ACTIONS',0,'table_field','Listez les prochaines étapes / Actions à mener',NULL,0),(873,34,'points abordés / Décisions','POINTSABORDESDECISIONS',0,'table_field','Listez les points abordés / Décisions',NULL,0),(874,309,'Points abordés / Décisions','POINTSABORDESDECISIONS',3,'table_field','Points abordés / Décisions',NULL,0),(875,309,'Prochaines étapes','PROCHAINESETAPES',4,'table_field','Prochaines étapes',NULL,0),(876,407,'Contexte et objectifs','CONTEXTEETOBJECTIFS',0,'textarea','Contexte et objectifs',NULL,1),(877,408,'Périmètre','PERIMETRE',0,'textarea','Périmètre',NULL,1),(878,409,'Acteurs / Rôles','ACTEURSROLES',0,'listable_field','Acteurs / Rôles',NULL,1),(879,410,'Besoins fonctionnels','BESOINSFONCTIONNELS',1,'textarea','Besoins fonctionnels',NULL,1),(880,411,'Règles de gestion','REGLESDEGESTION',0,'table_field','Règles de gestion',NULL,1),(881,412,'Cas d\'usage','CASDUSAGE',0,'table_field','Cas d\'usage',NULL,0),(882,412,'Jeux de données','JEUXDEDONNEES',1,'textarea','Fournissez un fichier avec un jeu de données couvrant tous les cas d\'usage et toutes les règles de gestion',NULL,0),(883,413,'Definition of Done','DEFINITIONOFDONE',0,'listable_field','Critères d’acceptation (Definition of Done)',NULL,1),(884,414,'Liste de tâches','LISTEDETACHES',0,'table_field','Listez les tâches à effectuer et indiquez un nombre d\'heure',NULL,0),(885,315,'jeux de données','JEUXDEDONNEES',1,'file','Fournissez un fichier avec un jeux de données permettant de couvrir l\'ensemble des cas d\'usage et des règles de gestion',NULL,0),(886,415,'Objet','OBJET',0,'text','Objet (ex. “Atelier #2 – Parcours utilisateur / besoins”)',NULL,1),(887,415,'Date','DATE',1,'date','Date',NULL,1),(888,415,'Participants','PARTICIPANTS',2,'listable_field','Participants',NULL,1),(889,416,'points abordés / Décisions','POINTSABORDESDECISIONS',0,'table_field','Listez les points abordés / Décisions',NULL,0),(890,417,'Actions','ACTIONS',0,'table_field','Listez les prochaines étapes / Actions à mener',NULL,0);
/*!40000 ALTER TABLE `field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_field`
--

DROP TABLE IF EXISTS `file_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_field` (
  `id` int(11) NOT NULL,
  `value` varchar(1024) DEFAULT NULL,
  `path` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_F176F56FBF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_field`
--

LOCK TABLES `file_field` WRITE;
/*!40000 ALTER TABLE `file_field` DISABLE KEYS */;
INSERT INTO `file_field` VALUES (814,NULL,NULL),(815,NULL,NULL),(827,'logo-pdf.pdf','captures/162/elements/389/files/file_196bf46e0c35.pdf'),(828,NULL,NULL),(885,NULL,NULL);
/*!40000 ALTER TABLE `file_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_field`
--

DROP TABLE IF EXISTS `image_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_field` (
  `id` int(11) NOT NULL,
  `value` varchar(1024) DEFAULT NULL,
  `path` varchar(1024) DEFAULT NULL,
  `display_mode` varchar(20) NOT NULL DEFAULT 'medium',
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_4CB0C1F1BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_field`
--

LOCK TABLES `image_field` WRITE;
/*!40000 ALTER TABLE `image_field` DISABLE KEYS */;
INSERT INTO `image_field` VALUES (815,NULL,NULL,'medium'),(828,'logo-png.png','captures/162/elements/389/images/image_1b3a9420a350.png','small');
/*!40000 ALTER TABLE `image_field` ENABLE KEYS */;
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
INSERT INTO `integer_field` VALUES (785,NULL),(820,10);
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
INSERT INTO `listable_field` VALUES (627),(636),(640),(743),(817),(844),(846),(848),(849),(878),(883),(888);
/*!40000 ALTER TABLE `listable_field` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listable_field_text_item`
--

LOCK TABLES `listable_field_text_item` WRITE;
/*!40000 ALTER TABLE `listable_field_text_item` DISABLE KEYS */;
INSERT INTO `listable_field_text_item` VALUES (28,817,'item 1'),(29,817,'item 2'),(30,888,'Julien Armond'),(31,888,'Sylvie Raton'),(32,888,'Erwan Dogon');
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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE9033212A` (`tenant_id`),
  KEY `IDX_2FB3D0EE9B6B5FBA` (`account_id`),
  KEY `IDX_2FB3D0EE602AD315` (`responsible_id`),
  CONSTRAINT `FK_2FB3D0EE602AD315` FOREIGN KEY (`responsible_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2FB3D0EE9033212A` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`id`),
  CONSTRAINT `FK_2FB3D0EE9B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (25,1,'Projet de développement','Projet de développement','template',NULL,NULL,1),(30,1,'Projet de développement','Projet de développement','pending',4,20,1);
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
INSERT INTO `project_capture` VALUES (25,140),(25,141),(30,168),(30,169);
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
INSERT INTO `project_recurring_capture` VALUES (30,170);
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
INSERT INTO `project_recurring_capture_templates` VALUES (25,17),(25,139),(30,17),(30,139);
/*!40000 ALTER TABLE `project_recurring_capture_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendering_config`
--

DROP TABLE IF EXISTS `rendering_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rendering_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_title_color` varchar(255) DEFAULT NULL,
  `title_h1_color` varchar(255) DEFAULT NULL,
  `title_h2_color` varchar(255) DEFAULT NULL,
  `title_h3_color` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `table_header_background_color` varchar(255) DEFAULT NULL,
  `table_header_color` varchar(255) DEFAULT NULL,
  `border_color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendering_config`
--

LOCK TABLES `rendering_config` WRITE;
/*!40000 ALTER TABLE `rendering_config` DISABLE KEYS */;
INSERT INTO `rendering_config` VALUES (1,'#0060a7','#0060a7','#0060a7','#0060a7','logos/logo_94f0da2bf765.png','#0060a7','#ffffff','#0060a7');
/*!40000 ALTER TABLE `rendering_config` ENABLE KEYS */;
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
-- Table structure for table `table_field`
--

DROP TABLE IF EXISTS `table_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_field` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_57098820BF396750` FOREIGN KEY (`id`) REFERENCES `field` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_field`
--

LOCK TABLES `table_field` WRITE;
/*!40000 ALTER TABLE `table_field` DISABLE KEYS */;
INSERT INTO `table_field` VALUES (744),(816),(865),(866),(869),(871),(873),(874),(875),(880),(881),(884),(889),(890);
/*!40000 ALTER TABLE `table_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_field_column`
--

DROP TABLE IF EXISTS `table_field_column`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_field_column` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_field_id` int(11) NOT NULL,
  `col_key` varchar(80) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_table_field_column_key` (`table_field_id`,`col_key`),
  KEY `IDX_E2F49ABED48A2960` (`table_field_id`),
  CONSTRAINT `FK_E2F49ABED48A2960` FOREIGN KEY (`table_field_id`) REFERENCES `table_field` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_field_column`
--

LOCK TABLES `table_field_column` WRITE;
/*!40000 ALTER TABLE `table_field_column` DISABLE KEYS */;
INSERT INTO `table_field_column` VALUES (7,744,'nom','nom','text',0),(8,744,'date','date','date',1),(16,744,'int','int','integer',2),(23,816,'nom','nom','text',0),(24,816,'date','date','date',1),(25,816,'int','int','integer',2),(29,865,'objetcible','Objet / Cible','text',0),(30,865,'condition','Condition','text',1),(31,865,'scope','Scope','text',2),(32,865,'regle','Règle','text',3),(33,866,'acteur','Acteur','text',0),(34,866,'action','Action','text',1),(35,866,'resultat','Résultat','text',2),(36,869,'tache','Tâche','text',0),(37,869,'dureeh','Durée (H)','integer',1),(41,871,'acteur','Acteur','text',0),(42,871,'action','Action','text',1),(43,871,'datedecheance','Date d\'échéance','date',2),(44,873,'pointaborde','Point abordé','text',0),(45,873,'decisionprise','Décision prise','text',1),(46,874,'pointsaborde','Points abordé','text',0),(47,874,'decision','Décision','text',1),(48,875,'acteur','Acteur','text',0),(49,875,'action','Action','text',1),(50,875,'datedecheance','Date d\'échéance','date',2),(51,880,'objetcible','Objet / Cible','text',0),(52,880,'condition','Condition','text',1),(53,880,'scope','Scope','text',2),(54,880,'regle','Règle','text',3),(55,881,'acteur','Acteur','text',0),(56,881,'action','Action','text',1),(57,881,'resultat','Résultat','text',2),(58,884,'tache','Tâche','text',0),(59,884,'dureeh','Durée (H)','integer',1),(60,889,'pointaborde','Point abordé','text',0),(61,889,'decisionprise','Décision prise','text',1),(62,890,'acteur','Acteur','text',0),(63,890,'action','Action','text',1),(64,890,'datedecheance','Date d\'échéance','date',2);
/*!40000 ALTER TABLE `table_field_column` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `table_field_row`
--

DROP TABLE IF EXISTS `table_field_row`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_field_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_field_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `row_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`row_values`)),
  PRIMARY KEY (`id`),
  KEY `IDX_A2827539D48A2960` (`table_field_id`),
  CONSTRAINT `FK_A2827539D48A2960` FOREIGN KEY (`table_field_id`) REFERENCES `table_field` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_field_row`
--

LOCK TABLES `table_field_row` WRITE;
/*!40000 ALTER TABLE `table_field_row` DISABLE KEYS */;
INSERT INTO `table_field_row` VALUES (6,816,0,'{\"nom\":\"row 1\",\"date\":\"2026-01-14\",\"int\":1}'),(7,816,0,'{\"nom\":\"row 2\",\"date\":\"2026-01-14\",\"int\":2}'),(8,816,0,'{\"nom\":\"row 3\",\"date\":\"2026-01-21\",\"int\":3}'),(9,880,0,'{\"objetcible\":\"R\\u00e9cup\\u00e9ration des extrait\",\"condition\":\"Aucune\",\"scope\":\"Tous les compte FR\",\"regle\":\"1 fois par jour \\u00e0 8h\"}'),(10,881,0,'{\"acteur\":\"User technique\",\"action\":\"R\\u00e9cup\\u00e8re les extraits de compte du jour et les charges dans SAP ByDesign\",\"resultat\":\"Les extraits du jour sont pr\\u00e9sents et au statut \\\"comptabilis\\u00e9\\\" dans SAP ByDesign\"}'),(11,889,0,'{\"pointaborde\":\"API MesBanques pour r\\u00e9cup\\u00e9ration des extraits\",\"decisionprise\":\"API REST\"}'),(12,890,0,'{\"acteur\":\"Julien Armond\",\"action\":\"Configure MesBanques pour activer l\'API\",\"datedecheance\":\"2026-01-20\"}'),(13,890,0,'{\"acteur\":\"Julien Armond\",\"action\":\"Fourni les acc\\u00e8s API pour test\",\"datedecheance\":\"2026-01-21\"}'),(14,890,0,'{\"acteur\":\"Sylvie Raton\",\"action\":\"Test l\'API d\'upload des extrait dans SAP et fait un retour\",\"datedecheance\":\"2026-01-29\"}');
/*!40000 ALTER TABLE `table_field_row` ENABLE KEYS */;
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
  `rendering_config_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4E59C462CB6D06F4` (`rendering_config_id`),
  CONSTRAINT `FK_4E59C462CB6D06F4` FOREIGN KEY (`rendering_config_id`) REFERENCES `rendering_config` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant`
--

LOCK TABLES `tenant` WRITE;
/*!40000 ALTER TABLE `tenant` DISABLE KEYS */;
INSERT INTO `tenant` VALUES (1,'Idev4U',1),(2,'ERP Logic - Intégrateur multi technos',NULL);
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
INSERT INTO `text_area_field` VALUES (634,NULL),(635,NULL),(637,NULL),(783,NULL),(818,'dqgjbq:kjb:q v,qsmk,mlsd,q;nvmq,mflpizhqifjmdkqjgohpzmheglkbldsqhsghoihzljebjgzqgzG'),(842,NULL),(843,'Toutes les sociétés du groupe (USA, FR, ES, IT)'),(845,NULL),(847,NULL),(876,'Récupérer automatiquement les extraits de compte dans MesBanques (Cegid) et les intégrer automatiquement dans SAP ByDesign'),(877,'Concerne toute les sociétés FR'),(879,NULL),(882,NULL);
/*!40000 ALTER TABLE `text_area_field` ENABLE KEYS */;
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
INSERT INTO `text_field` VALUES (157,NULL),(784,NULL),(819,'Un texte un peu court'),(886,'Atelier Idev4U / Cegid');
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
) ENGINE=InnoDB AUTO_INCREMENT=453 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `title`
--

LOCK TABLES `title` WRITE;
/*!40000 ALTER TABLE `title` DISABLE KEYS */;
INSERT INTO `title` VALUES (49,'Contexte',2),(50,'Points abordés / Décisions',2),(51,'Prochaines étapes',2),(52,'Compte rendu d\'atelier',1),(335,'[COPILCOPROJ]',1),(336,'Compte rendu de COPIL',1),(337,'Contexte et objectifs',2),(338,'Périmètre',2),(339,'Acteurs / Rôles',2),(340,'Règles de gestion',2),(341,'Parcours / cas d’usage',2),(342,'Critères d’acceptation (Definition of Done)',2),(343,NULL,1),(344,'Chiffrage',1),(345,'Spécification fonctionnelle',1),(392,'TEST de rendu',1),(403,'Tableau',2),(404,'Liste',2),(408,'Autres champs',2),(417,'TEST de rendu',1),(418,'Tableau',2),(419,'Liste',2),(420,'Autres champs',2),(425,'Spécification fonctionnelle',1),(426,'Contexte et objectifs',2),(427,'Périmètre',2),(428,'Acteurs / Rôles',2),(429,'Règles de gestion',2),(430,'Parcours / cas d’usage',2),(431,'Critères d’acceptation (Definition of Done)',2),(432,'Chiffrage',1),(433,NULL,1),(440,'Spécification fonctionnelle',1),(441,'Contexte et objectifs',2),(442,'Périmètre',2),(443,'Acteurs / Rôles',2),(444,'Règles de gestion',2),(445,'Parcours / cas d’usage',2),(446,'Critères d’acceptation (Definition of Done)',2),(447,'Chiffrage',1),(448,NULL,1),(449,'Compte rendu d\'atelier',1),(450,'Contexte',2),(451,'Points abordés / Décisions',2),(452,'Prochaines étapes',2);
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
INSERT INTO `url_field` VALUES (790,NULL),(825,'https://example.com');
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

-- Dump completed on 2026-01-19 10:56:56
