<?php
 require_once '../../../common/misc/misc_functions.php';

 /*
  *
  */
 function setPlayerSummarySessionVars()
 {
    $_SESSION['allPlayersIncluded'] = ($_SESSION['playerSelector'] == 'All Players'); // Boolean;

    setPlayerSummarySessionVarsForBattingTable();
    setPlayerSummarySessionVarsForBowlingTable();
    setPlayerSummarySessionVarsForOverallTable();
 }

 /*
  *
  */
 function setPlayerSummarySessionVarsForBattingTable()
 {
    // Build MySQL query components for totals. /////////////////////////////////////////////////
    $selectExp =  ' count(*) as `n_innings`,'
                . ' sum(`wickets_lost`) as `total_wickets_lost`,'
                . ' sum(`runs_scored`) as `total_runs_scored`,'
                . " max(`runs_scored`) as `max_runs_scored`\n";
    $fromExp   =  " `view_innings`\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'];

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForBattingTable().',
                 mysql_errno(), mysql_error()                      );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error(  "Unexpected result in {$_SERVER['PHP_SELF']}::"
            . 'setPlayerSummarySessionVarsForBattingTable().');

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    $_SESSION['n_innings'       ] = $dataRowArray['n_innings'         ];
    $_SESSION['totalWicketsLost'] = $dataRowArray['total_wickets_lost'];
    $_SESSION['totalRunsScored' ] = $dataRowArray['total_runs_scored' ];

    $_SESSION['bestInnings'] = array(2);
    $_SESSION['bestInnings']['runsScored'] = $dataRowArray['max_runs_scored'];

    mysql_free_result($qResult);

    // Build MySQL query components for best. ///////////////////////////////////////////////////
    $selectExp  =  (($_SESSION['allPlayersIncluded'])? ' `first_name`, `last_name`,': '')
                 . " date_format(`match_date`, '%d/%m/%Y') as `match_date`,"
                 . " `wickets_lost`, `opp_team_name`\n";
    $fromExp    =  " `view_innings`\n";
    $whereExp   =  ' and `runs_scored` = ' . $_SESSION['bestInnings']['runsScored'] . "\n";
    $orderByExp =  " `match_date` asc, `match_time` asc\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'    . $selectExp
                 . 'from'      . $fromExp
                 . 'where'     . $_SESSION['MySQLwhereExp'] . $whereExp
                 . 'order by ' . $orderByExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForBattingTable() (2).',
                 mysql_errno(), mysql_error()                          );

    // Get number of rows in result.
    $n_rows = mysql_num_rows($qResult);
      if ($n_rows < 1)
        error(  'Expected at least one row in result of MySQL query in'
              . " {$_SERVER['PHP_SELF']}::setPlayerSummarySessionVarsForBattingTable().");

    $_SESSION['bestInnings']['list'] = array($n_rows);

    for ($i = 0; $i < $n_rows; ++$i)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $nArrayElements = ($_SESSION['allPlayersIncluded'])? 4: 3;
       $_SESSION['bestInnings']['list'][$i] = array($nArrayElements);

       $_SESSION['bestInnings']['list'][$i]['matchDate'  ] = $dataRowArray['match_date'   ];
       $_SESSION['bestInnings']['list'][$i]['wicketsLost'] = $dataRowArray['wickets_lost' ];
       $_SESSION['bestInnings']['list'][$i]['oppTeamName'] = $dataRowArray['opp_team_name'];

       if ($_SESSION['allPlayersIncluded'])
         $_SESSION['bestInnings']['list'][$i]['name']
           = $dataRowArray['first_name'] . ' ' . $dataRowArray['last_name'];
    }

    mysql_free_result($qResult);
 }

 /*
  *
  */
 function setPlayerSummarySessionVarsForBowlingTable()
 {
    // Build MySQL query components. ////////////////////////////////////////////////////////////
    $selectExp =  ' count(*) as `n_overs`,'
                . ' sum(`wickets_taken`) as total_wickets_taken,'
                . ' sum(`runs_conceded`) as total_runs_conceded,'
                . " min(`runs_conceded`) as `min_runs_conceded`\n";
    $fromExp   =  " `view_overs`\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'];

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForBowlingTable() (1).',
                 mysql_errno(), mysql_error()                          );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error(  "Unexpected result in {$_SERVER['PHP_SELF']}::"
            . 'setPlayerSummarySessionVarsForBowlingTable() (1).');

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    $_SESSION['n_overs'          ] = $dataRowArray['n_overs'            ];
    $_SESSION['totalWicketsTaken'] = $dataRowArray['total_wickets_taken'];
    $_SESSION['totalRunsConceded'] = $dataRowArray['total_runs_conceded'];

    $_SESSION['bestOver'] = array(2);
    $_SESSION['bestOver']['runsConceded'] = $dataRowArray['min_runs_conceded'];

    mysql_free_result($qResult);

    // Build MySQL query components for best over. //////////////////////////////////////////////
    $selectExp  =  (($_SESSION['allPlayersIncluded'])? ' `first_name`, `last_name`,': '')
                 . " date_format(`match_date`, '%d/%m/%Y') as `match_date`,"
                 . " `wickets_taken`, `opp_team_name`\n";
    $fromExp    =  " `view_overs`\n";
    $whereExp   =  ' and `runs_conceded` = ' . $_SESSION['bestOver']['runsConceded'] . "\n";
    $orderByExp =  " `match_date` asc, `match_time` asc\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'    . $selectExp
                 . 'from'      . $fromExp
                 . 'where'     . $_SESSION['MySQLwhereExp'] . $whereExp
                 . 'order by ' . $orderByExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForBowlingTable() (2).',
                 mysql_errno(), mysql_error()                          );

    // Get number of rows in result.
    $n_rows = mysql_num_rows($qResult);
      if ($n_rows < 1)
        error(  'Expected at least one row in result of MySQL query in'
              . " {$_SERVER['PHP_SELF']}::setPlayerSummarySessionVarsForBowlingTable() (2)." );

    $_SESSION['bestOver']['list'] = array($n_rows);

    for ($i = 0; $i < $n_rows; ++$i)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $nArrayElements = ($_SESSION['allPlayersIncluded'])? 4: 3;
       $_SESSION['bestOver']['list'][$i] = array($nArrayElements);

       $_SESSION['bestOver']['list'][$i]['matchDate'   ] = $dataRowArray['match_date'   ];
       $_SESSION['bestOver']['list'][$i]['wicketsTaken'] = $dataRowArray['wickets_taken'];
       $_SESSION['bestOver']['list'][$i]['oppTeamName' ] = $dataRowArray['opp_team_name'];

       if ($_SESSION['allPlayersIncluded'])
         $_SESSION['bestOver']['list'][$i]['name']
           = $dataRowArray['first_name'] . ' ' . $dataRowArray['last_name'];
    }

    mysql_free_result($qResult);

    // Build MySQL query components for best match bowling figures (1st query). /////////////////
    $selectExp  =  ' min((2.0 * `runs_conceded_in_match`) / `n_overs_in_match`)'
                 . " as `normalised_runs_conceded_in_match`\n";
    $fromExp    =  " `view_match_bowling_figures`\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where'  . $_SESSION['MySQLwhereExp'];

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForBowlingTable() (3).',
                 mysql_errno(), mysql_error()                          );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error(  "Unexpected result in {$_SERVER['PHP_SELF']}::"
            . 'setPlayerSummarySessionVarsForBowlingTable() (3).');

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    $_SESSION['bestMBFigs'] = array(2);
    $_SESSION['bestMBFigs']['normalisedRunsConcededInMatch']
      = $dataRowArray['normalised_runs_conceded_in_match'];

    mysql_free_result($qResult);

    // Build MySQL query components for best match bowling figures (2nd query). /////////////////
    $selectExp  =  (($_SESSION['allPlayersIncluded'])? ' `first_name`, `last_name`,': '')
                 . " date_format(`match_date`, '%d/%m/%Y') as `match_date`,"
                 . ' (2.0 * `wickets_taken_in_match`) / `n_overs_in_match`'
                 . " as `normalised_wickets_taken_in_match`, `opp_team_name`\n";
    $fromExp    =  " `view_match_bowling_figures`\n";
    $whereExp   =  ' and (2.0 * `runs_conceded_in_match`) / `n_overs_in_match` = '
                 . $_SESSION['bestMBFigs']['normalisedRunsConcededInMatch'] . "\n";
    $orderByExp =  " `match_date` asc, `match_time` asc\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'    . $selectExp
                 . 'from'      . $fromExp
                 . 'where'     . $_SESSION['MySQLwhereExp'] . $whereExp
                 . 'order by ' . $orderByExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForBowlingTable() (4).',
                 mysql_errno(), mysql_error()                          );

    // Get number of rows in result.
    $n_rows = mysql_num_rows($qResult);
      if ($n_rows < 1)
        error(  'Expected at least one row in result of MySQL query in'
              . " {$_SERVER['PHP_SELF']}::setPlayerSummarySessionVarsForBowlingTable() (4).");

    $_SESSION['bestMBFigs']['list'] = array($n_rows);

    for ($i = 0; $i < $n_rows; ++$i)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $nArrayElements = ($_SESSION['allPlayersIncluded'])? 4: 3;
       $_SESSION['bestMBFigs']['list'][$i] = array($nArrayElements);

       $_SESSION['bestMBFigs']['list'][$i]['matchDate'  ] = $dataRowArray['match_date'   ];
       $_SESSION['bestMBFigs']['list'][$i]['oppTeamName'] = $dataRowArray['opp_team_name'];
       $_SESSION['bestMBFigs']['list'][$i]['normalisedWicketsTakenInMatch']
         = $dataRowArray['normalised_wickets_taken_in_match'];

       if ($_SESSION['allPlayersIncluded'])
         $_SESSION['bestMBFigs']['list'][$i]['name']
           = $dataRowArray['first_name'] . ' ' . $dataRowArray['last_name'];
    }

    mysql_free_result($qResult);
 }

 /*
  *
  */
 function setPlayerSummarySessionVarsForOverallTable()
 {
    // Build MySQL query components for totals. /////////////////////////////////////////////////
    $selectExp =  ' count(distinct `match_id`) as `n_matches`,'
                . ' max(`runs_scored_in_match` / `n_innings_in_match`'
                . ' - (2.0 * `runs_conceded_in_match`) / `n_overs_in_match`)'
                . " as `normalised_net_runs_scored_in_match`\n";
    $fromExp   =  " `view_match_contributions`\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select' . $selectExp
                 . 'from'   . $fromExp
                 . 'where ' . $_SESSION['MySQLwhereExp'];

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForOverallTable() (1).',
                 mysql_errno(), mysql_error()                          );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error(  "Unexpected result in {$_SERVER['PHP_SELF']}::"
            . 'setPlayerSummarySessionVarsForOverallTable() (1).');

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    $_SESSION['n_matches'  ] = $dataRowArray['n_matches'];
    $_SESSION['bestOverall'] = array(2);
    $_SESSION['bestOverall']['normalisedNetRunsScoredInMatch']
      = $dataRowArray['normalised_net_runs_scored_in_match'];

    mysql_free_result($qResult);

    // Build MySQL query components for best. ///////////////////////////////////////////////////
    $selectExp  =  (($_SESSION['allPlayersIncluded'])? ' `first_name`, `last_name`,': '')
                 . " date_format(`match_date`, '%d/%m/%Y') as `match_date`, `opp_team_name`,"
                 . ' ((2.0 * `wickets_taken_in_match`) / `n_overs_in_match`'
                 . ' - `wickets_lost_in_match` / `n_innings_in_match`)'
                 . " as `normalised_net_wickets_taken_in_match`\n";
    $fromExp    =  " `view_match_contributions`\n";
    $whereExp   =  ' and `runs_scored_in_match` / `n_innings_in_match`'
                 . ' - (2.0 * `runs_conceded_in_match`) / `n_overs_in_match` = '
                 . $_SESSION['bestOverall']['normalisedNetRunsScoredInMatch'] . "\n";
    $orderByExp =  " `match_date` asc, `match_time` asc\n";

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'   . $selectExp
                 . 'from'     . $fromExp
                 . 'where'    . $_SESSION['MySQLwhereExp'] . $whereExp
                 . 'order by' . $orderByExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror(  "Problem in {$_SERVER['PHP_SELF']}::"
                 . 'setPlayerSummarySessionVarsForOverallTable() (2).',
                 mysql_errno(), mysql_error()                          );

    // Get number of rows in result.
    $n_rows = mysql_num_rows($qResult);
      if ($n_rows < 1)
        error(  'Expected at least one row in result of MySQL query in'
              . " {$_SERVER['PHP_SELF']}::setPlayerSummarySessionVarsForOverallTable() (2).");

    $_SESSION['bestOverall']['list'] = array($n_rows);

    for ($i = 0; $i < $n_rows; ++$i)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $nArrayElements = ($_SESSION['allPlayersIncluded'])? 4: 3;
       $_SESSION['bestOverall']['list'][$i] = array($nArrayElements);

       $_SESSION['bestOverall']['list'][$i]['matchDate'  ] = $dataRowArray['match_date'   ];
       $_SESSION['bestOverall']['list'][$i]['oppTeamName'] = $dataRowArray['opp_team_name'];
       $_SESSION['bestOverall']['list'][$i]['normalisedNetWicketsTakenInMatch']
         = $dataRowArray['normalised_net_wickets_taken_in_match'];

       if ($_SESSION['allPlayersIncluded'])
         $_SESSION['bestOverall']['list'][$i]['name']
           = $dataRowArray['first_name'] . ' ' . $dataRowArray['last_name'];
    }

    mysql_free_result($qResult);
 }

 /*
  *
  */
 function displayPlayerStatsSummary()
 {
    // Test $_SESSION[] variables to be used in this function.

    if (   !isset($_SESSION['n_matches'       ]) // From innings table
        || !isset($_SESSION['n_innings'       ]) // ''
        || !isset($_SESSION['totalWicketsLost']) // ''
        || !isset($_SESSION['totalRunsScored' ]) // ''
        || !isset($_SESSION['n_overs'          ]) // From overs table
        || !isset($_SESSION['totalWicketsTaken']) // ''
        || !isset($_SESSION['totalRunsConceded']) // ''
        || !isset($_SESSION['bestInnings'         ])
        || !isset($_SESSION['bestOver'            ])
        || !isset($_SESSION['bestMBFigs'          ])
        || !isset($_SESSION['bestOverall'         ]))
      error(  'Required $_SESSION[] variable not set in '
            . "{$_SERVER['PHP_SELF']}::displayPlayerStatsSummary().");

    echoDoctypeXHTMLstrictString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   td {padding-left: 4px; padding-right: 4px;}
   th {padding-left: 4px; padding-right: 4px;}
   .padding0 {padding: 0;}
  </style>
  <title>IndoorCricketStats.net (Player Statistics Summary)</title>
 </head>
 <body>
  <table class="backh2"><?php /* NOTE: cellspacing="0" NOT required for outermost table. */ ?>
   <thead>
    <tr>
     <th class="h1 bordersTLRB" colspan="3">
      Player Statistics Summary (<?php echo $_SESSION['playerSelector']; ?>)
     </th>
    </tr>
<?php
    if ($_SESSION['restrictionsSubtitle'] != '')
    {
?>
    <tr>
     <th class="h2 bordersTLRB" colspan="3"><?php echo $_SESSION['restrictionsSubtitle']; ?></th>
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
       <thead>
        <tr>
         <th class="h2 borders__RB" colspan="2">Batting</th>
         <th class="h2 borders___B" colspan="3">Bowling</th>
         <th class="h2 borders_L_B" colspan="2">Overall</th>
        </tr>
       </thead>
       <tbody>
        <tr>
         <th class="h3 alignL borders__R_" colspan="2">Total</th>
         <th class="h3 alignL" colspan="3">Total</th>
         <th class="h3 alignL borders_L__" colspan="2">Total</th>
        </tr>
        <tr>
         <td class="l alignL">Innings:</td>
         <td class="l alignR borders__R_"><?php echo $_SESSION['n_innings']; ?></td>
         <td class="l alignL" colspan="2">Overs:</td>
         <td class="l alignR"><?php echo $_SESSION['n_overs'  ]; ?></td>
         <td class="l alignL borders_L__">Matches:</td>
         <td class="l alignR"><?php echo $_SESSION['n_matches']; ?></td>
        </tr>
<?php
    $netWicketsTaken = $_SESSION['totalWicketsTaken'] - $_SESSION['totalWicketsLost'];
?>
        <tr>
         <td class="d alignL">Wickets Lost:</td>
         <td class="d alignR borders__R_"><?php echo $_SESSION['totalWicketsLost']; ?></td>
         <td class="d alignL" colspan="2">Wickets Taken:</td>
         <td class="d alignR"><?php echo $_SESSION['totalWicketsTaken']; ?></td>
         <td class="d alignL borders_L__">Net Wickets Taken:</td>
         <td class="d alignR"><?php echo $netWicketsTaken; ?></td>
        </tr>
<?php
    $netRunsScored = $_SESSION['totalRunsScored'] - $_SESSION['totalRunsConceded'];
?>
        <tr>
         <td class="l alignL nowrap">Runs Scored:</td>
         <td class="l alignR borders__R_"><?php echo $_SESSION['totalRunsScored']; ?></td>
         <td class="l alignL nowrap" colspan="2">Runs Conceded:</td>
         <td class="l alignR"><?php echo $_SESSION['totalRunsConceded']; ?></td>
         <td class="l alignL nowrap borders_L__">Net Runs Scored:</td>
         <td class="l alignR"><?php echo $netRunsScored; ?></td>
        </tr>
        <tr>
         <th class="h3 alignL">Average</th>
         <th class="h3 alignR nowrap borders__R_">(per innings)</th>
         <th class="h3 alignL">Average</th>
         <th class="h3 alignR nowrap">(per over)</th>
         <th class="h3 alignR nowrap">(per match*)</th>
         <th class="h3 alignL borders_L__">Average</th>
         <th class="h3 alignR nowrap">(per match*)</th>
        </tr>
<?php
    $avgWicketsLostPerInnings   =        $_SESSION['totalWicketsLost' ]  / $_SESSION['n_innings'];
    $avgWicketsTakenPerOver     =        $_SESSION['totalWicketsTaken']  / $_SESSION['n_overs'  ];
    $avgWicketsTakenPerMatch    = (2.0 * $_SESSION['totalWicketsTaken']) / $_SESSION['n_overs'  ];
    $avgNetWicketsTakenPerMatch = $avgWicketsTakenPerMatch - $avgWicketsLostPerInnings;
    // NOTE: The above avgWicketsTakenPerMatch and avgNetWicketsTakenPerMatch
    //       calculations are normalised to counter the effect of extra innings/overs
    //       when short players.

?>
        <tr>
         <td class="l alignL nowrap">Wickets Lost:</td>
         <td class="l alignR borders__R_"><?php printf('%.3f', $avgWicketsLostPerInnings); ?></td>
         <td class="l alignL nowrap">Wickets Taken:</td>
         <td class="l alignR"><?php printf('%.3f', $avgWicketsTakenPerOver); ?></td>
         <td class="l alignR"><?php printf('%.3f', $avgWicketsTakenPerMatch); ?></td>
         <td class="l alignL nowrap borders_L__">Net Wickets Taken:</td>
         <td class="l alignR"><?php printf('%.3f', $avgNetWicketsTakenPerMatch); ?></td>
        </tr>
<?php
    $avgRunsScoredPerInnings  =        $_SESSION['totalRunsScored'  ]  / $_SESSION['n_innings'];
    $avgRunsConcededPerOver   =        $_SESSION['totalRunsConceded']  / $_SESSION['n_overs'  ];
    $avgRunsConcededPerMatch  = (2.0 * $_SESSION['totalRunsConceded']) / $_SESSION['n_overs'  ];
    $avgNetRunsScoredPerMatch = $avgRunsScoredPerInnings - $avgRunsConcededPerMatch;
    // NOTE: The above avgRunsConcededPerMatch and avgNetRunsScoredPerMatch
    //       calculations are normalised to counter the effect of extra innings/overs
    //       when short players.
?>
        <tr>
         <td class="d alignL nowrap">Runs Scored:</td>
         <td class="d alignR borders__R_"><?php printf('%.3f', $avgRunsScoredPerInnings); ?></td>
         <td class="d alignL nowrap">Runs Conceded:</td>
         <td class="d alignR"><?php printf('%.3f', $avgRunsConcededPerOver); ?></td>
         <td class="d alignR"><?php printf('%.3f', $avgRunsConcededPerMatch); ?></td>
         <td class="d alignL nowrap borders_L__">Net Runs Scored:</td>
         <td class="d alignR"><?php printf('%.3f', $avgNetRunsScoredPerMatch); ?></td>
        </tr>
        <tr>
         <th class="h3 alignL">Best</th>
         <th class="h3 alignR borders__R_">(innings)</th>
         <th class="h3 alignL">Best</th>
         <th class="h3 alignR">(over)</th>
         <th class="h3 alignR">(match*)</th>
         <th class="h3 alignL borders_L__">Best</th>
         <th class="h3 alignR">(match*)</th>
        </tr>
<?php
    // Convert numeric string to whole number if exact or round to 3 digits if not.
    $temp = (float)$_SESSION['bestMBFigs']['normalisedRunsConcededInMatch'];
    $bestMBFigsRunsConceded = convToIntOr3DigitDec($temp);

    // Convert numeric string to whole number if exact or round to 3 digits if not.
    $temp = (float)$_SESSION['bestOverall']['normalisedNetRunsScoredInMatch'];
    $bestOverallNetRunsConceded = convToIntOr3DigitDec($temp);
?>
        <tr>
         <td class="l alignL">Runs Scored:</td>
         <td class="l alignR borders__R_"><?php echo $_SESSION['bestInnings']['runsScored']; ?></td>
         <td class="l alignL">Runs Conceded:</td>
         <td class="l alignR"><?php echo $_SESSION['bestOver']['runsConceded']; ?></td>
         <td class="l alignR"><?php echo $bestMBFigsRunsConceded; ?></td>
         <td class="l alignL borders_L__">Net Runs Scored:</td>
         <td class="l alignR"><?php echo $bestOverallNetRunsConceded; ?></td>
        </tr>
<?php
    $n_equalInnings  = count($_SESSION['bestInnings']['list']);
    $n_equalOvers    = count($_SESSION['bestOver'   ]['list']);
    $n_equalMBFigs   = count($_SESSION['bestMBFigs' ]['list']);
    $n_equalOveralls = count($_SESSION['bestOverall']['list']);

    $n_equalBestRows
      = max($n_equalInnings, $n_equalOvers, $n_equalMBFigs , $n_equalOveralls);

    for ($i = 0; $i < $n_equalBestRows; ++$i)
    {
       $lORd = (($i % 2 == 0)? 'd': 'l'); // Shade of row (light or dark).

       if ($n_equalBestRows > 1 && $i == 0)
       {
          $otherlORd = (($lORd == 'l')? 'd': 'l');

          $n_eqInnsText     = getTextExp($n_equalInnings );
          $n_eqOversText    = getTextExp($n_equalOvers   );
          $n_eqMBFigsText   = getTextExp($n_equalMBFigs  );
          $n_eqOverallsText = getTextExp($n_equalOveralls);
?>
        <tr>
         <td class="<?php echo $otherlORd; ?> alignL nowrap">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR borders__R_"><?php echo $n_eqInnsText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignL nowrap">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqOversText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqMBFigsText; ?></td>
         <td class="<?php echo $otherlORd; ?> alignL nowrap borders_L__">Times Achieved:</td>
         <td class="<?php echo $otherlORd; ?> alignR"><?php echo $n_eqOverallsText; ?></td>
        </tr>
<?php
       }

       // Set innings heading and field variables.
       $printInnings = $i < $n_equalInnings; // Boolean.
       if ($printInnings)
       {
          $inningsDateH    = 'Date:';
          $inningsOppTeamH = 'Opposition:';
          $inningsWicketsH = 'Wickets Lost:';

          $inningsDate    = $_SESSION['bestInnings']['list'][$i]['matchDate'  ];
          $inningsOppTeam = $_SESSION['bestInnings']['list'][$i]['oppTeamName'];
          $inningsWickets = $_SESSION['bestInnings']['list'][$i]['wicketsLost'];
       }
       else
       {
          $inningsDateH    = '&nbsp;'; $inningsDate    = '&nbsp;';
          $inningsOppTeamH = '&nbsp;'; $inningsOppTeam = '&nbsp;';
          $inningsWicketsH = '&nbsp;'; $inningsWickets = '&nbsp;';
       }

       // Set over field variables.
       $printOver = $i < $n_equalOvers; // Boolean.
       if ($printOver)
       {
          $overDate    = $_SESSION['bestOver']['list'][$i]['matchDate'   ];
          $overOppTeam = $_SESSION['bestOver']['list'][$i]['oppTeamName' ];
          $overWickets = $_SESSION['bestOver']['list'][$i]['wicketsTaken'];
       }
       else
       {
          $overDate    = '&nbsp;';
          $overOppTeam = '&nbsp;';
          $overWickets = '&nbsp;';
       }

       // Set match bowling figures field variables.
       $printMBFigs = $i < $n_equalMBFigs; // Boolean.
       if ($printMBFigs)
       {
          $mBFigsDate    = $_SESSION['bestMBFigs']['list'][$i]['matchDate'  ];
          $mBFigsOppTeam = $_SESSION['bestMBFigs']['list'][$i]['oppTeamName'];

          // Convert numeric string to whole number if exact or round to 3 digits if not.
          $temp = $_SESSION['bestMBFigs']['list'][$i]['normalisedWicketsTakenInMatch'];
          $mBFigsWickets = convToIntOr3DigitDec($temp);
       }
       else
       {
          $mBFigsDate    = '&nbsp;';
          $mBFigsOppTeam = '&nbsp;';
          $mBFigsWickets = '&nbsp;';
       }

       // Set bowling heading variables.
       if ($printOver || $printMBFigs)
       {
          $bowlingDateH    = 'Date:';
          $bowlingOppTeamH = 'Opposition:';
          $bowlingWicketsH = 'Wickets Taken:';
       }
       else
       {
          $bowlingDateH    = '&nbsp;';
          $bowlingOppTeamH = '&nbsp;';
          $bowlingWicketsH = '&nbsp;';
       }

       // Set overall heading and field variables.
       $printOverall = $i < $n_equalOveralls; // Boolean.
       if ($printOverall)
       {
          $overallDateH    = 'Date:';
          $overallOppTeamH = 'Opposition:';
          $overallWicketsH = 'Net Wickets Taken:';

          $overallDate    = $_SESSION['bestOverall']['list'][$i]['matchDate'  ];
          $overallOppTeam = $_SESSION['bestOverall']['list'][$i]['oppTeamName'];

          // Convert numeric string to whole number if exact or round to 3 digits if not.
          $temp = $_SESSION['bestOverall']['list'][$i]['normalisedNetWicketsTakenInMatch'];
          $overallWickets = convToIntOr3DigitDec($temp);
       }
       else
       {
          $overallDateH    = '&nbsp;'; $overallDate    = '&nbsp;';
          $overallOppTeamH = '&nbsp;'; $overallOppTeam = '&nbsp;';
          $overallWicketsH = '&nbsp;'; $overallWickets = '&nbsp;';
       }

       // Set name heading and field variables if necessary.
       if ($_SESSION['allPlayersIncluded'])
       {
          $inningsNameH = ($printInnings             )? 'Name:': '&nbsp;';
          $bowlingNameH = ($printOver || $printMBFigs)? 'Name:': '&nbsp;';
          $overallNameH = ($printOverall             )? 'Name:': '&nbsp;';

          $inningsName  = ($printInnings)? $_SESSION['bestInnings']['list'][$i]['name']: '&nbsp;';
          $overName     = ($printOver   )? $_SESSION['bestOver'   ]['list'][$i]['name']: '&nbsp;';
          $mBFigsName   = ($printMBFigs )? $_SESSION['bestMBFigs' ]['list'][$i]['name']: '&nbsp;';
          $overallName  = ($printOverall)? $_SESSION['bestOverall']['list'][$i]['name']: '&nbsp;';
?>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $inningsNameH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $inningsName; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $bowlingNameH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overName; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $mBFigsName; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $overallNameH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overallName; ?></td>
        </tr>
<?php
       }
?>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $inningsDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $inningsDate; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $bowlingDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overDate; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $mBFigsDate; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $overallDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overallDate; ?></td>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $inningsOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $inningsOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $bowlingOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $mBFigsOppTeam; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $overallOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overallOppTeam; ?></td>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $inningsWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders__R_"><?php echo $inningsWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $bowlingWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $mBFigsWickets; ?></td>
         <td class="<?php echo $lORd; ?> alignL borders_L__"><?php echo $overallWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $overallWickets; ?></td>
        </tr>
<?php
   }
?>
       </tbody>
      </table>
     </td>
    </tr>
    <tr>
     <td class="bordersTLRB">
      * The 'per match' figures are normalised to counter the effect of extra innings/overs
        batted/bowled by players when the team is short players.&nbsp; In a normal match, players
        each bat one innings and bowl two overs, so 'per match' here means 'per two overs
        and one innings'.&nbsp; Fractional amounts are thus possible in the best 'per match'
        statistics.
     </td>
    </tr>
   </tbody>
  </table>
 </body>
</html>
<?php
}
?>
