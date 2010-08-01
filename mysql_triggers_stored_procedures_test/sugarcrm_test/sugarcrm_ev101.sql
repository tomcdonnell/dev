CREATE TABLE `ev101_event`
(
  `id` char(36) NOT NULL,
  `name` varchar(255) default NULL,
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `modified_user_id` char(36) default NULL,
  `created_by` char(36) default NULL,
  `description` text,
  `deleted` tinyint(1) default '0',
  `assigned_user_id` char(36) default NULL,
  `ev101_event_number` int(11) NOT NULL auto_increment,
  `type` varchar(255) default NULL,
  `status` varchar(25) default NULL,
  `priority` varchar(25) default NULL,
  `resolution` varchar(255) default NULL,
  `work_log` text,
  `location` varchar(50) NOT NULL,
  `summary` text,
  `pcode` varchar(4) default NULL,
  `contact_id_c` char(36) default NULL,
  `issues_raised` text,
  `event_type` varchar(100) default 'other',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ev101_eventnumk` (`ev101_event_number`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8



CREATE TABLE `_ev101_event_sensible`
(
   `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `id_ev101_event` CHAR(36),
   `issue_raised` VARCHAR(256)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# COMMENT
# Splits a string with separator ',', from table `test`,
# and inserts the resulting substrings to table `testSensible`.
# 
#  Eg. Table ev101_event    Table _ev101_event_sensible
#      +--+-------------+   +--+--------------+------------+
#      |id|issues_raised|   |id|id_ev101_event|issue_raised|
#      +--+-------------+   +--+--------------+------------+
#      | 1|one,two,three|   | 1|             1|one         |
#      | 2|three        |   | 2|             1|two         |
#      | 3|five,three   |   | 3|             1|three       |
#      +--+-------------+   | 4|             2|three       |
#                           | 5|             3|five        |
#                           | 6|             3|three       |
#                           +--+--------------+------------+

DELIMITER $$

DROP PROCEDURE IF EXISTS `insertNewRowsIn_ev101_event_sensible`;
CREATE PROCEDURE         `insertNewRowsIn_ev101_event_sensible`
(
   `pNewId`  INT(11),
   `pNewStr` TEXT
)

BEGIN

   DECLARE `vSubstring` VARCHAR(16);
   DECLARE `vSeparator` VARCHAR( 8) DEFAULT ',';
   DECLARE `vDone`      TINYINT( 1) DEFAULT 0;
   DECLARE `vIndex`     INT(11) DEFAULT 1;

   WHILE `vDone`=0 DO

      SET `vSubstring`=SUBSTRING(
         `pNewStr`,`vIndex`,
         IF(
            LOCATE(`vSeparator`,`pNewStr`,`vIndex`)>0,
            LOCATE(`vSeparator`,`pNewStr`,`vIndex`)-`vIndex`,
            LENGTH(`pNewStr`)
         )
      );

      IF LENGTH(`vSubstring`)>0 THEN
         SET `vIndex`=`vIndex`+LENGTH(`vSubstring`)+1;
         INSERT INTO `_ev101_event_sensible`
         SET `id_ev101_event`=`pNewId`,
             `issue_raised`=TRIM(`vSubstring`);
      ELSE
         SET `vDone`=1;
      END IF;

   END WHILE;

END$$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `deleteOldRowsIn_ev101_event_sensible`;
CREATE PROCEDURE         `deleteOldRowsIn_ev101_event_sensible`
(
   `pOldId` INT(11)
)
BEGIN
   DELETE FROM `_ev101_event_sensible`
   WHERE `id_ev101_event`=`pOldId`;
END$$
DELIMITER ;



DELIMITER $$

CREATE TRIGGER `runDeleteOldRowsIn_ev101_event_sensible_AfterDelete` AFTER DELETE ON `ev101_event`
FOR EACH ROW
BEGIN
   CALL `deleteOldRowsIn_ev101_event_sensible`(OLD.`id`);
END$$

CREATE TRIGGER `runDeleteOldRowsIn_ev101_event_sensible_AfterUpdate` AFTER UPDATE ON `ev101_event`
FOR EACH ROW
BEGIN
   CALL `deleteOldRowsIn_ev101_event_sensible`(OLD.`id`);
   CALL `insertNewRowsIn_ev101_event_sensible`(NEW.`id`, NEW.`issues_raised`);
END$$

CREATE TRIGGER `runInsertNewRowsInTable_ev101_event_sensible_AfterInsert` AFTER INSERT ON `ev101_event`
FOR EACH ROW
BEGIN
   CALL `insertNewRowsIn_ev101_event_sensible`(NEW.`id`, NEW.`issues_raised`);
END$$

DELIMITER ;
