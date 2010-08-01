<?php
 require_once 'misc_functions.php';

 /*
  *
  */
 function connectToMySQL_icdb()
 {
    // Connect to MySQL.
    if (!mysql_connect('localhost', 'tom'/*'indoorc_Tom'*/, 'igaiasma'))
      MySQLerror(  'Could not connect to MySQL server in'
                 . " {$_SERVER['PHP_SELF']}::connectToMySQL_icdb().",
                 mysql_errno(), mysql_error()                        );

    // Select database.
    if (!mysql_select_db('indoor_cricket_database'/*'indoorc_IndoorCricketDatabase'*/))
      MySQLerror(  'Could not select database in'
                 . " {$_SERVER['PHP_SELF']}::connectToMySQL_icdb().",
                 mysql_errno(), mysql_error()                        );
 }

 /*
  *
  */
 function testPassword($teamID, $statisticiansPassword)
 {
    // Build query.
    $MySQLquery = "select `password` from `statisticians_passwords` where `team_id` = $teamID";

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::testPassword().",
                 mysql_errno(), mysql_error()                         );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::testPassword().");

    // Store password as temporary variable.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
    $storedPassword = $dataRowArray['password'];

    mysql_free_result($qResult);

    if ($storedPassword == $statisticiansPassword)
      return true;
    else
      return false;
 }

 /*
  * This function is to be called from "icdb_title_page.php".
  * Read all country names and IDs from the MySQL database (countries table)
  * and store in $_SESSION['countriesArray'].
  * Also store the number of countries in $_SESSION['n_countries'].
  */
 function readCountryNamesAndIDs()
 {
    // Build query.
    $MySQLquery =  "select `country_id`, `country_name` from `countries`\n"
                 . 'order by `country_name` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readCountryNames().",
                 mysql_errno(), mysql_error()                           );

    // Copy team names from result of query to $_SESSION[].
    $_SESSION['n_countries'] = mysql_num_rows($qResult);
    for ($i = 0; $i < $_SESSION['n_countries']; $i++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       // Set $_SESSION[] variable.
       $_SESSION['countriesArray'][$i]['countryID'  ] = $dataRowArray['country_id'  ];
       $_SESSION['countriesArray'][$i]['countryName'] = $dataRowArray['country_name'];
    }
    mysql_free_result($qResult);
 }

 /*
  * This function is to be called from "icdb_title_page.php".
  * Read all state names and IDs from the MySQL database (states table)
  * and store in $_SESSION['statesArray'].
  * Also store the number of countries in $_SESSION['n_states'].
  */
 function readStateDetails()
 {
    // Build query.
    $MySQLquery =  "select `country_id`, `state_id`, `state_name` from `states`\n"
                 . 'order by `state_name` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readStateDetails().",
                 mysql_errno(), mysql_error()                            );

    // Copy team names from result of query to $_SESSION[].
    $_SESSION['n_states'] = mysql_num_rows($qResult);
    for ($i = 0; $i < $_SESSION['n_states']; $i++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       // Set $_SESSION[] variable.
       $_SESSION['statesArray'][$i]['countryID'] = $dataRowArray['country_id'];
       $_SESSION['statesArray'][$i]['stateID'  ] = $dataRowArray['state_id'  ];
       $_SESSION['statesArray'][$i]['stateName'] = $dataRowArray['state_name'];
    }
    mysql_free_result($qResult);
 }

 /*
  * This function is to be called from "icdb_title_page.php".
  * Read all centre names from the MySQL database (centres table)
  * and store in $_SESSION['centreNamesArray'].
  * Also store the number of centres in $_SESSION['n_centres'].
  */
 function readCentreDetails()
 {
    // Build query.
    $MySQLquery =  "select `country_id`, `state_id`, `centre_id`, `centre_name` from `centres`\n"
                 . 'order by `centre_name` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readCentreDetails().",
                 mysql_errno(), mysql_error()                           );

    // Copy team names from result of query to $_SESSION[].
    $_SESSION['n_centres'] = mysql_num_rows($qResult);
    for ($i = 0; $i < $_SESSION['n_centres']; $i++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       // Set $_SESSION[] variable.
       $_SESSION['centresArray'][$i]['countryID' ] = $dataRowArray['country_id' ];
       $_SESSION['centresArray'][$i]['stateID'   ] = $dataRowArray['state_id'   ];
       $_SESSION['centresArray'][$i]['centreID'  ] = $dataRowArray['centre_id'  ];
       $_SESSION['centresArray'][$i]['centreName'] = $dataRowArray['centre_name'];
    }
    mysql_free_result($qResult);
 }

 /*
  * This function is to be called from "icdb_title_page.php".
  * Read all team names and IDs from the MySQL database (teams table)
  * and store in $_SESSION['teamsArray'].
  * Also store the number of team names in $_SESSION['n_teams'].
  */
 function readTeamDetails()
 {
    // Build query.
    $MySQLquery =  "select `centre_id`, `teams`.`team_id`, `team_name`, count(*) as `n_matches`\n"
                 . "from `teams`, `matches`\n"
                 . "where `teams`.`team_id` = `matches`.`team_id`\n"
                 . "group by `teams`.`team_id`\n"
                 . 'order by `team_name` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readTeamDetails().",
                 mysql_errno(), mysql_error()                            );

    // Copy team names from result of query to $_SESSION[].
    $_SESSION['n_teams'] = mysql_num_rows($qResult);
    for ($i = 0; $i < $_SESSION['n_teams']; $i++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       // Set $_SESSION[] variables.
       $_SESSION['teamsArray'][$i]['centreID' ] = $dataRowArray['centre_id'];
       $_SESSION['teamsArray'][$i]['teamID'   ] = $dataRowArray['team_id'  ];
       $_SESSION['teamsArray'][$i]['teamName' ] = $dataRowArray['team_name'];
       $_SESSION['teamsArray'][$i]['n_matches'] = $dataRowArray['n_matches'];
    }
    mysql_free_result($qResult);
 }

 /*
  * Read the team_name corresponding to the given '$teamID' from MySQL database (teams table)
  * and store in $_SESSION['teamName'].
  */
 function readTeamName($teamID)
 {
    // build query
    $MySQLquery = "select `team_name` from `teams` where `team_id` = $teamID";

    // execute query
    $qResult = mysql_query($MySQLquery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readTeamName().",
                 mysql_errno(), mysql_error()                          );

    // test for 1 row in result
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::readTeamName().");

    // store result in $_SESSION[]
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
    $_SESSION['teamName'] = $dataRowArray['team_name'];

    mysql_free_result($qResult);
 }

 /*
  * Read all player names from the team given by '$team_id' from MySQL database (players table)
  * and store in $_SESSION['firstNamesArray'] and $_SESSION['lastNamesArray'].
  * Also store the number of player names in $_SESSION['n_players'].
  */
 function readPlayerNames($teamID)
 {
    // build query
    $MySQLquery =  "select `first_name`, `last_name` from `players`\n"
                 . "where `team_id` = $teamID\n"
                 . 'order by `first_name` asc, `last_name` asc';

    // execute query
    $qResult = mysql_query($MySQLquery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readPlayerNames().",
                 mysql_errno(), mysql_error()                             );

    // copy player names from result of query to $_SESSION[]
    $_SESSION['n_players'] = mysql_num_rows($qResult);
    for ($i = 0; $i < $_SESSION['n_players']; $i++)
    {
       // get row
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $_SESSION['firstNamesArray'][$i] = $dataRowArray['first_name'];
       $_SESSION['lastNamesArray' ][$i] = $dataRowArray['last_name' ];

       $_SESSION['playerNamesArray'][$i]
         = $_SESSION['firstNamesArray'][$i] . " " . $_SESSION['lastNamesArray' ][$i];
    }
    mysql_free_result($qResult);
 }

 /*
  * Read season names and start amd finish dates from MySQL database (seasons table)
  * for the team given by '$teamID' and store in $_SESSION['seasonNamesArray'],
  * $_SESSION['seasonStartDatesArray'], and $_SESSION['seasonFinishDatesArray'].
  */
 function readSeasons($teamID)
 {
    // build query
    $MySQLquery =  "select `season_name`, `start_date`, `finish_date` from `seasons`\n"
                 . "where `team_id` = $teamID order by `start_date` desc";

    // execute query
    $qResult = mysql_query($MySQLquery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readSeasons().",
                 mysql_errno(), mysql_error()                         );

    // copy player names from result of query to $_SESSION[]
    for ($i = 0; $i < mysql_num_rows($qResult); $i++)
    {
       // get row
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $_SESSION['seasonNamesArray'      ][$i] = $dataRowArray['season_name'];
       $_SESSION['seasonStartDatesArray' ][$i] = $dataRowArray['start_date' ];
       $_SESSION['seasonFinishDatesArray'][$i] = $dataRowArray['finish_date'];
    }
    mysql_free_result($qResult);
 }

 /*
  * Read all opposition team names from the team given by '$teamID'
  * from MySQL database (players table) and store in $_SESSION['oppTeamNamesArray'].
  * Also store the number of opposition teams in $_SESSION['n_oppTeamNames'].
  */
 function readOppTeamNames($teamID)
 {
    // build query
    $MySQLquery =  "select `opp_team_name` from `opp_teams` where `team_id` = $teamID\n"
                 . 'order by `opp_team_name` asc';

    // execute query
    $qResult = mysql_query($MySQLquery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readOppTeamNames().",
                 mysql_errno(), mysql_error()                              );

    // copy opposition team names from result of query to $_SESSION[]
    $_SESSION['n_oppTeamNames'] = mysql_num_rows($qResult);
    for ($i = 0; $i < $_SESSION['n_oppTeamNames']; $i++)
    {
       // get row
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
       
       $_SESSION['oppTeamNamesArray'][$i] = $dataRowArray['opp_team_name'];
    }
    mysql_free_result($qResult);
 }

 /*
  * For each player, player ID, player name, whether the player is a fill in,
  * and whether the player is retired from MySQL database (players table) and
  * store in $_SESSION['playerDetailsArray'].
  */
 function readPlayerDetails($teamID)
 {
    $_SESSION['playerDetailsArray'] = array(); // Clear array.

    // Build query (order by name so can use directly in select options list).
    $MySQLquery =  "select `player_id`, `first_name`, `last_name`, `fill_in`, `retired`\n"
                 . "from `players` where `team_id` = $teamID\n"
                 . 'order by `first_name` asc, `last_name` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readPlayerIDsAndNames().",
                 mysql_errno(), mysql_error()                                  );

    // Copy player details from result of query to $_SESSION[]
    for ($i = 0; $i < mysql_num_rows($qResult); $i++)
    {
       // get row
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
       
       $_SESSION['playerDetailsArray'][$i]['playerID' ] = $dataRowArray['player_id' ];
       $_SESSION['playerDetailsArray'][$i]['firstName'] = $dataRowArray['first_name'];
       $_SESSION['playerDetailsArray'][$i]['lastName' ] = $dataRowArray['last_name' ];
       $_SESSION['playerDetailsArray'][$i]['fillIn'   ] = $dataRowArray['fill_in'   ];
       $_SESSION['playerDetailsArray'][$i]['retired'  ] = $dataRowArray['retired'   ];
    }
    mysql_free_result($qResult);
 }

 /*
  * For each opp. team, read all opp. team IDs and names from MySQL database
  * (opp_teams table) and store in $_SESSION['oppTeamIDsAndNamesArray'].
  */
 function readOppTeamIDsAndNames($teamID)
 {
    $_SESSION['oppTeamIDsAndNamesArray'] = array(); // clear array

    // build query (order by name so can use directly in select options list)
    $MySQLquery =  "select `opp_team_id`, `opp_team_name`\n"
                 . "from `opp_teams` where `team_id` = $teamID\n"
                 . 'order by `opp_team_name` asc';

    // execute query
    $qResult = mysql_query($MySQLquery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readOppTeamIDsAndNames().",
                 mysql_errno(), mysql_error()                                    );

    // copy opposition team names and IDs from result of query to $_SESSION[]
    for ($i = 0; $i < mysql_num_rows($qResult); $i++)
    {
       // get row
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
       
       $_SESSION['oppTeamIDsAndNamesArray'][$i]['oppTeamID'  ] = $dataRowArray['opp_team_id'  ];
       $_SESSION['oppTeamIDsAndNamesArray'][$i]['oppTeamName'] = $dataRowArray['opp_team_name'];
    }
    mysql_free_result($qResult);
 }

 /*
  * For each match in period, read assorted match details from MySQL database
  * (view_matches table) and store in $_SESSION['matchDetailsArray'].
  * Indices are:
  *  'matchDate', 'matchTime', 'oppTeamID', 'matchType', 'teamBatted1st', 'n_players', 'result',
  *  'batsmanIDsArray' (array of eight integers).
  */
 function readMatchDetails($teamID)
 {
    $title = 'matchDetailsArray';

    $_SESSION['title'] = array(); // Clear array.

    // Build query (order by match_date asc so can read first and last match_date for init. period).
    $MySQLquery
/*
      =  "select `match_date`, `match_time`, `opp_team_id`,\n"
       . "       `match_type`, `team_batted_1st`, `n_players`, `result`, \n"
       . "       `i1`.`player_id` as b1, `i2`.`player_id` as b2, `i3`.`player_id` as b3,\n"
       . "       `i4`.`player_id` as b4, `i5`.`player_id` as b5, `i6`.`player_id` as b6,\n"
       . "       `i7`.`player_id` as b7, `i8`.`player_id` as b8\n"
       . "from `view_matches`,\n"
       . "     `innings` as i1, `innings` as i2, `innings` as i3, `innings` as i4,\n"
       . "     `innings` as i5, `innings` as i6, `innings` as i7, `innings` as i8\n"
       . "where `view_matches`.`team_id` = $teamID\n"
       . "  and `view_matches`.`match_id` = `i1`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i2`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i3`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i4`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i5`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i6`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i7`.`match_id`\n"
       . "  and `view_matches`.`match_id` = `i8`.`match_id`\n"
       . "  and `i1`.`batting_pos` = 1 and `i2`.`batting_pos` = 2 and `i3`.`batting_pos` = 3\n"
       . "  and `i4`.`batting_pos` = 4 and `i5`.`batting_pos` = 5 and `i6`.`batting_pos` = 6\n"
       . "  and `i7`.`batting_pos` = 7 and `i8`.`batting_pos` = 8\n"
       . 'order by `match_date` asc, `match_time` asc';
*/
      =  "select `match_date`, `match_time`, `opp_team_id`,\n"
       . "       `match_type`, `team_batted_1st`,\n"
       . "       `i1`.`player_id` as b1, `i2`.`player_id` as b2, `i3`.`player_id` as b3,\n"
       . "       `i4`.`player_id` as b4, `i5`.`player_id` as b5, `i6`.`player_id` as b6,\n"
       . "       `i7`.`player_id` as b7, `i8`.`player_id` as b8\n"
       . "from `matches`,\n"
       . "     `innings` as i1, `innings` as i2, `innings` as i3, `innings` as i4,\n"
       . "     `innings` as i5, `innings` as i6, `innings` as i7, `innings` as i8\n"
       . "where `matches`.`team_id` = $teamID\n"
       . "  and `matches`.`match_id` = `i1`.`match_id`\n"
       . "  and `matches`.`match_id` = `i2`.`match_id`\n"
       . "  and `matches`.`match_id` = `i3`.`match_id`\n"
       . "  and `matches`.`match_id` = `i4`.`match_id`\n"
       . "  and `matches`.`match_id` = `i5`.`match_id`\n"
       . "  and `matches`.`match_id` = `i6`.`match_id`\n"
       . "  and `matches`.`match_id` = `i7`.`match_id`\n"
       . "  and `matches`.`match_id` = `i8`.`match_id`\n"
       . "  and `i1`.`batting_pos` = 1 and `i2`.`batting_pos` = 2 and `i3`.`batting_pos` = 3\n"
       . "  and `i4`.`batting_pos` = 4 and `i5`.`batting_pos` = 5 and `i6`.`batting_pos` = 6\n"
       . "  and `i7`.`batting_pos` = 7 and `i8`.`batting_pos` = 8\n"
       . 'order by `match_date` asc, `match_time` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readMatchDetails().",
                 mysql_errno(), mysql_error()                             );

    // Copy opposition team Ids and match dates from result of query to $_SESSION[].
    for ($i = 0; $i < mysql_num_rows($qResult); $i++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $_SESSION[$title][$i]['matchDate'    ] = $dataRowArray['match_date'     ];
       $_SESSION[$title][$i]['matchTime'    ] = $dataRowArray['match_time'     ];
       $_SESSION[$title][$i]['oppTeamID'    ] = $dataRowArray['opp_team_id'    ];
       $_SESSION[$title][$i]['matchType'    ] = $dataRowArray['match_type'     ];
       $_SESSION[$title][$i]['teamBatted1st'] = $dataRowArray['team_batted_1st'];
//       $_SESSION[$title][$i]['n_players'    ] = $dataRowArray['n_players'      ];
//       $_SESSION[$title][$i]['result'       ] = $dataRowArray['result'         ];

       $_SESSION[$title][$i]['batsmanIDsArray'][1] = $dataRowArray['b1'];
       $_SESSION[$title][$i]['batsmanIDsArray'][2] = $dataRowArray['b2'];
       $_SESSION[$title][$i]['batsmanIDsArray'][3] = $dataRowArray['b3'];
       $_SESSION[$title][$i]['batsmanIDsArray'][4] = $dataRowArray['b4'];
       $_SESSION[$title][$i]['batsmanIDsArray'][5] = $dataRowArray['b5'];
       $_SESSION[$title][$i]['batsmanIDsArray'][6] = $dataRowArray['b6'];
       $_SESSION[$title][$i]['batsmanIDsArray'][7] = $dataRowArray['b7'];
       $_SESSION[$title][$i]['batsmanIDsArray'][8] = $dataRowArray['b8'];
    }

    mysql_free_result($qResult);
 }

 /*
  * Create time selector inside an existing HTML table with separate selectors for
  * hour, minute, and AM/PM on the same row (row starts and ends not included).
  * NOTE: Use JavaScript if need to set default selection.
  */
 function timeSelector($indent,
                       $hourSelectorId, $minuteSelectorId, $am_pmSelectorId,
                       $hourTdClass, $minuteTdClass, $am_pmTdClass          )
 {
    // hour selector
    echo $indent , "<td class=\"$hourTdClass\">\n";
    echo $indent , " <select id=\"$hourSelectorId\" name=\"$hourSelectorId\">\n";
    for ($i = 1; $i <= 12; $i++)
      echo $indent , "  <option>$i</option>\n";
    echo $indent , " </select>\n";
    echo $indent , "</td>\n";

    // minute selector
    echo $indent , "<td class=\"$minuteTdClass\">\n";
    echo $indent , " <select id=\"$minuteSelectorId\" name=\"$minuteSelectorId\">\n";
    for ($i = 0; $i <= 59; $i++)
    {
       echo $indent , "  <option>";
       if ($i < 10) echo '0';
       echo $i , "</option>\n";
    }
    echo $indent , " </select>\n";
    echo $indent , "</td>\n";

    // AM/PM selector
    echo $indent , "<td class=\"$am_pmTdClass\">\n";
    echo $indent , " <select id=\"$am_pmSelectorId\" name=\"$am_pmSelectorId\">\n";
    echo $indent , "  <option>AM</option>\n";
    echo $indent , "  <option selected>PM</option>\n";
    echo $indent , " </select>\n";
    echo $indent , "</td>\n";
 }

 /*
  *
  */
 function buildOppTeamNamesWithNmatchesArray()
 {
    $_SESSION['oppTeamNamesWithNmatchesArray'] = array(); // clear array

    foreach ($_SESSION['oppTeamIDsAndNamesArray'] as $key => $oppTeam)
    {
       // count matches versus opp_team
       $n_matches = 0;
       foreach ($_SESSION['matchDatesOppTeamIDsAndBatsmanIDsArray'] as $match)
         if ($match['oppTeamID'] == $oppTeam['oppTeamID'])
           ++$n_matches;

       // build $_SESSION['oppTeamNamesWithNmatchesArray'][$key]
       $_SESSION['oppTeamNamesWithNmatchesArray'][$key]
         = $oppTeam['oppTeamName'] . ' (' . $n_matches . ' match' . (($n_matches > 1)? 'es)': ')');
    }
 }

 /*
  *
  */
 function buildPlayerNamesWithNmatchesArray()
 {
    $_SESSION['playerNamesWithNmatchesArray'] = array(); // clear array

    foreach ($_SESSION['playerIDsAndNamesArray'] as $key => $player)
    {
       // count matches in which player batted
       $n_matches = 0;
       foreach ($_SESSION['matchDatesOppTeamIDsAndBatsmanIDsArray'] as $match)
         if (in_array($player['playerID'], $match['batsmanIDsArray']))
            ++$n_matches;

       // build $_SESSION['playerNamesWithNmatchesArray'][$key]
       $_SESSION['playerNamesWithNmatchesArray'][$key]
         =  $player['firstName'] . ' ' . $player['lastName']
          . ' (' . $n_matches . ' match' . (($n_matches > 1)? 'es)': ')');
    }
 }
?>
