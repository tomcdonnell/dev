/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "createTables.sql"
*
* Project: High scores.
*
* Purpose: Create the high scores database and tables.
*
* Author: Tom McDonnell 2007-12-23.
*
\**************************************************************************************************/

CREATE DATABASE `highScores`;

USE `highScores`;

CREATE TABLE `game`
(
   `id` int(10) unsigned NOT NULL auto_increment,
   `name` varchar(32),
   PRIMARY KEY (`id`),
   KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `player`
(
   `id` int(10) unsigned NOT NULL auto_increment,
   `name` varchar(32),
   PRIMARY KEY (`id`),
   KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `highScore`
(
   `id` int(10) unsigned NOT NULL auto_increment,
   `create` timestamp,
   `idGame` int(10) unsigned NOT NULL,
   `idPlayer` int(10) unsigned NOT NULL,
   `score` int(10) NOT NULL,
   `data` text,
   PRIMARY KEY (`id`),
   CONSTRAINT `highScores_fk_1` FOREIGN KEY (`idGame`  ) REFERENCES `game`   (`id`),
   CONSTRAINT `highScores_fk_2` FOREIGN KEY (`idPlayer`) REFERENCES `player` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*******************************************END*OF*FILE********************************************/
