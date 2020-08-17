-- MySQL dump 10.13  Distrib 5.7.23, for osx10.9 (x86_64)
--
-- Host: localhost    Database: jorgo
-- ------------------------------------------------------
-- Server version	5.7.23

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
-- Table structure for table `admin_menu`
--

DROP TABLE IF EXISTS `admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `css` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_groups`
--

DROP TABLE IF EXISTS `admin_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_groups`
--

LOCK TABLES `admin_user_groups` WRITE;
/*!40000 ALTER TABLE `admin_user_groups` DISABLE KEYS */;
INSERT INTO `admin_user_groups` VALUES (1,'Администраторы'),(2,'Менеджеры'),(3,'Партнеры'),(4,'Seo');
/*!40000 ALTER TABLE `admin_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `group_id` int(10) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group_id`),
  CONSTRAINT `admin_users_group_id` FOREIGN KEY (`group_id`) REFERENCES `admin_user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'root','','$2y$13$AncwFQCyPpr6ZOUNXLiZtuwdWHW1aQAGbCyrNaf3dIEqwq.nbE7le',NULL,'root@mail.com',1,1,1451743769,1519313199),(26,'admin','g1LUEu6tYtInMHr6KqKWavXbzThmuIZV','$2y$13$AncwFQCyPpr6ZOUNXLiZtuwdWHW1aQAGbCyrNaf3dIEqwq.nbE7le',NULL,'admin@mail.com',1,1,1527498789,1564402785),(27,'manager','RS83hLzMLCe34qZs8QcfcqU-vCz2gnZQ','$2y$13$Wz7VntGyYzJ.wtuHvu3HlORlBCZBp0.ZYiBP0q1Z5g6N71zeIPZ1y',NULL,'manager@mail.com',1,2,1527498807,1527498807),(28,'aziz','GDbWgjOZZOho4odazy2YvuthsP4yGK64','$2y$13$fhuxEHGAmmf2wWOBhpO5e.0tMlr4ZHAqQNTmdUDYwwg8lzZCIueRO',NULL,'azizismailov872872@gmail.com',1,1,1597581517,1597581517);
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','26',1527498861),('admin','28',1597581517),('manager','27',1527498880);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',1,'admin',NULL,NULL,1527498861,1527498861),('catalogCreate',2,'Create catalog item',NULL,NULL,1527499054,1527499054),('catalogDelete',2,'Delete catalog item',NULL,NULL,1527499097,1527499097),('catalogUpdate',2,'Update catalog item',NULL,NULL,1527499078,1527499078),('catalogView',2,'View catalog item',NULL,NULL,1527499040,1527499040),('manager',1,'manager',NULL,NULL,1527498880,1527498880);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('manager','catalogCreate'),('admin','catalogDelete'),('manager','catalogUpdate'),('manager','catalogView'),('admin','manager');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_modules`
--

DROP TABLE IF EXISTS `auth_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_modules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_modules`
--

LOCK TABLES `auth_modules` WRITE;
/*!40000 ALTER TABLE `auth_modules` DISABLE KEYS */;
INSERT INTO `auth_modules` VALUES (25,'catalog'),(30,'content');
/*!40000 ALTER TABLE `auth_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `content_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1,2,'О компании','<div class=\"section-in\">\r\n<h2>О компании</h2>\r\n\r\n<div class=\"text-wrap\">\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Жорго такси это динамично развивающая таксомоторная компания по перевозке пассажиров. Мы работаем с 2014 года, и за столь короткое время мы заслужили доверие горожан и гостей столицы. Отличается наша компания от других тем, что в автопарке у нас насчитывается с выше 2000 автомобилей и что не маловажно по приемлимым ценам доставят вас до нужного место и это ещё не все, мы представляем следующие скидкиЖ а именно, ветеранам Великой Отечественной Войны 100% скидку на проезд пожизненно, а также 50% скидку на проезд. Со дня независимости Кыргызской Республики это единственная компания, которая при обращении в наш call-center представляется на официальном кыргызском языке и предоставляет право выбора языка нашим клиентам.</p>\r\n</div>\r\n</div>\r\n','О компании','О компании','О компании',1,1564538175,1564538184),(3,3,'Контакты','<div class=\"section-in\">\r\n<h2>Контакты</h2>\r\n\r\n<div class=\"item-wrap\">\r\n<div class=\"item-adress left-label\">\r\n<h5>г. Бишкек</h5>\r\n\r\n<p>ул. Карасаева 14 / Белорусская</p>\r\n\r\n<p>jorgokg@gmail.com</p>\r\n</div>\r\n\r\n<div class=\"item-adress right-label\">\r\n<h5>г. Нарын</h5>\r\n\r\n<p>ул. Кулумбаева 131</p>\r\n\r\n<p>jorgo.naryn@gmail.com</p>\r\n</div>\r\n\r\n<div class=\"item-adress time24-7\">\r\n<p>Телефон для жалоб и предложений</p>\r\n\r\n<p>0 706 01 2022</p>\r\n</div>\r\n</div>\r\n</div>\r\n','Контакты','Контакты','Контакты',1,1564538276,1564538318),(4,1,'Тарифы','<div class=\"section-in border-line\">\r\n<h2>Тарифы:</h2>\r\n\r\n<div class=\"label-wrap\">\r\n<div class=\"label-item\">Стандарт</div>\r\n\r\n<div class=\"label-item\">Минивен</div>\r\n\r\n<div class=\"label-item\">Комфорт</div>\r\n\r\n<div class=\"label-item\">Трезвый водитель</div>\r\n\r\n<div class=\"label-item pre-last\">Аэропорт</div>\r\n\r\n<div class=\"label-item advancaed-label\">Доплаты и услуги</div>\r\n</div>\r\n<!-- Modal -->\r\n\r\n<div class=\"modal fade\" id=\"exampleModal\">\r\n<div class=\"modal-dialog\">\r\n<div class=\"modal-content\">\r\n<div class=\"modal-header\">\r\n<h5>&nbsp;</h5>\r\n\r\n<p>Тариф</p>\r\nСтандарт&times;</div>\r\n\r\n<div class=\"modal-body\">\r\n<ul>\r\n	<li>\r\n	<div class=\"param\">50 с Посадка</div>\r\n	</li>\r\n	<li>\r\n	<div class=\"param\">12 с КМ</div>\r\n	</li>\r\n</ul>\r\n\r\n<p>Количество людей</p>\r\n\r\n<ul>\r\n	<li>\r\n	<div class=\"param\">Без доплаты 4 человек</div>\r\n	</li>\r\n	<li>\r\n	<div class=\"param\">+50 с Последующий каждый человек</div>\r\n	</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"tarif-description\">\r\n<div class=\"ad-l-ban\"><img alt=\"\" src=\"upload/ad1.png\" /></div>\r\n\r\n<div class=\"circle-description\"><!-- 						<div class=\"top_part\">\r\n							<p>Посадка</p>\r\n							<p class=\"price\">\r\n								50 <span class=\"som\">c</span>\r\n							</p>\r\n						</div> --><img alt=\"\" src=\"upload/ad3.png\" /></div>\r\n\r\n<div class=\"ad-r-ban\"><img alt=\"\" src=\"upload/ad2.png\" /></div>\r\n</div>\r\n\r\n<div class=\"warning\">Все жилмасивы и новостройки\r\n<p>Без доплаты!</p>\r\n</div>\r\n</div>\r\n','Тарифы','Тарифы','Тарифы',1,1564538315,1564538320);
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL DEFAULT '0',
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `backoffice` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `order` tinyint(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,1,0,0,'Тарифы','prices',1,NULL),(2,1,0,0,'О компании','about',1,NULL),(3,1,0,0,'Контакты','contacts',1,NULL);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_categories`
--

LOCK TABLES `menu_categories` WRITE;
/*!40000 ALTER TABLE `menu_categories` DISABLE KEYS */;
INSERT INTO `menu_categories` VALUES (1,'top-menu');
/*!40000 ALTER TABLE `menu_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1535360928),('m130524_201442_init',1535360934);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `short_text` text NOT NULL,
  `text` text NOT NULL,
  `meta_title` varchar(100) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_user_id` (`author_id`),
  CONSTRAINT `news_user_id` FOREIGN KEY (`author_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rates`
--

DROP TABLE IF EXISTS `rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rates`
--

LOCK TABLES `rates` WRITE;
/*!40000 ALTER TABLE `rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `index` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'pager','admin-users','100'),(2,'pager','admin-permissions','25'),(3,'pager','admin-catalog','12'),(4,'pager','frontend-catalog','12'),(5,'pager','admin-order-items','2'),(6,'pager','history-orders','10'),(7,'pager','admin-orders','25'),(8,'pager','admin-news','10'),(9,'pager','frontend-news','10'),(10,'pager','admin-items-counter','25'),(11,'pager','admin-rates','10');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `static_content`
--

DROP TABLE IF EXISTS `static_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_content`
--

LOCK TABLES `static_content` WRITE;
/*!40000 ALTER TABLE `static_content` DISABLE KEYS */;
INSERT INTO `static_content` VALUES (1,'social-buttons','<ul>\r\n	<li>&nbsp;</li>\r\n	<li>&nbsp;</li>\r\n	<li>&nbsp;</li>\r\n	<li>&nbsp;</li>\r\n</ul>',1,1564535442,1564537220),(2,'bottom-years','<p>2014-2019</p>',1,1564535475,1564537222),(3,'index','<div class=\"section-in\">\r\n<div class=\"left-part\">\r\n<h2>Бишкек</h2>\r\n\r\n<div class=\"short-number\">2022</div>\r\n\r\n<ul>\r\n	<li>0 701 11 2022</li>\r\n	<li>0 705 77 2022</li>\r\n	<li>0 550 66 2022</li>\r\n	<li>0 773 88 2022</li>\r\n</ul>\r\n\r\n<div class=\"wapp\">\r\n<div class=\"wapp-title\">WhatsApp</div>\r\n\r\n<div class=\"wapp-number\">0776 11 2022</div>\r\n</div>\r\nЗаказать такси</div>\r\n\r\n<div class=\"middle-part\">\r\n<div class=\"link-wrap\">\r\n<div class=\"link\">\r\n<div class=\"link-android\"><a href=\"https://play.google.com/store/apps/details?id=ru.taximaster.tmtaxicaller.id2362&amp;pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1\" target=\"_blank\"><img alt=\"Доступно в Google Play\" src=\"/frontend/themes/jorgo/images/google.png\" style=\"height:80px; width:250px\" /></a></div>\r\n</div>\r\n\r\n<div class=\"link-text\">Скачай мобильное приложение для вызова такси</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"right-part\">\r\n<h2>Нарын</h2>\r\n\r\n<div class=\"short-number\">133</div>\r\n\r\n<ul>\r\n	<li>0 501 000 133</li>\r\n	<li>0 551 000 133</li>\r\n	<li>0 776 000 133</li>\r\n</ul>\r\n\r\n<div class=\"wapp\">\r\n<div class=\"wapp-title\">WhatsApp</div>\r\n\r\n<div class=\"wapp-number\">0 501 000 133</div>\r\n</div>\r\nЗаказать такси</div>\r\n\r\n<div class=\"mob-part\">\r\n<div class=\"link-wrap\" style=\"text-align:center;\">\r\n<div class=\"link\">\r\n<div class=\"link-android\"><a href=\"https://play.google.com/store/apps/details?id=ru.taximaster.tmtaxicaller.id2362&amp;pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1\"><img alt=\"Доступно в Google Play\" src=\"/frontend/themes/jorgo/images/google.png\" style=\"height:83px; width:250px\" /></a></div>\r\n\r\n<div class=\"link-apple\">&nbsp;</div>\r\n</div>\r\n\r\n<div class=\"link-text\" style=\"text-align: center;\">Скачай мобильное приложение для вызова такси</div>\r\n</div>\r\n</div>\r\n</div>',1,1564535540,1597583069);
/*!40000 ALTER TABLE `static_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','','$2y$13$OEySkvjE7ARLBGrj4HoO9./.n.ZVFbkezY1ZfIW9uGbobnxzObEDu',NULL,'root@mail.com',1,1451743769,1519313199),(2,'content','','$2y$13$OEySkvjE7ARLBGrj4HoO9./.n.ZVFbkezY1ZfIW9uGbobnxzObEDu',NULL,'content@mail.com',1,1451743466,1451743666),(3,'editor','','$2y$13$OEySkvjE7ARLBGrj4HoO9./.n.ZVFbkezY1ZfIW9uGbobnxzObEDu',NULL,'editor@mail.com',1,1451743676,1451743576);
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

-- Dump completed on 2020-08-17 20:25:02
