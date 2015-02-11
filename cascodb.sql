-- MySQL dump 10.13  Distrib 5.6.19, for osx10.9 (x86_64)
--
-- Host: localhost    Database: cascodb
-- ------------------------------------------------------
-- Server version	5.6.19

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
-- Table structure for table `ad`
--

DROP TABLE IF EXISTS `ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ad的id',
  `doc_id` int(11) NOT NULL COMMENT '所属doc的id',
  `title` varchar(20) NOT NULL COMMENT 'item名称',
  `description` text NOT NULL COMMENT '描述',
  `category` varchar(20) NOT NULL COMMENT '类别',
  `contribution` varchar(10) NOT NULL COMMENT '贡献',
  `allocation` varchar(40) NOT NULL COMMENT '分配对象',
  `source_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_id` (`doc_id`),
  KEY `title` (`title`),
  KEY `source_id` (`source_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ad`
--

LOCK TABLES `ad` WRITE;
/*!40000 ALTER TABLE `ad` DISABLE KEYS */;
/*!40000 ALTER TABLE `ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document` (
  `id` varchar(36) NOT NULL COMMENT '默认索引',
  `name` varchar(100) NOT NULL COMMENT '文档名称',
  `type` enum('rs','tc','ad','tr','folder') NOT NULL COMMENT '文档类型',
  `project_id` varchar(36) NOT NULL COMMENT '所属项目',
  `build_version` varchar(10) NOT NULL COMMENT '软件测试版本',
  `test_version` varchar(10) NOT NULL COMMENT '测试软件版本',
  `fid` varchar(36) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
INSERT INTO `document` VALUES ('d6889236-ad21-11e4-aa9b-cf2d72b432dc','TSP-SYRS','rs','1','','','dc14a208-ad21-11e4-aa9b-cf2d72b432dc','2014-11-23 14:05:41','2015-02-05 01:16:30'),('dc14a208-ad21-11e4-aa9b-cf2d72b432dc','sytem','folder','1','','','0','2014-12-08 22:31:02','2014-12-08 22:31:02'),('e1c83444-ad21-11e4-aa9b-cf2d72b432dc','TSP-SYTC','tc','1','','','dc14a208-ad21-11e4-aa9b-cf2d72b432dc','2014-11-23 13:21:30','2015-01-28 00:00:44'),('e7146468-ad21-11e4-aa9b-cf2d72b432dc','subsystem','folder','1','','','0','2014-12-08 15:42:01','2014-12-08 15:42:01'),('eca32cc0-ad21-11e4-aa9b-cf2d72b432dc','test case','tc','1','','','e7146468-ad21-11e4-aa9b-cf2d72b432dc','2014-12-08 15:58:51','2015-01-28 00:00:51'),('c00dc5ad-c45f-410c-9f88-b27562414c59','adf','rs','1','','','0','2015-02-06 01:36:36','2015-02-06 01:36:36');
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` varchar(36) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `graph` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES ('1','TSP-PJG','卡斯卡项目','{\"cells\":[{\"type\":\"basic.Rect\",\"position\":{\"x\":115,\"y\":219},\"size\":{\"width\":150,\"height\":30},\"angle\":0,\"id\":\"d6889236-ad21-11e4-aa9b-cf2d72b432dc\",\"z\":1,\"attrs\":{\"rect\":{\"fill\":\"#E74C3C\"},\"text\":{\"text\":\"TSP-SYRS\",\"fill\":\"white\"}}},{\"type\":\"basic.Rect\",\"position\":{\"x\":445,\"y\":63},\"size\":{\"width\":150,\"height\":30},\"angle\":0,\"id\":\"e1c83444-ad21-11e4-aa9b-cf2d72b432dc\",\"z\":2,\"attrs\":{\"rect\":{\"fill\":\"#8E44AD\"},\"text\":{\"text\":\"TSP-SYTC\",\"fill\":\"white\"}}},{\"type\":\"basic.Rect\",\"position\":{\"x\":400,\"y\":140},\"size\":{\"width\":150,\"height\":30},\"angle\":0,\"id\":\"eca32cc0-ad21-11e4-aa9b-cf2d72b432dc\",\"z\":3,\"attrs\":{\"rect\":{\"fill\":\"#8E44AD\"},\"text\":{\"text\":\"test case\",\"fill\":\"white\"}}},{\"type\":\"basic.Rect\",\"position\":{\"x\":123,\"y\":105},\"size\":{\"width\":150,\"height\":30},\"angle\":0,\"id\":\"c00dc5ad-c45f-410c-9f88-b27562414c59\",\"z\":5,\"attrs\":{\"rect\":{\"fill\":\"#E74C3C\"},\"text\":{\"text\":\"adf\",\"fill\":\"white\"}}},{\"type\":\"fsa.Arrow\",\"smooth\":true,\"source\":{\"id\":\"c00dc5ad-c45f-410c-9f88-b27562414c59\"},\"target\":{\"id\":\"d6889236-ad21-11e4-aa9b-cf2d72b432dc\"},\"id\":\"4e8924ea-ef36-4d1c-b0c1-4537371842b6\",\"z\":6,\"attrs\":{}},{\"type\":\"fsa.Arrow\",\"smooth\":true,\"source\":{\"id\":\"d6889236-ad21-11e4-aa9b-cf2d72b432dc\"},\"target\":{\"id\":\"eca32cc0-ad21-11e4-aa9b-cf2d72b432dc\"},\"id\":\"624b0f68-07be-4755-b9a4-85fb364c52d3\",\"z\":7,\"attrs\":{}},{\"type\":\"fsa.Arrow\",\"smooth\":true,\"source\":{\"id\":\"e1c83444-ad21-11e4-aa9b-cf2d72b432dc\"},\"target\":{\"id\":\"d6889236-ad21-11e4-aa9b-cf2d72b432dc\"},\"id\":\"e5aa1834-4c97-4c0f-83f9-949575b5ef0f\",\"z\":8,\"attrs\":{}}]}','2014-11-24 00:00:00','2015-02-06 10:38:30'),('7','RAIL-FORWARD','1','','2014-11-23 18:32:34','2014-11-23 18:32:34');
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_user`
--

DROP TABLE IF EXISTS `project_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_user` (
  `project_id` varchar(36) NOT NULL COMMENT '工程id',
  `user_id` varchar(36) NOT NULL COMMENT '员工id',
  `role_type` enum('TE','MTE','Manager') NOT NULL COMMENT '与user_role里面的role_type是否重复？'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_user`
