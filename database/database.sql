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
  `code` varchar(20) DEFAULT NULL,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_COLUMNS` (`code`),
  KEY `supplier_fk` (`supplier_id`),
  CONSTRAINT `supplier_fk` FOREIGN KEY (`supplier_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `catalog` */

/*Table structure for table `catalog_item` */

DROP TABLE IF EXISTS `catalog_item`;

CREATE TABLE `catalog_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `catalog_id` int(10) unsigned DEFAULT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_i_fk` (`catalog_id`),
  KEY `product_i_fk` (`product_id`),
  CONSTRAINT `catalog_i_fk` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`),
  CONSTRAINT `product_i_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `catalog_item` */

/*Table structure for table `order_form` */

DROP TABLE IF EXISTS `order_form`;

CREATE TABLE `order_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `code` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL,
  `catalog_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_COLUMNS` (`code`),
  KEY `catalog_fk` (`catalog_id`),
  CONSTRAINT `catalog_fk` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order_form` */

/*Table structure for table `order_form_item` */

DROP TABLE IF EXISTS `order_form_item`;

CREATE TABLE `order_form_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `quantity` int(10) DEFAULT NULL,
  `order_form_id` int(10) unsigned DEFAULT NULL,
  `catalog_item_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_form_i_fk` (`order_form_id`),
  KEY `catalog_item_i_fk` (`catalog_item_id`),
  CONSTRAINT `catalog_item_i_fk` FOREIGN KEY (`catalog_item_id`) REFERENCES `catalog_item` (`id`),
  CONSTRAINT `order_form_i_fk` FOREIGN KEY (`order_form_id`) REFERENCES `order_form` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order_form_item` */

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

/*Data for the table `place` */

insert  into `place`(`id`,`version`,`deactivated`,`zip_code`,`name`) values (1,1,0,11000,'Beograd'),(2,1,0,16000,'Leskovac'),(3,1,0,37000,'Brus'),(4,1,0,34000,'Kragujevac'),(5,1,0,21000,'Novi Sad');

/*Table structure for table `position` */

DROP TABLE IF EXISTS `position`;

CREATE TABLE `position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT NULL,
  `deactivated` tinyint(1) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `position` */

insert  into `position`(`id`,`version`,`deactivated`,`name`) values (1,NULL,0,'Generalni direktor'),(2,NULL,0,'Finansijski direktor'),(3,NULL,0,'Magacioner'),(4,NULL,0,'Direktor sektora nabavke'),(5,NULL,0,'Ekonomista');

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `unit` enum('komad','litar','kilogram') DEFAULT NULL,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_COLUMNS` (`code`),
  KEY `supplier_fk1` (`supplier_id`),
  CONSTRAINT `supplier_fk1` FOREIGN KEY (`supplier_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `product` */

/*Table structure for table `product_price` */

DROP TABLE IF EXISTS `product_price`;

CREATE TABLE `product_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(10) unsigned DEFAULT '1',
  `deactivated` tinyint(1) DEFAULT '0',
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_p_fk` (`product_id`),
  CONSTRAINT `product_p_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `product_price` */

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
  PRIMARY KEY (`id`),
  KEY `place_fk` (`supplier_place_id`),
  KEY `position_fk` (`employee_position_id`),
  CONSTRAINT `place_fk` FOREIGN KEY (`supplier_place_id`) REFERENCES `place` (`id`),
  CONSTRAINT `position_fk` FOREIGN KEY (`employee_position_id`) REFERENCES `position` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`version`,`deactivated`,`username`,`email`,`password`,`role`,`administrator_name`,`administrator_surname`,`employee_name`,`employee_surname`,`employee_position_id`,`supplier_name`,`supplier_pib`,`supplier_street`,`supplier_street_number`,`supplier_place_id`) values (1,1,0,'admin','admin@admin.com','admin',1,'Admin','Admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
