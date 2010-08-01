CREATE TABLE `test`
(
   `id`  INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
   `str` TEXT
);



CREATE TABLE `testSensible`
(
   `id`     INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
   `idTest` INT(11) NOT NULL,
   `substr` VARCHAR(256)
);



# COMMENT
# Splits a string with separator ',', from table `test`,
# and inserts the resulting substrings to table `testSensible`.
# 
#  Eg. Table Input          Table Output
#      +--+-------------+   +--+------------+---------+
#      |id|string       |   |id|idInputTable|substring|
#      +--+-------------+   +--+------------+---------+
#      | 1|one,two,three|   | 1|           1|one      |
#      | 2|three        |   | 2|           1|two      |
#      | 3|five,three   |   | 3|           1|three    |
#      +--+-------------+   | 4|           2|three    |
#                           | 5|           3|five     |
#                           | 6|           3|three    |
#                           +--+------------+---------+

DELIMITER $$

DROP PROCEDURE IF EXISTS `insertNewRowsInTestSensible`;
CREATE PROCEDURE         `insertNewRowsInTestSensible`
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
         INSERT INTO `testSensible`
         SET `idTest`=`pNewId`,
             `substr`=TRIM(`vSubstring`);
      ELSE
         SET `vDone`=1;
      END IF;

   END WHILE;

END$$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `deleteOldRowsInTestSensible`;
CREATE PROCEDURE         `deleteOldRowsInTestSensible`
(
   `pOldId` INT(11)
)
BEGIN
   DELETE FROM `testSensible`
   WHERE `idTest`=`pOldId`;
END$$
DELIMITER ;



DELIMITER $$

CREATE TRIGGER `runDeleteOldRowsInTestSensibleAfterDelete` AFTER DELETE ON `test`
FOR EACH ROW
BEGIN
   CALL `deleteOldRowsInTestSensible`(OLD.`id`);
END$$

CREATE TRIGGER `runDeleteOldRowsInTestSensibleAfterUpdate` AFTER UPDATE ON `test`
FOR EACH ROW
BEGIN
   CALL `deleteOldRowsInTestSensible`(OLD.`id`);
   CALL `insertNewRowsInTestSensible`(NEW.`id`, NEW.`str`);
END$$

CREATE TRIGGER `runInsertNewRowsInTableTestSensibleAfterInsert` AFTER INSERT ON `test`
FOR EACH ROW
BEGIN
   CALL `insertNewRowsInTestSensible`(NEW.`id`, NEW.`str`);
END$$

DELIMITER ;
