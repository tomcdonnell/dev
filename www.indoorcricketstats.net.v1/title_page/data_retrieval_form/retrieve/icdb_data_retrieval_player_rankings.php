<?php
 require_once '../display/icdb_display_MySQL_table.php';

 /*
  *
  */
 function displayPlayerRankingsSelection()
 {
    preBuildClauses();

    addOversSubtitleStringAndMySQLwhereExp();

    switch ($_SESSION['radioButton'])
    {
     case 'totalMatches'    : break;
     case 'totalInnings'    : break;
     case 'totalOvers'      : break;
     case 'bestRunsBatt'    : buildBestRunsBattMySQLquery();     break;
     case 'bestRunsBowl'    : buildBestRunsBowlMySQLquery();     break;
     case 'bestWicketsBatt' : buildBestWicketsBattMySQLquery();  break;
     case 'bestWicketsBowl' : buildBestWicketsBowlMySQLquery();  break;
     case 'bestOverall'     : buildBestOverallMySQLquery();      break;
     case 'avgRunsBatt'     : buildAvgRunsBattMySQLquery();      break;
     case 'avgRunsBowl'     : buildAvgRunsBowlMySQLquery();      break;
     case 'avgWicketsBatt'  : buildAvgWicketsBattMySQLquery();   break;
     case 'avgWicketsBowl'  : buildAvgWicketsBowlMySQLquery();   break;
     case 'avgOverall'      : buildAvgOverallMySQLquery();       break;
     case 'totalRunsBatt'   : buildtotalRunsBattMySQLquery();    break;
     case 'totalRunsBowl'   : buildtotalRunsBowlMySQLquery();    break;
     case 'totalWicketsBatt': buildtotalWicketsBattMySQLquery(); break;
     case 'totalWicketsBowl': buildtotalWicketsBowlMySQLquery(); break;
     case 'totalOverall'    : buildtotalOverallMySQLquery();     break;
    }

    displayMySQLtable(true);

    // PROBLEM: would like to use line below instead of line above,
    //          but encounter problems with session variables.
    //http_redirect('icdb_display_MySQL_table.php', null, true);
 }

 /*
  *
  */
 function preBuildClauses()
 {
    // begin MySQL query expressions
    $_SESSION['MySQLselectExp'       ] = ' `first_name`, `last_name`';
    $_SESSION['MySQLselectRankAsExp' ] = ''; 
    $_SESSION['MySQLfromExp'         ] = '';
    $_SESSION['MySQLwhereExp'        ] = ' `team_id` = ' . $_SESSION['teamID']
                                        . $_SESSION['MySQLperiodWhereExp'    ]
                                        . $_SESSION['MySQLoppositionWhereExp']
                                        . $_SESSION['MySQLplayersWhereExp'   ]
                                        . $_SESSION['MySQLmatchesWhereExp'   ];
                                        // NOTE: 'MySQLoversOversWhereExp' or
                                        //       'MySQLoversInningsWhereExp' is added elsewhere
                                        //       (in the 'addOversSubtitleStringAndMySQLwhereExp()'
                                        //       function) depending on whether the query
                                        //       concerns batting or bowling.
    $_SESSION['MySQLgroupByExp'      ] = '';
    $_SESSION['MySQLorderByExp'      ] = '';
    $_SESSION['MySQLrankExp'         ] = '';
    $_SESSION['MySQLrankAscOrDesc'   ] = '';
    $_SESSION['MySQLlimit'           ] = 20;
    $_SESSION['MySQLoffset'          ] =  0;
    $_SESSION['tableHeading1'        ] = '';
    $_SESSION['tableHeading2'        ] = $_SESSION['restrictionsSubtitle'];
    $_SESSION['tableHeading3'        ] = '';
    $_SESSION['tableColHeadingsArray'] = array('First<br />Name', 'Last<br />Name');
    $_SESSION['tableDataTypes'       ] = array('string', 'string');
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
       // add HTML newline tag to 'tableHeading2' string if necessary.
       if ($_SESSION['tableHeading2'] != '')
         $_SESSION['tableHeading2'] .= '<br />';

       switch ($_SESSION['radioButton'])
       {
        case 'bestRunsBatt'    :
        case 'bestWicketsBatt' :
        case 'avgRunsBatt'     :
        case 'avgWicketsBatt'  :
        case 'totalRunsBatt'   :
        case 'totalWicketsBatt':
          // Add 'oversInningsSubtitleString' to 'tableHeading2' string.
          $_SESSION['tableHeading2'] .= $_SESSION['oversInningsSubtitleString'];

          // Add 'oversInningsMySQLwhereExp' to 'MySQLwhereExp'.
          $_SESSION['MySQLwhereExp'] .= $_SESSION['MySQLoversInningsWhereExp'];
          break;

        case 'bestRunsBowl'    :
        case 'bestWicketsBowl' :
        case 'avgRunsBowl'     :
        case 'avgWicketsBowl'  :
        case 'totalRunsBowl'   :
        case 'totalWicketsBowl':
          // Add 'oversOversSubtitleString' to 'tableHeading2' string.
          $_SESSION['tableHeading2'] .= $_SESSION['oversOversSubtitleString'];

          // Add 'oversOversMySQLwhereExp' to 'MySQLwhereExp'.
          $_SESSION['MySQLwhereExp'] .= $_SESSION['MySQLoversOversWhereExp'];
          break;

        // NOTE: Neither oversSubtitle string applies to cases
        //       concerning overall bests, totals, or averages.
       }
    }
 }

 // Tables concerning bests. ///////////////////////////////////////////////////////////////////////

 function buildBestRunsBattMySQLquery()
 {
    // finish MySQL query expressions
    $_SESSION['MySQLselectExp'      ]
      .=  ", date_format(`match_date`, '%d/%m/%Y'), time_format(`match_time`, '%l:%i%p')"
        . ', `opp_team_name`, `batting_pos`, `wickets_lost`';
    $_SESSION['MySQLselectRankAsExp'] = '`runs_scored` as `rs`';
    $_SESSION['MySQLfromExp'        ] = '`view_innings`';
    $_SESSION['MySQLorderByExp'     ]
      = '`rs` desc, `match_date` asc, `match_time` asc, `batting_pos` asc';
    $_SESSION['MySQLrankExp'        ] = '`rs`';
    $_SESSION['MySQLrankAscOrDesc'  ] = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'Match<br />Date', 'Match<br />Time', 'Opposition',
               'Batting<br />Position', 'Wickets<br />Lost', 'Runs<br />Scored');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'string',
               'int', 'int', 'int'          );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Most Runs Scored in an Innings';
 }

 function buildBestRunsBowlMySQLquery()
 {
    switch ($_SESSION['perOverORperMatch'])
    {
     case 'perOver':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ]
        .=  ", date_format(`match_date`, '%d/%m/%Y'), time_format(`match_time`, '%l:%i%p')"
          . ', `opp_team_name`, `over_no`, `wickets_taken`';

       $_SESSION['MySQLselectRankAsExp'] = '`runs_conceded` as `rc`';
       $_SESSION['MySQLfromExp'        ] = '`view_overs`';
       $_SESSION['MySQLorderByExp'     ]
         = '`rc` asc, `match_date` asc, `match_time` asc, `over_no` asc';
       $_SESSION['MySQLrankExp'        ] = '`rc`';
       $_SESSION['MySQLrankAscOrDesc'  ] = 'Asc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'Match<br />Date', 'Match<br />Time', 'Opposition',
                  'Over<br />Number', 'Wickets<br />Taken', 'Runs<br />Conceded');
       array_push($_SESSION['tableDataTypes'],
                  'string', 'string', 'string',
                  'int', 'int', 'int'          );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Least Runs Conceded in an Over';
       break;

     case 'perMatch':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ]
        .=  ", date_format(`match_date`, '%d/%m/%Y'), time_format(`match_time`, '%l:%i%p')"
          . ', `opp_team_name`, `n_overs_in_match`'
          . ', (2.0 * `wickets_taken_in_match`) / `n_overs_in_match`';
       $_SESSION['MySQLselectRankAsExp']
         = '((2.0 * `runs_conceded_in_match`) / `n_overs_in_match`) as `rc`';
       $_SESSION['MySQLfromExp'        ]  = '`view_match_bowling_figures`';
       $_SESSION['MySQLorderByExp'     ]  = '`rc` asc, `match_date` asc, `match_time` asc';
       $_SESSION['MySQLrankExp'        ]  = '`rc`';
       $_SESSION['MySQLrankAscOrDesc'  ]  = 'Asc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'Match<br />Date', 'Match<br />Time',
                  'Opposition', 'No.<br />Overs',
                  'Wickets<br />Taken*',
                  'Runs<br />Conceded*' );
       array_push($_SESSION['tableDataTypes'],
                  'string', 'string',
                  'string', 'int',
                  'intORhalvesORthirds',
                  'intORhalvesORthirds' );

       // Finish table headings.
       $_SESSION['tableHeading1'] .= 'Least Runs Conceded in a Match';
       break;

     default:
       error(  "Expected 'perOver' or 'perMatch', received: '{$_SESSION['perOverORperMatch']}'"
             . " in {$_SERVER['PHP_SELF']}::buildBestRunsBowlMySQLquery()."                    );
    }
 }

 function buildBestWicketsBattMySQLquery()
 {
    // Finish MySQL query clauses.
    // (see "icdb_useful_queries.sql")
    $_SESSION['MySQLselectExp'      ]
     .=  ", date_format(`start_date`, '%d/%m/%Y'), date_format(`finish_date`, '%d/%m/%Y')"
       . ', `n_innings`, `wickets_lost`';
    $_SESSION['MySQLfromExp'        ]
      =  "\n"
       . "(\n"
       . "   select `player_id`, `first_name`, `last_name`, `wickets_lost`,\n"
       . "          min(`match_date`) as `start_date`,\n"
       . "          max(`match_date`) as `finish_date`,\n"
       . "          count(*) as `n_innings`\n"
       . "   from\n"
       . "   (\n"
       . "      select `player_id`, `first_name`, `last_name`,\n"
       . "             `match_date`, `match_time`,\n"
       . "             `batting_pos`, `wickets_lost`,\n"
       . "      (\n"
       . "         select count(*)\n"
       . "         from `view_innings` as `vi1`\n"
       . "         where `team_id` = {$_SESSION['teamID']}\n"
       . "         and   `vi1`.`player_id` = `vi2`.`player_id`\n"
       . "         and   `vi1`.`wickets_lost` <> `vi2`.`wickets_lost`\n"
       . "         and\n"
       . "         (\n"
       . "            `vi1`.`match_date` < `vi2`.`match_date`\n"
       . "            or\n"
       . "            (\n"
       . "               `vi1`.`match_date` = `vi2`.`match_date`\n"
       . "               and\n"
       . "               (\n"
       . "                  `vi1`.`match_time` < `vi2`.`match_time`\n"
       . "                  or\n"
       . "                  (\n"
       . "                     `vi1`.`match_time` = `vi2`.`match_time`\n"
       . "                     and\n"
       . "                     `vi1`.`batting_pos` < `vi2`.`batting_pos`\n"
       . "                  )\n"
       . "               )\n"
       . "            )\n"
       . "         )\n"
       . "      ) as `streak_group`\n"
       . "      from `view_innings` as `vi2`\n"
       . "      where `team_id` = {$_SESSION['teamID']}\n"
       . "      order by `player_id`, `match_date`, `match_time`, `batting_pos`\n"
       . "   ) as `dummy`\n"
       . "   group by `player_id`, `wickets_lost`, `streak_group`\n"
       . "   order by min(`match_date`), max(`match_date`)\n"
       . ") as `dummy2`\n";
    $_SESSION['MySQLwhereExp'] =  '`wickets_lost` = 0';
    if ($_SESSION['periodStartDateStr'] != '' && $_SESSION['periodFinishDateStr'] != '')
    {
       $_SESSION['MySQLwhereExp']
         .= "\n"
          . "and `start_date` >= {$_SESSION['periodStartDateStr']}\n"
          . "and `finish_date` <= {$_SESSION['periodFinishDateStr']}";
    }
    $_SESSION['MySQLorderByExp'] = '`n_innings` desc, `start_date` asc, `finish_date` asc';
    array_push($_SESSION['tableColHeadingsArray'],
               'Start<br />Date', 'Finish<br />Date', 'No.<br />Innings');
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'int'   );

    // Finish table headings.
    $_SESSION['tableHeading1'] .= 'Most Consecutive Wicketless Innings';
 }

 function buildBestWicketsBowlMySQLquery()
 {
    switch ($_SESSION['perOverORperMatch'])
    {
     case 'perOver':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ]
        .=  ", date_format(`match_date`, '%d/%m/%Y'), time_format(`match_time`, '%l:%i%p')"
          . ', `opp_team_name`, `over_no`, `runs_conceded`';
       $_SESSION['MySQLselectRankAsExp'] = '`wickets_taken` as `wt`';
       $_SESSION['MySQLfromExp'        ] = '`view_overs`';
       $_SESSION['MySQLorderByExp'     ]
         = '`wt` desc, `match_date` asc, `match_time` asc, `over_no` asc';
       $_SESSION['MySQLrankExp'        ] = '`wt`';
       $_SESSION['MySQLrankAscOrDesc'  ] = 'Desc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'Match<br />Date', 'Match<br />Time', 'Opposition',
                  'Over<br />Number', 'Runs<br />Conceded', 'Wickets<br />Taken');
       array_push($_SESSION['tableDataTypes'],
                  'string', 'string', 'string',
                  'int', 'int', 'int'          );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Most Wickets Taken in an Over';
       break;

     case 'perMatch':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ]
         .=  ", date_format(`match_date`, '%d/%m/%Y'), time_format(`match_time`, '%l:%i%p')"
           . ', `opp_team_name`, `n_overs_in_match`'
           . ', (2.0 * `runs_conceded_in_match`) / `n_overs_in_match`';
       $_SESSION['MySQLselectRankAsExp']
         = '((2.0 * `wickets_taken_in_match`) / `n_overs_in_match`) as `wt`';
       $_SESSION['MySQLfromExp'        ] = '`view_match_bowling_figures`';
       $_SESSION['MySQLorderByExp'     ] = '`wt` desc, `match_date` asc, `match_time` asc';
       $_SESSION['MySQLrankExp'        ] = '`wt`';
       $_SESSION['MySQLrankAscOrDesc'  ] = 'Desc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'Match<br />Date', 'Match<br />Time',
                  'Opposition', 'No.<br />Overs',
                  'Runs<br />Conceded*',
                  'Wickets<br />Taken*'                );
       array_push($_SESSION['tableDataTypes'],
                  'string', 'string',
                  'string', 'int',
                  'intORhalvesORthirds',
                  'intORhalvesORthirds'       );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Most Wickets Taken in a Match';
       break;

     default:
       error(  "Expected 'perOver' or 'perMatch', received: '{$_SESSION['perOverORperMatch']}'"
             . " in {$_SERVER['PHP_SELF']}::buildBestWicketsBowlMySQLquery()."                 );
    }
 }

 function buildBestOverallMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ]
      .=  ", date_format(`match_date`, '%d/%m/%Y'), time_format(`match_time`, '%l:%i%p')"
        . ', `opp_team_name`, (2.0 * `wickets_taken_in_match`) / `n_overs_in_match`'
        . ' - `wickets_lost_in_match` / `n_innings_in_match`';
    $_SESSION['MySQLselectRankAsExp']
      =  '(`runs_scored_in_match` / `n_innings_in_match`'
       . ' - (2.0 * `runs_conceded_in_match`) / `n_overs_in_match`) as `nrs`';
    $_SESSION['MySQLfromExp'        ] = '`view_match_contributions`';
    $_SESSION['MySQLorderByExp'     ] = '`nrs` desc, `match_date` asc, `match_time` asc';
    $_SESSION['MySQLrankExp'        ] = '`nrs`';
    $_SESSION['MySQLrankAscOrDesc'  ] = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'Match<br />Date', 'Match<br />Time', 'Opposition',
               'Net Wickets<br />Taken*', 'Net Runs<br />Scored*' );
    array_push($_SESSION['tableDataTypes'],
               'string', 'string', 'string',
               'intORfraction', 'intORfraction');

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Most Net Runs Scored in a Match';
 }

 // Tables concerning averages. ////////////////////////////////////////////////////////////////////

 function buildAvgRunsBattMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
    $_SESSION['MySQLselectRankAsExp']  = 'avg(`runs_scored`) as `ars`';
    $_SESSION['MySQLfromExp'        ]  = '`view_innings`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`ars` desc';
    $_SESSION['MySQLrankExp'        ]  = '`ars`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches', 'No.<br />Innings', 'Average<br />Runs Scored<br />Per Innings');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'float'       );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Average Runs Scored Per Innings';
 }

 function buildAvgRunsBowlMySQLquery()
 {
    switch ($_SESSION['perOverORperMatch'])
    {
     case 'perOver':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
       $_SESSION['MySQLselectRankAsExp']  = 'avg(`runs_conceded`) as `arc`';
       $_SESSION['MySQLfromExp'        ]  = '`view_overs`';
       $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
       $_SESSION['MySQLorderByExp'     ]  = '`arc` asc';
       $_SESSION['MySQLrankExp'        ]  = '`arc`';
       $_SESSION['MySQLrankAscOrDesc'  ]  = 'Asc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'No.<br />Matches', 'No.<br />Overs', 'Average<br />Runs Conceded<br />Per Over');
       array_push($_SESSION['tableDataTypes'],
                  'int', 'int', 'float'       );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Least Average Runs Conceded Per Over';
       break;

     case 'perMatch':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
       $_SESSION['MySQLselectRankAsExp']  = 'avg(2.0 * `runs_conceded`) as `arc`';
       $_SESSION['MySQLfromExp'        ]  = '`view_overs`';
       $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
       $_SESSION['MySQLorderByExp'     ]  = '`arc` asc';
       $_SESSION['MySQLrankExp'        ]  = '`arc`';
       $_SESSION['MySQLrankAscOrDesc'  ]  = 'Asc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'No.<br />Matches', 'No.<br />Overs',
                  'Average<br />Runs Conceded<br />Per Match*');
       array_push($_SESSION['tableDataTypes'],
                  'int', 'int',
                  'float'                     );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Least Average Runs Conceded Per Match';
       break;

     default:
       error(  "Expected 'perOver' or 'perMatch', received: '{$_SESSION['perOverORperMatch']}'"
             . " in {$_SERVER['PHP_SELF']}::buildAvgRunsBowlMySQLquery()."                     );
    }
 }

 function buildAvgWicketsBattMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
    $_SESSION['MySQLselectRankAsExp']  = 'avg(`wickets_lost`) as `awl`';
    $_SESSION['MySQLfromExp'        ]  = '`view_innings`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`awl` asc';
    $_SESSION['MySQLrankExp'        ]  = '`awl`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Asc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches', 'No.<br />Innings',
               'Average<br />Wickets Lost<br />Per Innings');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int',
               'float'                     );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Least Average Wickets Lost Per Innings';
 }

 function buildAvgWicketsBowlMySQLquery()
 {
    switch ($_SESSION['perOverORperMatch'])
    {
     case 'perOver':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
       $_SESSION['MySQLselectRankAsExp']  = 'avg(`wickets_taken`) as `awt`';
       $_SESSION['MySQLfromExp'        ]  = '`view_overs`';
       $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
       $_SESSION['MySQLorderByExp'     ]  = '`awt` desc';
       $_SESSION['MySQLrankExp'        ]  = '`awt`';
       $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'No.<br />Matches', 'No.<br />Overs', 'Average<br />Wickets Taken<br />Per Over');
       array_push($_SESSION['tableDataTypes'],
                  'int', 'int', 'float'       );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Greatest Average Wickets Taken Per Over';
       break;

     case 'perMatch':
       // finish MySQL query clauses
       $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
       $_SESSION['MySQLselectRankAsExp']  = 'avg(2.0 * `wickets_taken`) as `awt`';
       $_SESSION['MySQLfromExp'        ]  = '`view_overs`';
       $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
       $_SESSION['MySQLorderByExp'     ]  = '`awt` desc';
       $_SESSION['MySQLrankExp'        ]  = '`awt`';
       $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
       array_push($_SESSION['tableColHeadingsArray'],
                  'No.<br />Matches', 'No.<br />Overs',
                  'Average<br />Wickets Taken<br />Per Match*');
       array_push($_SESSION['tableDataTypes'],
                  'int', 'int',
                  'float'                     );

       // finish table headings
       $_SESSION['tableHeading1'] .= 'Greatest Average Wickets Taken Per Match';
       break;

     default:
       error(  "Expected 'perOver' or 'perMatch', received: '{$_SESSION['perOverORperMatch']}'"
             . " in {$_SERVER['PHP_SELF']}::buildAvgWicketsBowlMySQLquery()."                  );
    }
 }

 function buildAvgOverallMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ]
      .=  ', count(*), (2.0 * sum(`wickets_taken_in_match`)) / sum(`n_overs_in_match`)'
        . ' - sum(`wickets_lost_in_match`) / sum(`n_innings_in_match`)';
    $_SESSION['MySQLselectRankAsExp']
      =  'sum(`runs_scored_in_match`) / sum(`n_innings_in_match`)'
       . ' - (2.0 * sum(`runs_conceded_in_match`)) / sum(`n_overs_in_match`) as `anrs`';
    $_SESSION['MySQLfromExp'        ] = '`view_match_contributions`';
    $_SESSION['MySQLgroupByExp'     ] = '`player_id`';
    $_SESSION['MySQLorderByExp'     ] = '`anrs` desc';
    $_SESSION['MySQLrankExp'        ] = '`anrs`';
    $_SESSION['MySQLrankAscOrDesc'  ] = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches',
               'Average<br />Net Wickets<br />Taken*', 'Average<br />Net Runs<br />Scored*');
    array_push($_SESSION['tableDataTypes'],
               'int',
               'float', 'float');

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Average Net Runs Scored Per Match';
 }

 // Tables concerning totals. //////////////////////////////////////////////////////////////////////

 function buildTotalRunsBattMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
    $_SESSION['MySQLselectRankAsExp']  = 'sum(`runs_scored`) as `srs`';
    $_SESSION['MySQLfromExp'        ]  = '`view_innings`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`srs` desc';
    $_SESSION['MySQLrankExp'        ]  = '`srs`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches', 'No.<br />Innings', 'Total<br />Runs<br />Scored');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'int'         );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Total Runs Scored';
 }

 function buildTotalRunsBowlMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
    $_SESSION['MySQLselectRankAsExp']  = 'sum(`runs_conceded`) as `src`';
    $_SESSION['MySQLfromExp'        ]  = '`view_overs`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`src` desc';
    $_SESSION['MySQLrankExp'        ]  = '`src`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches', 'No.<br />Overs', 'Total<br />Runs<br />Conceded');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'int'         );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Total Runs Conceded';
 }

 function buildTotalWicketsBattMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
    $_SESSION['MySQLselectRankAsExp']  = 'sum(`wickets_lost`) as `swl`';
    $_SESSION['MySQLfromExp'        ]  = '`view_innings`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`swl` desc';
    $_SESSION['MySQLrankExp'        ]  = '`swl`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches', 'No.<br />Innings', 'Total<br />Wickets<br />Lost');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'int'         );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Total Wickets Lost';
 }

 function buildTotalWicketsBowlMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(distinct `match_id`), count(*)';
    $_SESSION['MySQLselectRankAsExp']  = 'sum(`wickets_taken`) as swt';
    $_SESSION['MySQLfromExp'        ]  = '`view_overs`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`swt` desc';
    $_SESSION['MySQLrankExp'        ]  = '`swt`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches', 'No.<br />Overs', 'Total<br />Wickets<br />Taken');
    array_push($_SESSION['tableDataTypes'],
               'int', 'int', 'int'         );

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Total Wickets Taken';
 }

 function buildTotalOverallMySQLquery()
 {
    // finish MySQL query clauses
    $_SESSION['MySQLselectExp'      ] .= ', count(*), sum(`net_wickets_taken_in_match`)';
    $_SESSION['MySQLselectRankAsExp']  = 'sum(`net_runs_scored_in_match`) as `snrs`';
    $_SESSION['MySQLfromExp'        ]  = '`view_match_contributions`';
    $_SESSION['MySQLgroupByExp'     ]  = '`player_id`';
    $_SESSION['MySQLorderByExp'     ]  = '`snrs` desc';
    $_SESSION['MySQLrankExp'        ]  = '`snrs`';
    $_SESSION['MySQLrankAscOrDesc'  ]  = 'Desc';
    array_push($_SESSION['tableColHeadingsArray'],
               'No.<br />Matches',
               'Total<br />Net Wickets<br />Taken', 'Total<br />Net Runs<br />Scored');
    array_push($_SESSION['tableDataTypes'],
               'int',
               'intORfraction', 'intORfraction');

    // finish table headings
    $_SESSION['tableHeading1'] .= 'Greatest Total Match Contribution';
 }
?>