--

LOCK TABLES `project_user` WRITE;
/*!40000 ALTER TABLE `project_user` DISABLE KEYS */;
INSERT INTO `project_user` VALUES ('90640116-ad10-450c-9d30-91b8a6acc607','a7b12e32-b0f5-11e4-abb7-c17404b78885','TE');
/*!40000 ALTER TABLE `project_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relation`
--

DROP TABLE IF EXISTS `relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relation` (
  `src` varchar(36) NOT NULL COMMENT '文档关系起点',
  `dest` varchar(36) NOT NULL COMMENT '文档关系终点',
  `src_type` varchar(10) NOT NULL COMMENT '源item所属类别',
  `dest_type` varchar(10) NOT NULL COMMENT '覆盖item所属类别',
  `src_tag` varchar(20) NOT NULL COMMENT '源tag内容',
  `dest_tag` varchar(20) NOT NULL COMMENT '目标tag内容',
  KEY `src` (`src`),
  KEY `dest` (`dest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relation`
--

LOCK TABLES `relation` WRITE;
/*!40000 ALTER TABLE `relation` DISABLE KEYS */;
INSERT INTO `relation` VALUES ('c00dc5ad-c45f-410c-9f88-b27562414c59','d6889236-ad21-11e4-aa9b-cf2d72b432dc','','','',''),('d6889236-ad21-11e4-aa9b-cf2d72b432dc','eca32cc0-ad21-11e4-aa9b-cf2d72b432dc','','','',''),('c00dc5ad-c45f-410c-9f88-b27562414c59','d6889236-ad21-11e4-aa9b-cf2d72b432dc','','','',''),('d6889236-ad21-11e4-aa9b-cf2d72b432dc','eca32cc0-ad21-11e4-aa9b-cf2d72b432dc','','','',''),('e1c83444-ad21-11e4-aa9b-cf2d72b432dc','d6889236-ad21-11e4-aa9b-cf2d72b432dc','','','','');
/*!40000 ALTER TABLE `relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `root`
--

DROP TABLE IF EXISTS `root`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `root` (
  `admin` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL DEFAULT '8888',
  PRIMARY KEY (`admin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `root`
--

LOCK TABLES `root` WRITE;
/*!40000 ALTER TABLE `root` DISABLE KEYS */;
INSERT INTO `root` VALUES ('zhanghui','8888');
/*!40000 ALTER TABLE `root` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rs`
--

DROP TABLE IF EXISTS `rs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rs` (
  `id` varchar(36) NOT NULL COMMENT 'rsitems的主键',
  `document_id` varchar(36) NOT NULL COMMENT 'rsitems所属文献',
  `tag` varchar(20) NOT NULL COMMENT '标签名称，添加索引',
  `description` text NOT NULL COMMENT '标签描述',
  `implement` varchar(10) NOT NULL COMMENT '手段',
  `priority` enum('High','Average','Low') NOT NULL COMMENT '优先级',
  `contribution` varchar(10) NOT NULL COMMENT '安全性',
  `category` varchar(20) NOT NULL COMMENT '类别',
  `allocation` varchar(200) NOT NULL COMMENT '分配对象',
  `vatstr_id` varchar(36) NOT NULL COMMENT '对应管理员分配的vat',
  `varstr_result` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`tag`),
  FULLTEXT KEY `title_2` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rs`
--

LOCK TABLES `rs` WRITE;
/*!40000 ALTER TABLE `rs` DISABLE KEYS */;
INSERT INTO `rs` VALUES ('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b','d6889236-ad21-11e4-aa9b-cf2d72b432dc','132313','3123','32','High','33','12','321','b85c2c4a-b01f-11e4-8ace-bdc5a2dd6e8c',0,'0000-00-00 00:00:00','2015-02-09 07:23:48'),('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b','d6889236-ad21-11e4-aa9b-cf2d72b432dc','[TSP-SyRS-0001]','Trackside safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n',' 1.1.0','',' SIL0',' Functional',' [TSP-SyAD]\r\n','bcd23d6e-b01f-11e4-8ace-bdc5a2dd6e8c',0,'2014-12-09 03:50:31','2015-02-10 04:57:49');
/*!40000 ALTER TABLE `rs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rs_tc`
--

DROP TABLE IF EXISTS `rs_tc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rs_tc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rs_id` int(11) NOT NULL,
  `tc_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rs_tc`
--

LOCK TABLES `rs_tc` WRITE;
/*!40000 ALTER TABLE `rs_tc` DISABLE KEYS */;
INSERT INTO `rs_tc` VALUES (1,1,1,'2014-12-15 00:00:00','2014-12-15 00:00:00'),(2,0,8,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,1,10,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,5,10,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,11,11,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,5,11,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,10,11,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,13,11,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,16,11,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,20,11,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,5,12,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,8,12,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(13,10,12,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(14,5,13,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(15,8,13,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(16,10,13,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(17,0,14,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(18,14,15,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(19,13,15,'0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `rs_tc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rs_vat`
--

DROP TABLE IF EXISTS `rs_vat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rs_vat` (
  `rs_id` varchar(36) NOT NULL,
  `vat_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rs_vat`
--

LOCK TABLES `rs_vat` WRITE;
/*!40000 ALTER TABLE `rs_vat` DISABLE KEYS */;
INSERT INTO `rs_vat` VALUES ('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b','fd7bdad4-c56c-410c-95b6-910e53f1dd6b'),('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b','399b97e7-776f-49f3-b4fe-ffc218f0ff55'),('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b','01e87349-d3b1-4985-b261-2de78719a825');
/*!40000 ALTER TABLE `rs_vat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `source`
--

DROP TABLE IF EXISTS `source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `source` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'src的id',
  `item_id` int(11) NOT NULL COMMENT '所属item的id',
  `src_type` varchar(20) NOT NULL COMMENT '所属item的类别',
  `source` varchar(20) NOT NULL COMMENT '数据源',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `items_id` (`item_id`,`source`),
  KEY `src_type` (`src_type`),
  KEY `source_1` (`source`)
) ENGINE=MyISAM AUTO_INCREMENT=248 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `source`
--

LOCK TABLES `source` WRITE;
/*!40000 ALTER TABLE `source` DISABLE KEYS */;
INSERT INTO `source` VALUES (1,1,'sadwqesdqwewq','adwedqdxwq','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,5,'','TSP-SyUR-0001','2014-12-08 08:21:21','2014-12-08 08:21:21'),(3,6,'','TSP-SyUR-0004','2014-12-08 08:21:21','2014-12-08 08:21:21'),(4,7,'','TSP-SyUR-0001','2014-12-08 08:26:33','2014-12-08 08:26:33'),(5,8,'','TSP-SyUR-0004','2014-12-08 08:26:33','2014-12-08 08:26:33'),(6,9,'','TSP-SyUR-0001','2014-12-08 08:27:52','2014-12-08 08:27:52'),(7,10,'','TSP-SyUR-0004','2014-12-08 08:27:52','2014-12-08 08:27:52'),(8,11,'','TSP-SyUR-0001','2014-12-08 08:30:19','2014-12-08 08:30:19'),(9,12,'','TSP-SyUR-0004','2014-12-08 08:30:19','2014-12-08 08:30:19'),(10,16,'','TSP-SyUR-0001','2014-12-08 08:50:48','2014-12-08 08:50:48'),(11,17,'','TSP-SyUR-0004','2014-12-08 08:50:48','2014-12-08 08:50:48'),(12,18,'','TSP-SyUR-0001','2014-12-08 08:52:54','2014-12-08 08:52:54'),(13,19,'','TSP-SyUR-0004','2014-12-08 08:52:54','2014-12-08 08:52:54'),(14,20,'','TSP-SyUR-0001','2014-12-08 08:56:42','2014-12-08 08:56:42'),(15,21,'','TSP-SyUR-0004','2014-12-08 08:56:42','2014-12-08 08:56:42'),(16,22,'','TSP-SyUR-0001','2014-12-08 10:20:39','2014-12-08 10:20:39'),(17,23,'','TSP-SyUR-0004','2014-12-08 10:20:39','2014-12-08 10:20:39'),(18,24,'','TSP-RAMSRs-0010','2014-12-08 10:20:39','2014-12-08 10:20:39'),(19,25,'','TSP-SyUR-0032','2014-12-08 10:20:39','2014-12-08 10:20:39'),(20,25,'','TSP-SyUR-0038','2014-12-08 10:20:39','2014-12-08 10:20:39'),(21,25,'','TSP-SyUR-0101','2014-12-08 10:20:39','2014-12-08 10:20:39'),(22,25,'','TSP-SyUR-0048','2014-12-08 10:20:39','2014-12-08 10:20:39'),(23,25,'','TSP-SyUR-0117','2014-12-08 10:20:39','2014-12-08 10:20:39'),(24,25,'','TSP-SyUR-0001','2014-12-08 10:20:39','2014-12-08 10:20:39'),(25,26,'','TSP-SyUR-0032','2014-12-08 10:20:39','2014-12-08 10:20:39'),(26,27,'','TSP-SyUR-0004','2014-12-08 10:20:39','2014-12-08 10:20:39'),(27,28,'','TSP-SyUR-0115','2014-12-08 10:20:39','2014-12-08 10:20:39'),(28,28,'','TSP-SyPHA-0023','2014-12-08 10:20:39','2014-12-08 10:20:39'),(29,29,'','TSP-SyPHA-0007','2014-12-08 10:20:39','2014-12-08 10:20:39'),(30,29,'','TSP-SyPHA-0008','2014-12-08 10:20:39','2014-12-08 10:20:39'),(31,30,'','TSP-SyUR-0001','2014-12-08 10:20:39','2014-12-08 10:20:39'),(32,31,'','TSP-SyPHA-0021','2014-12-08 10:20:39','2014-12-08 10:20:39'),(33,32,'','TSP-SyUR-0004','2014-12-08 10:20:39','2014-12-08 10:20:39'),(34,33,'','TSP-SyUR-0049','2014-12-08 10:20:39','2014-12-08 10:20:39'),(35,33,'','TSP-SyPHA-0015','2014-12-08 10:20:40','2014-12-08 10:20:40'),(36,33,'','TSP-SyPHA-0017','2014-12-08 10:20:40','2014-12-08 10:20:40'),(37,34,'','TSP-SyUR-0021','2014-12-08 10:20:40','2014-12-08 10:20:40'),(38,35,'','TSP-SyUR-0019','2014-12-08 10:20:40','2014-12-08 10:20:40'),(39,36,'','TSP-SyUR-0003','2014-12-08 10:20:40','2014-12-08 10:20:40'),(40,37,'','TSP-SyPHA-0027','2014-12-08 10:20:40','2014-12-08 10:20:40'),(41,38,'','TSP-SyUR-0037','2014-12-08 10:20:40','2014-12-08 10:20:40'),(42,38,'','TSP-SyUR-0101','2014-12-08 10:20:40','2014-12-08 10:20:40'),(43,39,'','TSP-SyUR-0037','2014-12-08 10:20:40','2014-12-08 10:20:40'),(44,40,'','TSP-SyUR-0101','2014-12-08 10:20:40','2014-12-08 10:20:40'),(45,41,'','TSP-SyUR-0097','2014-12-08 10:20:40','2014-12-08 10:20:40'),(46,42,'','TSP-SyUR-0032','2014-12-08 10:20:40','2014-12-08 10:20:40'),(47,43,'','TSP-SyUR-0032','2014-12-08 10:20:40','2014-12-08 10:20:40'),(48,44,'','TSP-SyUR-0032','2014-12-08 10:20:40','2014-12-08 10:20:40'),(49,45,'','TSP-SyPHA-0028','2014-12-08 10:20:40','2014-12-08 10:20:40'),(50,45,'','TSP-SyPHA-0026','2014-12-08 10:20:40','2014-12-08 10:20:40'),(51,46,'','TSP-SyPHA-0029','2014-12-08 10:20:40','2014-12-08 10:20:40'),(52,47,'','TSP-SyPHA-0025','2014-12-08 10:20:40','2014-12-08 10:20:40'),(53,48,'','TSP-SyUR-0038','2014-12-08 10:20:40','2014-12-08 10:20:40'),(54,48,'','TSP-SyUR-0054','2014-12-08 10:20:40','2014-12-08 10:20:40'),(55,49,'','TSP-SyUR-0054','2014-12-08 10:20:40','2014-12-08 10:20:40'),(56,50,'','TSP-SyUR-0038','2014-12-08 10:20:41','2014-12-08 10:20:41'),(57,51,'','TSP-SyUR-0023','2014-12-08 10:20:41','2014-12-08 10:20:41'),(58,52,'','TSP-SyPHA-0003','2014-12-08 10:20:41','2014-12-08 10:20:41'),(59,53,'','TSP-SyPHA-0001','2014-12-08 10:20:41','2014-12-08 10:20:41'),(60,53,'','TSP-SyPHA-0002','2014-12-08 10:20:41','2014-12-08 10:20:41'),(61,53,'','TSP-SyPHA-0003','2014-12-08 10:20:41','2014-12-08 10:20:41'),(62,53,'','TSP-SyPHA-0004','2014-12-08 10:20:41','2014-12-08 10:20:41'),(63,53,'','TSP-SyPHA-0005','2014-12-08 10:20:41','2014-12-08 10:20:41'),(64,54,'','TSP-SyUR-0023','2014-12-08 10:20:41','2014-12-08 10:20:41'),(65,55,'','TSP-SyUR-0115','2014-12-08 10:20:41','2014-12-08 10:20:41'),(66,56,'','TSP-SyUR-0054','2014-12-08 10:20:41','2014-12-08 10:20:41'),(67,57,'','TSP-SyUR-0054','2014-12-08 10:20:41','2014-12-08 10:20:41'),(68,57,'','TSP-SyUR-0115','2014-12-08 10:20:41','2014-12-08 10:20:41'),(69,57,'','TSP-RAMSRs-0016','2014-12-08 10:20:41','2014-12-08 10:20:41'),(70,57,'','TSP-RAMSRs-0019','2014-12-08 10:20:41','2014-12-08 10:20:41'),(71,58,'','TSP-SyUR-0115','2014-12-08 10:20:41','2014-12-08 10:20:41'),(72,59,'','TSP-SyUR-0115','2014-12-08 10:20:41','2014-12-08 10:20:41'),(73,60,'','TSP-SyUR-0115','2014-12-08 10:20:41','2014-12-08 10:20:41'),(74,61,'','TSP-SyUR-0117','2014-12-08 10:20:41','2014-12-08 10:20:41'),(75,62,'','TSP-RAMSRs-0010','2014-12-08 10:20:41','2014-12-08 10:20:41'),(76,63,'','TSP-SyUR-0070','2014-12-08 10:20:41','2014-12-08 10:20:41'),(77,64,'','TSP-RAMSRs-0015','2014-12-08 10:20:41','2014-12-08 10:20:41'),(78,65,'','TSP-SyUR-0106','2014-12-08 10:20:41','2014-12-08 10:20:41'),(79,66,'','TSP-SyUR-0019','2014-12-08 10:20:41','2014-12-08 10:20:41'),(80,66,'','TSP-SyUR-0104','2014-12-08 10:20:41','2014-12-08 10:20:41'),(81,66,'','TSP-SyPHA-0010','2014-12-08 10:20:42','2014-12-08 10:20:42'),(82,66,'','TSP-RAMSRs-0007','2014-12-08 10:20:42','2014-12-08 10:20:42'),(83,67,'','TSP-SyPHA-0030','2014-12-08 10:20:42','2014-12-08 10:20:42'),(84,67,'','TSP-RAMSRs-0010','2014-12-08 10:20:42','2014-12-08 10:20:42'),(85,68,'','TSP-SyUR-0100','2014-12-08 10:20:42','2014-12-08 10:20:42'),(86,69,'','TSP-RAMSRs-0010','2014-12-08 10:20:42','2014-12-08 10:20:42'),(87,70,'','TSP-SyUR-0003','2014-12-08 10:20:42','2014-12-08 10:20:42'),(88,71,'','TSP-SyUR-0001','2014-12-09 03:39:43','2014-12-09 03:39:43'),(89,72,'','TSP-SyUR-0004','2014-12-09 03:39:43','2014-12-09 03:39:43'),(90,73,'','TSP-RAMSRs-0010','2014-12-09 03:39:43','2014-12-09 03:39:43'),(91,74,'','TSP-SyUR-0032','2014-12-09 03:39:43','2014-12-09 03:39:43'),(92,74,'','TSP-SyUR-0038','2014-12-09 03:39:43','2014-12-09 03:39:43'),(93,74,'','TSP-SyUR-0101','2014-12-09 03:39:43','2014-12-09 03:39:43'),(94,74,'','TSP-SyUR-0048','2014-12-09 03:39:43','2014-12-09 03:39:43'),(95,74,'','TSP-SyUR-0117','2014-12-09 03:39:43','2014-12-09 03:39:43'),(96,74,'','TSP-SyUR-0001','2014-12-09 03:39:43','2014-12-09 03:39:43'),(97,75,'','TSP-SyUR-0032','2014-12-09 03:39:43','2014-12-09 03:39:43'),(98,76,'','TSP-SyUR-0004','2014-12-09 03:39:43','2014-12-09 03:39:43'),(99,77,'','TSP-SyUR-0115','2014-12-09 03:39:43','2014-12-09 03:39:43'),(100,77,'','TSP-SyPHA-0023','2014-12-09 03:39:43','2014-12-09 03:39:43'),(101,78,'','TSP-SyPHA-0007','2014-12-09 03:39:43','2014-12-09 03:39:43'),(102,78,'','TSP-SyPHA-0008','2014-12-09 03:39:43','2014-12-09 03:39:43'),(103,79,'','TSP-SyUR-0001','2014-12-09 03:39:43','2014-12-09 03:39:43'),(104,80,'','TSP-SyPHA-0021','2014-12-09 03:39:43','2014-12-09 03:39:43'),(105,81,'','TSP-SyUR-0004','2014-12-09 03:39:43','2014-12-09 03:39:43'),(106,82,'','TSP-SyUR-0049','2014-12-09 03:39:43','2014-12-09 03:39:43'),(107,82,'','TSP-SyPHA-0015','2014-12-09 03:39:43','2014-12-09 03:39:43'),(108,82,'','TSP-SyPHA-0017','2014-12-09 03:39:43','2014-12-09 03:39:43'),(109,83,'','TSP-SyUR-0021','2014-12-09 03:39:43','2014-12-09 03:39:43'),(110,84,'','TSP-SyUR-0019','2014-12-09 03:39:43','2014-12-09 03:39:43'),(111,85,'','TSP-SyUR-0003','2014-12-09 03:39:43','2014-12-09 03:39:43'),(112,86,'','TSP-SyPHA-0027','2014-12-09 03:39:43','2014-12-09 03:39:43'),(113,87,'','TSP-SyUR-0037','2014-12-09 03:39:43','2014-12-09 03:39:43'),(114,87,'','TSP-SyUR-0101','2014-12-09 03:39:43','2014-12-09 03:39:43'),(115,88,'','TSP-SyUR-0037','2014-12-09 03:39:43','2014-12-09 03:39:43'),(116,89,'','TSP-SyUR-0101','2014-12-09 03:39:43','2014-12-09 03:39:43'),(117,90,'','TSP-SyUR-0097','2014-12-09 03:39:43','2014-12-09 03:39:43'),(118,91,'','TSP-SyUR-0032','2014-12-09 03:39:43','2014-12-09 03:39:43'),(119,92,'','TSP-SyUR-0032','2014-12-09 03:39:43','2014-12-09 03:39:43'),(120,93,'','TSP-SyUR-0032','2014-12-09 03:39:43','2014-12-09 03:39:43'),(121,94,'','TSP-SyPHA-0028','2014-12-09 03:39:43','2014-12-09 03:39:43'),(122,94,'','TSP-SyPHA-0026','2014-12-09 03:39:43','2014-12-09 03:39:43'),(123,95,'','TSP-SyPHA-0029','2014-12-09 03:39:43','2014-12-09 03:39:43'),(124,96,'','TSP-SyPHA-0025','2014-12-09 03:39:43','2014-12-09 03:39:43'),(125,97,'','TSP-SyUR-0038','2014-12-09 03:39:43','2014-12-09 03:39:43'),(126,97,'','TSP-SyUR-0054','2014-12-09 03:39:43','2014-12-09 03:39:43'),(127,98,'','TSP-SyUR-0054','2014-12-09 03:39:43','2014-12-09 03:39:43'),(128,99,'','TSP-SyUR-0038','2014-12-09 03:39:43','2014-12-09 03:39:43'),(129,100,'','TSP-SyUR-0023','2014-12-09 03:39:43','2014-12-09 03:39:43'),(130,101,'','TSP-SyPHA-0003','2014-12-09 03:39:43','2014-12-09 03:39:43'),(131,102,'','TSP-SyPHA-0001','2014-12-09 03:39:43','2014-12-09 03:39:43'),(132,102,'','TSP-SyPHA-0002','2014-12-09 03:39:43','2014-12-09 03:39:43'),(133,102,'','TSP-SyPHA-0003','2014-12-09 03:39:43','2014-12-09 03:39:43'),(134,102,'','TSP-SyPHA-0004','2014-12-09 03:39:43','2014-12-09 03:39:43'),(135,102,'','TSP-SyPHA-0005','2014-12-09 03:39:43','2014-12-09 03:39:43'),(136,103,'','TSP-SyUR-0023','2014-12-09 03:39:43','2014-12-09 03:39:43'),(137,104,'','TSP-SyUR-0115','2014-12-09 03:39:43','2014-12-09 03:39:43'),(138,105,'','TSP-SyUR-0054','2014-12-09 03:39:43','2014-12-09 03:39:43'),(139,106,'','TSP-SyUR-0054','2014-12-09 03:39:43','2014-12-09 03:39:43'),(140,106,'','TSP-SyUR-0115','2014-12-09 03:39:43','2014-12-09 03:39:43'),(141,106,'','TSP-RAMSRs-0016','2014-12-09 03:39:43','2014-12-09 03:39:43'),(142,106,'','TSP-RAMSRs-0019','2014-12-09 03:39:43','2014-12-09 03:39:43'),(143,107,'','TSP-SyUR-0115','2014-12-09 03:39:43','2014-12-09 03:39:43'),(144,108,'','TSP-SyUR-0115','2014-12-09 03:39:43','2014-12-09 03:39:43'),(145,109,'','TSP-SyUR-0115','2014-12-09 03:39:43','2014-12-09 03:39:43'),(146,110,'','TSP-SyUR-0117','2014-12-09 03:39:43','2014-12-09 03:39:43'),(147,111,'','TSP-RAMSRs-0010','2014-12-09 03:39:43','2014-12-09 03:39:43'),(148,112,'','TSP-SyUR-0070','2014-12-09 03:39:43','2014-12-09 03:39:43'),(149,113,'','TSP-RAMSRs-0015','2014-12-09 03:39:43','2014-12-09 03:39:43'),(150,114,'','TSP-SyUR-0106','2014-12-09 03:39:43','2014-12-09 03:39:43'),(151,115,'','TSP-SyUR-0019','2014-12-09 03:39:43','2014-12-09 03:39:43'),(152,115,'','TSP-SyUR-0104','2014-12-09 03:39:43','2014-12-09 03:39:43'),(153,115,'','TSP-SyPHA-0010','2014-12-09 03:39:43','2014-12-09 03:39:43'),(154,115,'','TSP-RAMSRs-0007','2014-12-09 03:39:43','2014-12-09 03:39:43'),(155,116,'','TSP-SyPHA-0030','2014-12-09 03:39:43','2014-12-09 03:39:43'),(156,116,'','TSP-RAMSRs-0010','2014-12-09 03:39:43','2014-12-09 03:39:43'),(157,117,'','TSP-SyUR-0100','2014-12-09 03:39:43','2014-12-09 03:39:43'),(158,118,'','TSP-RAMSRs-0010','2014-12-09 03:39:43','2014-12-09 03:39:43'),(159,119,'','TSP-SyUR-0003','2014-12-09 03:39:43','2014-12-09 03:39:43'),(160,120,'','TSP-SyUR-0001','2014-12-09 03:41:35','2014-12-09 03:41:35'),(161,121,'','TSP-SyUR-0004','2014-12-09 03:41:35','2014-12-09 03:41:35'),(162,122,'','TSP-SyUR-0001','2014-12-09 03:43:02','2014-12-09 03:43:02'),(163,123,'','TSP-SyUR-0004','2014-12-09 03:43:02','2014-12-09 03:43:02'),(164,1,'','TSP-SyUR-0001','2014-12-09 03:44:14','2014-12-09 03:44:14'),(165,2,'','TSP-SyUR-0004','2014-12-09 03:44:14','2014-12-09 03:44:14'),(166,3,'','TSP-SyUR-0001','2014-12-09 03:45:11','2014-12-09 03:45:11'),(167,4,'','TSP-SyUR-0004','2014-12-09 03:45:11','2014-12-09 03:45:11'),(168,5,'','TSP-SyUR-0001','2014-12-09 03:45:35','2014-12-09 03:45:35'),(169,6,'','TSP-SyUR-0004','2014-12-09 03:45:35','2014-12-09 03:45:35'),(170,7,'','TSP-SyUR-0001','2014-12-09 03:47:45','2014-12-09 03:47:45'),(171,8,'','TSP-SyUR-0004','2014-12-09 03:47:45','2014-12-09 03:47:45'),(172,9,'','TSP-SyUR-0001','2014-12-09 03:49:34','2014-12-09 03:49:34'),(173,10,'','TSP-SyUR-0004','2014-12-09 03:49:34','2014-12-09 03:49:34'),(174,1,'','TSP-SyUR-0001','2014-12-09 03:50:31','2014-12-09 03:50:31'),(175,2,'','TSP-SyUR-0004','2014-12-09 03:50:31','2014-12-09 03:50:31'),(176,3,'','TSP-SyUR-0001','2014-12-09 06:27:47','2014-12-09 06:27:47'),(177,4,'','TSP-SyUR-0004','2014-12-09 06:27:47','2014-12-09 06:27:47'),(178,5,'','TSP-RAMSRs-0010','2014-12-09 06:27:47','2014-12-09 06:27:47'),(179,6,'','TSP-SyUR-0032','2014-12-09 06:27:47','2014-12-09 06:27:47'),(180,6,'','TSP-SyUR-0038','2014-12-09 06:27:47','2014-12-09 06:27:47'),(181,6,'','TSP-SyUR-0101','2014-12-09 06:27:47','2014-12-09 06:27:47'),(182,6,'','TSP-SyUR-0048','2014-12-09 06:27:47','2014-12-09 06:27:47'),(183,6,'','TSP-SyUR-0117','2014-12-09 06:27:47','2014-12-09 06:27:47'),(184,6,'','TSP-SyUR-0001','2014-12-09 06:27:47','2014-12-09 06:27:47'),(185,7,'','TSP-SyUR-0032','2014-12-09 06:27:47','2014-12-09 06:27:47'),(186,8,'','TSP-SyUR-0004','2014-12-09 06:27:47','2014-12-09 06:27:47'),(187,9,'','TSP-SyUR-0115','2014-12-09 06:27:47','2014-12-09 06:27:47'),(188,9,'','TSP-SyPHA-0023','2014-12-09 06:27:47','2014-12-09 06:27:47'),(189,10,'','TSP-SyPHA-0007','2014-12-09 06:27:47','2014-12-09 06:27:47'),(190,10,'','TSP-SyPHA-0008','2014-12-09 06:27:47','2014-12-09 06:27:47'),(191,11,'','TSP-SyUR-0001','2014-12-09 06:27:47','2014-12-09 06:27:47'),(192,12,'','TSP-SyPHA-0021','2014-12-09 06:27:47','2014-12-09 06:27:47'),(193,13,'','TSP-SyUR-0004','2014-12-09 06:27:47','2014-12-09 06:27:47'),(194,14,'','TSP-SyUR-0049','2014-12-09 06:27:47','2014-12-09 06:27:47'),(195,14,'','TSP-SyPHA-0015','2014-12-09 06:27:47','2014-12-09 06:27:47'),(196,14,'','TSP-SyPHA-0017','2014-12-09 06:27:47','2014-12-09 06:27:47'),(197,15,'','TSP-SyUR-0021','2014-12-09 06:27:47','2014-12-09 06:27:47'),(198,16,'','TSP-SyUR-0019','2014-12-09 06:27:47','2014-12-09 06:27:47'),(199,17,'','TSP-SyUR-0003','2014-12-09 06:27:47','2014-12-09 06:27:47'),(200,18,'','TSP-SyPHA-0027','2014-12-09 06:27:47','2014-12-09 06:27:47'),(201,19,'','TSP-SyUR-0037','2014-12-09 06:27:47','2014-12-09 06:27:47'),(202,19,'','TSP-SyUR-0101','2014-12-09 06:27:47','2014-12-09 06:27:47'),(203,20,'','TSP-SyUR-0037','2014-12-09 06:27:47','2014-12-09 06:27:47'),(204,21,'','TSP-SyUR-0101','2014-12-09 06:27:47','2014-12-09 06:27:47'),(205,22,'','TSP-SyUR-0097','2014-12-09 06:27:47','2014-12-09 06:27:47'),(206,23,'','TSP-SyUR-0032','2014-12-09 06:27:47','2014-12-09 06:27:47'),(207,24,'','TSP-SyUR-0032','2014-12-09 06:27:47','2014-12-09 06:27:47'),(208,25,'','TSP-SyUR-0032','2014-12-09 06:27:47','2014-12-09 06:27:47'),(209,26,'','TSP-SyPHA-0028','2014-12-09 06:27:47','2014-12-09 06:27:47'),(210,26,'','TSP-SyPHA-0026','2014-12-09 06:27:47','2014-12-09 06:27:47'),(211,27,'','TSP-SyPHA-0029','2014-12-09 06:27:47','2014-12-09 06:27:47'),(212,28,'','TSP-SyPHA-0025','2014-12-09 06:27:47','2014-12-09 06:27:47'),(213,29,'','TSP-SyUR-0038','2014-12-09 06:27:47','2014-12-09 06:27:47'),(214,29,'','TSP-SyUR-0054','2014-12-09 06:27:47','2014-12-09 06:27:47'),(215,30,'','TSP-SyUR-0054','2014-12-09 06:27:47','2014-12-09 06:27:47'),(216,31,'','TSP-SyUR-0038','2014-12-09 06:27:47','2014-12-09 06:27:47'),(217,32,'','TSP-SyUR-0023','2014-12-09 06:27:47','2014-12-09 06:27:47'),(218,33,'','TSP-SyPHA-0003','2014-12-09 06:27:47','2014-12-09 06:27:47'),(219,34,'','TSP-SyPHA-0001','2014-12-09 06:27:47','2014-12-09 06:27:47'),(220,34,'','TSP-SyPHA-0002','2014-12-09 06:27:47','2014-12-09 06:27:47'),(221,34,'','TSP-SyPHA-0003','2014-12-09 06:27:47','2014-12-09 06:27:47'),(222,34,'','TSP-SyPHA-0004','2014-12-09 06:27:47','2014-12-09 06:27:47'),(223,34,'','TSP-SyPHA-0005','2014-12-09 06:27:47','2014-12-09 06:27:47'),(224,35,'','TSP-SyUR-0023','2014-12-09 06:27:47','2014-12-09 06:27:47'),(225,36,'','TSP-SyUR-0115','2014-12-09 06:27:47','2014-12-09 06:27:47'),(226,37,'','TSP-SyUR-0054','2014-12-09 06:27:47','2014-12-09 06:27:47'),(227,38,'','TSP-SyUR-0054','2014-12-09 06:27:47','2014-12-09 06:27:47'),(228,38,'','TSP-SyUR-0115','2014-12-09 06:27:47','2014-12-09 06:27:47'),(229,38,'','TSP-RAMSRs-0016','2014-12-09 06:27:47','2014-12-09 06:27:47'),(230,38,'','TSP-RAMSRs-0019','2014-12-09 06:27:47','2014-12-09 06:27:47'),(231,39,'','TSP-SyUR-0115','2014-12-09 06:27:47','2014-12-09 06:27:47'),(232,40,'','TSP-SyUR-0115','2014-12-09 06:27:47','2014-12-09 06:27:47'),(233,41,'','TSP-SyUR-0115','2014-12-09 06:27:47','2014-12-09 06:27:47'),(234,42,'','TSP-SyUR-0117','2014-12-09 06:27:47','2014-12-09 06:27:47'),(235,43,'','TSP-RAMSRs-0010','2014-12-09 06:27:47','2014-12-09 06:27:47'),(236,44,'','TSP-SyUR-0070','2014-12-09 06:27:47','2014-12-09 06:27:47'),(237,45,'','TSP-RAMSRs-0015','2014-12-09 06:27:47','2014-12-09 06:27:47'),(238,46,'','TSP-SyUR-0106','2014-12-09 06:27:47','2014-12-09 06:27:47'),(239,47,'','TSP-SyUR-0019','2014-12-09 06:27:47','2014-12-09 06:27:47'),(240,47,'','TSP-SyUR-0104','2014-12-09 06:27:47','2014-12-09 06:27:47'),(241,47,'','TSP-SyPHA-0010','2014-12-09 06:27:47','2014-12-09 06:27:47'),(242,47,'','TSP-RAMSRs-0007','2014-12-09 06:27:47','2014-12-09 06:27:47'),(243,48,'','TSP-SyPHA-0030','2014-12-09 06:27:47','2014-12-09 06:27:47'),(244,48,'','TSP-RAMSRs-0010','2014-12-09 06:27:47','2014-12-09 06:27:47'),(245,49,'','TSP-SyUR-0100','2014-12-09 06:27:47','2014-12-09 06:27:47'),(246,50,'','TSP-RAMSRs-0010','2014-12-09 06:27:48','2014-12-09 06:27:48'),(247,51,'','TSP-SyUR-0003','2014-12-09 06:27:48','2014-12-09 06:27:48');
/*!40000 ALTER TABLE `source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!50001 DROP VIEW IF EXISTS `tag`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `tag` AS SELECT 
 1 AS `id`,
 1 AS `tag`,
 1 AS `document_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tc`
--

DROP TABLE IF EXISTS `tc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc` (
  `id` varchar(36) NOT NULL COMMENT '单个tc标签id',
  `document_id` varchar(36) NOT NULL COMMENT 'tc所属文献id',
  `tag` varchar(20) NOT NULL COMMENT 'tc名称',
  `description` text NOT NULL COMMENT 'tc描述',
  `test_method` varchar(10) NOT NULL COMMENT '测试方法',
  `pre_condition` text NOT NULL COMMENT '前提条件',
  `result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '结果，0未测试，1表示成功，2表示失败',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_id` (`document_id`),
  KEY `title` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tc`
--

LOCK TABLES `tc` WRITE;
/*!40000 ALTER TABLE `tc` DISABLE KEYS */;
INSERT INTO `tc` VALUES ('399b97e7-776f-49f3-b4fe-ffc218f0ff55','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','[TSP-SyRTC-0117]','Check TSP shall be a hot-redundant 2×2oo2 system.','EP','APP installed on A MPU1 and A MPU2 is the same as B MPU1 and B MPU2. ',0,'2015-01-28 08:22:08','2015-02-02 04:12:38'),('fd7bdad4-c56c-410c-95b6-910e53f1dd6b','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','[TSP-SyRTC-0117]','Check TSP shall be a hot-redundant 2×2oo2 system.','EP','APP installed on A MPU1 and A MPU2 is the same as B MPU1 and B MPU2. ',1,'2015-01-28 08:23:47','2015-02-05 06:37:29'),('01e87349-d3b1-4985-b261-2de78719a825','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','1','1','EP','1',1,'2015-01-29 05:55:57','2015-02-03 08:59:43'),('2e47eb3e-3582-49f8-9cf0-40a554841e7d','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','2','2','EP','2',0,'2015-01-29 05:58:34','2015-01-29 05:58:34'),('87e9f18a-9801-4353-99c8-66090109f093','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','1','1','EG','1',0,'2015-01-29 08:37:51','2015-02-02 03:35:34'),('7276c180-fd88-4d36-a9b6-724ea6efbd28','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','1','1','EP','1',0,'2015-01-30 05:37:24','2015-01-30 05:37:24'),('996417bd-d5d9-4d8d-9e72-8283feb39a8f','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','1','1','EP','1',0,'2015-01-30 05:40:31','2015-01-30 05:40:31'),('bf9839fe-31e2-495a-bf51-116ad2364c64','e1c83444-ad21-11e4-aa9b-cf2d72b432dc','13333245','1','EP','132',2,'2015-01-30 06:04:40','2015-02-03 08:40:29');
/*!40000 ALTER TABLE `tc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tc_source`
--

DROP TABLE IF EXISTS `tc_source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_source` (
  `tc_id` varchar(36) NOT NULL,
  `source_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tc_source`
--

LOCK TABLES `tc_source` WRITE;
/*!40000 ALTER TABLE `tc_source` DISABLE KEYS */;
INSERT INTO `tc_source` VALUES ('2e47eb3e-3582-49f8-9cf0-40a554841e7d','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('',''),('89cddcf6-eba7-42ce-a6f6-316dc51e9aa4','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('2bd7c67e-deb9-4ee6-8ded-321afe0ba38b','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('',''),('7276c180-fd88-4d36-a9b6-724ea6efbd28','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('87e9f18a-9801-4353-99c8-66090109f093','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('399b97e7-776f-49f3-b4fe-ffc218f0ff55','28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b'),('bf9839fe-31e2-495a-bf51-116ad2364c64','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('01e87349-d3b1-4985-b261-2de78719a825','28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b'),('996417bd-d5d9-4d8d-9e72-8283feb39a8f','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),('fd7bdad4-c56c-410c-95b6-910e53f1dd6b','28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b'),('fd7bdad4-c56c-410c-95b6-910e53f1dd6b','9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b');
/*!40000 ALTER TABLE `tc_source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tc_step`
--

DROP TABLE IF EXISTS `tc_step`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_step` (
  `tc_id` varchar(36) NOT NULL COMMENT '所属tc的id',
  `num` int(11) NOT NULL COMMENT '步骤数',
  `actions` text NOT NULL COMMENT '采取的行动',
  `expected_result` text NOT NULL COMMENT '所希望的现象',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  KEY `tc_id` (`tc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tc_step`
--

LOCK TABLES `tc_step` WRITE;
/*!40000 ALTER TABLE `tc_step` DISABLE KEYS */;
INSERT INTO `tc_step` VALUES ('87e9f18a-9801-4353-99c8-66090109f093',3,'4','4','2015-02-02 03:35:34','2015-02-02 03:35:34'),('87e9f18a-9801-4353-99c8-66090109f093',2,'3','3','2015-02-02 03:35:34','2015-02-02 03:35:34'),('87e9f18a-9801-4353-99c8-66090109f093',1,'1','1','2015-02-02 03:35:34','2015-02-02 03:35:34'),('7276c180-fd88-4d36-a9b6-724ea6efbd28',1,'1','1','2015-01-30 05:37:24','2015-01-30 05:37:24'),('7276c180-fd88-4d36-a9b6-724ea6efbd28',2,'3','3','2015-01-30 05:37:24','2015-01-30 05:37:24'),('7276c180-fd88-4d36-a9b6-724ea6efbd28',3,'4','4','2015-01-30 05:37:24','2015-01-30 05:37:24'),('996417bd-d5d9-4d8d-9e72-8283feb39a8f',3,'4','4','2015-02-04 03:43:32','2015-02-04 03:43:32'),('996417bd-d5d9-4d8d-9e72-8283feb39a8f',2,'3','3','2015-02-04 03:43:32','2015-02-04 03:43:32'),('996417bd-d5d9-4d8d-9e72-8283feb39a8f',1,'1','1','2015-02-04 03:43:32','2015-02-04 03:43:32'),('01e87349-d3b1-4985-b261-2de78719a825',1,'1','11','2015-02-03 08:59:43','2015-02-03 08:59:43'),('bf9839fe-31e2-495a-bf51-116ad2364c64',2,'33','22','2015-02-03 08:40:29','2015-02-03 08:40:29'),('fd7bdad4-c56c-410c-95b6-910e53f1dd6b',1,'4','4','2015-02-05 06:37:29','2015-02-05 06:37:29'),('399b97e7-776f-49f3-b4fe-ffc218f0ff55',1,'2','3','2015-02-02 04:12:38','2015-02-02 04:12:38'),('399b97e7-776f-49f3-b4fe-ffc218f0ff55',2,'3','3323','2015-02-02 04:12:38','2015-02-02 04:12:38'),('bf9839fe-31e2-495a-bf51-116ad2364c64',1,'3','3','2015-02-03 08:40:29','2015-02-03 08:40:29'),('399b97e7-776f-49f3-b4fe-ffc218f0ff55',3,'123','3213微软','2015-02-02 04:12:38','2015-02-02 04:12:38');
/*!40000 ALTER TABLE `tc_step` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` varchar(36) NOT NULL,
  `jobnumber` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `account` varchar(30) NOT NULL,
  `realname` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('a7b12e32-b0f5-11e4-abb7-c17404b78885','123','202cb962ac59075b964b07152d234b70','guodong','郭栋','2015-02-10 00:00:00','2015-02-10 00:00:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vatstr`
--

DROP TABLE IF EXISTS `vatstr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vatstr` (
  `name` varchar(30) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vatstr`
--

LOCK TABLES `vatstr` WRITE;
/*!40000 ALTER TABLE `vatstr` DISABLE KEYS */;
INSERT INTO `vatstr` VALUES ('5','acb9a4bb-4a3e-401f-9243-23de4aac8918','2015-02-10 10:11:05','2015-02-10 10:11:05'),('vat a','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),('vat b','1','0000-00-00 00:00:00','0000-00-00 00:00:00'),('6','d4a36d2e-860d-4426-82f7-7fe7dad1def7','2015-02-10 10:12:47','2015-02-10 10:12:47');
/*!40000 ALTER TABLE `vatstr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `tag`
--

/*!50001 DROP VIEW IF EXISTS `tag`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `tag` AS select `rs`.`id` AS `id`,`rs`.`tag` AS `tag`,`rs`.`document_id` AS `document_id` from `rs` union select `tc`.`id` AS `id`,`tc`.`tag` AS `tag`,`tc`.`document_id` AS `document_id` from `tc` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-11 13:07:04
