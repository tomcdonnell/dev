<?php
 require_once '../../common/misc/icdb_functions.php';
 require_once '../../common/date_and_time/date_functions.php';

 // Save teamID and teamName before clearing $_SESSION[] variables.
 $teamID   = $_SESSION['teamID'  ];
 $teamName = $_SESSION['teamName'];

 // Clear $_SESSION[] variables.
 $_SESSION = array();

 // Reinstate saved $_SESSION[] variables.
 $_SESSION['teamID'  ] = $teamID;
 $_SESSION['teamName'] = $teamName;

 // Enable/disable debugging info.
 $_SESSION['debug'] = true;

 connectToMySQL_icdb();

 echoDoctypeXHTMLtransitionalString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../common/css/style.css" type="text/css" />
  <title>IndoorCricketStats.net (Data Retrieval Form)</title>
  <script type="text/javascript" src="../../common/misc/misc_functions.js"></script>
  <script type="text/javascript" src="../../common/date_and_time/date_functions.js"></script>
  <script type="text/javascript" src="icdb_data_retrieval_form.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_s_tab_player_stats.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_s_tab_player_rankings.js">
  </script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_s_tab_team_stats.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_s_tab_team_records.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_s_tab_batt_pships.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_r_tab_period.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_r_tab_opposition.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_r_tab_players.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_r_tab_matches.js"></script>
  <script type="text/javascript" src="tabs/icdb_data_retrieval_form_r_tab_overs.js"></script>
 </head>
 <body onload="init()">
  <form method="post"
   target="icdb_data_retrieval_form_result_id"
   action="retrieve/icdb_data_retrieval_form_result.php">

   <!-- Top level hidden data -->
   <input type="hidden" value="Two Dogs" id="teamNameId" name="teamNameId" />
   <input type="hidden" value="1" id="teamIdId" name="teamIdId" />
   <input type="hidden" value="8" id="n_playersPerTeamId" name="n_playersPerTeamId" />

   <!-- s_tabPS hidden data -->
   <input type="hidden" id="s_tabPShiddenDataRadioButtonId"
    name="s_tabPShiddenDataRadioButtonId" value="summary" />
   <input type="hidden" id="s_tabPShiddenDataPlayerSelectorId"
    name="s_tabPShiddenDataPlayerSelectorId" value="All Players" />

   <!-- s_tabPR hidden data -->
   <input type="hidden" id="s_tabPRhiddenDataRadioButtonId"
    name="s_tabPRhiddenDataRadioButtonId" value="bestRunsBatt" />
   <input type="hidden" id="s_tabPRhiddenDataBowlingToggleId"
    name="s_tabPRhiddenDataBowlingToggleId" value="perOver" />

   <!-- s_tabTS hidden data -->
   <input type="hidden" id="s_tabTShiddenDataRadioButtonId"
    name="s_tabTShiddenDataRadioButtonId" value="summary" />
   <input type="hidden" id="s_tabTShiddenDataMatchSelectorId"
    name="s_tabTShiddenDataMatchSelectorId" value="Summary" />

   <!-- s_tabPR hidden data -->
   <input type="hidden" id="s_tabTRhiddenDataRadioButtonId"
    name="s_tabTRhiddenDataRadioButtonId" value="bestStreak" />

   <!-- s_tabBP hidden data -->
   <input type="hidden" id="s_tabBPhiddenDataRadioButtonId"
    name="s_tabBPhiddenDataRadioButtonId" value="summary" />
   <input type="hidden" id="s_tabBPhiddenDataBatsmanOneNameSelectorId"
    name="s_tabBPhiddenDataBatsmanOneNameSelectorId" value="All Partners" />
   <input type="hidden" id="s_tabBPhiddenDataBatsmanTwoNameSelectorId"
    name="s_tabBPhiddenDataBatsmanTwoNameSelectorId" value="All Partners" />

   <!-- r_tabPeriod hidden data -->
   <input type="hidden" id="r_tabPeriodHiddenDataSeasonId"
    name="r_tabPeriodHiddenDataSeasonId" value="Select Season" />
   <input type="hidden" id="r_tabPeriodHiddenDataStartDayId"
    name="r_tabPeriodHiddenDataStartDayId" value="" />
   <input type="hidden" id="r_tabPeriodHiddenDataStartMonthId"
    name="r_tabPeriodHiddenDataStartMonthId" value="" />
   <input type="hidden" id="r_tabPeriodHiddenDataStartYearId"
    name="r_tabPeriodHiddenDataStartYearId" value="" />
   <input type="hidden" id="r_tabPeriodHiddenDataFinishDayId"
    name="r_tabPeriodHiddenDataFinishDayId" value="" />
   <input type="hidden" id="r_tabPeriodHiddenDataFinishMonthId"
    name="r_tabPeriodHiddenDataFinishMonthId" value="" />
   <input type="hidden" id="r_tabPeriodHiddenDataFinishYearId"
    name="r_tabPeriodHiddenDataFinishYearId" value="" />
   <input type="hidden" id="r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id"
    name="r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id" value="0" />
   <input type="hidden" id="r_tabPeriodHiddenDataStartHourId"
    name="r_tabPeriodHiddenDataStartHourId" value="12" />
   <input type="hidden" id="r_tabPeriodHiddenDataStartMinuteId"
    name="r_tabPeriodHiddenDataStartMinuteId" value="0" />
   <input type="hidden" id="r_tabPeriodHiddenDataStartAMorPMId"
    name="r_tabPeriodHiddenDataStartAMorPMId" value="AM" />
   <input type="hidden" id="r_tabPeriodHiddenDataFinishHourId"
    name="r_tabPeriodHiddenDataFinishHourId" value="11" />
   <input type="hidden" id="r_tabPeriodHiddenDataFinishMinuteId"
    name="r_tabPeriodHiddenDataFinishMinuteId" value="59" />
   <input type="hidden" id="r_tabPeriodHiddenDataFinishAMorPMId"
    name="r_tabPeriodHiddenDataFinishAMorPMId" value="PM" />

   <!-- r_tabOpposition hidden data -->
   <input type="hidden" id="r_tabOppositionHiddenDataOppTeamNameSelectorId"
    name="r_tabOppositionHiddenDataOppTeamNameSelectorId" value="All Opposition Teams" />

   <!-- r_tabPlayers hidden data -->
   <input type="hidden" id="r_tabPlayersHiddenDataMinMatchesId"
    name="r_tabPlayersHiddenDataMinMatchesId" value="1" />
   <input type="hidden" id="r_tabPlayersHiddenDataRegular0or1Id"
    name="r_tabPlayersHiddenDataRegular0or1Id" value="1" />
   <input type="hidden" id="r_tabPlayersHiddenDataFillin0or1Id"
    name="r_tabPlayersHiddenDataFillin0or1Id" value="1" />
   <input type="hidden" id="r_tabPlayersHiddenDataCurrent0or1Id"
    name="r_tabPlayersHiddenDataCurrent0or1Id" value="1" />
   <input type="hidden" id="r_tabPlayersHiddenDataRetired0or1Id"
    name="r_tabPlayersHiddenDataRetired0or1Id" value="1" />

   <!-- r_tabMatches hidden data -->
   <input type="hidden" id="r_tabMatchesHiddenDataWin0or1Id"
    name="r_tabMatchesHiddenDataWin0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataDraw0or1Id"
    name="r_tabMatchesHiddenDataDraw0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataLoss0or1Id"
    name="r_tabMatchesHiddenDataLoss0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataBatted1st0or1Id"
    name="r_tabMatchesHiddenDataBatted1st0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataBatted2nd0or1Id"
    name="r_tabMatchesHiddenDataBatted2nd0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataFullTeam0or1Id"
    name="r_tabMatchesHiddenDataFullTeam0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataShortOne0or1Id"
    name="r_tabMatchesHiddenDataShortOne0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataShortTwo0or1Id"
    name="r_tabMatchesHiddenDataShortTwo0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataRegular0or1Id"
    name="r_tabMatchesHiddenDataRegular0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataIrregular0or1Id"
    name="r_tabMatchesHiddenDataIrregular0or1Id" value="1" />
   <input type="hidden" id="r_tabMatchesHiddenDataFinals0or1Id"
    name="r_tabMatchesHiddenDataFinals0or1Id" value="1" />

   <!-- r_tabOvers hidden data -->
   <input type="hidden" id="r_tabOversHiddenDataOver1_0or1Id"
    name="r_tabOversHiddenDataOver1_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver2_0or1Id"
    name="r_tabOversHiddenDataOver2_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver3_0or1Id"
    name="r_tabOversHiddenDataOver3_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver4_0or1Id"
    name="r_tabOversHiddenDataOver4_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver5_0or1Id"
    name="r_tabOversHiddenDataOver5_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver6_0or1Id"
    name="r_tabOversHiddenDataOver6_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver7_0or1Id"
    name="r_tabOversHiddenDataOver7_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver8_0or1Id"
    name="r_tabOversHiddenDataOver8_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver9_0or1Id"
    name="r_tabOversHiddenDataOver9_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver10_0or1Id"
    name="r_tabOversHiddenDataOver10_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver11_0or1Id"
    name="r_tabOversHiddenDataOver11_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver12_0or1Id"
    name="r_tabOversHiddenDataOver12_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver13_0or1Id"
    name="r_tabOversHiddenDataOver13_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver14_0or1Id"
    name="r_tabOversHiddenDataOver14_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver15_0or1Id"
    name="r_tabOversHiddenDataOver15_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataOver16_0or1Id"
    name="r_tabOversHiddenDataOver16_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings1_0or1Id"
    name="r_tabOversHiddenDataInnings1_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings2_0or1Id"
    name="r_tabOversHiddenDataInnings2_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings3_0or1Id"
    name="r_tabOversHiddenDataInnings3_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings4_0or1Id"
    name="r_tabOversHiddenDataInnings4_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings5_0or1Id"
    name="r_tabOversHiddenDataInnings5_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings6_0or1Id"
    name="r_tabOversHiddenDataInnings6_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings7_0or1Id"
    name="r_tabOversHiddenDataInnings7_0or1Id" value="1" />
   <input type="hidden" id="r_tabOversHiddenDataInnings8_0or1Id"
    name="r_tabOversHiddenDataInnings8_0or1Id" value="1" />

   <table class="backh2" width="100%">
