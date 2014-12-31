/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.5.20-log : Database - homework
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`homework` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `homework`;
SET NAMES 'UTF8';
/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(4) NOT NULL,
  `group_id` int(11) NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`user_name`,`password`,`salt`,`group_id`,`add_time`) values (1,'admin','f5ee885c7ae4a664dfc091376f57f42a','asty',1,1419942140),(3,'test8','49129bb1c3b20c4fad3d8f7845e401dd','kmbg',2,1419993546),(4,'test2','32fe6718fe17244795305e7a3896a313','HtNl',2,1419995222),(5,'test3','0956a4e91bd595141167c4d884753636','ZGYI',3,1419995230),(6,'test4','59b912c97aa3314494c0456664818191','DJoj',3,1419995238),(7,'test5','a456fed4684760d19647bee8370d4e79','YdHT',2,1419995248),(10,'test6','4fefe0201ef055278196c3b8f3d53a68','Hwye',14,1420006846),(11,'test9','46fa425063268eac872bad52116eb31e','ksfL',14,1420006855),(12,'test10','5ed475093c190fb302b0f49eb79e93a9','guYR',14,1420006910),(13,'test11','54a8fce66b6c3807b5f8f4ad0f0f5770','yWdO',14,1420006921),(14,'test12','75d25f090a8200d1e98aef8e8e13a527','WpKt',2,1420006936),(15,'test13','6410e2f529b10c6e8885a98aa47d54da','PqDA',3,1420006954);

/*Table structure for table `user_group` */

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `allow_add` tinyint(1) NOT NULL DEFAULT '0',
  `allow_delete` tinyint(1) NOT NULL DEFAULT '0',
  `allow_edit` tinyint(1) NOT NULL DEFAULT '0',
  `allow_select` tinyint(1) NOT NULL DEFAULT '0',
  `permission_sum` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标识权限的级别，值越大，级别越高',
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `user_group` */

insert  into `user_group`(`group_id`,`group_name`,`description`,`allow_add`,`allow_delete`,`allow_edit`,`allow_select`,`permission_sum`,`add_time`) values (1,'超级管理员','超级管理员',1,1,1,1,4,1419942140),(2,'主编','主编',1,0,1,1,3,1419942140),(3,'小编','小编',1,0,0,1,2,1419942140),(14,'游客','游客',0,0,0,1,1,1420006832);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
