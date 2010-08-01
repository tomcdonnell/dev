####################################################################################################
#                                                                                                  #
# FILENAME: "icdb_create_tables_script.sql"                                                        #
#                                                                                                  #
# PURPOSE: This file is a script that creates all                                                  #
#          tables for the database "indoor_cricket_database".                                      #
#                                                                                                  #
# AUTHOR: Tom McDonnell 2006                                                                       #
#                                                                                                  #
####################################################################################################

# Drop Tables ######################################################################################

# NOTE: order is important because of foreign key constraints
DROP TABLE IF EXISTS `innings`;
DROP TABLE IF EXISTS `overs`;
DROP TABLE IF EXISTS `seasons`;
DROP TABLE IF EXISTS `matches`;
DROP TABLE IF EXISTS `players`;
DROP TABLE IF EXISTS `opp_teams`;
DROP TABLE IF EXISTS `statisticians_passwords`;
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `centres`;
DROP TABLE IF EXISTS `states`;
DROP TABLE IF EXISTS `countries`;

# Create Tables ####################################################################################

# NOTE: In the "create table" statements below, no index is created explicitly.
#       Automatic creation of indices is assumed.
#       To check what indices have been created, use "show index from <table_name>" statement.

CREATE TABLE `countries`
(
   `country_id` int(10) unsigned NOT NULL auto_increment,
   `country_name` varchar(64) NOT NULL default '<default_country_name>',
   PRIMARY KEY (`country_id`),
   UNIQUE KEY (`country_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `states`
(
   `state_id` int(10) unsigned NOT NULL auto_increment,
   `state_name` varchar(64) NOT NULL default '<default_state_name>',
   `country_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`state_id`, `country_id`),
   UNIQUE KEY (`country_id`, `state_name`),
   FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `centres`
(
   `centre_id` int(10) unsigned NOT NULL auto_increment,
   `centre_name` varchar(64) NOT NULL default '<default_centre_name>',
   `country_id` int(10) unsigned NOT NULL default '0',
   `state_id` int(10) unsigned NOT NULL default '0',
   `address` varchar(128) NOT NULL default '<default_address>',
   PRIMARY KEY (`centre_id`),
   UNIQUE KEY (`centre_name`, `country_id`, `state_id`, `address`),
   UNIQUE KEY (`country_id`, `state_id`, `address`),
   FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`),
   FOREIGN KEY (`state_id`, `country_id`) REFERENCES `states` (`state_id`, `country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `teams`
(
   `team_id` int(10) unsigned NOT NULL auto_increment,
   `team_name` varchar(64) NOT NULL default '<default_team_name>',
   `centre_id` int(10) unsigned NOT NULL default '0',
   `retired` BOOLEAN NOT NULL default '0',
   PRIMARY KEY (`team_id`),
   UNIQUE KEY (`team_name`, `centre_id`),
   FOREIGN KEY (`centre_id`) REFERENCES `centres` (`centre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `statisticians_passwords`
(
   `password` varchar(16) NOT NULL default '<password>',
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`team_id`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `opp_teams`
(
   `opp_team_id` int(10) unsigned NOT NULL auto_increment,
   `opp_team_name` varchar(64) NOT NULL default '<default_opp_team_name>',
   `retired` BOOLEAN NOT NULL default '0',
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`opp_team_id`, `team_id`),
   UNIQUE KEY (`opp_team_name`, `team_id`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `matches`
(
   `match_id` int(10) unsigned NOT NULL auto_increment,
   `opp_team_id` int(10) unsigned NOT NULL default '0',
   `match_date` date NOT NULL default '0000-00-00',
   `match_time` time NOT NULL default '00:00:00',
   `team_batted_1st` BOOLEAN NOT NULL default '0',
   `match_notes` varchar(1024) default NULL,
   `match_type` char(1) NOT NULL default 'R', # 'R' = Regular, 'I' = Irregular, 'F' = Final
   `team_penalty_runs` int(10) unsigned NOT NULL default '0',
   `opp_team_penalty_runs` int(10) unsigned NOT NULL default '0',
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`match_id`, `team_id`),
   UNIQUE KEY (`team_id`, `match_date`,`match_time`),
   UNIQUE KEY (`team_id`, `opp_team_id`,`match_date`,`match_time`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`),
   FOREIGN KEY (`opp_team_id`, `team_id`) REFERENCES `opp_teams` (`opp_team_id`, `team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `players`
(
   `player_id` int(10) unsigned NOT NULL auto_increment,
   `first_name` varchar(32) NOT NULL default '<default_first_name>',
   `last_name` varchar(32) NOT NULL default '<default_last_name>',
   `retired` BOOLEAN NOT NULL default '0',
   `fill_in` BOOLEAN NOT NULL default '0',
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`player_id`, `team_id`),
   UNIQUE KEY (`first_name`,`last_name`, `team_id`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `innings`
(
   `match_id` int(10) unsigned NOT NULL default '0',
   `player_id` int(10) unsigned NOT NULL default '0',
   `batting_pos` int(10) unsigned NOT NULL default '0',
   `runs_scored` int(11) NOT NULL default '0',
   `wickets_lost` int(10) unsigned NOT NULL default '0',
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`match_id`,`player_id`,`batting_pos`, `team_id`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`),
   FOREIGN KEY (`match_id`, `team_id`) REFERENCES `matches` (`match_id`, `team_id`),
   FOREIGN KEY (`player_id`, `team_id`) REFERENCES `players` (`player_id`, `team_id`),
   CHECK (1 <= batting_pos and batting_pos <= 8) # has no effect (feature not implemented in MySQL)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `overs`
(
   `match_id` int(10) unsigned NOT NULL default '0',
   `player_id` int(10) unsigned NOT NULL default '0',
   `over_no` int(10) unsigned NOT NULL default '0',
   `wickets_taken` int(10) unsigned NOT NULL default '0',
   `runs_conceded` int(11) NOT NULL default '0',
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`match_id`,`player_id`,`over_no`, `team_id`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`),
   FOREIGN KEY (`match_id`, `team_id`) REFERENCES `matches` (`match_id`, `team_id`),
   FOREIGN KEY (`player_id`, `team_id`) REFERENCES `players` (`player_id`, `team_id`),
   CHECK (1 <= over_no and over_no <= 16) # has no effect (feature not implemented in MySQL)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `seasons`
(
   `season_id` int(10) unsigned NOT NULL auto_increment,
   `season_name` varchar(32) NOT NULL,
   `start_date` date NOT NULL,
   `finish_date` date default NULL,
   `team_id` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`season_id`, `team_id`),
   UNIQUE KEY (`season_name`, `team_id`),
   UNIQUE KEY (`start_date`,`finish_date`, `team_id`),
   FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# End of File ######################################################################################
