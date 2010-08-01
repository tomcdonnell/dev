<?php
 require_once '../../../common/misc/misc_functions.php';

 /*
  *
  */
 function setTeamSummarySessionVars()
 {
    setTeamSummarySessionVarsP1();
    setTeamSummarySessionVarsP2('bestRunsScored'   , 'team_score'    , 'lost' );
    setTeamSummarySessionVarsP2('worstRunsScored'  , 'team_score'    , 'lost' );
    setTeamSummarySessionVarsP2('bestRunsConceded' , 'opp_team_score', 'taken');
    setTeamSummarySessionVarsP2('worstRunsConceded', 'opp_team_score', 'taken');
    setTeamSummarySessionVarsP2('bestMargin'       , 'margin'        , 'net'  );
    setTeamSummarySessionVarsP2('worstMargin'      , 'margin'        , 'net'  );
    //setTeamSummarySessionVarsStreaks();
 }

 /*
  *
  */
 function setTeamSummarySessionVarsP1()
 {
    // Build MySQL query components for totals, bests, and worsts. //////////////////////////////
    $selectExp =  ' count(*) as `n_matches`,'
                . ' count(distinct `opp_team_id`) as n_oppTeams,'
                . ' sum(`wickets_lost`) as `totalWicketsLost`,'
                . ' sum(`wickets_taken`) as `totalWicketsTaken`,'
                . ' sum(`team_score`) as `totalRunsScored`,'
                . ' sum(`opp_team_score`) as `totalRunsConceded`,'
                . ' max(`team_score`) as `bestRunsScored`,'
                . ' min(`team_score`) as `worstRunsScored`,'
                . ' min(`opp_team_score`) as `bestRunsConceded`,'
                . ' max(`opp_team_score`) as `worstRunsConceded`,'
                . ' max(`margin`) as `bestMargin`,'
                . ' min(`margin`) as `worstMargin`,'
                . ' max(`wickets_taken`) as `bestWicketsTaken`,'
                . ' min(`wickets_taken`) as `worstWicketsTaken`,'
                . ' min(`wickets_lost`) as `bestWicketsLost`,'
                . " max(`wickets_lost`) as `worstWicketsLost`\n";
    $fromExp   =  " `view_matches`\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'];

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1().",
                 mysql_errno(), mysql_error()                                        );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1().");

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    // Set $_SESSION[] variables for totals.
    $_SESSION['n_matches'        ] = $dataRowArray['n_matches'        ];
    $_SESSION['totalWicketsLost' ] = $dataRowArray['totalWicketsLost' ];
    $_SESSION['totalWicketsTaken'] = $dataRowArray['totalWicketsTaken'];
    $_SESSION['totalRunsScored'  ] = $dataRowArray['totalRunsScored'  ];
    $_SESSION['totalRunsConceded'] = $dataRowArray['totalRunsConceded'];

    // Create $_SESSION[] arrays for bests/worsts.
    $_SESSION['bestRunsScored'   ] = array(2);
    $_SESSION['worstRunsScored'  ] = array(2);
    $_SESSION['bestRunsConceded' ] = array(2);
    $_SESSION['worstRunsConceded'] = array(2);
    $_SESSION['bestMargin'       ] = array(2);
    $_SESSION['worstMargin'      ] = array(2);
    $_SESSION['bestWicketsTaken' ] = array(2);
    $_SESSION['worstWicketsTaken'] = array(2);
    $_SESSION['bestWicketsLost'  ] = array(2);
    $_SESSION['worstWicketsLost' ] = array(2);

    // Set $_SESSION[] variables for bests/worsts.
    $_SESSION['bestRunsScored'   ]['bestRunsScored'   ] = $dataRowArray['bestRunsScored'   ];
    $_SESSION['worstRunsScored'  ]['worstRunsScored'  ] = $dataRowArray['worstRunsScored'  ];
    $_SESSION['bestRunsConceded' ]['bestRunsConceded' ] = $dataRowArray['bestRunsConceded' ];
    $_SESSION['worstRunsConceded']['worstRunsConceded'] = $dataRowArray['worstRunsConceded'];
    $_SESSION['bestMargin'       ]['bestMargin'       ] = $dataRowArray['bestMargin'       ];
    $_SESSION['worstMargin'      ]['worstMargin'      ] = $dataRowArray['worstMargin'      ];
    $_SESSION['bestWicketsTaken' ]['bestWicketsTaken' ] = $dataRowArray['bestWicketsTaken' ];
    $_SESSION['worstWicketsTaken']['worstWicketsTaken'] = $dataRowArray['worstWicketsTaken'];
    $_SESSION['bestWicketsLost'  ]['bestWicketsLost'  ] = $dataRowArray['bestWicketsLost'  ];
    $_SESSION['worstWicketsLost' ]['worstWicketsLost' ] = $dataRowArray['worstWicketsLost' ];

    mysql_free_result($qResult);

    // Build MySQL query components for n_wins. /////////////////////////////////////////////////
    $selectExp = " count(*) as `n_wins`\n";
    $fromExp   = " `view_matches`\n";
    $whereExp  = " and `result` = 'W'\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'] . $whereExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1() (2).",
                 mysql_errno(), mysql_error()                                        );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1() (2).");

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    // Set $_SESSION[] variables for totals.
    $_SESSION['n_wins'] = $dataRowArray['n_wins'];

    mysql_free_result($qResult);

    // Build MySQL query components for n_draws. ////////////////////////////////////////////////
    $selectExp = " count(*) as `n_draws`\n";
    $fromExp   = " `view_matches`\n";
    $whereExp  = " and `result` = 'D'\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'] . $whereExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1() (3).",
                 mysql_errno(), mysql_error()                                            );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1() (3).");

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    // Set $_SESSION[] variables for totals.
    $_SESSION['n_draws'] = $dataRowArray['n_draws'];

    mysql_free_result($qResult);

    // Build MySQL query components for n_losses. ///////////////////////////////////////////////
    $selectExp = " count(*) as `n_losses`\n";
    $fromExp   = " `view_matches`\n";
    $whereExp  = " and `result` = 'L'\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'] . $whereExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1() (4).",
                 mysql_errno(), mysql_error()                                            );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP1() (4).");

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    // Set $_SESSION[] variables for totals.
    $_SESSION['n_losses'] = $dataRowArray['n_losses'];

    mysql_free_result($qResult);
 }

 /*
  * Precondition: $_SESSION[$varname][$varname] has been set
  *               to the relevant best or worst statistic.
  */
 function setTeamSummarySessionVarsP2($varName, $MySQLvarName, $lostORtakenORnet)
 {
    // Test $_SESSION[] variables to be used in this function.
    if (!isset($_SESSION[$varName][$varName]))
      error(  "Required \$_SESSION[] variable \$_SESSION['$varName']['$varName'] not set in "
            . "{$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP2()."                       );

    // Set wickets variables and test $lostORtakenORnet.
    switch ($lostORtakenORnet)
    {
     case 'lost':
       $MySQLwicketsVarName   = '`wickets_lost`';
       $MySQLwicketsAsVarName = 'wickets_lost';
       $wicketsVarName        = 'wicketsLost';
       break;
     case 'taken':
       $MySQLwicketsVarName   = '`wickets_taken`';
       $MySQLwicketsAsVarName = 'wickets_taken';
       $wicketsVarName        = 'wicketsTaken';
       break;
     case 'net':
       $MySQLwicketsVarName   = '`wickets_taken` - `wickets_lost`';
       $MySQLwicketsAsVarName = 'net_wickets_taken';
       $wicketsVarName        = 'netWicketsTaken';
       break;
     default:
      error(  "Expected 'lost' or 'taken' or 'net', received '$lostORtakenORnet' in "
            . "{$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP2()."               );
    }

    // Build MySQL query components. ////////////////////////////////////////////////////////////
    $selectExp  =  " date_format(`match_date`, '%d/%m/%Y') as `match_date`, `opp_team_name`,"
                 . " $MySQLwicketsVarName as `$MySQLwicketsAsVarName`\n";
    $fromExp    = " `view_matches`\n";
    $whereExp   = " and `$MySQLvarName` = {$_SESSION[$varName][$varName]}\n";
    $orderByExp = ' `match_date` asc, `match_time` asc';

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'   . $selectExp
                 . 'from'     . $fromExp
                 . 'where'    . $_SESSION['MySQLwhereExp'] . $whereExp
                 . 'order by' . $orderByExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP2().",
                 mysql_errno(), mysql_error()                                        );

    // Get number of rows in result.
    $n_rows = mysql_num_rows($qResult);
      if ($n_rows < 1)
        error(  'Expected at least one row in result of MySQL query in'
              . " {$_SERVER['PHP_SELF']}::setTeamSummarySessionVarsP2().");

    $_SESSION[$varName]['list'] = array($n_rows);

    for ($i = 0; $i < $n_rows; ++$i)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $_SESSION[$varName]['list'][$i] = array(3);

       $_SESSION[$varName]['list'][$i]['matchDate'    ] = $dataRowArray['match_date'          ];
       $_SESSION[$varName]['list'][$i]['oppTeamName'  ] = $dataRowArray['opp_team_name'       ];
       $_SESSION[$varName]['list'][$i][$wicketsVarName] = $dataRowArray[$MySQLwicketsAsVarName];
    }

    mysql_free_result($qResult);
 }

 /*
  *
  */
 function setTeamSummarySessionVarsStreaks()
 {
 }

 /*
  *
  */
 function displayTeamStatsSummary()
 {
    // Test $_SESSION[] variables to be used in this function.
    if (   !isset($_SESSION['n_matches'])
        || !isset($_SESSION['n_wins'   ])
        || !isset($_SESSION['n_losses' ])
        || !isset($_SESSION['n_draws'  ])

        || !isset($_SESSION['totalWicketsLost' ])
        || !isset($_SESSION['totalWicketsTaken'])
        || !isset($_SESSION['totalRunsScored'  ])
        || !isset($_SESSION['totalRunsConceded'])

        //|| !isset($_SESSION['longestWinningStreak'])  // --\ Array[2] (n_matches, list)
        //|| !isset($_SESSION['longestLosingStreak' ])  // --/ list is an array of 2D arrays, each
                                                      //     containing: (startDate, finishDate).

        || !isset($_SESSION['bestRunsScored' ])    // --\ Array[2] (teamScore, list)
        || !isset($_SESSION['worstRunsScored'])    // --/ list is an array of 2D arrays, each
                                                   //     containing: (matchDate, oppTeamName).

        || !isset($_SESSION['bestRunsConceded' ])  // --\ Array[2] (oppTeamScore, list)
        || !isset($_SESSION['worstRunsConceded'])  // --/ list is an array of 2D arrays, each
                                                   //     containing: (matchDate, oppTeamName).

        || !isset($_SESSION['bestMargin' ])        // --\ Array[2] (winningMargin, list)
        || !isset($_SESSION['worstMargin'])        // --/ list is an array of 2D arrays, each
                                                   //     containing: (matchDate, oppTeamName).

        || !isset($_SESSION['bestWicketsTaken' ])  // --\ Array[2] (wicketsTaken, list)
        || !isset($_SESSION['worstWicketsTaken'])  // --/ list is an array of 2D arrays, each
                                                   //     containing: (matchDate, oppTeamName).

        || !isset($_SESSION['bestWicketsLost' ])   // --\ Array[2] (wicketsLost, list)
        || !isset($_SESSION['worstWicketsLost']) ) // --/ list is an array of 2D arrays, each
                                                   //     containing: (matchDate, oppTeamName).

      error(  'Required $_SESSION[] variable not set in '
            . "{$_SERVER['PHP_SELF']}::displayTeamStatsSummary().");

    echoDoctypeXHTMLtransitionalString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   td {padding-left: 4px; padding-right: 4px;}
   th {padding-left: 4px; padding-right: 4px;}
   .padding0 {padding: 0;}
  </style>
  <title>IndoorCricketStats.net (Team Statistics Summary)</title>
 </head>
 <body>
  <table class="backh2"><?php /* NOTE: cellspacing="0" NOT required for outermost table. */ ?>
   <thead>
    <tr><th class="h1 bordersTLRB">Team Statistics Summary</th></tr>
<?php
    if ($_SESSION['restrictionsSubtitle'] != '')
    {
?>
    <tr>
     <th class="h2 bordersTLRB"><?php echo $_SESSION['restrictionsSubtitle']; ?></th>
    </tr>
<?php
    }
?>
   </thead>
   <tbody>
    <tr>
     <td class="padding0">
      <table class="bordersTLRB" width="100%" cellspacing="0">
<?php /* NOTE: cellspacing="0" in table declaration above is required by IE. */ ?>
       <tbody>
        <tr><th class="h2 borders___B" colspan="5">Results</th></tr>
        <tr>
         <th class="h3l" width="20%">Played</th>
         <th class="h3d" width="20%">Won</th>
         <th class="h3l" width="20%">Drawn</th>
         <th class="h3d" width="20%">Lost</th>
         <th class="h3l" width="20%">Win %</th>
        </tr>
<?php
    $winsPerMatch = $_SESSION['n_wins'] / $_SESSION['n_matches'];
?>
        <tr>
         <td class="l"><?php echo $_SESSION['n_matches' ]; ?></td>
         <td class="d"><?php echo $_SESSION['n_wins'  ]; ?></td>
         <td class="l"><?php echo $_SESSION['n_draws' ]; ?></td>
         <td class="d"><?php echo $_SESSION['n_losses']; ?></td>
         <td class="l"><?php printf('%.2f', 100 * $winsPerMatch); ?>%</td>
        </tr>
       </tbody>
      </table>
     </td>
    </tr>
<?php
/*
    <tr>
     <td class="padding0">
      <table class="bordersTLRB" width="100%" cellspacing="0">
// NOTE: cellspacing="0" in table declaration above is required by IE.
       <tbody>
        <tr>
         <th class="h2 borders__RB" colspan="3">Best Winning Streak</th>
         <th class="h2 borders___B" colspan="3">Worst Losing Streak</th>
        </tr>
        <tr>
         <th class="h3l" width="17%">No. Matches</th>
         <th class="h3d" width="16%">Start Date</th>
         <th class="h3l borders__R_" width="17%">Finish Date</th>
         <th class="h3l" width="17%">No. Matches</th>
         <th class="h3d" width="16%">Start Date</th>
         <th class="h3l" width="17%">Finish Date</th>
        </tr>
        <tr>
         <td class="l">4</td>
         <td class="d">06/12/2004</td>
         <td class="l borders__R_">24/02/2005</td>
         <td class="l">4</td>
         <td class="d">06/12/2004</td>
         <td class="l">24/02/2005</td>
        </tr>
       </tbody>
      </table>
     </td>
    </tr>
*/
?>
    <tr>
     <td class="padding0">
      <table class="bordersTLRB" width="100%" cellspacing="0">
<?php /* NOTE: cellspacing="0" in table declaration above is required by IE. */ ?>
       <tbody>
        <tr>
         <th class="h2 borders__RB" colspan="2" width="33%">Batting</th>
         <th class="h2 borders___B" colspan="2" width="34%">Bowling / Fielding</th>
         <th class="h2 borders_L_B" colspan="2" width="33%">Overall</th>
        </tr>
        <tr>
         <th class="h3 alignL borders__R_" colspan="2">Total</th>
         <th class="h3 alignL" colspan="2">Total</th>
         <th class="h3 alignL borders_L__" colspan="2">Total</th>
        </tr>
<?php
    $netWicketsTaken = $_SESSION['totalWicketsTaken'] - $_SESSION['totalWicketsLost'];
?>
        <tr>
         <td class="l alignL">Wickets Lost:</td>
         <td class="l alignR borders__R_"><?php echo $_SESSION['totalWicketsLost']; ?></td>
         <td class="l alignL">Wickets Taken:</td>
         <td class="l alignR"><?php echo $_SESSION['totalWicketsTaken']; ?></td>
         <td class="l alignL borders_L__">Net Wickets Taken:</td>
         <td class="l alignR"><?php echo $netWicketsTaken; ?></td>
        </tr>
<?php
    $netRunsScored = $_SESSION['totalRunsScored'] - $_SESSION['totalRunsConceded'];
?>
        <tr>
         <td class="d alignL nowrap">Runs Scored:</td>
         <td class="d alignR borders__R_"><?php echo $_SESSION['totalRunsScored']; ?></td>
         <td class="d alignL nowrap">Runs Conceded:</td>
         <td class="d alignR"><?php echo $_SESSION['totalRunsConceded']; ?></td>
         <td class="d alignL nowrap borders_L__">Net Runs Scored:</td>
         <td class="d alignR"><?php echo $netRunsScored; ?></td>
        </tr>
        <tr>
         <th class="h3 alignL">Average</th>
         <th class="h3 alignR nowrap borders__R_">(per match)</th>
         <th class="h3 alignL">Average</th>
         <th class="h3 alignR nowrap">(per match)</th>
         <th class="h3 alignL borders_L__">Average</th>
         <th class="h3 alignR nowrap">(per match)</th>
        </tr>
<?php
    $avgWicketsLostPerMatch     = $_SESSION['totalWicketsLost' ] / $_SESSION['n_matches'];
    $avgWicketsTakenPerMatch    = $_SESSION['totalWicketsTaken'] / $_SESSION['n_matches'];
    $avgNetWicketsTakenPerMatch = $avgWicketsTakenPerMatch - $avgWicketsLostPerMatch;
?>
        <tr>
         <td class="l alignL nowrap">Wickets Lost:</td>
         <td class="l alignR borders__R_"><?php printf('%.3f', $avgWicketsLostPerMatch); ?></td>
         <td class="l alignL nowrap">Wickets Taken:</td>
         <td class="l alignR"><?php printf('%.3f', $avgWicketsTakenPerMatch); ?></td>
         <td class="l alignL nowrap borders_L__">Net Wickets Taken:</td>
         <td class="l alignR"><?php printf('%.3f', $avgNetWicketsTakenPerMatch); ?></td>
        </tr>
<?php
    $avgRunsScoredPerMatch    = $_SESSION['totalRunsScored'  ] / $_SESSION['n_matches'];
    $avgRunsConcededPerMatch  = $_SESSION['totalRunsConceded'] / $_SESSION['n_matches'];
    $avgNetRunsScoredPerMatch = $avgRunsScoredPerMatch - $avgRunsConcededPerMatch;
?>
        <tr>
         <td class="d alignL nowrap">Runs Scored:</td>
         <td class="d alignR borders__R_"><?php printf('%.3f', $avgRunsScoredPerMatch); ?></td>
         <td class="d alignL nowrap">Runs Conceded:</td>
         <td class="d alignR"><?php printf('%.3f', $avgRunsConcededPerMatch); ?></td>
         <td class="d alignL nowrap borders_L__">Net Runs Scored:</td>
         <td class="d alignR"><?php printf('%.3f', $avgNetRunsScoredPerMatch); ?></td>
        </tr>
        <tr>
         <th class="h3 alignL">Best</th>
         <th class="h3 alignR borders__R_">(match)</th>
         <th class="h3 alignL">Best</th>
         <th class="h3 alignR">(match)</th>
         <th class="h3 alignL borders_L__">Best</th>
         <th class="h3 alignR">(match)</th>
        </tr>
<?php
    // Abbreviations.
    $bestRunsScored   = $_SESSION['bestRunsScored'  ]['bestRunsScored'  ];
    $bestRunsConceded = $_SESSION['bestRunsConceded']['bestRunsConceded'];
    $bestMargin       = $_SESSION['bestMargin'      ]['bestMargin'      ];
?>
        <tr>
         <td class="l alignL">Runs Scored:</td>
         <td class="l alignR borders__R_"><?php echo $bestRunsScored; ?></td>
         <td class="l alignL">Runs Conceded:</td>
         <td class="l alignR"><?php echo $bestRunsConceded; ?></td>
         <td class="l alignL borders_L__">Net Runs Scored:</td>
         <td class="l alignR"><?php echo $bestMargin; ?></td>
        </tr>
<?php
    $n_equalBestRunsScored   = count($_SESSION['bestRunsScored'  ]['list']);
    $n_equalBestRunsConceded = count($_SESSION['bestRunsConceded']['list']);
    $n_equalBestMargins      = count($_SESSION['bestMargin'      ]['list']);

    $n_equalBestRows
      = max($n_equalBestRunsScored, $n_equalBestRunsConceded, $n_equalBestMargins);

    for ($i = 0; $i < $n_equalBestRows; ++$i)
    {
       $lORd = (($i % 2 == 0)? 'd': 'l'); // Shade of row (light or dark).

       if ($n_equalBestRows > 1 && $i == 0)
       {
          $otherlORd = (($lORd == 'l')? 'd': 'l');

          $n_eqBRSText     = getTextExp($n_equalBestRunsScored  );
          $n_eqBRCText     = getTextExp($n_equalBestRunsConceded);
          $n_eqBMarginText = getTextExp($n_equalBestMargins     );
?>
        <tr>
         <td class="<?php echo $otherlORd; ?> alignL nowrap">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR borders__R_"><?php echo $n_eqBRSText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignL nowrap">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqBRCText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignL nowrap borders_L__">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqBMarginText; ?></td>
        </tr>
<?php
       }

       // Set runsScored heading and field variables.
       $printBestRunsScored = $i < $n_equalBestRunsScored; // Boolean.
       if ($printBestRunsScored)
       {
          $runsScoredDateH    = 'Date:';
          $runsScoredOppTeamH = 'Opposition:';
          $runsScoredWicketsH = 'Wickets Lost:';

          $runsScoredDate    = $_SESSION['bestRunsScored']['list'][$i]['matchDate'  ];
          $runsScoredOppTeam = $_SESSION['bestRunsScored']['list'][$i]['oppTeamName'];
          $runsScoredWickets = $_SESSION['bestRunsScored']['list'][$i]['wicketsLost'];
       }
       else
       {
          $runsScoredDateH    = '&nbsp;'; $runsScoredDate    = '&nbsp;';
          $runsScoredOppTeamH = '&nbsp;'; $runsScoredOppTeam = '&nbsp;';
          $runsScoredWicketsH = '&nbsp;'; $runsScoredWickets = '&nbsp;';
       }

       // Set runsConceded field variables.
       $printBestRunsConceded = $i < $n_equalBestRunsConceded; // Boolean.
       if ($printBestRunsConceded)
       {
          $runsConcededDateH    = 'Date:';
          $runsConcededOppTeamH = 'Opposition:';
          $runsConcededWicketsH = 'Wickets Taken:';

          $runsConcededDate    = $_SESSION['bestRunsConceded']['list'][$i]['matchDate'   ];
          $runsConcededOppTeam = $_SESSION['bestRunsConceded']['list'][$i]['oppTeamName' ];
          $runsConcededWickets = $_SESSION['bestRunsConceded']['list'][$i]['wicketsTaken'];
       }
       else
       {
          $runsConcededDateH    = '&nbsp;'; $runsConcededDate    = '&nbsp;';
          $runsConcededOppTeamH = '&nbsp;'; $runsConcededOppTeam = '&nbsp;';
          $runsConcededWicketsH = '&nbsp;'; $runsConcededWickets = '&nbsp;';
       }

       // Set overall heading and field variables.
       $printBestMargin = $i < $n_equalBestMargins; // Boolean.
       if ($printBestMargin)
       {
          $marginDateH    = 'Date:';
          $marginOppTeamH = 'Opposition:';
          $marginWicketsH = 'Net Wickets Taken:';

          $marginDate    = $_SESSION['bestMargin']['list'][$i]['matchDate'      ];
          $marginOppTeam = $_SESSION['bestMargin']['list'][$i]['oppTeamName'    ];
          $marginWickets = $_SESSION['bestMargin']['list'][$i]['netWicketsTaken'];
       }
       else
       {
          $marginDateH    = '&nbsp;'; $marginDate    = '&nbsp;';
          $marginOppTeamH = '&nbsp;'; $marginOppTeam = '&nbsp;';
          $marginWicketsH = '&nbsp;'; $marginWickets = '&nbsp;';
       }
?>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $runsScoredDate; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsConcededDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsConcededDate; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $marginDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $marginDate; ?></td>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $runsScoredOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsConcededOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsConcededOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $marginOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $marginOppTeam; ?></td>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $runsScoredWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsConcededWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsConcededWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $marginWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $marginWickets; ?></td>
        </tr>
<?php
   }
?>
        <tr>
         <th class="h3 alignL">Worst</th>
         <th class="h3 alignR borders__R_">(match)</th>
         <th class="h3 alignL">Worst</th>
         <th class="h3 alignR">(match)</th>
         <th class="h3 alignL borders_L__">Worst</th>
         <th class="h3 alignR">(match)</th>
        </tr>
<?php
    // Abbreviations.
    $worstRunsScored   = $_SESSION['worstRunsScored'  ]['worstRunsScored'  ];
    $worstRunsConceded = $_SESSION['worstRunsConceded']['worstRunsConceded'];
    $worstMargin       = $_SESSION['worstMargin'      ]['worstMargin'      ];
?>
        <tr>
         <td class="l alignL">Runs Scored:</td>
         <td class="l alignR borders__R_"><?php echo $worstRunsScored; ?></td>
         <td class="l alignL">Runs Conceded:</td>
         <td class="l alignR"><?php echo $worstRunsConceded; ?></td>
         <td class="l alignL borders_L__">Net Runs Scored:</td>
         <td class="l alignR"><?php echo $worstMargin; ?></td>
        </tr>
<?php
    $n_equalWorstRunsScored   = count($_SESSION['worstRunsScored'  ]['list']);
    $n_equalWorstRunsConceded = count($_SESSION['worstRunsConceded']['list']);
    $n_equalWorstMargins      = count($_SESSION['worstMargin'      ]['list']);

    $n_equalWorstRows
      = max($n_equalWorstRunsScored, $n_equalWorstRunsConceded, $n_equalWorstMargins);

    for ($i = 0; $i < $n_equalWorstRows; ++$i)
    {
       $lORd = (($i % 2 == 0)? 'd': 'l'); // Shade of row (light or dark).

       if ($n_equalWorstRows > 1 && $i == 0)
       {
          $otherlORd = (($lORd == 'l')? 'd': 'l');

          $n_eqBRSText     = getTextExp($n_equalWorstRunsScored  );
          $n_eqBRCText     = getTextExp($n_equalWorstRunsConceded);
          $n_eqBMarginText = getTextExp($n_equalWorstMargins     );
?>
        <tr>
         <td class="<?php echo $otherlORd; ?> alignL nowrap">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR borders__R_"><?php echo $n_eqBRSText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignL nowrap">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqBRCText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignL nowrap borders_L__">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqBMarginText; ?></td>
        </tr>
<?php
       }

       // Set runsScored heading and field variables.
       $printWorstRunsScored = $i < $n_equalWorstRunsScored; // Boolean.
       if ($printWorstRunsScored)
       {
          $runsScoredDateH    = 'Date:';
          $runsScoredOppTeamH = 'Opposition:';
          $runsScoredWicketsH = 'Wickets Lost:';

          $runsScoredDate    = $_SESSION['worstRunsScored']['list'][$i]['matchDate'  ];
          $runsScoredOppTeam = $_SESSION['worstRunsScored']['list'][$i]['oppTeamName'];
          $runsScoredWickets = $_SESSION['worstRunsScored']['list'][$i]['wicketsLost'];
       }
       else
       {
          $runsScoredDateH    = '&nbsp;'; $runsScoredDate    = '&nbsp;';
          $runsScoredOppTeamH = '&nbsp;'; $runsScoredOppTeam = '&nbsp;';
          $runsScoredWicketsH = '&nbsp;'; $runsScoredWickets = '&nbsp;';
       }

       // Set runsConceded field variables.
       $printWorstRunsConceded = $i < $n_equalWorstRunsConceded; // Boolean.
       if ($printWorstRunsConceded)
       {
          $runsConcededDateH    = 'Date:';
          $runsConcededOppTeamH = 'Opposition:';
          $runsConcededWicketsH = 'Wickets Taken:';

          $runsConcededDate    = $_SESSION['worstRunsConceded']['list'][$i]['matchDate'   ];
          $runsConcededOppTeam = $_SESSION['worstRunsConceded']['list'][$i]['oppTeamName' ];
          $runsConcededWickets = $_SESSION['worstRunsConceded']['list'][$i]['wicketsTaken'];
       }
       else
       {
          $runsConcededDateH    = '&nbsp;'; $runsConcededDate    = '&nbsp;';
          $runsConcededOppTeamH = '&nbsp;'; $runsConcededOppTeam = '&nbsp;';
          $runsConcededWicketsH = '&nbsp;'; $runsConcededWickets = '&nbsp;';
       }

       // Set overall heading and field variables.
       $printWorstMargin = $i < $n_equalWorstMargins; // Boolean.
       if ($printWorstMargin)
       {
          $marginDateH    = 'Date:';
          $marginOppTeamH = 'Opposition:';
          $marginWicketsH = 'Net Wickets Taken:';

          $marginDate    = $_SESSION['worstMargin']['list'][$i]['matchDate'      ];
          $marginOppTeam = $_SESSION['worstMargin']['list'][$i]['oppTeamName'    ];
          $marginWickets = $_SESSION['worstMargin']['list'][$i]['netWicketsTaken'];
       }
       else
       {
          $marginDateH    = '&nbsp;'; $marginDate    = '&nbsp;';
          $marginOppTeamH = '&nbsp;'; $marginOppTeam = '&nbsp;';
          $marginWicketsH = '&nbsp;'; $marginWickets = '&nbsp;';
       }
?>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $runsScoredDate; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsConcededDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsConcededDate; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $marginDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $marginDate; ?></td>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $runsScoredOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsConcededOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsConcededOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $marginOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $marginOppTeam; ?></td>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $runsScoredWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsConcededWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsConcededWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $marginWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $marginWickets; ?></td>
        </tr>
<?php
   }
?>
       </tbody>
      </table>
     </td>
    </tr>
   </tbody>
  </table>
 </body>
</html>
<?php
}
?>