<?php
 /*
  * NOTE: cellspacing="0" NOT required in table declaration above (for outermost table).
  *       All other tables in this file require cellspacing="0" for correct display in IE.
  */
?>
    <tr>
     <th class="h1 bordersTLRB" colspan="2">
      Team '<?php echo $_SESSION['teamName']; ?>' Data Retrieval Form
     </th>
    </tr>
    <tr>
     <td class="nopadding" width="50%">
      <input type="hidden" value="none" id="s_tabSelectedTabId" name="s_tabSelectedTabId" />
      <table class="bordersTLRB" width="100%" cellspacing="0">
       <tr><th class="h2 borders___B" width="50%" colspan="5">Selections</th></tr>
       <tr>
        <td class="nopadding" width="20%"
         id="s_tabPlayerStatsId" onclick="selectS_tab('playerStats')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody><tr><th class="h3l">Player<br />Statistics</th></tr></tbody>
         </table>
        </td>
        <td class="nopadding" width="20%"
         id="s_tabPlayerRankingsId" onclick="selectS_tab('playerRankings')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody><tr><th class="h3d">Player<br />Rankings</th></tr></tbody>
         </table>
        </td>
        <td class="nopadding" width="20%"
         id="s_tabTeamStatsId" onclick="selectS_tab('teamStats')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody><tr><th class="h3l">Team<br />Statistics</th></tr></tbody>
         </table>
        </td>
        <td class="nopadding" width="20%"
         id="s_tabTeamRecordsId" onclick="selectS_tab('teamRecords')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody><tr><th class="h3d">Team<br />Records</th></tr></tbody>
         </table>
        </td>
        <td class="nopadding" width="20%"
         id="s_tabBattPshipsId" onclick="selectS_tab('battPships')">
         <table class="borders___B" width="100%" cellspacing="0">
          <tbody><tr><th class="h3l">Batting<br />Partnerships</th></tr></tbody>
         </table>
        </td>
       </tr>
       <tr>
        <td colspan="5" height="160" id="s_tabBodyTd">
         &nbsp;
         <!-- contents to be filled by scripts -->
        </td>
       </tr>
      </table>
     </td>
     <td class="nopadding" width="50%">
      <input type="hidden" value="none" id="r_tabSelectedTabId" name="r_tabSelectedTabId" />
      <table class="bordersTLRB" width="100%" cellspacing="0">
       <tr><th class="h2 borders___B" width="50%" colspan="5">Restrictions</th></tr>
       <tr>
        <td class="nopadding" width="20%" id="r_tabPeriodId" onclick="selectR_tab('Period')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody>
           <tr>
            <th class="h3l">
             &nbsp;
             <input type="checkbox" id="r_tabPeriodCheckboxId"
              name="r_tabPeriodCheckboxId" onclick="onClickR_tabCheckBox('Period')" />
             &nbsp;
             <br />
             Period
            </th>
           </tr>
          </tbody>
         </table>
        </td>
        <td class="nopadding" width="20%"
         id="r_tabOppositionId" onclick="selectR_tab('Opposition')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody>
           <tr>
            <th class="h3d">
             &nbsp;
             <input type="checkbox" id="r_tabOppositionCheckboxId"
              name="r_tabOppositionCheckboxId" onclick="onClickR_tabCheckBox('Opposition')" />
             &nbsp;
             <br />
             Opposition
            </th>
           </tr>
          </tbody>
         </table>
        </td>
        <td class="nopadding" width="20%" id="r_tabPlayersId" onclick="selectR_tab('Players')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody>
           <tr>
            <th class="h3l">
             &nbsp;
             <input type="checkbox" id="r_tabPlayersCheckboxId"
              name="r_tabPlayersCheckboxId" onclick="onClickR_tabCheckBox('Players')" />
             &nbsp;
             <br />
             Players
            </th>
           </tr>
          </tbody>
         </table>
        </td>
        <td class="nopadding" width="20%" id="r_tabMatchesId" onclick="selectR_tab('Matches')">
         <table class="borders__RB" width="100%" cellspacing="0">
          <tbody>
           <tr>
            <th class="h3d">
             &nbsp;
             <input type="checkbox" id="r_tabMatchesCheckboxId"
              name="r_tabMatchesCheckboxId" onclick="onClickR_tabCheckBox('Matches')" />
             &nbsp;
             <br />
             Matches
            </th>
           </tr>
          </tbody>
         </table>
        </td>
        <td class="nopadding" width="20%" id="r_tabOversId" onclick="selectR_tab('Overs')">
         <table class="borders___B" width="100%" cellspacing="0">
          <tbody>
           <tr>
            <th class="h3l">
             &nbsp;
             <input type="checkbox" id="r_tabOversCheckboxId"
              name="r_tabOversCheckboxId" onclick="onClickR_tabCheckBox('Overs')" />
             &nbsp;
             <br />
             Overs
            </th>
           </tr>
          </tbody>
         </table>
        </td>
       </tr>
       <tr>
        <td colspan="5" height="160" id="r_tabBodyTd">
         &nbsp;
         <!-- contents to be filled by scripts -->
        </td>
       </tr>
      </table>
     </td>
    </tr>
    <tr>
     <th class="h1 bordersTLRB" colspan="2"><input type="submit" value="Retrieve Data"/></th>
    </tr>
   </table>
  </form>
  <table cellspacing="0">
   <tbody>
    <tr>
     <td id="codedData">
      <!--
