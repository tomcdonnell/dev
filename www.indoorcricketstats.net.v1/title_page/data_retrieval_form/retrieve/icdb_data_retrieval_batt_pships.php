<?php
 require_once '../display/icdb_display_MySQL_table.php';
 require_once '../display/icdb_display_MySQL_chartORhist.php';
 require_once '../display/icdb_display_batt_pships_stats_summary.php';

 /*
  *
  */
 function displayBattPshipsSelection()
 {
    preBuildClauses();

    addOversSubtitleStringAndMySQLwhereExp();

    switch ($_SESSION['radioButton'])
    {
     case 'summary':
       setBPshipsSummarySessionVars();
       displayBPshipsStatsSummary();
       break;
     case 'history':
       extendClausesForNonAggregateQueries();
       buildHistoryTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'chart':
       buildHistoryChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'histogram':
       buildHistoryChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
     case 'bestRuns':
       extendClausesForNonAggregateQueries();
       buildBestRunsTableMySQLquery();
       displayMySQLtable(true);
       break;
     case 'bestWickets':
       extendClausesForNonAggregateQueries();
       buildBestWicketsTableMySQLquery();
       displayMySQLtable(true);
       break;
     case 'avgRuns':
       extendClausesForAggregateQueries();
       extendClausesForAggregateAvgQueries();
       buildAvgRunsTableMySQLquery();
       displayMySQLtable(true);
       break;
     case 'avgWickets':
       extendClausesForAggregateQueries();
       extendClausesForAggregateAvgQueries();
       buildAvgWicketsTableMySQLquery();
       displayMySQLtable(true);
       break;
     case 'totalRuns':
       extendClausesForAggregateQueries();
       extendClausesForAggregateSumQueries();
       buildTotalRunsTableMySQLquery();
       displayMySQLtable(true);
       break;
     case 'totalWickets':
       extendClausesForAggregateQueries();
       extendClausesForAggregateSumQueries();
       buildTotalWicketsTableMySQLquery();
       displayMySQLtable(true);
       break;
     default:
       error(  "Invalid \$_SESSION['radioButton'] '{$_SESSION['radioButton']}'"
             . " in {$_SERVER['PHP_SELF']}::displayBattPshipsSelection()."   );
    }
 }

 /*
  * Finish the 'restrictionsSubtitle' string by adding the appropriate
  * 'overs<overs OR innings>SubtitleString' depending on whether the
  * particular MySQL query concerns bowling (overs) or batting (innings).
  *
  * All the batting partnership MySQL queries concern batting, so the function is trivial
  * in this case (similar functions for the other selections pages utilize a switch statement).
  * Note that a subtitle string is provided specifically for queries involving batting partnerships
  * ('MySQLoversInningsWhereExpForBattingPships').  It is needed because for queries involving
  * batting partnerships, instead of there being one `batting_pos` column, there are two:
  * (`p1_batting_pos` and `p2_batting_pos`.
  */
 function addOversSubtitleStringAndMySQLwhereExp()
 {
    if ($_SESSION['oversInningsSubtitleStringForBattingPships'] != '')
    {
       // Add 'oversInningsSubtitleString' to 'tableHeading2' string.
       if ($_SESSION['tableHeading2'] != '')
         $_SESSION['tableHeading2'] .= '<br />'; // add HTML newline tag if necessary
       $_SESSION['tableHeading2'] .= $_SESSION['oversInningsSubtitleStringForBattingPships'];

       // Add 'oversInningsMySQLwhereExp' to 'MySQLwhereExp'.
       $_SESSION['MySQLwhereExp'] .= $_SESSION['MySQLoversInningsWhereExpForBattingPships'];
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
    $_SESSION['MySQLfromExp'         ] = ' `view_batting_partnerships`';
    $_SESSION['MySQLwhereExp'        ] = ' `team_id` = ' . $_SESSION['teamID']
                                        . $_SESSION['MySQLperiodWhereExp'    ]
                                        . $_SESSION['MySQLoppositionWhereExp']
                                        . $_SESSION['MySQLplayersWhereExpForBattingPships']
                                        . $_SESSION['MySQLmatchesWhereExp'   ];
    $_SESSION['MySQLgroupByExp'      ] = '';
    $_SESSION['MySQLorderByExp'      ] = '';
    $_SESSION['MySQLrankExp'         ] = '';
    $_SESSION['MySQLrankAscOrDesc'   ] = '';
    $_SESSION['MySQLlimit'           ] = 10;
    $_SESSION['MySQLoffset'          ] =  0;
    $_SESSION['tableHeading1'        ] = '';
    $_SESSION['tableHeading2'        ] = $_SESSION['restrictionsSubtitle'];
    $_SESSION['tableHeading3'        ] = '';
    $_SESSION['tableColHeadingsArray'] = array();
    $_SESSION['tableDataTypes'       ] = array();

    // extend MySQL query clauses & headings depending on the batsmanOneNameSelector
    if ($_SESSION['batsmanOneNameSelector'] != 'All Partners')
    {
       // tokenize first & last names
       $firstName = strtok($_SESSION['batsmanOneNameSelector'], ' ');
       $lastName  = strtok(' ');

       // extend MySQL query clauses & headings
       $_SESSION['MySQLwhereExp']
         .=  " and\n"
           . "(\n"
           . "   (`p1_first_name` = \"$firstName\" and `p1_last_name` = \""
           . (($lastName)? $lastName: '') . "\")\n"
           . "   or "
           . "   (`p2_first_name` = \"$firstName\" and `p2_last_name` = \""
           . (($lastName)? $lastName: '') . "\")\n"
           . ")\n";
    }

    // extend MySQL query clauses & headings depending on the batsmanTwoNameSelector
    if ($_SESSION['batsmanTwoNameSelector'] != 'All Partners')
    {
       // tokenize first & last names
       $firstName = strtok($_SESSION['batsmanTwoNameSelector'], ' ');
       $lastName  = strtok(' ');

       // extend MySQL query clauses & headings
       $_SESSION['MySQLwhereExp']
         .=  " and\n"
           . "(\n"
           . "   (`p1_first_name` = \"$firstName\" and `p1_last_name` = \""
           . (($lastName)? $lastName: '') . "\")\n"
           . "   or "
           . "   (`p2_first_name` = \"$firstName\" and `p2_last_name` = \""
           . (($lastName)? $lastName: '') . "\")\n"
           . ")\n";
    }
 }

 function extendClausesForNonAggregateQueries()
 {
    // extend MySQL query clauses & headings
    $_SESSION['MySQLselectExp']
      .= " date_format(`match_date`, '%d/%m/%Y'),"
       . " time_format(`match_time`, '%l:%i%p'), `opp_team_name`,"
       . ' concat(`p1_batting_pos`, "<br />", `p2_batting_pos`),'
       . ' concat(`p1_first_name`, "<br />", `p2_first_name`),'
       . ' concat(`p1_last_name`,  "<br />&nbsp;", `p2_last_name`, "&nbsp;"),'
       . ' concat(`p1_wickets_lost`, "<br />", `p2_wickets_lost`),'
       . ' concat(`p1_runs_scored`,  "<br />", `p2_runs_scored` ),'
       . ' `partnership_wickets`, `partnership_runs`';

    array_push($_SESSION['tableColHeadingsArray'],
               'Match<br />Date', 'Match<br />Time', 'Opposition',
               'Batting<br />Position',
               'First<br />Name', 'Last<br />Name',
               'Wickets<br />Lost', 'Runs<br />Scored',
               'P`ship<br />Wickets', 'P`ship<br />Runs');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'string',
               'int',
               'string', 'string',
               'int', 'int',
               'int', 'int'                 );
 }

 function extendClausesForAggregateQueries()
 {
    $_SESSION['MySQLselectExp']
       =  " concat(`p1_first_name`, \"<br />\", `p2_first_name`),"
        . " concat(`p1_last_name`,  \"<br />&nbsp;\", `p2_last_name`, \"&nbsp;\")";
    $c = 'case when `p1_player_id` < `p2_player_id` then'; // condition for 'MySQLfromExp' below
    $_SESSION['MySQLfromExp']
       =  "\n"
        . "(\n"
        . "   select `team_id`, `opp_team_name`, `match_date`,\n"
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
    $_SESSION['MySQLgroupByExp'] = '`partnership_group`';

    array_push($_SESSION['tableColHeadingsArray'], 'First<br />Name', 'Last<br />Name');
    array_push($_SESSION['tableDataTypes'], 'string', 'string');

 }

 function extendClausesForAggregateAvgQueries()
 {
    $_SESSION['MySQLselectExp']
      .=  ",\n"
        . "concat(avg(`p1_wickets_lost`), \"<br />\", avg(`p2_wickets_lost`)),\n"
        . "concat(avg(`p1_runs_scored`),  \"<br />\", avg(`p2_runs_scored`)),\n"
        . 'count(*), avg(`partnership_wickets`), avg(`partnership_runs`)';
    array_push($_SESSION['tableColHeadingsArray'],
               'Average<br />Wickets<br />Lost', 'Average<br />Runs<br />Scored',
               "No.<br />P'ships",
               'Average<br />P`ship<br />Wickets', 'Average<br />P`ship<br />Runs');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', // PROBLEM: Cannot use 'float' here as vars are actually
               'int',              //          strings of two floats eg. "6.7834<br />2.4312".
               'float', 'float'            );
 }

 function extendClausesForAggregateSumQueries()
 {
    $_SESSION['MySQLselectExp']
      .=  ",\n"
        . "concat(sum(`p1_wickets_lost`), \"<br />\", sum(`p2_wickets_lost`)),\n"
        . "concat(sum(`p1_runs_scored`),  \"<br />\", sum(`p2_runs_scored`)),\n"
        . 'count(*), sum(`partnership_wickets`), sum(`partnership_runs`)';

    array_push($_SESSION['tableColHeadingsArray'],
               'Total<br />Wickets<br />Lost', 'Total<br />Runs<br />Scored',
               "No.<br />P'ships",
               'Total<br />P`ship<br />Wickets', 'Total<br />P`ship<br />Runs');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', // NOTE: vars here are strings of two ints eg. "67<br />23".
               'int',
               'int', 'int'                );
 }

 function buildHistoryTableMySQLquery()
 {
    $_SESSION['MySQLorderByExp'] = '`match_date` desc, `match_time` desc, `p1_batting_pos` desc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Batting Partnerships History Table';

    addBatsmenNamesToTableHeading1();
 }

 function buildHistoryChartMySQLquery($chartORhist)
 {
    $_SESSION['MySQLselectExp']
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' `partnership_wickets` as `wickets`, `partnership_runs` as `runs`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc';

    // Set horizontal stripe height (units are runs).
    $_SESSION['horizStripeHeight'] = 10;

    // Finish table headings.
    $_SESSION['tableHeading1'] = 'Batting Partnerships ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildHistoryChartMySQLquery().");
    }
    $_SESSION['chartVertAxisHeading' ] = 'Runs Scored';
    $_SESSION['chartHorizAxisHeading'] = 'Partnerships';
 }

 function buildBestRunsTableMySQLquery()
 {
    $_SESSION['MySQLorderByExp'] = '`partnership_runs` desc, `match_date` asc, `match_time` asc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Most Runs Scored in a Batting Partnership';

    addBatsmenNamesToTableHeading1();
 }

 function buildBestWicketsTableMySQLquery()
 {
    $_SESSION['MySQLorderByExp'] = '`partnership_wickets` asc, `match_date` asc, `match_time` asc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Least Wickets Lost in a Batting Partnership';

    addBatsmenNamesToTableHeading1();
 }

 function buildAvgRunsTableMySQLquery()
 {
    // extend MySQL query clauses & headings
    $_SESSION['MySQLorderByExp'] .= 'avg(`partnership_runs`) desc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Greatest Average Runs Scored Per Batting Partnership';

    addBatsmenNamesToTableHeading1();
 }

 function buildAvgWicketsTableMySQLquery()
 {
    // extend MySQL query clauses & headings
    $_SESSION['MySQLorderByExp'] .= 'avg(`partnership_wickets`) asc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Least Average Wickets Lost Per Batting Partnership';

    addBatsmenNamesToTableHeading1();
 }

 function buildTotalRunsTableMySQLquery()
 {
    // extend MySQL query clauses & headings
    $_SESSION['MySQLorderByExp'] .= 'sum(`partnership_runs`) desc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Greatest Total Runs Scored in Batting Partnerships';

    addBatsmenNamesToTableHeading1();
 }

 function buildTotalWicketsTableMySQLquery()
 {
    // extend MySQL query clauses & headings
    $_SESSION['MySQLorderByExp'] .= 'sum(`partnership_wickets`) desc';

    // finish table headings
    $_SESSION['tableHeading1'  ] = 'Greatest Total Wickets Lost in Batting Partnerships';

    addBatsmenNamesToTableHeading1();
 }

 /*
  * Add a bracketed string containing the names of the
  * selected batsmen to $_SESSION['tableHeading1'] on a new line.
  * Examples: (Tom McDonnell with all partners) or (Tom McDonnell with Eric Morrison).
  */
 function addBatsmenNamesToTableHeading1()
 {
    $bOneSelected = ($_SESSION['batsmanOneNameSelector'] != 'All Partners');
    $bTwoSelected = ($_SESSION['batsmanTwoNameSelector'] != 'All Partners');

    if ($bOneSelected || $bTwoSelected)
    {
       $_SESSION['tableHeading1'] .= '<br />(';

       if ($bOneSelected)
         $_SESSION['tableHeading1']
           .=  $_SESSION['batsmanOneNameSelector'] . ' with '
             . (($bTwoSelected)? $_SESSION['batsmanTwoNameSelector']: 'all partners');
       else
         $_SESSION['tableHeading1']
           .=  $_SESSION['batsmanTwoNameSelector'] . ' with '
             . (($bOneSelected)? $_SESSION['batsmanOneNameSelector']: 'all partners');

       $_SESSION['tableHeading1'] .= ')';
    }
 }
?>
