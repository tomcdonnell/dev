<?php
 require_once '../../../common/misc/misc_functions.php';

 /*
  *
  */
 function setBPshipsSummarySessionVars()
 {
    setBPshipsSummarySessionVarsP1();
    setBPshipsSummarySessionVarsP2();
 }

 /*
  * Part 1.
  */
 function setBPshipsSummarySessionVarsP1()
 {
    $n_partnersSelected = 0;
    if ($_SESSION['batsmanOneNameSelector'] != 'All Partners') ++$n_partnersSelected;
    if ($_SESSION['batsmanTwoNameSelector'] != 'All Partners') ++$n_partnersSelected;

    // Build MySQL query components for totals, bests, and worsts. //////////////////////////////
    $selectExp = (($n_partnersSelected > 0)? (  " `p1_first_name`, `p1_last_name`,\n"
                                              . " `p2_first_name`, `p2_last_name`,\n"): '')
                . " count(*) as `n_pships`,\n"
                . " sum(`partnership_wickets`) as `totalPshipWicketsLost`,\n"
                . " sum(`partnership_runs`) as `totalPshipRunsScored`,\n"
                . " sum(`p1_wickets_lost`) as `totalP1WicketsLost`,\n"
                . " sum(`p1_runs_scored`) as `totalP1RunsScored`,\n"
                . " sum(`p2_wickets_lost`) as `totalP2WicketsLost`,\n"
                . " sum(`p2_runs_scored`) as `totalP2RunsScored`,\n"
                . " max(`partnership_runs`) as `bestPshipRunsScored`";

    switch ($n_partnersSelected)
    {
     case 0:
       // No partners are selected.

       $_SESSION['MySQLfromExp'] = " `view_batting_partnerships`";
       $groupByExp = '';
       break;

     case 1:
       echo "<p>This feature is not yet implemented.</p>\n";
       return;
       break;

     case 2:
       // Both partners are selected.

       $c = 'case when `p1_player_id` < `p2_player_id` then'; // condition for 'fromExp' below
       $_SESSION['MySQLfromExp']
         =  "\n"
          . "(\n"
          . "   select `team_id`, `opp_team_name`, `match_date`, `match_time`,\n"
          . "   (" . $c . " `p1_player_id`    else `p2_player_id`    end) as `p1_player_id`,\n"
          . "   (" . $c . " `p1_batting_pos`  else `p2_batting_pos`  end) as `p1_batting_pos`,\n"
          . "   (" . $c . " `p1_first_name`   else `p2_first_name`   end) as `p1_first_name`,\n"
          . "   (" . $c . " `p1_last_name`    else `p2_last_name`    end) as `p1_last_name`,\n"
          . "   (" . $c . " `p1_wickets_lost` else `p2_wickets_lost` end) as `p1_wickets_lost`,\n"
          . "   (" . $c . " `p1_runs_scored`  else `p2_runs_scored`  end) as `p1_runs_scored`,\n"
          . "   (" . $c . " `p2_player_id`    else `p1_player_id`    end) as `p2_player_id`,\n"
          . "   (" . $c . " `p2_batting_pos`  else `p1_batting_pos`  end) as `p2_batting_pos`,\n"
          . "   (" . $c . " `p2_first_name`   else `p1_first_name`   end) as `p2_first_name`,\n"
          . "   (" . $c . " `p2_last_name`    else `p1_last_name`    end) as `p2_last_name`,\n"
          . "   (" . $c . " `p2_wickets_lost` else `p1_wickets_lost` end) as `p2_wickets_lost`,\n"
          . "   (" . $c . " `p2_runs_scored`  else `p1_runs_scored`  end) as `p2_runs_scored`,\n"
          . "   `partnership_wickets`, `partnership_runs`,\n"
          . "   (\n"
          . "      case\n"
          . "         when `p1_player_id` < `p2_player_id`\n"
          . "         then `p1_player_id` + 1000000 * `p2_player_id`\n"
          . "         else `p2_player_id` + 1000000 * `p1_player_id`\n"
          . "      end\n"
          . "   ) as `partnership_group`\n"
          . "   from `view_batting_partnerships`\n"
          . "   where `team_id` = {$_SESSION['teamID']}\n"
          . ") as `dummy`";
       $groupByExp = ' `partnership_group`';
       break;

     default:
       error(  'Unexpected $n_partnersSelected (' . $n_partnersSelected
             . ') received in ' . $_SERVER['PHP_SELF'] . ':setBPshipsSummarySessionVarsP1().');
    }

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'   . $selectExp                 . "\n"
                 . 'from'     . $_SESSION['MySQLfromExp' ] . "\n"
                 . 'where'    . $_SESSION['MySQLwhereExp']
                 . (($groupByExp == '')? '': "\ngroup by" . $groupByExp);

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setBPshipsSummarySessionVarsP1().",
                 mysql_errno(), mysql_error()                                        );

    // Test for 1 row in result.
    if (mysql_num_rows($qResult) != 1)
      error("Unexpected result in {$_SERVER['PHP_SELF']}::setBPshipsSummarySessionVarsP1().");

    // Get row.
    $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

    // Set $_SESSION[] variables for totals.
    if ($n_partnersSelected > 0)
    {
       $_SESSION['p1FirstName'] = $dataRowArray['p1_first_name'];
       $_SESSION['p1LastName' ] = $dataRowArray['p1_last_name' ];
       $_SESSION['p2FirstName'] = $dataRowArray['p2_first_name'];
       $_SESSION['p2LastName' ] = $dataRowArray['p2_last_name' ];
    }

    $_SESSION['n_pships'             ] = $dataRowArray['n_pships'             ];
    $_SESSION['totalPshipWicketsLost'] = $dataRowArray['totalPshipWicketsLost'];
    $_SESSION['totalPshipRunsScored' ] = $dataRowArray['totalPshipRunsScored' ];
    $_SESSION['totalP1WicketsLost'   ] = $dataRowArray['totalP1WicketsLost'   ];
    $_SESSION['totalP1RunsScored'    ] = $dataRowArray['totalP1RunsScored'    ];
    $_SESSION['totalP2WicketsLost'   ] = $dataRowArray['totalP2WicketsLost'   ];
    $_SESSION['totalP2RunsScored'    ] = $dataRowArray['totalP2RunsScored'    ];

    // Create $_SESSION[] arrays for bests/worsts.
    $_SESSION['bestPshipRunsScored'] = array(2);

    // Set $_SESSION[] variables for bests/worsts.
    $_SESSION['bestPshipRunsScored']['bestPshipRunsScored'] = $dataRowArray['bestPshipRunsScored'];

    mysql_free_result($qResult);
 }

 /*
  * Part 2.
  */
 function setBPshipsSummarySessionVarsP2()
 {
    // START: Temporary section. ////////////////////////////////////////////////////////////////
    // NOTE: This section should be removed when the code for 1 selected
    //       partner is completed in the setBPshipsSummarySessionVarsP1() function.
    $n_partnersSelected = 0;
    if ($_SESSION['batsmanOneNameSelector'] != 'All Partners') ++$n_partnersSelected;
    if ($_SESSION['batsmanTwoNameSelector'] != 'All Partners') ++$n_partnersSelected;
    if ($n_partnersSelected == 1)
      return;
    // FINISH: Temporary section. ///////////////////////////////////////////////////////////////

    // Test $_SESSION[] variables to be used in this function.
    if (   !isset($_SESSION['MySQLfromExp'])
        || !isset($_SESSION['bestPshipRunsScored']['bestPshipRunsScored']))
      error(  'Required $_SESSION[] variable not set in '
            . "{$_SERVER['PHP_SELF']}::setBPshipsSummarySessionVarsP2().");

    // Build MySQL query components. ////////////////////////////////////////////////////////////
    $selectExp  =  " date_format(`match_date`, '%d/%m/%Y') as `match_date`, `opp_team_name`,"
                 . " `partnership_wickets`,\n"
                 . " `p1_wickets_lost`, `p1_runs_scored`,\n"
                 . " `p2_wickets_lost`, `p2_runs_scored`\n";
    $whereExp   =  " and `partnership_runs`"
                 . " = {$_SESSION['bestPshipRunsScored']['bestPshipRunsScored']}\n";
    $orderByExp = ' `match_date` asc, `match_time` asc';

    // Combine components to form full MySQL query.
    $MySQLquery =  'select'   . $selectExp                             . "\n"
                 . 'from'     . $_SESSION['MySQLfromExp' ]             . "\n"
                 . 'where'    . $_SESSION['MySQLwhereExp'] . $whereExp . "\n"
                 . 'order by' . $orderByExp;

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::setBPshipsSummarySessionVarsP2().",
                 mysql_errno(), mysql_error()                                        );

    // Get number of rows in result.
    $n_rows = mysql_num_rows($qResult);
      if ($n_rows < 1)
        error(  'Expected at least one row in result of MySQL query in'
              . " {$_SERVER['PHP_SELF']}::setBPshipsSummarySessionVarsP2().");

    // Abbreviation.
    $arrName = 'bestPshipRunsScored';

    $_SESSION[$arrName]['list'] = array($n_rows);

    for ($i = 0; $i < $n_rows; ++$i)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $_SESSION[$arrName]['list'][$i] = array(7);

       $_SESSION[$arrName]['list'][$i]['matchDate'  ] = $dataRowArray['match_date'   ];
       $_SESSION[$arrName]['list'][$i]['oppTeamName'] = $dataRowArray['opp_team_name'];
       $_SESSION[$arrName]['list'][$i]['bestPshipWicketsLost'  ]
         = $dataRowArray['partnership_wickets'];
       $_SESSION[$arrName]['list'][$i]['bestPshipP1WicketsLost'] = $dataRowArray['p1_wickets_lost'];
       $_SESSION[$arrName]['list'][$i]['bestPshipP1RunsScored' ] = $dataRowArray['p1_runs_scored' ];
       $_SESSION[$arrName]['list'][$i]['bestPshipP2WicketsLost'] = $dataRowArray['p2_wickets_lost'];
       $_SESSION[$arrName]['list'][$i]['bestPshipP2RunsScored' ] = $dataRowArray['p2_runs_scored' ];
    }

    mysql_free_result($qResult);
 }

 /*
  *
  */
 function displayBPshipsStatsSummary()
 {
    // START: Temporary section. ////////////////////////////////////////////////////////////////
    // NOTE: This section should be removed when the code for 1 selected
    //       partner is completed in the setBPshipsSummarySessionVarsP1() function.
    $n_partnersSelected = 0;
    if ($_SESSION['batsmanOneNameSelector'] != 'All Partners') ++$n_partnersSelected;
    if ($_SESSION['batsmanTwoNameSelector'] != 'All Partners') ++$n_partnersSelected;
    if ($n_partnersSelected == 1)
      return;
    // FINISH: Temporary section. ///////////////////////////////////////////////////////////////

    // Test $_SESSION[] variables to be used in this function.
    if (   !isset($_SESSION['n_pships'])

        || !isset($_SESSION['totalPshipWicketsLost'])
        || !isset($_SESSION['totalPshipRunsScored' ])

        || !isset($_SESSION['bestPshipRunsScored'])  ) // --\ Array[2] (pshipRunsScored, list)
                                                       // --/ list is an array of 2D arrays, each
                                                       //     containing: (matchDate, oppTeamName).

      error(  'Required $_SESSION[] variable not set in '
            . "{$_SERVER['PHP_SELF']}::displayBPshipsStatsSummary().");

    echoDoctypeXHTMLStrictString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   td {padding-left: 4px; padding-right: 4px;}
   th {padding-left: 4px; padding-right: 4px;}
   .padding0 {padding: 0;}
  </style>
  <title>Indoor Cricket Database (Batting Partnerships Statistics Summary)</title>
 </head>
 <body>
  <table class="backh2"><?php /* NOTE: cellspacing="0" NOT required for outermost table. */ ?>
   <thead>
<?php
    $aPartnerIsSelected
      = (   $_SESSION['batsmanOneNameSelector'] != 'All Partners'
         || $_SESSION['batsmanTwoNameSelector'] != 'All Partners');

    $_SESSION['tableHeading1'] = 'Batting Partnerships Statistics Summary';

    addBatsmenNamesToTableHeading1();
?>
    <tr>
     <th class="h1 bordersTLRB"><?php echo $_SESSION['tableHeading1']; ?></th>
    </tr>
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
        <tr>
         <th class="h2 borders___B" width="25%">&nbsp;</th>
         <th class="h2 alignR borders___B" width="25%">Combined</th>
<?php
    if ($aPartnerIsSelected)
    {
       // Abbreviations.
       $p1Name = $_SESSION['p1FirstName'] . '<br />' . $_SESSION['p1LastName'];
       $p2Name = $_SESSION['p2FirstName'] . '<br />' . $_SESSION['p2LastName'];
?>
         <th class="h2 alignR borders_L_B nowrap" width="25%"><?php echo $p1Name; ?></th>
         <th class="h2 alignR borders_L_B nowrap" width="25%"><?php echo $p2Name; ?></th>
<?php
    }
?>
        </tr>
        <tr>
         <th class="h3 alignL" colspan="2">Total</th>
<?php
    if ($aPartnerIsSelected)
    {
?>
         <th class="h3 borders_L__">&nbsp;</th>
         <th class="h3 borders_L__">&nbsp;</th>
<?php
    }
?>
        </tr>
        <tr>
         <td class="l alignL">Partnerships:</td>
         <td class="l alignR"><?php echo $_SESSION['n_pships']; ?></td>
<?php
    if ($aPartnerIsSelected)
    {
?>
         <td class="l borders_L__">&nbsp;</td>
         <td class="l borders_L__">&nbsp;</td>
<?php
    }
?>
        </tr>
        <tr>
         <td class="d alignL">Wickets Lost:</td>
         <td class="d alignR"><?php echo $_SESSION['totalPshipWicketsLost']; ?></td>
<?php
    if ($aPartnerIsSelected)
    {
?>
         <td class="d alignR borders_L__"><?php echo $_SESSION['totalP1WicketsLost']; ?></td>
         <td class="d alignR borders_L__"><?php echo $_SESSION['totalP2WicketsLost']; ?></td>
<?php
    }
?>
        </tr>
        <tr>
         <td class="l alignL nowrap">Runs Scored:</td>
         <td class="l alignR"><?php echo $_SESSION['totalPshipRunsScored']; ?></td>
<?php
    if ($aPartnerIsSelected)
    {
?>
         <td class="l alignR borders_L__"><?php echo $_SESSION['totalP1RunsScored']; ?></td>
         <td class="l alignR borders_L__"><?php echo $_SESSION['totalP2RunsScored']; ?></td>
<?php
    }
?>
        </tr>
        <tr>
         <th class="h3 alignL" colspan="2">Average</th>
<?php
    if ($aPartnerIsSelected)
    {
?>
         <th class="h3 borders_L__">&nbsp;</td>
         <th class="h3 borders_L__">&nbsp;</td>
<?php
    }
?>
        </tr>
<?php
    $avgPshipWicketsLostPerPship = $_SESSION['totalPshipWicketsLost'] / $_SESSION['n_pships'];
?>
        <tr>
         <td class="l alignL nowrap">Wickets Lost:</td>
         <td class="l alignR"><?php printf('%.3f', $avgPshipWicketsLostPerPship); ?></td>
<?php
    if ($aPartnerIsSelected)
    {
       $avgP1WicketsLostPerPship = $_SESSION['totalP1WicketsLost'] / $_SESSION['n_pships'];
       $avgP2WicketsLostPerPship = $_SESSION['totalP2WicketsLost'] / $_SESSION['n_pships'];
?>
         <td class="l alignR borders_L__"><?php printf('%.3f', $avgP1WicketsLostPerPship); ?></td>
         <td class="l alignR borders_L__"><?php printf('%.3f', $avgP2WicketsLostPerPship); ?></td>
<?php
    }
?>
        </tr>
<?php
    $avgPshipRunsScoredPerPship = $_SESSION['totalPshipRunsScored'] / $_SESSION['n_pships'];
?>
        <tr>
         <td class="d alignL nowrap">Runs Scored:</td>
         <td class="d alignR"><?php printf('%.3f', $avgPshipRunsScoredPerPship); ?></td>
<?php
    if ($aPartnerIsSelected)
    {
       $avgP1RunsScoredPerPship = $_SESSION['totalP1RunsScored'] / $_SESSION['n_pships'];
       $avgP2RunsScoredPerPship = $_SESSION['totalP2RunsScored'] / $_SESSION['n_pships'];
?>
         <td class="d alignR borders_L__"><?php printf('%.3f', $avgP1RunsScoredPerPship); ?></td>
         <td class="d alignR borders_L__"><?php printf('%.3f', $avgP2RunsScoredPerPship); ?></td>
<?php
    }
?>
        </tr>
        <tr>
         <th class="h3 alignL" colspan="2">Best</th>
<?php
    if ($aPartnerIsSelected)
    {
?>
         <th class="h3 borders_L__">&nbsp;</td>
         <th class="h3 borders_L__">&nbsp;</td>
<?php
    }
?>
        </tr>
<?php
    $n_equalBestPshipRunsScored = count($_SESSION['bestPshipRunsScored']['list']);

    for ($i = 0; $i < $n_equalBestPshipRunsScored; ++$i)
    {
       $lORd = (($i % 2 == 0)? 'l': 'd'); // Shade of row (light or dark).

       // Set runsScored heading and field variables.
       $printBestPshipRunsScored = $i < $n_equalBestPshipRunsScored; // Boolean.
       if ($printBestPshipRunsScored)
       {
          $runsScoredDateH    = 'Date:';
          $runsScoredOppTeamH = 'Opposition:';
          $runsScoredWicketsH = 'Wickets Lost:';

          $runsScoredDate    = $_SESSION['bestPshipRunsScored']['list'][$i]['matchDate'           ];
          $runsScoredOppTeam = $_SESSION['bestPshipRunsScored']['list'][$i]['oppTeamName'         ];
          $runsScoredWickets = $_SESSION['bestPshipRunsScored']['list'][$i]['bestPshipWicketsLost'];
       }
       else
       {
          $runsScoredDateH    = '&nbsp;'; $runsScoredDate    = '&nbsp;';
          $runsScoredOppTeamH = '&nbsp;'; $runsScoredOppTeam = '&nbsp;';
          $runsScoredWicketsH = '&nbsp;'; $runsScoredWickets = '&nbsp;';
       }

       // Abbreviations.
       $bestPshipRunsScored = $_SESSION['bestPshipRunsScored']['bestPshipRunsScored'];
?>
        <tr>
         <td class="<?php echo $lORd; ?> alignL">Runs Scored:</td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $bestPshipRunsScored; ?></td>
<?php
       if ($aPartnerIsSelected)
       {
          // Abbreviations.
          $p1RunsScored = $_SESSION['bestPshipRunsScored']['list'][$i]['bestPshipP1RunsScored'];
          $p2RunsScored = $_SESSION['bestPshipRunsScored']['list'][$i]['bestPshipP2RunsScored'];
?>
         <td class="<?php echo $lORd; ?> alignR borders_L__"><?php echo $p1RunsScored; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders_L__"><?php echo $p2RunsScored; ?></td>
<?php
       }
?>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredWicketsH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsScoredWickets; ?></td>
<?php
       if ($aPartnerIsSelected)
       {
          // Abbreviations.
          $p1WicketsLost = $_SESSION['bestPshipRunsScored']['list'][$i]['bestPshipP1WicketsLost'];
          $p2WicketsLost = $_SESSION['bestPshipRunsScored']['list'][$i]['bestPshipP2WicketsLost'];
?>
         <td class="<?php echo $lORd; ?> alignR borders_L__"><?php echo $p1WicketsLost; ?></td>
         <td class="<?php echo $lORd; ?> alignR borders_L__"><?php echo $p2WicketsLost; ?></td>
<?php
       }
?>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredDateH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsScoredDate;  ?></td>
<?php
       if ($aPartnerIsSelected)
       {
?>
         <td class="<?php echo $lORd; ?> borders_L__">&nbsp;</td>
         <td class="<?php echo $lORd; ?> borders_L__">&nbsp;</td>
<?php
       }
?>
        </tr>
        <tr>
         <td class="<?php echo $lORd; ?> alignL"><?php echo $runsScoredOppTeamH; ?></td>
         <td class="<?php echo $lORd; ?> alignR"><?php echo $runsScoredOppTeam; ?></td>
<?php
       if ($aPartnerIsSelected)
       {
?>
         <td class="<?php echo $lORd; ?> borders_L__">&nbsp;</td>
         <td class="<?php echo $lORd; ?> borders_L__">&nbsp;</td>
<?php
       }
?>
        </tr>
<?php
    }
?>
       </tbody>
      </table>
     </td>
   </tbody>
  </table>
 </body>
</html>
<?php
 }
?>
