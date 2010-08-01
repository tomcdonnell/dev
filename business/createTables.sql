/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "createTables.sql"
*
* Project: Business.
*
* Purpose: Create the business database and tables.
*
* Author: Tom McDonnell 2007-12-23.
*
\**************************************************************************************************/

CREATE DATABASE `business`;

USE `business`;

CREATE TABLE `employee`
(
   `id` int(10) unsigned NOT NULL auto_increment,
   `nameFirst` varchar(32) NOT NULL,
   `nameMiddle` varchar(32),
   `nameLast` varchar(32) NOT NULL,
   PRIMARY KEY (`id`),
   KEY (`namefirst`, `nameMiddle`, `nameLast`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `job`
(
   `id` int(10) unsigned NOT NULL auto_increment,
   `idEmployee` unsigned NOT NULL,
   `timeStart` timestamp NOT NULL,
   `timeFinish` timestamp NOT NULL,
   PRIMARY KEY (`id`),
   CONSTRAINT `job_fk_1` FOREIGN KEY (`idEmployee`) REFERENCES `employee` (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*******************************************END*OF*FILE********************************************/
