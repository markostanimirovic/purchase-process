/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.21-MariaDB : Database - purchase_process
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`purchase_process` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `purchase_process`;

/*Table structure for table `catalog` */

DROP TABLE IF EXISTS `catalog`;

CREATE TABLE `catalog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_fk` (`supplier_id`),
  CONSTRAINT `supplier_fk` FOREIGN KEY (`supplier_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Table structure for table `order_form` */

DROP TABLE IF EXISTS `order_form`;

CREATE TABLE `order_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `code` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `order_form_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `order_form_item` */

DROP TABLE IF EXISTS `order_form_item`;

CREATE TABLE `order_form_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `quantity` int(10) unsigned DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `order_form_id` int(10) unsigned DEFAULT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_form_id` (`order_form_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_form_item_ibfk_1` FOREIGN KEY (`order_form_id`) REFERENCES `order_form` (`id`),
  CONSTRAINT `order_form_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `place` */

DROP TABLE IF EXISTS `place`;

CREATE TABLE `place` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `zip_code` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `position` */

DROP TABLE IF EXISTS `position`;

CREATE TABLE `position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `catalog_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `username` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `role` tinyint(1) unsigned DEFAULT NULL,
  `administrator_name` varchar(50) DEFAULT NULL,
  `administrator_surname` varchar(50) DEFAULT NULL,
  `employee_name` varchar(50) DEFAULT NULL,
  `employee_surname` varchar(50) DEFAULT NULL,
  `employee_position_id` int(10) unsigned DEFAULT NULL,
  `supplier_name` varchar(50) DEFAULT NULL,
  `supplier_pib` varchar(9) DEFAULT NULL,
  `supplier_street` varchar(50) DEFAULT NULL,
  `supplier_street_number` varchar(10) DEFAULT NULL,
  `supplier_place_id` int(10) unsigned DEFAULT NULL,
  `supplier_api_url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `place_fk` (`supplier_place_id`),
  KEY `position_fk` (`employee_position_id`),
  CONSTRAINT `place_fk` FOREIGN KEY (`supplier_place_id`) REFERENCES `place` (`id`),
  CONSTRAINT `position_fk` FOREIGN KEY (`employee_position_id`) REFERENCES `position` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
