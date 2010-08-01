<?php
 require_once '../../../common/misc/icdb_functions.php';

 // Clear $_SESSION[] array;
 $_SESSION = array();

 $_SESSION['databaseName'] = 'indoor_cricket_database';

 $_SESSION['teamName'        ] = $_POST['teamNameId'        ];
 $_SESSION['teamID'          ] = $_POST['teamIdId'          ];
 $_SESSION['n_playersPerTeam'] = $_POST['n_playersPerTeamId'];

 $_SESSION['debug'] = true;

 connectToMySQL_icdb();

 // Create $_SESSION[] variables relating to the 'Restrictions' table.
 // ('Period', 'Opposition', & 'Matches' restrictions apply to all selections)
 createSessionVarsForR_tabPeriod();
 createSessionVarsForR_tabOpposition();
 createSessionVarsForR_tabMatches();
 // ('Players' & 'Overs' restrictions do not apply to 'teamStats' and 'teamRecords' selections)
 if (   $_POST['s_tabSelectedTabId'] != 'teamStats'
     && $_POST['s_tabSelectedTabId'] != 'teamRecords')
 {
    createSessionVarsForR_tabPlayers();
    createSessionVarsForR_tabOvers();
 }

 // create subtitle string ($_SESSION['restrictionsSubtitle'])
 createRestrictionsSubtitle();

 // include only relevant files
 switch ($_POST['s_tabSelectedTabId'])
 {
  case 'playerStats'   : $requiredFile = 'icdb_data_retrieval_player_stats.php';    break;
  case 'playerRankings': $requiredFile = 'icdb_data_retrieval_player_rankings.php'; break;
  case 'teamStats'     : $requiredFile = 'icdb_data_retrieval_team_stats.php';      break;
  case 'teamRecords'   : $requiredFile = 'icdb_data_retrieval_team_records.php';    break;
  case 'battPships'    : $requiredFile = 'icdb_data_retrieval_batt_pships.php';     break;
  default:
    error(  'Unexpected $_POST[\'s_tabSelectedTabId\'] variable'
          . " '{$_POST['s_tabSelectedTabId']}' in {$_SERVER['PHP_SELF']}.");
 }
 require_once $requiredFile;

 // Save only relevant $_POST[] variables to $_SESSION[] and call function for selected tab.
 switch ($_POST['s_tabSelectedTabId'])
 {
  case 'playerStats':
    $_SESSION['radioButton'   ] = $_POST['s_tabPShiddenDataRadioButtonId'   ];
    $_SESSION['playerSelector'] = $_POST['s_tabPShiddenDataPlayerSelectorId'];
    displayPlayerStatsSelection();
    break;

  case 'playerRankings':
    $_SESSION['radioButton'      ] = $_POST['s_tabPRhiddenDataRadioButtonId'  ];
    $_SESSION['perOverORperMatch'] = $_POST['s_tabPRhiddenDataBowlingToggleId'];
    displayPlayerRankingsSelection();
    break;

  case 'teamStats':
    $_SESSION['radioButton'  ] = $_POST['s_tabTShiddenDataRadioButtonId'  ];
    $_SESSION['matchSelector'] = $_POST['s_tabTShiddenDataMatchSelectorId'];
    displayTeamStatsSelection();
    break;

  case 'teamRecords':
    $_SESSION['radioButton'] = $_POST['s_tabTRhiddenDataRadioButtonId'];
    displayTeamRecordsSelection();
    break;

  case 'battPships':
    $_SESSION['radioButton'           ] = $_POST['s_tabBPhiddenDataRadioButtonId'           ];
    $_SESSION['batsmanOneNameSelector'] = $_POST['s_tabBPhiddenDataBatsmanOneNameSelectorId'];
    $_SESSION['batsmanTwoNameSelector'] = $_POST['s_tabBPhiddenDataBatsmanTwoNameSelectorId'];
    displayBattPshipsSelection();
    break;
 }

 /*
  *
  */
 function createSessionVarsForR_tabPeriod()
 {
    // initialize r_tabPeriod $_SESSION[] variables
    $_SESSION['MySQLperiodWhereExp' ] = '';
    $_SESSION['periodSubtitleString'] = '';
    $_SESSION['periodStartDateStr'  ] = '';
    $_SESSION['periodFinishDateStr' ] = '';

    // if r_tabPeriod restrictions have been selected, extend r_tabPeriod $_SESSION[] variables
    if (isset($_POST['r_tabPeriodCheckboxId']))
    {
       // get season name from $_POST[] array
       $seasonName = $_POST['r_tabPeriodHiddenDataSeasonId'];
       if ($seasonName == 'Select Season')
         $seasonName = '';

       // get start & finish dates from $_POST[] array
       $startDay    = $_POST['r_tabPeriodHiddenDataStartDayId'   ];
       $startMonth  = $_POST['r_tabPeriodHiddenDataStartMonthId' ];
       $startYear   = $_POST['r_tabPeriodHiddenDataStartYearId'  ];
       $finishDay   = $_POST['r_tabPeriodHiddenDataFinishDayId'  ];
       $finishMonth = $_POST['r_tabPeriodHiddenDataFinishMonthId'];
       $finishYear  = $_POST['r_tabPeriodHiddenDataFinishYearId' ];

       // create $_SESSION['periodStartDateStr'] and $_SESSION['periodStartDateStr']
       // (These are intended to be used to extend MySQL queries
       //  where $_SESSION['MySQLperiodWhereExp'] does not apply.)
       $_SESSION['periodStartDateStr' ] = "'$startYear-$startMonth-$startDay'";
       $_SESSION['periodFinishDateStr'] = "'$finishYear-$finishMonth-$finishDay'";

       // extend $_SESSION['MySQLperiodWhereExp']
       $_SESSION['MySQLperiodWhereExp']
         .= ' and `match_date` >= ' . $_SESSION['periodStartDateStr' ]
          . ' and `match_date` <= ' . $_SESSION['periodFinishDateStr'];

       // extend $_SESSION['periodSubtitleString']
       $_SESSION['periodSubtitleString']
         .= 'Period: '
          . $seasonName
          . ' [' . $startDay  . '/' . $startMonth  . '/' . $startYear  . ', '
          .        $finishDay . '/' . $finishMonth . '/' . $finishYear . ']';

       // if match time restrictions have been set, extend r_tabPeriod $_SESSION[] variables
       if ($_POST['r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id'] == '1')
       {
          // get start & finish times from $_POST[] array
          $startHour    = $_POST['r_tabPeriodHiddenDataStartHourId'   ];
          $startMinute  = $_POST['r_tabPeriodHiddenDataStartMinuteId' ];
          $startAMorPM  = $_POST['r_tabPeriodHiddenDataStartAMorPMId' ];
          $finishHour   = $_POST['r_tabPeriodHiddenDataFinishHourId'  ];
          $finishMinute = $_POST['r_tabPeriodHiddenDataFinishMinuteId'];
          $finishAMorPM = $_POST['r_tabPeriodHiddenDataFinishAMorPMId'];

          // convert times to 24 hour format
          $startHour24  = (($startAMorPM  == 'AM')? $startHour : $startHour  + 12);
          $finishHour24 = (($finishAMorPM == 'AM')? $finishHour: $finishHour + 12);

          // extend $_SESSION['MySQLperiodWhereExp']
          $_SESSION['MySQLperiodWhereExp']
            .= " and `match_time` >= '$startHour24:"
             . (($startMinute  <= 9)? '0': '') . $startMinute  . ":00'"
             . " and `match_time` <= '$finishHour24:"
             . (($finishMinute <= 9)? '0': '') . $finishMinute . ":00'";

          // extend $_SESSION['periodSubtitleString']
          $_SESSION['periodSubtitleString']
            .= ', [' . $startHour  . ':'
                     . (($startMinute  <= 9)? '0': '') . $startMinute  . $startAMorPM  . ', '
                     . $finishHour . ':'
                     . (($finishMinute <= 9)? '0': '') . $finishMinute . $finishAMorPM . ']';
       }
    }
 }

 /*
  *
  */
 function createSessionVarsForR_tabOpposition()
 {
    // initialize r_tabOpposition $_SESSION[] variables
    $_SESSION['MySQLoppositionWhereExp' ] = '';
    $_SESSION['oppositionSubtitleString'] = '';

    // If r_tabOpposition restrictions have been selected,
    // extend r_tabOpposition $_SESSION[] variables.
    if (isset($_POST['r_tabOppositionCheckboxId']))
    {
       // get opposition team name from $_POST[] array
       $oppTeamName = $_POST['r_tabOppositionHiddenDataOppTeamNameSelectorId'];

       // extend $_SESSION['MySQLoppositionWhereExp']
       $_SESSION['MySQLoppositionWhereExp'] .= ' and `opp_team_name` = "' . $oppTeamName . '"';

       // extend $_SESSION['oppositionSubtitleString']
       $_SESSION['oppositionSubtitleString'] .= 'Opposition: ' . $oppTeamName;
    }
 }

 /*
  * NOTE: The javascript files prevent any 
  */
 function createSessionVarsForR_tabPlayers()
 {
    // initialize r_tabPlayers $_SESSION[] variables
    $_SESSION['MySQLplayersWhereExp' ] = '';
    $_SESSION['playersSubtitleString'] = '';
    $_SESSION['MySQLplayersWhereExpForBattingPships'] = '';

    // if r_tabPlayers restrictions have been selected, extend r_tabPlayers $_SESSION[] variables
    if (isset($_POST['r_tabPlayersCheckboxId']))
    {
       $_SESSION['playersSubtitleString'] .= 'Players: ';

       // If 'minMatches' is greater than '1', further extend r_tabPlayers $_SESSION[] variables.
       $minMatches = $_POST['r_tabPlayersHiddenDataMinMatchesId'];
       if ($minMatches > 1)
       {
          // extend $_SESSION['MySQLplayersWhereExp'] to account for minimum matches restriction
          $_SESSION['MySQLplayersWhereExp']
            .=  "\n"
              . "and `player_id` in\n"
              . "(\n"
              . "   select `player_id`\n"
              . "   from\n"
              . "   (\n"
              . "      select `player_id`, count(distinct `match_id`)\n"
              . "      from `view_innings`\n"
              . '      where true ' // 'true' required because added conditions start with ' and '.
              . $_SESSION['MySQLperiodWhereExp'] . $_SESSION['MySQLoppositionWhereExp'] . "\n"
              . "      group by `player_id`\n"
              . "      having count(distinct `match_id`) >= $minMatches\n"
              . "   ) as `dummy`\n"
              . ")\n";

          // Create similar expression as one above for use with batting partnership queries,
          // where instead of `player_id` we have `p1_player_id` and `p2_player_id`.
          $_SESSION['MySQLplayersWhereExpForBattingPships']
            .=  "\n"
              . "and `p1_player_id` in\n"
              . "(\n"
              . "   select `player_id`\n"
              . "   from\n"
              . "   (\n"
              . "      select `player_id`, count(distinct `match_id`)\n"
              . "      from `view_innings`\n"
              . '      where true' // 'true' required because added conditions start with ' and '.
              . $_SESSION['MySQLperiodWhereExp'] . $_SESSION['MySQLoppositionWhereExp'] . "\n"
              . "      group by `player_id`\n"
              . "      having count(distinct `match_id`) >= $minMatches\n"
              . "   ) as `dummy`\n"
              . ")\n"
              . "and `p2_player_id` in\n"
              . "(\n"
              . "   select `player_id`\n"
              . "   from\n"
              . "   (\n"
              . "      select `player_id`, count(distinct `match_id`)\n"
              . "      from `view_innings`\n"
              . '      where true' // 'true' required because added conditions start with ' and '.
              . $_SESSION['MySQLperiodWhereExp'] . $_SESSION['MySQLoppositionWhereExp'] . "\n"
              . "      group by `player_id`\n"
              . "      having count(distinct `match_id`) >= $minMatches\n"
              . "   ) as `dummy`\n"
              . ")\n";

          // extend $_SESSION['playersSubtitleString'] to account for minimum matches restriction
          $_SESSION['playersSubtitleString'] .=  'Minimum Matches = ' . $minMatches;
          $playersSubtitleListEmpty = false;
       }

       // If 'regular', 'current', 'fill in' or 'retired' are unchecked,
       // further extend r_tabPlayers $_SESSION[] variables.
       $retired = $_POST['r_tabPlayersHiddenDataRetired0or1Id'];
       $current = $_POST['r_tabPlayersHiddenDataCurrent0or1Id'];
       $fillin  = $_POST['r_tabPlayersHiddenDataFillin0or1Id' ];
       $regular = $_POST['r_tabPlayersHiddenDataRegular0or1Id'];
       if ($retired == 0 || $current == 0 || $fillin == 0 || $regular == 0)
       {
          // extend $_SESSION['MySQLplayersWhereExp'] to account for player category restriction
          // NOTE: 'current' means not 'retired', 'regular' means not 'fill in'.
          //       So with default selection (all four checkboxes checked),
          //       included players are:
          //       (retired OR not retired) and (fill in OR not fill in)
          if (!$regular && !$fillin ) $regularORfillin  = 'neither';
          if (!$regular &&  $fillin ) $regularORfillin  =  'fillin';
          if ( $regular && !$fillin ) $regularORfillin  = 'regular';
          if ( $regular &&  $fillin ) $regularORfillin  =  'either';
          if (!$current && !$retired) $currentORretired = 'neither';
          if (!$current &&  $retired) $currentORretired = 'retired';
          if ( $current && !$retired) $currentORretired = 'current';
          if ( $current &&  $retired) $currentORretired =  'either';
          $_SESSION['MySQLplayersWhereExp']
            .=  " and `player_id` in\n"
              . "(\n"
              . "   select `player_id`\n"
              . "   from `players`\n"
              . "   where ";
          switch ($regularORfillin)
          {
             case 'neither':
               $_SESSION['MySQLplayersWhereExp']
                 .= '(`fill_in` <> 0 and `fill_in` <> 1)'; // will exclude all records
               break;
             case 'either':
               $_SESSION['MySQLplayersWhereExp']
                 .= '(`fill_in` =  0 or  `fill_in` =  1)'; // will include all records
               break;
             case 'regular': $_SESSION['MySQLplayersWhereExp'] .= '`fill_in` = 0'; break;
             case  'fillin': $_SESSION['MySQLplayersWhereExp'] .= '`fill_in` = 1'; break;
             default:
               error(  'Default case reached in switch ($regularORfillin) statement in'
                     . " {$_SERVER['PHP_SELF']}::createSessionVarsForR_tabPlayers()."  );
          }
          switch ($currentORretired)
          {
             case 'neither':
               $_SESSION['MySQLplayersWhereExp']
                 .= ' and (`retired` <> 0 and `retired` <> 1)'; // will exclude all records
               break;
             case 'either':
               $_SESSION['MySQLplayersWhereExp']
                 .= ' and (`retired` =  0 or  `retired` =  1)'; // will include all records
               break;
             case 'current': $_SESSION['MySQLplayersWhereExp'] .= ' and `retired` = 0'; break;
             case 'retired': $_SESSION['MySQLplayersWhereExp'] .= ' and `retired` = 1'; break;
             default:
               error(  'Default case reached in switch ($currentORretired) statement in'
                     . " {$_SERVER['PHP_SELF']}::createSessionVarsForR_tabPlayers()."   );
          }
          $_SESSION['MySQLplayersWhereExp']
             .= ")\n";

          // extend $_SESSION['playersSubtitleString'] to account for minimum matches restriction
          $_SESSION['playersSubtitleString'] .= (($minMatches > 1)? ', ': '') . 'Excluding ';
          $excludedPlayerCategoriesList = array();
          if (!$regular) array_push($excludedPlayerCategoriesList, 'regular');
          if (!$current) array_push($excludedPlayerCategoriesList, 'current');
          if (!$fillin ) array_push($excludedPlayerCategoriesList, 'fill in');
          if (!$retired) array_push($excludedPlayerCategoriesList, 'retired');
          $_SESSION['playersSubtitleString']
            .= createTextListWithCorrectGrammer($excludedPlayerCategoriesList) . ' players.';
       }
    }
 }

 /*
  *
  */
 function createSessionVarsForR_tabMatches()
 {
    // Initialize r_tabMatches $_SESSION[] variables.
    $_SESSION['MySQLmatchesWhereExp' ] = '';
    $_SESSION['matchesSubtitleString'] = '';

    // If r_tabMatches restrictions have been selected, extend r_tabMatches $_SESSION[] variables.
    if (isset($_POST['r_tabMatchesCheckboxId']))
    {
       $_SESSION['matchesSubtitleString'] = 'Matches: ';
       $_SESSION['MySQLmatchesWhereExp']
         .=  " and `match_id` in\n"
           . "(\n"
           . "   select `match_id`\n"
           . "   from `view_matches`\n"
           . '   where true'; // 'true' is included so that all conditions added start with ' and '.

       // Get values of 'Match Result' checkboxes from $_POST[] array. //////////////////////////
       $win  = $_POST['r_tabMatchesHiddenDataWin0or1Id' ];
       $draw = $_POST['r_tabMatchesHiddenDataDraw0or1Id'];
       $loss = $_POST['r_tabMatchesHiddenDataLoss0or1Id'];

       // Extend $_SESSION['MySQLmatchesWhereExp'] to account for 'Match Result' settings.
       if (!$win || !$draw || !$loss)
       {
          $_SESSION['MySQLmatchesWhereExp']
            .=  ' and ('
              . ((!$win )? "`result` <> 'W'" . ((!$draw || !$loss)? ' and ': ''): '')
              . ((!$draw)? "`result` <> 'D'" . ((!$loss          )? ' and ': ''): '')
              . ((!$loss)? "`result` <> 'L'": '')
              . ')';

          // Extend $_SESSION['matchesSubtitleString'] to account for 'Match Result' settings.
          $_SESSION['matchesSubtitleString'] .= '(';
          $includedCategoriesList = array();
          if ($win ) array_push($includedCategoriesList, 'Wins'  );
          if ($draw) array_push($includedCategoriesList, 'Draws' );
          if ($loss) array_push($includedCategoriesList, 'Losses');
          $_SESSION['matchesSubtitleString']
            .= createTextListWithCorrectGrammer($includedCategoriesList) . ')';
       }

       // Get values of 'Innings Order' checkboxes from $_POST[] array. /////////////////////////
       $batted1st = $_POST['r_tabMatchesHiddenDataBatted1st0or1Id'];
       $batted2nd = $_POST['r_tabMatchesHiddenDataBatted2nd0or1Id'];

       // Extend $_SESSION['MySQLmatchesWhereExp'] to account for 'Innings Order' settings.
       if (!$batted1st || !$batted2nd)
       {
          $_SESSION['MySQLmatchesWhereExp']
            .=  ' and ('
              . ((!$batted1st)? "`team_batted_1st` <> 1" . ((!$batted2nd)? ' and ': ''): '')
              . ((!$batted2nd)? "`team_batted_1st` <> 0": '')
              . ')';

          // Extend $_SESSION['matchesSubtitleString'] to account for 'Innings Order' settings.
          $_SESSION['matchesSubtitleString'] .= ((!$win || !$draw || !$loss)? ', ': '') . '(';
          $includedCategoriesList = array();
          if ($batted1st) array_push($includedCategoriesList, 'Batting 1st');
          if ($batted2nd) array_push($includedCategoriesList, 'Batting 2nd');
          $_SESSION['matchesSubtitleString']
            .= createTextListWithCorrectGrammer($includedCategoriesList) . ')';
       }

       // Get values of 'Missing Players' checkboxes from $_POST[] array. ///////////////////////
       $fullTeam = $_POST['r_tabMatchesHiddenDataFullTeam0or1Id' ];
       $shortOne = $_POST['r_tabMatchesHiddenDataShortOne0or1Id'];
       $shortTwo = $_POST['r_tabMatchesHiddenDataShortTwo0or1Id'];

       // Extend $_SESSION['MySQLmatchesWhereExp'] to account for 'Missing Players' settings.
       if (!$fullTeam || !$shortOne || !$shortTwo)
       {
          $n = $_SESSION['n_playersPerTeam'];
          $_SESSION['MySQLmatchesWhereExp']
            .=  ' and ('
              . ((!$fullTeam)? "`n_players` <> $n" . ((!$shortOne || !$shortTwo)? ' and ': ''): '')
              . ((!$shortOne)? "`n_players` <> $n - 1" . ((!$shortTwo)? ' and ': ''): '')
              . ((!$shortTwo)? "`n_players` <> $n - 2": '')
              . ')';

          // Extend $_SESSION['matchesSubtitleString'] to account for 'Missing Players' settings.
          $_SESSION['matchesSubtitleString']
            .= ((!$win || !$draw || !$loss || !$batted1st || !$batted2nd)? ', ': '') . '(';
          $includedCategoriesList = array();
          if ($fullTeam) array_push($includedCategoriesList, 'Full Team');
          if ($shortOne) array_push($includedCategoriesList, 'Short One Player');
          if ($shortTwo) array_push($includedCategoriesList, 'Short Two Players');
          $_SESSION['matchesSubtitleString']
            .= createTextListWithCorrectGrammer($includedCategoriesList) . ')';
       }

       // Get values of 'Match Type' checkboxes from $_POST[] array. ////////////////////////////
       $regular   = $_POST['r_tabMatchesHiddenDataRegular0or1Id'  ];
       $irregular = $_POST['r_tabMatchesHiddenDataIrregular0or1Id'];
       $finals    = $_POST['r_tabMatchesHiddenDataFinals0or1Id'   ];

       // Extend $_SESSION['MySQLmatchesWhereExp'] to account for 'Match Type' settings.
       if (!$regular || !$irregular || !$finals)
       {
          $n = $_SESSION['n_playersPerTeam'];
          $_SESSION['MySQLmatchesWhereExp']
            .=  ' and ('
              . ((!$regular  )? "`match_type` <> 'R'" . ((!$irregular || !$finals)? ' and ': ''):'')
              . ((!$irregular)? "`match_type` <> 'I'" . ((!$finals)? ' and ': ''): '')
              . ((!$finals   )? "`match_type` <> 'F'": '')
              . ')';

          // Extend $_SESSION['matchesSubtitleString'] to account for 'Match Type' settings.
          $_SESSION['matchesSubtitleString']
            .= ((   !$win       || !$draw     || !$loss     || !$batted1st
                 || !$batted2nd || !$fullTeam || !$shortOne || !$shortTwo )? ', ': '') . '(';
          $includedCategoriesList = array();
          if ($regular  ) array_push($includedCategoriesList, 'Regular'  );
          if ($irregular) array_push($includedCategoriesList, 'Irregular');
          if ($finals   ) array_push($includedCategoriesList, 'Finals'   );
          $_SESSION['matchesSubtitleString']
            .= createTextListWithCorrectGrammer($includedCategoriesList) . ')';
       }

       $_SESSION['MySQLmatchesWhereExp']
         .=  "\n)";
    }
 }

 /*
  * NOTE: Since the 'Overs' restrictions page contains restrictions for both overs and innings,
  *       and in no query are both overs and innings retrictions used together,
  *       the MySQL where expression for the 'Overs' restrictions page,
  *       and the subtitle expression for the 'Overs' restrictions page,
  *       have each been divided into two variables:
  *         $_SESSION['MySQLoversOversWhereExp'  ] $_SESSION['oversOversSubtitleString'  ]
  *         $_SESSION['MySQLoversInningsWhereExp'] $_SESSION['oversInningsSubtitleString']
  */
 function createSessionVarsForR_tabOvers()
 {
    // initialize r_tabOvers $_SESSION[] variables
    $_SESSION['MySQLoversOversWhereExp'                   ] = '';
    $_SESSION['MySQLoversInningsWhereExp'                 ] = '';
    $_SESSION['MySQLoversInningsWhereExpForBattingPships' ] = '';
    $_SESSION['oversOversSubtitleString'                  ] = '';
    $_SESSION['oversInningsSubtitleString'                ] = '';
    $_SESSION['oversInningsSubtitleStringForBattingPships'] = '';

    // if r_tabOvers restrictions have been selected, extend r_tabOvers $_SESSION[] variables
    if (isset($_POST['r_tabOversCheckboxId']))
    {
       // get values of 'Overs' checkboxes from $_POST[] array //////////////////////////////////
       $oversArray = array(16); // indices 0-15 correspond to overs 1-16
       for ($overNo = 1; $overNo <= 16; ++$overNo)
         $oversArray[$overNo - 1] = $_POST['r_tabOversHiddenDataOver' . $overNo . '_0or1Id'];

       if (in_array(0, $oversArray)) // if any overs have been excluded (checkbox unchecked)
       {
          $_SESSION['oversOversSubtitleString'] = 'Overs:';

          // extend $_SESSION['MySQLoversWhereExp'] to account for 'Overs' settings
          $_SESSION['MySQLoversOversWhereExp'] .= ' and (';
          $bracketedExpEmpty = true;
          for ($overNo = 1; $overNo <= 16; ++$overNo)
          {
             if ($oversArray[$overNo - 1])
             {
                $_SESSION['MySQLoversOversWhereExp']
                  .= (($bracketedExpEmpty)? '': ' or ') . "`over_no` = $overNo";

                $bracketedExpEmpty = false;
             }
          }
          $_SESSION['MySQLoversOversWhereExp'] .= ')';

          // extend $_SESSION['oversSubtitleString']
          $checkedOversList = createTextIndicesExpWithCorrectGrammer($oversArray);
          $_SESSION['oversOversSubtitleString']
            .= ' Over' . ((strlen($checkedOversList) > 2)? 's': '') . ' '
                       . $checkedOversList . '.';
       }

       // get values of 'Innings' checkboxes from $_POST[] array ////////////////////////////////
       $inningsArray = array(8); // indices 0-7 correspond to innings 1-8
       for ($inningsNo = 1; $inningsNo <= 8; ++$inningsNo)
         $inningsArray[$inningsNo - 1]
           = $_POST['r_tabOversHiddenDataInnings' . $inningsNo . '_0or1Id'];

       if (in_array(0, $inningsArray)) // if any innings have been excluded (checkbox unchecked)
       {
          $_SESSION['oversInningsSubtitleString'                ] = 'Overs:';
          $_SESSION['oversInningsSubtitleStringForBattingPships'] = 'Overs:';

          // extend $_SESSION['MySQLoversWhereExp'] to account for 'Innings' settings
          $_SESSION['MySQLoversInningsWhereExp'                ] .= ' and (';
          $_SESSION['MySQLoversInningsWhereExpForBattingPships'] .= ' and (';
          $tempExpForBattingPshipsP1 = '(';
          $tempExpForBattingPshipsP2 = '(';
          $bracketedExpEmpty = true;
          $bracketedExpEmptyPshipsP1 = true; // For 'tempExpForBattingPshipsP1' defined above.
          $bracketedExpEmptyPshipsP2 = true; // For 'tempExpForBattingPshipsP2' defined above.
          for ($inningsNo = 1; $inningsNo <= 8; ++$inningsNo)
          {
             if ($inningsArray[$inningsNo - 1])
             {
                // continue $_SESSION['MySQLoversInningsWhereExp']
                if ($bracketedExpEmpty ) $bracketedExpEmpty  = false;
                else                     $_SESSION['MySQLoversInningsWhereExp'] .= ' or ';
                $_SESSION['MySQLoversInningsWhereExp'] .= "`batting_pos` = $inningsNo";

                // continue $tempExpForBattingPshipsP1
                if ($bracketedExpEmptyPshipsP1) $bracketedExpEmptyPshipsP1 = false;
                else                            $tempExpForBattingPshipsP1 .= ' or ';
                $tempExpForBattingPshipsP1 .= "`p1_batting_pos` = $inningsNo";

                // continue $tempExpForBattingPshipsP2
                if ($bracketedExpEmptyPshipsP2) $bracketedExpEmptyPshipsP2 = false;
                else                            $tempExpForBattingPshipsP2 .= ' or ';
                $tempExpForBattingPshipsP2 .= "`p2_batting_pos` = $inningsNo";
             }
          }
          $_SESSION['MySQLoversInningsWhereExp'] .= ')';
          $tempExpForBattingPshipsP1 .= ')';
          $tempExpForBattingPshipsP2 .= ')';
          $_SESSION['MySQLoversInningsWhereExpForBattingPships']
            .= $tempExpForBattingPshipsP1 . ' and ' . $tempExpForBattingPshipsP2 .')';

          // extend $_SESSION['oversSubtitleString']
          $checkedInningsList = createTextIndicesExpWithCorrectGrammer($inningsArray);
          $_SESSION['oversInningsSubtitleString']
            .= ' Batting Position' . ((strlen($checkedInningsList) > 2)? 's': '') . ' '
                                   . $checkedInningsList . '.';
          $checkedBattingPshipsList = createBattingPshipsList($inningsArray);
          $_SESSION['oversInningsSubtitleStringForBattingPships']
            .= ' Batting Partnership' . ((strlen($checkedBattingPshipsList) > 2)? 's': '') . ' '
                                      . $checkedBattingPshipsList . '.';
       }
    }
 }

 /*
  * Create a string describing which of the 4 batting partnerships have been selected.
  * Eg. Partnership 1 is selected if inningsArray[1] and inningsArray[2] are both equal to 1.
  */
 function createBattingPshipsList($inningsArray)
 {
    if (count($inningsArray) != 8)
      error(  'Ineligible inningsArray received in'
            . " {$_SERVER['PHP_SELF']}::createBattingPshipsList().");

    $pshipsArray = array(4); // Indices 0-3 correspond to pships 1-4.
    for ($p = 0; $p < 4; ++$p)
      if ($inningsArray[2 * $p] && $inningsArray[2 * $p + 1])
        $pshipsArray[$p] = true;
      else
        $pshipsArray[$p] = false;

    return createTextIndicesExpWithCorrectGrammer($pshipsArray);
 }

 /*
  * Complete table subtitles ($_SESSION['tableHeading2'])
  * ensuring that the subtitle string for each of the five restrictions
  * pages is on a separate line, and that there are no empty lines.
  */
 function createRestrictionsSubtitle()
 {
    $_SESSION['restrictionsSubtitle'] = '';

    if ($_SESSION['periodSubtitleString'] != '')
      $_SESSION['restrictionsSubtitle'] .= (($_SESSION['restrictionsSubtitle'] == '')? '': '<br />')
                                         . $_SESSION['periodSubtitleString'    ];
    if ($_SESSION['oppositionSubtitleString'] != '')
      $_SESSION['restrictionsSubtitle'] .= (($_SESSION['restrictionsSubtitle'] == '')? '': '<br />')
                                         . $_SESSION['oppositionSubtitleString'];

    if (   $_POST['s_tabSelectedTabId'] != 'teamStats'    // NOTE: Players restrictions are not
        && $_POST['s_tabSelectedTabId'] != 'teamRecords'  //       applicable to teamStats/Records.
        && $_SESSION['playersSubtitleString'] != ''
        && !(    $_POST['s_tabSelectedTabId'] == 'playerStats'
             // NOTE: Players restrictions are not applicable to stats by batting
             //       position or stats by over when 'All Players' is not selected.
             && (   $_POST['s_tabPShiddenDataRadioButtonId'] == 'statsByBattPosTable'
                 || $_POST['s_tabPShiddenDataRadioButtonId'] == 'statsByBattPosCharts'
                 || $_POST['s_tabPShiddenDataRadioButtonId'] == 'statsByOverNoTable'
                 || $_POST['s_tabPShiddenDataRadioButtonId'] == 'statsByOverNoCharts' )
             && $_SESSION['playerSelector'] != 'All Players'                           ))
      $_SESSION['restrictionsSubtitle'] .= (($_SESSION['restrictionsSubtitle'] == '')? '': '<br />')
                                         . $_SESSION['playersSubtitleString'];

    if ($_SESSION['matchesSubtitleString'] != '')
      $_SESSION['restrictionsSubtitle'] .= (($_SESSION['restrictionsSubtitle'] == '')? '': '<br />')
                                         . $_SESSION['matchesSubtitleString'];

    // NOTE: Overs restrictions are added to the $_SESSION['restrictionsSubtitle'] string
    //       later (in the 'addOversSubtitleStringAndMySQLwhereExp()' functions).  This is
    //       because the overs restrictions do not apply to batting queries and the innings
    //       restrictions do not apply to bowling queries.
 }
?>
