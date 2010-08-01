<?php
 require_once '../display/icdb_display_MySQL_table.php';
 require_once '../display/icdb_display_MySQL_chartORhist.php';
 require_once '../display/icdb_display_match_score_sheet.php';
 require_once '../display/icdb_display_team_stats_summary.php';

 /*
  *
  */
 function displayTeamStatsSelection()
 {
    // NOTE: The function 'addOversSubtitleStringAndMySQLwhereExp()' is not
    //       required here as overs restrictions do not apply to team queries.

    preBuildClauses();

    switch ($_SESSION['radioButton'])
    {
     case 'matchScoreSheet':
       setVarsForDisplayMatchScoreSheet();
       displayMatchScoreSheet();
       break;
     case 'summary':
       setTeamSummarySessionVars();
       displayTeamStatsSummary();
       break;
     case 'history':
       buildHistoryTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'scoresTable':
       buildScoresTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'scoresChart':
       buildScoresChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'scoresHist':
       buildScoresChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
     case 'wicketsTable':
       buildWicketsTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'wicketsChart':
       buildWicketsChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'wicketsHist':
       buildWicketsChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
     case 'marginsTable':
       buildMarginsTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'marginsChart':
       buildMarginsChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'marginsHist':
       buildMarginsChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
    }
 }

 /*
  *
  */
 function preBuildClauses()
 {
    // begin MySQL query clauses
    $_SESSION['MySQLselectExp'       ] = '';
    $_SESSION['MySQLselectRankAsExp' ] = '';
    $_SESSION['MySQLfromExp'         ] = ' `view_matches`';
    $_SESSION['MySQLwhereExp'        ] = '`team_id` = ' . $_SESSION['teamID']
                                        . $_SESSION['MySQLperiodWhereExp'    ]
                                        . $_SESSION['MySQLoppositionWhereExp']
                                        // NOTE: players restrictions are not applicable
                                        . $_SESSION['MySQLmatchesWhereExp'   ];
                                        // NOTE: overs   restrictions are not applicable
    $_SESSION['MySQLgroupByExp'      ] = '';
    $_SESSION['MySQLorderByExp'      ] = ' `match_date` desc, `match_time` desc';
    $_SESSION['MySQLrankExp'         ] = '';
    $_SESSION['MySQLrankAscOrDesc'   ] = '';
    $_SESSION['MySQLlimit'           ] = 20;
    $_SESSION['MySQLoffset'          ] =  0;
    $_SESSION['tableHeading1'        ] = '';
    $_SESSION['tableHeading2'        ] = $_SESSION['restrictionsSubtitle'];
    $_SESSION['tableHeading3'        ] = '';
    $_SESSION['tableColHeadingsArray'] = array();
    $_SESSION['tableDataTypes'       ] = array();

    // extend MySQL query clauses & headings
    $_SESSION['MySQLselectExp'] .= " date_format(`match_date`, '%d/%m/%Y'),"
                                  ." time_format(`match_time`, '%l:%i%p'), `opp_team_name`";
    array_push($_SESSION['tableColHeadingsArray'],
               'Match<br />Date', 'Match<br />Time', 'Opposition');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'string');
 }

 /*
  * Set the $_SESSION[] variables that are required for the function 'displayMatchScoreSheet()'.
  * The required variables are:
  *   teamName, oppTeamName,
  *   day, month, year,
  *   hour, minute, amORpm,
  *   teamBatted1st,
  *   firstName, lastName, wicketsLost,  runsScored   (for each of 8  innings),
  *   firstName, lastName, wicketsTaken, runsConceded (for each of 16   overs).
  */
 function setVarsForDisplayMatchScoreSheet()
 {
    $str = $_SESSION['matchSelector']; // Abbreviation.

    // Replace HTML special character ('&nbsp;') with a space (' ').
    // (The HTML special character ('&nbsp') will otherwise
    //  cause problems in extracting the $hour substring below).
    // NOTE: This step previously caused problems when first uploaded to web host's server.
    //       With both lines below, output is correct on both my machine and web host's server.
    $str = str_replace(chr(194), '' , $str);
    $str = str_replace(chr(160), ' ', $str);

    // Get matchDate, matchTime, and oppTeamName from $str string.
    // Example of the format of string $str:
    //   "22/04/2004  7:40pm (Vs. Pissed 'n Broke)".
    $_SESSION['day'   ] = substr($str,  0, 2);
    $_SESSION['month' ] = substr($str,  3, 2);
    $_SESSION['year'  ] = substr($str,  6, 4);
    $_SESSION['hour'  ] = substr($str, 11, 2);
    $_SESSION['minute'] = substr($str, 14, 2);
    $_SESSION['AM_PM' ] = substr($str, 16, 2);
    $oppTeamName        = substr($str, 24   );

    // Remove closing bracket from oppTeamName.
    $_SESSION['oppTeamName'] = substr($oppTeamName, 0, strlen($oppTeamName) - 1);

    // NOTE: $_SESSION['teamName'] should already be stored.

    // Get hour in 24 hour format (required for MySQL query).
    $hour24 = (int)($_SESSION['hour']) + (($_SESSION['AM_PM'] == 'PM')? 12: 0);

    // Read matchID from MySQL database and store in $_SESSION['matchID'].
    readMatchID($_SESSION['teamID'],
                $_SESSION['day'], $_SESSION['month'], $_SESSION['year'],
                $hour24, $_SESSION['minute']                            );

    // Read teamBatted1st, matchNotes, and penaltyRuns for each team from MySQL database
    // and store in: $_SESSION['teamBatted1st'], $_SESSION['matchNotes'],
    //               $_SESSION['teamPenaltyRuns'], and $_SESSION['oppTeamPenaltyRuns'].
    readTeamBatted1stMatchNotesAndPenaltyRuns($_SESSION['teamID'], $_SESSION['matchID']);

    // Read match innings details from MySQL database and store in $_SESSION['innings'][<0-7>].
    readMatchInningsDetails($_SESSION['teamID'], $_SESSION['matchID']);

    // Read match overs details from MySQL database and store in $_SESSION['overs'][<0-15>].
    readMatchOversDetails($_SESSION['teamID'], $_SESSION['matchID']);
 }

 /*
  * Read the details of the innings batted by the team specified by 'teamID' in the
  * match specified by given 'matchID', and store all in $_SESSION['innings'] array.
  *
  * Format of $_SESSION['innings'] array:
  *   $_SESSION['innings']:
  *     is an array containing eight arrays.
  *   $_SESSION['innings'][<n>] (for 'n' being an integer in range [0, 7]):
  *     is an array containing four elements:
  *       ['firstName'  ], (string)
  *       ['lastName'   ], (string)
  *       ['wicketsLost'], (positive integer)
  *       ['runsScored' ], (integer).
  */
 function readMatchInningsDetails($teamID, $matchID)
 {
    // Build query.
    $MySQLquery =  "select `first_name`, `last_name`, `wickets_lost`, `runs_scored`\n"
                 . "from `view_innings`\n"
                 . "where `team_id` = $teamID\n"
                 . "  and `match_id` = $matchID\n"
                 . 'order by `batting_pos` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readMatchInningsDetails().",
                 mysql_errno(), mysql_error()                                    );

    // Copy results of query to $_SESSION[].
    $n_innings = mysql_num_rows($qResult);
    if ($n_innings != 8)
      error(  'Expected n_innings = 8, recieved n_innings = $n_innings in'
            . " {$_SERVER['PHP_SELF']}::readMatchInningsDetails()."       );
    for ($batting_pos = 1; $batting_pos <= $n_innings; $batting_pos++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       // Extract results and store in $_SESSION[].
       $_SESSION['innings'][$batting_pos - 1]['firstName'  ] = $dataRowArray['first_name'  ];
       $_SESSION['innings'][$batting_pos - 1]['lastName'   ] = $dataRowArray['last_name'   ];
       $_SESSION['innings'][$batting_pos - 1]['wicketsLost'] = $dataRowArray['wickets_lost'];
       $_SESSION['innings'][$batting_pos - 1]['runsScored' ] = $dataRowArray['runs_scored' ];
    }
    mysql_free_result($qResult);
 }

 /*
  * Read the details of the overs bowled by the team specified by 'teamID' in the
  * match specified by given 'matchID', and store all in $_SESSION['overs'] array.
  *
  * Format of $_SESSION['overs'] array:
  *   $_SESSION['overs']:
  *     is an array containing sixteen arrays.
  *   $_SESSION['overs'][<n>] (for 'n' being an integer in range [0, 15]):
  *     is an array containing four elements:
  *       ['firstName'   ], (string)
  *       ['lastName'    ], (string)
  *       ['wicketsTaken'], (positive integer)
  *       ['runsConceded'], (integer).
  */
 function readMatchOversDetails($teamID, $matchID)
 {
    // Build query.
    $MySQLquery =  "select `first_name`, `last_name`, `wickets_taken`, `runs_conceded`\n"
                 . "from `view_overs`\n"
                 . "where `team_id` = $teamID\n"
                 . "  and `match_id` = $matchID\n"
                 . 'order by `over_no` asc';

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readMatchOversDetails().",
                 mysql_errno(), mysql_error()                                  );

    // Copy results of query to $_SESSION[].
    $n_overs = mysql_num_rows($qResult);
    if ($n_overs != 16)
      error(  'Expected n_overs = 16, recieved n_overs = $n_innings in'
            . " {$_SERVER['PHP_SELF']}::readMatchInningsDetails()."    );
    for ($over_no = 1; $over_no <= $n_overs; $over_no++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       // Extract results and store in $_SESSION[].
       $_SESSION['overs'][$over_no - 1]['firstName'   ] = $dataRowArray['first_name'   ];
       $_SESSION['overs'][$over_no - 1]['lastName'    ] = $dataRowArray['last_name'    ];
       $_SESSION['overs'][$over_no - 1]['wicketsTaken'] = $dataRowArray['wickets_taken'];
       $_SESSION['overs'][$over_no - 1]['runsConceded'] = $dataRowArray['runs_conceded'];
    }
    mysql_free_result($qResult);
 }

 /*
  * Read the matchID of the match corresponding to the given date and time
  * from MySQL database (matches table) and store in $_SESSION['matchID'].
  */
 function readMatchID($teamID, $day, $month, $year, $hour24, $minute)
 {
    // Create date string and time string for use in MySQL query.
    $MySQLdateString = "'$year-$month-$day'";
    $MySQLtimeString = "'$hour24:$minute:00'";

    // Build query.
    $MySQLquery =  "select `match_id`\n"
                 . "from `matches`\n"
                 . "where `team_id` = $teamID\n"
                 . "  and `match_date` = $MySQLdateString\n"
                 . "  and `match_time` = $MySQLtimeString";

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readMatchID().",
                 mysql_errno(), mysql_error()                        );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::readMatchID().");

    // Store result as $_SESSION[] variable.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
    $_SESSION['matchID'] = $dataRowArray['match_id'];

    mysql_free_result($qResult);
 }

 /*
  * Read the 'teamBatted1st', 'matchNotes', 'teamPenaltyRuns', and 'oppTeamPenaltyRuns'
  * variables for the match corresponding to the given matchID and teamID
  * from the MySQL database (matches table) and store in $_SESSION['teamBatted1st'],
  * $_SESSION['matchNotes'], $_SESSION['teamPenaltyRuns'], and $_SESSION['oppTeamPenaltyRuns'].
  */
 function readTeamBatted1stMatchNotesAndPenaltyRuns($teamID, $matchID)
 {
    // Build query.
    $MySQLquery =  'select `team_batted_1st`, `match_notes`,'
                 .       " `team_penalty_runs`, `opp_team_penalty_runs`\n"
                 . "from `matches`\n"
                 . "where `team_id` = $teamID\n"
                 . "  and `match_id` = $matchID";

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::readTeamBatted1st().",
                 mysql_errno(), mysql_error()                              );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::readTeamBatted1st().");

    // Store result as $_SESSION[] variable.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);
    $_SESSION['teamBatted1st'     ] = $dataRowArray['team_batted_1st'      ];
    $_SESSION['matchNotes'        ] = $dataRowArray['match_notes'          ];
    $_SESSION['teamPenaltyRuns'   ] = $dataRowArray['team_penalty_runs'    ];
    $_SESSION['oppTeamPenaltyRuns'] = $dataRowArray['opp_team_penalty_runs'];

    // Set $_SESSION['matchNotes'] so that it will pass 'isset()' test in displayMatchScoreSheet().
    if ($_SESSION['matchNotes'] == null)
      $_SESSION['matchNotes'] = '';

    mysql_free_result($qResult);
 }

 function buildScoresTableMySQLquery()
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp' ] .= ', `team_score`, `opp_team_score`';
    array_push($_SESSION['tableColHeadingsArray'], 'Team<br />Score', 'Opposition<br />Score');
    array_push($_SESSION['tableDataTypes'], 'int', 'int');

    // Finish table headings.
    $_SESSION['tableHeading1'] .= 'Match Scores Table';
 }

 function buildScoresChartMySQLquery($chartORhist)
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp']
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' `wickets_lost` as `wickets`, `team_score` as `runs`';
    $_SESSION['MySQLfromExp'   ] = ' `view_matches`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc';

    // Set horizontal stripe height (units are runs).
    $_SESSION['horizStripeHeight'] = 40;

    // Finish table headings.
    $_SESSION['tableHeading1'] = 'Team Scores ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildScoresChartMySQLquery().");
    }
    $_SESSION['chartVertAxisHeading' ] = 'Team Score';
    $_SESSION['chartHorizAxisHeading'] = 'Matches';
 }

 function buildWicketsTableMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp' ] .= ', `wickets_lost`, `wickets_taken`';
    array_push($_SESSION['tableColHeadingsArray'],
               'Team<br />Wickets<br />Lost', 'Opposition<br />Wickets<br />Lost');
    array_push($_SESSION['tableDataTypes'], 'int', 'int');

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Match Wickets Table';
 }

 function buildWicketsChartMySQLquery($chartORhist)
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp']
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' `wickets_lost` as `runs`, `team_score` as `wickets`';
    $_SESSION['MySQLfromExp'   ] = ' `view_matches`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc';

    // Set horizontal stripe height (units are wickets).
    $_SESSION['horizStripeHeight'] = 4;

    // Finish table headings.
    $_SESSION['tableHeading1'] = 'Team Wickets ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildWicketsChartMySQLquery().");
    }
    $_SESSION['chartVertAxisHeading' ] = 'Wickets Lost';
    $_SESSION['chartHorizAxisHeading'] = 'Matches';
 }

 function buildMarginsTableMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp' ] .= ', `margin`';
    array_push($_SESSION['tableColHeadingsArray'], 'Margin');
    array_push($_SESSION['tableDataTypes'], 'int');

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Match Margins Table';
 }

 function buildMarginsChartMySQLquery($chartORhist)
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp']
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' `margin` as `runs`, `wickets_lost` as `wickets`';
    $_SESSION['MySQLfromExp'   ] = ' `view_matches`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc';

    // Set horizontal stripe height (units are runs).
    $_SESSION['horizStripeHeight'] = 40;

    // Finish table headings.
    $_SESSION['tableHeading1'] = 'Match Winning Margins ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildMarginsChartMySQLquery().");
    }
    $_SESSION['chartVertAxisHeading' ] = 'Winning Margin';
    $_SESSION['chartHorizAxisHeading'] = 'Matches';
 }

 function buildHistoryTableMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp' ]
      .= ', `wickets_lost`, `team_score`, `wickets_taken`, `opp_team_score`, `margin`, `result`';
    array_push($_SESSION['tableColHeadingsArray'],
               'Team<br />Wickets<br />Lost', 'Team<br />Score',
               'Opposition<br />Wickets<br />Lost', 'Opposition<br />Score', 'Margin', 'Result');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int',
               'int', 'int', 'int', 'string');

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Match History Table';
 }
?>
