<?php
 require_once '../display/icdb_display_MySQL_table.php';

 /*
  *
  */
 function displayTeamRecordsSelection()
 {
    // NOTE: The function 'addOversSubtitleStringAndMySQLwhereExp()' is not
    //       required here as overs restrictions do not apply to team queries.

    preBuildClauses();

    switch ($_SESSION['radioButton'])
    {
     case 'bestStreak':        buildStreakTableMySQLquery('best');        break;
     case 'worstStreak':       buildStreakTableMySQLquery('worst');       break;
     case 'bestTeamScore':     buildTeamScoreTableMySQLquery('best');     break;
     case 'worstTeamScore':    buildTeamScoreTableMySQLquery('worst');    break;
     case 'bestOppScore':      buildOppScoreTableMySQLquery('best');      break;
     case 'worstOppScore':     buildOppScoreTableMySQLquery('worst');     break;
     case 'bestMargin':        buildMarginTableMySQLquery('best');        break;
     case 'worstMargin':       buildMarginTableMySQLquery('worst');       break;
     case 'bestWicketsTaken':  buildWicketsTakenTableMySQLquery('best');  break;
     case 'worstWicketsTaken': buildWicketsTakenTableMySQLquery('worst'); break;
     case 'bestWicketsLost':   buildWicketsLostTableMySQLquery('best');   break;
     case 'worstWicketsLost':  buildWicketsLostTableMySQLquery('worst');  break;
     default:
       error(  "Unexpected \$_SESSION['radioButton'], received '{$_SESSION['radioButton']}'"
             . " in {$_SERVER['PHP_SELF']}::buildStreakTableMySQLquery()."                 );
    }

    displayMySQLtable(true);
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
    $_SESSION['MySQLorderByExp'      ] = '';
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
    $_SESSION['MySQLselectExp']
      .= " date_format(`match_date`, '%d/%m/%Y'),"
       . " time_format(`match_time`, '%l:%i%p'), `opp_team_name`";

    array_push($_SESSION['tableColHeadingsArray'],
               'Match<br />Date', 'Match<br />Time', 'Opposition');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'string');
 }

 function buildStreakTableMySQLquery($bestORworst)
 {
    $_SESSION['MySQLselectExp']
      =  "date_format(`start_date`, '%d/%m/%Y'), date_format(`finish_date`, '%d/%m/%Y')"
       . ', `n_matches`';
    $_SESSION['MySQLfromExp'  ]
      =  "\n"
       . "(\n"
       . "   select `result`,\n"
       . "          min(`match_date`) as `start_date`,\n"
       . "          max(`match_date`) as `finish_date`,\n"
       . "          count(*) as `n_matches`\n"
       . "   from\n"
       . "   (\n"
       . "      select `match_date`, `match_time`, `result`,\n"
       . "      (\n"
       . "         select count(*)\n"
       . "         from `view_matches` as `vm1`\n"
       . "         where `team_id` = {$_SESSION['teamID']}\n"
       . "         and   `vm1`.`result` <> `vm2`.`result`\n"
       . "         and\n"
       . "         (\n"
       . "            `vm1`.`match_date` < `vm2`.`match_date`\n"
       . "            or\n"
       . "            (\n"
       . "               `vm1`.`match_date` = `vm2`.`match_date`\n"
       . "                and\n"
       . "               `vm1`.`match_time` < `vm2`.`match_time`\n"
       . "            )\n"
       . "         )\n"
       . "      ) as `streak_group`\n"
       . "      from `view_matches` as `vm2`\n"
       . "      where `team_id` = {$_SESSION['teamID']}\n"
       . "   ) as `dummy`\n"
       . "   group by `result`, `streak_group`\n"
       . "   order by min(`match_date`), max(`match_date`)\n"
       . ") as `dummy2`\n";

    // finish MySQL query clauses and extend table heading clauses
    switch ($bestORworst)
    {
     case 'best':
       $_SESSION['MySQLwhereExp']  = "`result` = 'W'";
       $_SESSION['tableHeading1'] .= 'Longest Winning Streaks';
       break;
     case 'worst':
       $_SESSION['MySQLwhereExp']  = "`result` = 'L'";
       $_SESSION['tableHeading1'] .= 'Longest Losing Streaks';
       break;
     default:
       error(  "Expected 'best' or 'worst', received '$bestORworst'"
             . " in {$_SERVER['PHP_SELF']}::buildStreakTableMySQLquery().");

    }

    // finish MySQL where clause (if period is restricted)
    if ($_SESSION['periodStartDateStr'] != '' && $_SESSION['periodFinishDateStr'] != '')
    {
       $_SESSION['MySQLwhereExp']
         .= "\n"
          . "and `start_date` >= {$_SESSION['periodStartDateStr']}\n"
          . "and `finish_date` <= {$_SESSION['periodFinishDateStr']}";
    }

    // finish table headings
    $_SESSION['MySQLorderByExp'      ] = '`n_matches` desc';
    $_SESSION['tableColHeadingsArray']
      = array('Start<br />Date', 'Finish<br />date', 'No.<br />Matches');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'int');
 }

 function buildTeamScoreTableMySQLquery($bestORworst)
 {
    $_SESSION['MySQLselectExp'] .= ', `team_score`';

    // finish MySQL query clauses and extend table heading clauses
    switch ($bestORworst)
    {
     case 'best':
       $_SESSION['MySQLorderByExp'   ] .= '`team_score` desc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'     ] .= 'Highest';
       $_SESSION['MySQLrankAscOrDesc'] = 'Desc';
       break;
     case 'worst':
       $_SESSION['MySQLorderByExp'   ] .= '`team_score` asc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'     ] .= 'Lowest';
       $_SESSION['MySQLrankAscOrDesc'] = 'Asc';
       break;
     default:
       error(  "Expected 'best' or 'worst', received '$bestORworst'"
             . " in {$_SERVER['PHP_SELF']}::buildteamScoreTableMySQLquery().");
    }

    // finish table headings
    $_SESSION['tableHeading1'] .= ' Team Score';
    array_push($_SESSION['tableColHeadingsArray'], 'Team<br />Score');
    array_push($_SESSION['tableDataTypes'], 'int');
 }

 function buildOppScoreTableMySQLquery($bestORworst)
 {
    $_SESSION['MySQLselectExp'] .= ', `opp_team_score`';

    // finish MySQL query clauses and extend table heading clauses
    switch ($bestORworst)
    {
     case 'best':
       $_SESSION['MySQLorderByExp'] .= '`opp_team_score` asc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Lowest';
       break;
     case 'worst':
       $_SESSION['MySQLorderByExp'] .= '`opp_team_score` desc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Highest';
       break;
     default:
       error(  "Expected 'best' or 'worst', received '$bestORworst'"
             . " in {$_SERVER['PHP_SELF']}::buildoppTeamScoreTableMySQLquery().");
    }

    // finish table headings
    $_SESSION['tableHeading1'] .= ' Opposition Score';
    array_push($_SESSION['tableColHeadingsArray'], 'Opposition<br />Score');
    array_push($_SESSION['tableDataTypes'], 'int');
 }

 function buildMarginTableMySQLquery($bestORworst)
 {
    // finish MySQL query clauses
    switch ($bestORworst)
    {
     case 'best':
       $_SESSION['MySQLselectExp' ] .= ', `margin`';
       $_SESSION['MySQLwhereExp'  ] .= ' and `margin` > 0';
       $_SESSION['MySQLorderByExp'] .= '`margin` desc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Greatest Winning';
       array_push($_SESSION['tableColHeadingsArray'], 'Winning<br />Margin');
       array_push($_SESSION['tableDataTypes'], 'int');
       break;
     case 'worst':
       $_SESSION['MySQLselectExp' ] .= ', -1 * `margin`';
       $_SESSION['MySQLwhereExp'  ] .= ' and `margin` < 0';
       $_SESSION['MySQLorderByExp'] .= '`margin` asc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Greatest Losing';
       array_push($_SESSION['tableColHeadingsArray'], 'Losing<br />Margin');
       array_push($_SESSION['tableDataTypes'], 'int');
       break;
     default:
       error(  "Expected 'best' or 'worst', received '$bestORworst'"
             . " in {$_SERVER['PHP_SELF']}::buildMarginTableMySQLquery().");
    }

    // finish table headings
    $_SESSION['tableHeading1'] .= ' Margin';
 }

 function buildWicketsTakenTableMySQLquery($bestORworst)
 {
    $_SESSION['MySQLselectExp'] .= ', `wickets_taken`';

    // finish MySQL query clauses
    switch ($bestORworst)
    {
     case 'best':
       $_SESSION['MySQLorderByExp'] .= '`wickets_taken` desc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Most';
       break;
     case 'worst':
       $_SESSION['MySQLorderByExp'] .= '`wickets_taken` asc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Least';
       break;
     default:
       error(  "Expected 'best' or 'worst', received '$bestORworst'"
             . " in {$_SERVER['PHP_SELF']}::buildWicketsTakenTableMySQLquery().");
    }

    // finish table headings
    $_SESSION['tableHeading1'] .= ' Wickets Taken in a Match';
    array_push($_SESSION['tableColHeadingsArray'], 'Wickets<br />Taken');
    array_push($_SESSION['tableDataTypes'], 'int');
 }

 function buildWicketsLostTableMySQLquery($bestORworst)
 {
    $_SESSION['MySQLselectExp'] .= ', `wickets_lost`';

    // finish MySQL query clauses
    switch ($bestORworst)
    {
     case 'best':
       $_SESSION['MySQLorderByExp'] .= '`wickets_lost` asc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Least';
       break;
     case 'worst':
       $_SESSION['MySQLorderByExp'] .= '`wickets_lost` desc, `match_date` asc, `match_time` asc';
       $_SESSION['tableHeading1'  ] .= 'Most';
       break;
     default:
       error(  "Expected 'best' or 'worst', received '$bestORworst'"
             . " in {$_SERVER['PHP_SELF']}::buildWicketsLostTableMySQLquery().");
    }

    // finish table headings
    $_SESSION['tableHeading1'] .= ' Wickets Lost in a Match';
    array_push($_SESSION['tableColHeadingsArray'], 'Wickets<br />Lost');
    array_push($_SESSION['tableDataTypes'], 'int');
 }
?>