<?php
 // START coded data for client side scripting //////////////////////////////////////////////////

 readPlayerDetails($_SESSION['teamID']);
 readOppTeamIdsAndNames($_SESSION['teamID']);
 readMatchDetails($_SESSION['teamID']);
 readSeasons($_SESSION['teamID']);

 // Totals.
 echo  count($_SESSION['playerDetailsArray'     ]) , ' '   // n_players
     , count($_SESSION['oppTeamIDsAndNamesArray']) , ' '   // n_oppTeams
     , count($_SESSION['seasonNamesArray'       ]) , ' '   // n_seasons
     , count($_SESSION['matchDetailsArray'      ]) , "\n"; // n_matches

 // playerId, playerName, fillIn, retired.
 foreach ($_SESSION['playerDetailsArray'] as $player)
 {
    echo $player['playerID'], ' ', $player['fillIn'], ' ', $player['retired'], ' ';

    echo $player['firstName'];
    if ($player['lastName'] != NULL)
      echo ' ' , $player['lastName'];

    echo "\n";
 }

 // oppTeamId, oppTeamName.
 foreach ($_SESSION['oppTeamIDsAndNamesArray'] as $oppTeam)
   echo $oppTeam['oppTeamID'] , ' ' , $oppTeam['oppTeamName'] , "\n";

 // startDate, finishDate, seasonName.
 for ($i = 0; $i < count($_SESSION['seasonNamesArray']); $i++)
 {
    echo $_SESSION['seasonStartDatesArray'][$i] , ' ';

    if ($_SESSION['seasonFinishDatesArray'][$i] != null)  // SeasonFinishDate may be null,
      echo $_SESSION['seasonFinishDatesArray'][$i] , ' '; // eg. for current season.
    else
    {
       // Find date of latest match.
       $n = count($_SESSION['matchDetailsArray']) - 1;
       $latestMatchDate = $_SESSION['matchDetailsArray'][$n]['matchDate'];

       // Set season end to date of latest match.
       echo $latestMatchDate , ' ';
    }

    echo $_SESSION['seasonNamesArray'][$i] , "\n";
 }

 // matchDate, matchTime, matchType,
 // teamBatted1st, n_players, result, oppTeamId,
 // batsman1Id, batsman2Id, batsman3Id, ..., batsman8Id.
 foreach ($_SESSION['matchDetailsArray'] as $key => $match)
 {
$_SESSION['match_detailsArray'][$key]['n_players'] = 8;
$_SESSION['match_detailsArray'][$key]['result'   ] = 'W';
$match['n_players'] = 8;
$match['result'   ] = 'W';

    echo $match['matchDate'], ' ', $match['matchTime'    ], ' ',
         $match['matchType'], ' ', $match['teamBatted1st'], ' ',
         $match['n_players'], ' ', $match['result'       ], ' ', $match['oppTeamID'], ' ';

    for ($i = 1; $i <= 8; $i++)
      echo $match['batsmanIDsArray'][$i] , ' ';

    echo "\n";
 }

 // FINISH coded data for client-side scripting. ////////////////////////////////////////////////
?>
      -->
     </td>
    </tr>
   </tbody>
  </table>
 </body>
</html>
