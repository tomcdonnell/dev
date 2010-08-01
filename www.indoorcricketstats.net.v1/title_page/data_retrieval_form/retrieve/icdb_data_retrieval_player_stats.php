<?php
 require_once '../display/icdb_display_MySQL_table.php';
 require_once '../display/icdb_display_MySQL_chartORhist.php';
 require_once '../display/icdb_display_player_stats_summary.php';

 /*
  *
  */
 function displayPlayerStatsSelection()
 {
    preBuildClauses();

    addOversSubtitleStringAndMySQLwhereExp();

    switch ($_SESSION['radioButton'])
    {
     case 'summary':
       setPlayerSummarySessionVars();
       displayPlayerStatsSummary();
       break;
     case 'battTable':
       buildBattTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'battChart':
       buildBattChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'battHist':
       buildBattChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
     case 'bowlTable':
       buildBowlTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'bowlChart':
       buildBowlChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'bowlHist':
       buildBowlChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
     case 'overallTable':
       buildOverallTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'overallChart':
       buildOverallChartMySQLquery('chart');
       displayMySQLchartORhist('chart');
       break;
     case 'overallHist':
       buildOverallChartMySQLquery('hist');
       displayMySQLchartORhist('hist');
       break;
     case 'statsByBattPosTable':
       buildStatsByBattPosTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'statsByBattPosCharts':
       echo "<p>This feature is not yet implemented.</p>\n";
       break;
     case 'statsByOverNoTable':
       buildStatsByOverNoTableMySQLquery();
       displayMySQLtable(false);
       break;
     case 'statsByOverNoCharts':
       echo "<p>This feature is not yet implemented.</p>\n";
       break;
    }
 }

 /*
  *
  */
 function preBuildClauses()
 {
    if (   !isset($_SESSION['radioButton'      ])
        || !isset($_SESSION['playerSelector'   ]))
      error(  'Required $_SESSION[] variable not set in '
            . "{$_SERVER['PHP_SELF']}::displayMySQLtable().");

    // Begin MySQL query clauses.
    $_SESSION['MySQLselectExp'       ] = '';
    $_SESSION['MySQLselectRankAsExp' ] = '';
    $_SESSION['MySQLfromExp'         ] = '';
    $_SESSION['MySQLwhereExp'        ] = ' `team_id` = ' . $_SESSION['teamID']
                                        . $_SESSION['MySQLperiodWhereExp'    ]
                                        . $_SESSION['MySQLoppositionWhereExp']
                                        // NOTE: 'MySQLplayersWhereExp' is added below.
                                        . $_SESSION['MySQLmatchesWhereExp'   ];
                                        // NOTE: 'MySQLoversOversWhereExp' or
                                        //       'MySQLoversInningsWhereExp' is added elsewhere
                                        //       (in the 'addOversSubtitleStringAndMySQLwhereExp()'
                                        //       function) depending on whether the query
                                        //       concerns batting or bowling.
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

    $_SESSION['includeAllPlayers'] = ($_SESSION['playerSelector'] == 'All Players'); // Boolean.

    // Add 'MySQLplayersWhereExp' to 'MySQLwhereExp' if players restrictions are applicable.
    if (   $_SESSION['includeAllPlayers']
        || (   $_SESSION['radioButton'] != 'statsByBattPosTable'
            && $_SESSION['radioButton'] != 'statsByBattPosCharts'
            && $_SESSION['radioButton'] != 'statsByOverNoTable'
            && $_SESSION['radioButton'] != 'statsByOverNoCharts' ))
    {
       $_SESSION['MySQLwhereExp'] .= $_SESSION['MySQLplayersWhereExp'];
    }

    // Extend MySQL query clauses & headings depending on 'includeAllPlayers'.
    if ($_SESSION['includeAllPlayers'])
    {
       // Extend MySQL query clauses & headings.
       $_SESSION['MySQLselectExp'] .= '`first_name`, `last_name`, '; // Note trailing comma.
       array_push($_SESSION['tableColHeadingsArray'], 'First<br />Name', 'Last<br />Name');
       array_push($_SESSION['tableDataTypes'       ], 'string'         , 'string'        );
    }
    else
    {
       // Tokenize first & last names.
       $_SESSION['firstName'] = strtok($_SESSION['playerSelector'], ' ');
       $_SESSION['lastName' ] = strtok(' ');

       // Extend MySQL query clauses & headings.
       $_SESSION['MySQLwhereExp']
         .=  " and `first_name` = \"{$_SESSION['firstName']}\""
           . ' and `last_name` = "' . (($_SESSION['lastName'])? $_SESSION['lastName']: '') . '"';
    }

    // Further extend MySQL query clauses & headings.
    $_SESSION['MySQLselectExp'] .= " date_format(`match_date`, '%d/%m/%Y'),"
                                  ." time_format(`match_time`, '%l:%i%p'), `opp_team_name`";
    array_push($_SESSION['tableColHeadingsArray'],
               'Match<br />Date', 'Match<br />Time', 'Opposition');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'string');
 }

 /*
  * Finish the 'restrictionsSubtitle' string by adding the appropriate
  * 'overs<overs OR innings>SubtitleString' depending on whether the
  * particular MySQL query concerns bowling (overs) or batting (innings).
  */
 function addOversSubtitleStringAndMySQLwhereExp()
 {
    if (   $_SESSION['oversOversSubtitleString'  ] != ''
        || $_SESSION['oversInningsSubtitleString'] != '')
    {
       // Add HTML newline tag to 'tableHeading2' string if necessary.
       if ($_SESSION['tableHeading2'] != '')
         $_SESSION['tableHeading2'] .= '<br />';

       switch ($_SESSION['radioButton'])
       {
        case 'battTable':
        case 'battChart':
        case 'battHist' :
        case 'statsByBattPosTable':
        case 'statsByBattPosCharts':
          // Add 'oversInningsSubtitleString' to 'tableHeading2' string.
          $_SESSION['tableHeading2'] .= $_SESSION['oversInningsSubtitleString'];

          // Add 'oversInningsMySQLwhereExp' to 'MySQLwhereExp'.
          $_SESSION['MySQLwhereExp'] .= $_SESSION['MySQLoversInningsWhereExp'];
          break;

        case 'bowlTable':
        case 'bowlChart':
        case 'bowlHist' :
        case 'statsByOverNoTable':
        case 'statsByOverNoCharts':
          // Add 'oversOversSubtitleString' to 'tableHeading2' string.
          $_SESSION['tableHeading2'] .= $_SESSION['oversOversSubtitleString'];

          // Add 'oversOversMySQLwhereExp' to 'MySQLwhereExp'.
          $_SESSION['MySQLwhereExp'] .= $_SESSION['MySQLoversOversWhereExp'];
          break;

        // NOTE: Neither oversSubtitle string applies to cases
        //       concerning overall bests, totals, or averages, or summary.
       }
    }
 }

 function buildBattTableMySQLquery()
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp' ] .= ', `batting_pos`, `wickets_lost`, `runs_scored`';
    $_SESSION['MySQLfromExp'   ] .= ' `view_innings`';
    $_SESSION['MySQLorderByExp'] .= ', `batting_pos` desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'Batting<br />Position', 'Wickets<br />Lost', 'Runs<br />Scored');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'int'         );

    // Finish table headings.
    $_SESSION['tableHeading1'] .= 'Batting Table';
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
 }

 function buildBattChartMySQLquery($chartORhist)
 {
    $_SESSION['MySQLselectExp' ]
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' `wickets_lost` as `wickets`, `runs_scored` as `runs`';
    $_SESSION['MySQLfromExp'   ] = ' `view_innings`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc, `batting_pos` asc';

    // Set horizontal stripe height (units are runs).
    $_SESSION['horizStripeHeight'] = 10;

    // Finish table headings.
    $_SESSION['tableHeading1'] = 'Batting ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildBattChartMySQLquery().");
    }
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
    $_SESSION['chartVertAxisHeading' ] = 'Runs Scored';
    $_SESSION['chartHorizAxisHeading'] = 'Innings';
 }

 function buildBowlTableMySQLquery()
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp' ] .= ', `over_no`, `wickets_taken`, `runs_conceded`';
    $_SESSION['MySQLfromExp'   ] .= ' `view_overs`';
    $_SESSION['MySQLorderByExp'] .= ', `over_no` desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'Over<br />Number', 'Wickets<br />Taken', 'Runs<br />Conceded');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'int'         );

    // Finish table headings.
    $_SESSION['tableHeading1'] .= 'Bowling Table';
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
 }

 function buildBowlChartMySQLquery($chartORhist)
 {
    $_SESSION['MySQLselectExp']
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' `wickets_taken` as `wickets`, `runs_conceded` as `runs`';
    $_SESSION['MySQLfromExp'   ] = ' `view_overs`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc, `over_no` asc';

    // Set horizontal stripe height (units are runs).
    $_SESSION['horizStripeHeight'] = 10;

    // Finish chart headings.
    $_SESSION['tableHeading1'] = 'Bowling ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildBowlChartMySQLquery().");
    }
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
    $_SESSION['chartVertAxisHeading' ] = 'Runs Conceded';
    $_SESSION['chartHorizAxisHeading'] = 'Overs';
 }

 function buildOverallTableMySQLquery()
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp']
      .=  ', `n_innings_in_match`, `n_overs_in_match`,'
        . ' (2.0 * `wickets_taken_in_match`) / `n_overs_in_match`'
        . ' - `wickets_lost_in_match` / `n_innings_in_match`,'
        . ' `runs_scored_in_match` / `n_innings_in_match`'
        . ' - (2.0 * `runs_conceded_in_match`) / `n_overs_in_match`';
        // NOTE: Normalised figures are used for net runs scored and net wickets taken.
    $_SESSION['MySQLfromExp' ] .= ' `view_match_contributions`';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Innings', 'No.<br />Overs',
               'Net Wickets<br />Taken*',
               'Net Runs<br />Scored*'   );
    array_push($_SESSION['tableDataTypes'],
               'int', 'int',
               'intORhalvesORthirds',
               'intORfraction'       );

    // Finish table headings.
    $_SESSION['tableHeading1'] .= 'Overall Table';
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
 }

 function buildOverallChartMySQLquery($chartORhist)
 {
    $_SESSION['MySQLselectExp']
      =  " date_format(`match_date`, '%d') as `day`,"
       . " date_format(`match_date`, '%m') as `month`,"
       . " date_format(`match_date`, '%Y') as `year`,"
       . ' (2.0 * `wickets_taken_in_match`) / `n_overs_in_match`'
       . ' - `wickets_lost_in_match` / `n_innings_in_match` as `wickets`,'
       . ' `runs_scored_in_match` / `n_innings_in_match`'
       . ' - (2.0 * `runs_conceded_in_match`) / `n_overs_in_match` as `runs`';
    $_SESSION['MySQLfromExp'   ] = ' `view_match_contributions`';
    $_SESSION['MySQLorderByExp'] = ' `match_date` asc, `match_time` asc';

    // Set horizontal stripe height (units are runs).
    $_SESSION['horizStripeHeight'] = 10;

    // Finish chart headings.
    $_SESSION['tableHeading1'] = 'Overall ';
    switch ($chartORhist)
    {
     case 'chart': $_SESSION['tableHeading1'] .= 'Chart';     break;
     case 'hist' : $_SESSION['tableHeading1'] .= 'Histogram'; break;
     default: error(  "Expected 'chart' or 'hist', received '$chartORhist'"
                    . " in {$_SERVER['PHP_SELF']}::buildOverallChartMySQLquery().");
    }
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
    $_SESSION['chartVertAxisHeading' ] = 'Net Runs Scored';
    $_SESSION['chartHorizAxisHeading'] = 'Matches';
 }

 function buildStatsByBattPosTableMySQLquery()
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp' ]
      =  ' `batting_pos`, count(`runs_scored`),'
       . ' min(`wickets_lost`), avg(`wickets_lost`), max(`wickets_lost`),'
       . ' min(`runs_scored`), avg(`runs_scored`), max(`runs_scored`) ';
    $_SESSION['MySQLfromExp'   ] = ' `view_innings`';
    $_SESSION['tableColHeadingsArray']
      = array('Batting<br />Position', 'Number of<br />Innings',
              'Least<br />Wickets Lost<br />Per Innings',
              'Average<br />Wickets Lost<br />Per Innings',
              'Greatest<br />Wickets Lost<br />Per Innings',
              'Least<br />Runs Scored<br />Per Innings',
              'Average<br />Runs Scored<br />Per Innings',
              'Greatest<br />Runs Scored<br />Per Innings'  );
    $_SESSION['tableDataTypes']
      = array('int', 'int',
              'int', 'float', 'int',
              'int', 'float', 'int' );

    $_SESSION['MySQLgroupByExp'] = '`batting_pos`';
    $_SESSION['MySQLorderByExp'] = '`batting_pos`';

    // Finish table headings.
    $_SESSION['tableHeading1'] = 'Statistics by Batting Position';
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
 }

 function buildStatsByOverNoTableMySQLquery()
 {
    // Finish MySQL query clauses.
    $_SESSION['MySQLselectExp' ]
      =  ' `over_no`, count(`runs_conceded`),'
       . ' min(`wickets_taken`), avg(`wickets_taken`), max(`wickets_taken`),'
       . ' min(`runs_conceded`), avg(`runs_conceded`), max(`runs_conceded`) ';
    $_SESSION['MySQLfromExp'   ] = ' `view_overs`';
    $_SESSION['tableColHeadingsArray']
      = array('Over<br />Number', 'Number<br />of Overs',
              'Least<br />Wickets Taken<br />Per Over',
              'Average<br />Wickets Taken<br />Per Over',
              'Greatest<br />Wickets Taken<br />Per Over',
              'Least<br />Runs Conceded<br />Per Over',
              'Average<br />Runs Conceded<br />Per Over',
              'Greatest<br />Runs Conceded<br />Per Over' );
    $_SESSION['tableDataTypes']
      = array('int', 'int',
              'int', 'float', 'int',
              'int', 'float', 'int' );
    $_SESSION['MySQLgroupByExp'] = '`over_no`';
    $_SESSION['MySQLorderByExp'] = '`over_no`';

    // Finish table headings.
    $_SESSION['tableHeading1'] .= 'Statistics by Over Number';
    if ($_SESSION['includeAllPlayers'])
      $_SESSION['tableHeading1'] .= ' (All Players)';
    else
      $_SESSION['tableHeading1'] .= " ({$_SESSION['firstName']} {$_SESSION['lastName']})";
 }
?>
