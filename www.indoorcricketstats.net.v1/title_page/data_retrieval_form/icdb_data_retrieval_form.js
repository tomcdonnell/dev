/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_data_retrieval_form.php".
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

// GLOBAL VARIABLES ////////////////////////////////////////////////////////////////////////////////

// Globals have been capitalized to reduce chance of accidental use.
// globals in this file are used also in files:
//   "icdb_data_retrieval_form_player_stats.js"
//   "icdb_data_retrieval_form_team_stats.js"
//   "icdb_data_retrieval_form_player_rankings.js"
//   "icdb_data_retrieval_form_batt_pships.js"

var N_PLAYERS_PER_TEAM = 8; // Should be const but causes error in IE.
                            // Used in buildMatchArrays() and
                            //         matchExcludedByMatchRestrictions(matchNo).

var CODED_DATA = ''; // variable for large string made global for efficiency reasons
var INDEX = 0;       // to be used in conjuction with string 'CODED_DATA' above

// totals which determine the size of global arrays below
var N_PLAYERS;
var N_OPP_TEAMS;
var N_SEASONS;
var N_MATCHES;

// global arrays to be created in extractCodedData()
var PLAYERS_ARRAY;   // contents for each player:  'playerID',   'playerName',  'fillIn', 'retired'.
var OPP_TEAMS_ARRAY; // contents for each oppTeam: 'oppTeamID',  'oppTeamName'.
var SEASONS_ARRAY;   // contents for each season:  'seasonName', 'startDate',   'finishDate'.
var MATCHES_ARRAY;   // contents for each match:   'dateArray',  'timeArray',
                     //                            'oppTeamId',  'batsmanIDarray'.

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

// Initialisation functions. ---------------------------------------------------------------------//

/*
 * PRIMARY - <body onload=init()>
 */
function init()
{
   extractCodedData();

   // Initialise main page hidden data period variables.
   var n = N_MATCHES - 1;
   var sD = MATCHES_ARRAY[0]['dateArray'][0];
   var sM = MATCHES_ARRAY[0]['dateArray'][1];
   var sY = MATCHES_ARRAY[0]['dateArray'][2];
   var fD = MATCHES_ARRAY[n]['dateArray'][0];
   var fM = MATCHES_ARRAY[n]['dateArray'][1];
   var fY = MATCHES_ARRAY[n]['dateArray'][2];
   setMainPageHiddenDataR_tabPeriodVars(sD, sM, sY, fD, fM, fY);

   // Initialise main page hidden data selected season name.
   document.getElementById('r_tabPeriodHiddenDataSeasonId').value = 'Select Season';

   selectR_tab('Period');
   selectS_tab('playerStats');
}

// Functions concerning decoding of coded data. --------------------------------------------------//

/*
 *
 */
function extractCodedData()
{
   // Get global CODED_DATA string.
   CODED_DATA = document.getElementById("codedData").innerHTML;

   // Initialise INDEX of 'CODED_DATA' string (global variable).
   INDEX = 0;

   // Skip comment start ('<!--').
   getNextWordFromCodedData();

   // Set global totals (NOTE: must be done in correct order).
   N_PLAYERS   = getNextWordFromCodedData();
   N_OPP_TEAMS = getNextWordFromCodedData();
   N_SEASONS   = getNextWordFromCodedData();
   N_MATCHES   = getNextWordFromCodedData();

   // Create global arrays (NOTE: must be done in correct order).
   buildPlayersArray();
   buildOppTeamsArray();
   buildSeasonsArray();
   buildMatchesArray();

   //testExtractCodedData(N_PLAYERS, N_OPP_TEAMS, N_SEASONS, N_MATCHES);
}

/*
 * Return the next word from the global variable string 'CODED_DATA' starting from the
 * global variable 'INDEX'.
 * Words are strings of any non-whitespace characters bounded by whitespace characters.
 */
function getNextWordFromCodedData()
{
   if (INDEX > CODED_DATA.length)
     error("ERROR: Index out of range in function 'icdb_data_retrieval_form.js'::getWord().");

   // Skip leading whitespace.
   eatWhiteSpaceFromCodedData();

   // Get word.
   var word = '';
   while (!isWhiteSpace(CODED_DATA.charAt(INDEX)))
     word += CODED_DATA.charAt(INDEX++);

   return word;
}

/*
 * Build 2D global array PLAYERS_ARRAY[N_PLAYERS][2].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_PLAYERS' has been set.
 *  * 'INDEX' is in the correct position.
 */
function buildPlayersArray()
{
   PLAYERS_ARRAY = new Array(N_PLAYERS);
   for (var i = 0; i < N_PLAYERS; ++i)
   {
      PLAYERS_ARRAY[i] = new Array(2); // Contents: 'playerID', 'playerName', 'fillIn', 'retired'.

      PLAYERS_ARRAY[i]['playerID'  ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      PLAYERS_ARRAY[i]['fillIn'    ] = (getNextWordFromCodedData() == '1'); // Boolean.
      eatWhiteSpaceFromCodedData();
      PLAYERS_ARRAY[i]['retired'   ] = (getNextWordFromCodedData() == '1'); // Boolean.
      eatWhiteSpaceFromCodedData();
      PLAYERS_ARRAY[i]['playerName'] = String(getRemainingLineFromCodedData());
   }
}

/*
 * Build 2D global array OPP_TEAMS_ARRAY[N_OPP_TEAMS][2].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_OPP_TEAMS' has been set,
 *  * 'INDEX' is in the correct position.
 */
function buildOppTeamsArray()
{
   OPP_TEAMS_ARRAY = new Array(N_OPP_TEAMS);
   for (var i = 0; i < N_OPP_TEAMS; ++i)
   {
      OPP_TEAMS_ARRAY[i] = new Array(2); // Contents: 'oppTeamID', 'oppTeamName'.

      OPP_TEAMS_ARRAY[i]['oppTeamID'  ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      OPP_TEAMS_ARRAY[i]['oppTeamName'] = String(getRemainingLineFromCodedData());
   }
}

/*
 * Build 3D global array SEASONS_ARRAY[N_SEASONS][3].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_SEASONS' has been set,
 *  * 'INDEX' is in the correct position.
 */
function buildSeasonsArray()
{
   var startDateString;
   var finishDateString;

   SEASONS_ARRAY = new Array(N_SEASONS);
   for (var i = 0; i < N_SEASONS; ++i)
   {
      SEASONS_ARRAY[i] = new Array(3); // Contents: 'seasonName',
                                       //           'startDateArray', 'finishDateArray'.

      startDateString = getNextWordFromCodedData();
      SEASONS_ARRAY[i]['startDateArray' ] = convMySQLdateStringToIntArray(startDateString);

      eatWhiteSpaceFromCodedData();

      finishDateString = getNextWordFromCodedData();
      SEASONS_ARRAY[i]['finishDateArray'] = convMySQLdateStringToIntArray(finishDateString);

      eatWhiteSpaceFromCodedData();

      SEASONS_ARRAY[i]['seasonName'     ] = String(getRemainingLineFromCodedData());
   }
}

/*
 * Build 3D global array buildMatchesArray[N_SEASONS][3].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_MATCHES' has been set,
 *  * 'INDEX' is in the correct position.
 */
function buildMatchesArray()
{
   var matchId;
   var dateString;
   var timeString;
   var intString;
   var j; // Counter.

   MATCHES_ARRAY = new Array(N_MATCHES);
   for (var i = 0; i < N_MATCHES; ++i)
   {
      MATCHES_ARRAY[i] = new Array(8); // Contents: 'dateArray', 'timeArray', 'matchType',
                                       //           'teamBatted1st', 'n_players', 'result',
                                       //           'oppTeamID', 'batsmanIDarray'.
      // Store 'dateArray'.
      dateString = getNextWordFromCodedData(); // Get date as text string (eg. '2006-06-29').
      MATCHES_ARRAY[i]['dateArray'] = convMySQLdateStringToIntArray(dateString);

      // Store 'timeArray'.
      timeString = getNextWordFromCodedData(); // Get time as text string (24hr) (eg. '19:40:00').
      MATCHES_ARRAY[i]['timeArray'] = convMySQLtimeStringToIntArray(timeString);
      if (MATCHES_ARRAY[i]['timeArray'][2] == 'am') MATCHES_ARRAY[i]['timeArray'][2] = 'AM';
      else
      {
         if (MATCHES_ARRAY[i]['timeArray'][2] == 'pm') MATCHES_ARRAY[i]['timeArray'][2] = 'PM';
         else
           error(  "Expected 'am' or 'pm', received '" + MATCHES_ARRAY[i]['timeArray'][2]
                 + "' in icdb_data_retrieval_form.js::buildMatchesArray()."              );
      }

      // Store 'matchType'.
      MATCHES_ARRAY[i]['matchType'] = getNextWordFromCodedData(); // Expecting a single character,
                                                                  // ('R' = Regular, or
                                                                  //  'I' = Irregular, or
                                                                  //  'F' = Final        ).

      // Store 'teamBatted1st'.
      MATCHES_ARRAY[i]['teamBatted1st'] = (getNextWordFromCodedData() == 1); // Expecting'0' or '1'.

      // Store 'n_players'.
      MATCHES_ARRAY[i]['n_players'] = Number(getNextWordFromCodedData()); // Expecting int [6, 8].

      // Store 'result'.
      MATCHES_ARRAY[i]['result'] = getNextWordFromCodedData(); // Expecting 'W', 'L' or 'D'.

      // Store 'oppTeamID'.
      MATCHES_ARRAY[i]['oppTeamID'] = Number(getNextWordFromCodedData()); // Expecting integer > 0.

      eatWhiteSpaceFromCodedData();

      // Store 'batsmanIDarray'.
      MATCHES_ARRAY[i]['batsmanIDarray'] = new Array(N_PLAYERS_PER_TEAM);
      for (j = 0; j < N_PLAYERS_PER_TEAM; ++j)
        MATCHES_ARRAY[i]['batsmanIDarray'][j] = Number(getNextWordFromCodedData());// Expecting int.
   }
}

/*
 * Increment global variable 'INDEX' until 'CODED_DATA.charAt(INDEX)' is not a whitespace character.
 */
function eatWhiteSpaceFromCodedData()
{
   while (isWhiteSpace(CODED_DATA.charAt(INDEX)))
     ++INDEX;
}

/*
 * Get the substring in the global string 'CODED_DATA' from
 * position global variable 'INDEX' to the next newline character.
 */
function getRemainingLineFromCodedData()
{
   var line = '';
   while (CODED_DATA.charAt(INDEX) != '\n')
     line += CODED_DATA.charAt(INDEX++);

   return line;
}

/*
 * Test function used to ensure data has been decoded successfully.
 */
function testExtractCodedData(N_PLAYERS, N_OPP_TEAMS, N_SEASONS, N_MATCHES)
{
   var txt = "Players (ID, Fill in, Retired, Name)\n";
   for (i = 0; i < N_PLAYERS; ++i)
     txt += PLAYERS_ARRAY[i]['playerID'  ] + ', '
          + PLAYERS_ARRAY[i]['fillIn'    ] + ', '
          + PLAYERS_ARRAY[i]['retired'   ] + ', '
          + PLAYERS_ARRAY[i]['playerName'] + '\n';
   alert(txt);

   var txt = "OppTeams (ID, Name)\n";
   for (i = 0; i < N_OPP_TEAMS; ++i)
     txt += OPP_TEAMS_ARRAY[i]['oppTeamID'] + ', ' + OPP_TEAMS_ARRAY[i]['oppTeamName'] + '\n';
   alert(txt);

   txt = "Seasons (startDate, finishDate)\n";
   for (i = 0; i < N_SEASONS; ++i)
   {
      txt += SEASONS_ARRAY[i]['startDateArray' ][0] + '/'
          +  SEASONS_ARRAY[i]['startDateArray' ][1] + '/'
          +  SEASONS_ARRAY[i]['startDateArray' ][2] + ', '
          +  SEASONS_ARRAY[i]['finishDateArray'][0] + '/'
          +  SEASONS_ARRAY[i]['finishDateArray'][1] + '/'
          +  SEASONS_ARRAY[i]['finishDateArray'][2] + ' '
          +  SEASONS_ARRAY[i]['seasonName'     ]    + "\n";
   }
   alert(txt);

   txt =   "Matches (date, time, matchType, teamBatted1st, n_players, result,"
         + " oppTeamID, batsmanIdArray[8].[oppTeamId], batsman1Id, ,,, , batsman8Id)\n";
   for (i = 0; i < N_MATCHES; ++i)
   {
      txt += MATCHES_ARRAY[i]['dateArray'][0] + '/'
           + MATCHES_ARRAY[i]['dateArray'][1] + '/'
           + MATCHES_ARRAY[i]['dateArray'][2]
           + ', '
           + MATCHES_ARRAY[i]['timeArray'][0] + ':'
           + MATCHES_ARRAY[i]['timeArray'][1]
           + MATCHES_ARRAY[i]['timeArray'][2]
           + ', '
           + MATCHES_ARRAY[i]['matchType'    ] + ', '
           + MATCHES_ARRAY[i]['teamBatted1st'] + ', '
           + MATCHES_ARRAY[i]['n_players'    ] + ', '
           + MATCHES_ARRAY[i]['result'       ] + ', '
           + MATCHES_ARRAY[i]['oppTeamID'    ] + ', ';

      for (j = 0; j < N_PLAYERS_PER_TEAM; ++j)
        txt +=  MATCHES_ARRAY[i]['batsmanIDarray'][j] + ' ';

      txt += '\n';
   }
   alert(txt);
}

// Event driven update functions. ----------------------------------------------------------------//

/*
 * PRIMARY
 *
 * If 'requestedTabName' is a valid name for a tab of the 'Selections' sub-table of the
 * data retrieval form, update the HTML code to indicate that 'requestedTabName' has been selected.
 *
 * Steps involved:
 *  (1) check validity of 'requestedTabName' & previously selected tab name
 *  (2) test whether requested tab is currently selected, if so return
 *  (3) unselect previously selected tab (update HTML)
 *  (4) select requested tab             (update HTML)
 *  (5) update HTML hidden data to indicate which tab is currently selected
 *  (6) pass control to init() function for newly selected tab
 */
function selectS_tab(requestedTabName)
{
   // STEP 1: Test validity of requested tab name. //////////////////////////////////////////////
   switch (requestedTabName)
   {
    case 'playerStats':    break; // OK.
    case 'playerRankings': break; // OK.
    case 'teamStats':      break; // OK.
    case 'teamRecords':    break; // OK.
    case 'battPships':     break; // OK.
    default: error(  'Unexpected tabName "' + requestedTabName
                   + '" in function \'icdb_data_retrieval_form.js\'::selectS_Tab().');
   }


   // STEP 2: Test whether requested tab is currently selected. /////////////////////////////////
   var prevSelectedTabName = document.getElementById('s_tabSelectedTabId').value;
   if (requestedTabName == prevSelectedTabName)
     return;


   // Variables needed for steps 3 & 4 (select & unselect stages) below.
   var tabLabel;      // Tab label                   (eg. 'Player<br />Statistics').
   var tabElemId;     // Tab element ID in HTML page (eg. 's_tabPlayerStatsId'    ).
   var className;     // CSS class of replacement tab (no border is required for rightmost tab).
   var i = '       '; // Indent.


   // STEP 3: Unselect previously selected tab. /////////////////////////////////////////////////

   // Only unselect if a tab was previously selected
   // (on first load, prevSelectedTabName will be 'none').
   if (prevSelectedTabName != 'none')
   {
      tabLabel  = getS_tabLabel(prevSelectedTabName);
      tabElemId = getS_tabElemId(prevSelectedTabName);
      className = (tabElemId == 's_tabBattPshipsId')? 'borders___B': 'borders__RB';

      // Find whether previously selected tab is light or dark coloured.
      var l_OR_d = (   prevSelectedTabName == 'playerRankings'
                    || prevSelectedTabName == 'teamRecords'   )? 'd': 'l';

      // Replace selected (highlighted) tab with unselected (unhighlighted) tab.
      document.getElementById(tabElemId).innerHTML
        = i + '<table class="' + className + '" width="100%" cellspacing="0">\n'
                                                           // NOTE: cellspacing="0" required by IE.
        + i + ' <tr><th class="h3' + l_OR_d + '">' + tabLabel + '</th></tr>\n'
        + i + '</table>\n';
   }


   // STEP 4: Select new tab. ///////////////////////////////////////////////////////////////////

   tabLabel  = getS_tabLabel(requestedTabName);
   tabElemId = getS_tabElemId(requestedTabName);
   className = (tabElemId == 's_tabBattPshipsId')? 'borders____': 'borders__R_';

   // Replace unselected (unhighlighted) tab with selected (highlighted) tab.
   document.getElementById(tabElemId).innerHTML
     = i + '<table class="' + className + '" width="100%" cellspacing="0">\n'
                                                           // NOTE: cellspacing="0" required by IE.
     + i + ' <tr><th class="colorTd">' + tabLabel + '</th></tr>\n'
     + i + '</table>\n';


   // STEP 5: Update selected tab in HTML form hidden input. ////////////////////////////////////
   document.getElementById("s_tabSelectedTabId").value = requestedTabName;

   // STEP 6: Pass control to init() function for newly selected tab. ///////////////////////////
   initSelectedS_tab();
}

/*
 * PRIMARY
 *
 * If 'requestedTabName' is a valid name for a tab of the 'Restrictions' sub-table of the
 * data retrieval form, update the HTML code to indicate that 'requestedTabName' has been selected.
 *
 * Steps involved:
 *  (1) check validity of 'requestedTabName' & previously selected tab name
 *  (2) test whether requested tab is currently selected, if so return
 *  (3) unselect previously selected tab (update HTML)
 *  (4) select requested tab             (update HTML)
 *  (5) update HTML hidden data to indicate which tab is currently selected
 *  (6) pass control to init() function for newly selected tab
 */
function selectR_tab(requestedTabName)
{
   // STEP 1: Test validity of requested tab name. //////////////////////////////////////////////
   switch (requestedTabName)
   {
    case 'Period':     break; // OK.
    case 'Opposition': break; // OK.
    case 'Players':    break; // OK.
    case 'Matches':    break; // OK.
    case 'Overs':      break; // OK.
    default: error(  'Unexpected tabName "' + requestedTabName
                   + '" function \'icdb_data_retrieval_form.js\'::in selectR_Tab().');
   }


   // STEP 2: Test whether requested tab is currently selected. /////////////////////////////////
   var prevSelectedTabName = document.getElementById('r_tabSelectedTabId').value;
   if (requestedTabName == prevSelectedTabName)
     return;


   // Variables needed for steps 3 & 4 (select & unselect stages) below.
   var checked;       // Whether checkbox in tab to be replaced was checked (boolean).
   var tabElemId;     // Tab element ID in HTML page (eg. 'r_tabPeriodId').
   var className;     // CSS class of replacement tab (no border is required for rightmost tab).
   var newInnerHTML;  // HTML text of replacement tab.
   var i = '       '; // Indent.


   // STEP 3: Unselect previously selected tab. /////////////////////////////////////////////////

   if (prevSelectedTabName != 'none')
   {
      checked   = document.getElementById('r_tab' + prevSelectedTabName + 'CheckboxId').checked;
      tabElemId = getR_tabElemId(prevSelectedTabName);
      className = (tabElemId == 'r_tabOversId')? 'borders___B': 'borders__RB';

      // Find whether previously selected tab is light or dark coloured.
      var l_OR_d
        = (prevSelectedTabName == 'Opposition' || prevSelectedTabName == 'Matches')? 'd': 'l';

      newInnerHTML
        =  i + '<table class="' + className + '" width="100%" cellspacing="0">\n'
                                                           // NOTE: cellspacing="0" required by IE.
        +  i + ' <tbody>\n'
        +  i + '  <tr>\n'
        +  i + '   <th class="h3' + l_OR_d + '">\n'
        +  i + '    &nbsp;\n'
        +  i + '    <input type="checkbox"'
      newInnerHTML += (checked)? ' checked="checked"\n': '\n';
      newInnerHTML
        += i + '     id="r_tab' + prevSelectedTabName + 'CheckboxId"\n'
        +  i + '     name="r_tab' + prevSelectedTabName + 'CheckboxId"\n'
        +  i + '     onclick="onClickR_tabCheckBox(\'' + prevSelectedTabName + '\')" />\n'
        +  i + '    &nbsp;\n'
        +  i + '    <br />\n'
        +  i + '   ' + prevSelectedTabName + '\n'
        +  i + '   </th>\n'
        +  i + '  </tr>\n'
        +  i + ' </tbody>\n';
        +  i + '</table>\n';
      document.getElementById(tabElemId).innerHTML = newInnerHTML;
   }


   // STEP 4: Select new tab. ///////////////////////////////////////////////////////////////////

   // ('var className' is needed because no border is required for rightmost tab.)
   checked = document.getElementById('r_tab' + requestedTabName + 'CheckboxId').checked;
   tabElemId = getR_tabElemId(requestedTabName);
   className = (tabElemId == 'r_tabOversId')? 'borders____': 'borders__R_';
   newInnerHTML
     =  i + '<table class="' + className + '" width="100%" cellspacing="0">\n'
                                                           // NOTE: cellspacing="0" required by IE.
     +  i + ' <tbody>\n'
     +  i + '  <tr>\n'
     +  i + '   <th class="colorTd">\n'
     +  i + '    &nbsp;\n'
     +  i + '    <input type="checkbox"';
   newInnerHTML += (checked)? ' checked="checked"\n': '\n';
   newInnerHTML
     += i + '     id="r_tab' + requestedTabName + 'CheckboxId"\n'
     +  i + '     name="r_tab' + requestedTabName + 'CheckboxId"\n'
     +  i + '     onclick="onClickR_tabCheckBox(\'' + requestedTabName + '\')" />\n'
     +  i + '    &nbsp;\n'
     +  i + '    <br />\n'
     +  i + '   ' + requestedTabName + '\n'
     +  i + '   </th>\n'
     +  i + '  </tr>\n'
     +  i + ' </tbody>\n'
     +  i + '</table>\n';
   document.getElementById(tabElemId).innerHTML = newInnerHTML;

   // STEP 5: Update selected tab in HTML form hidden input. ////////////////////////////////////
   document.getElementById("r_tabSelectedTabId").value = requestedTabName;

   // STEP 6: Pass control to init() function for newly selected tab. ///////////////////////////
   initSelectedR_tab();
}

/*
 * Return the inner HTML text of the tab corresponding to the tab name 'tabName'.
 *
 * This text is printed in the HTML code eg.
 *
 * <td width="20%"
 *  id="s_tabPlayerStatsId" name="s_tabPlayerStatsId" onclick="selectS_tab('playerStats')">
 *  <table class="borders__RB" width="100%" cellspacing="0">
 *   <tbody><tr><th class="h3l">Player<br />Statistics</th></tr></tbody>
 *  </table>
 * </td>
 *
 * In example above the tabName is 'playerStats' (abbreviation not contained in the HTML code),
 * and the s_tabLabel is 'Player<br />Statistics'.
 */
function getS_tabLabel(tabName)
{
   switch (tabName)
   {
    case 'none': // On initial load, tabName will be 'none'.  Treat same as 'playerStats'.
    case 'playerStats':    return 'Player<br />Statistics';
    case 'playerRankings': return 'Player<br />Rankings';
    case 'teamStats':      return 'Team<br />Statistics';
    case 'teamRecords':    return 'Team<br />Records';
    case 'battPships':     return 'Batting<br />Partnerships';
    default: error(  'Unexpected tabName "' + tabName
                   + '" in function \'icdb_data_retrieval_form.js\'::getS_tabLabel().');
   }
}

/*
 * Return the id of the HTML 'td' element corresponding to the tab name 'tabName'
 * of the 'Selections' sub-table of the data retrieval form.
 *
 * This text is printed in the HTML code eg:
 *
 * <td width="20%"
 *  id="s_tabPlayerStatsId" name="s_tabPlayerStatsId" onclick="selectS_tab('playerStats')">
 *  <table class="borders__RB" width="100%" cellspacing="0">
 *   <tbody><tr><th class="h3l">Player<br />Statistics</th></tr></tbody>
 *  </table>
 * </td>
 *
 * In example above the tabName is 'playerStats' (abbreviation not contained in the HTML code),
 * and the s_tabElemId is 's_tabPlayerStatsId'.
 */
function getS_tabElemId(tabName)
{
   switch (tabName)
   {
    case 'none': // On initial load, tabName will be 'none'.  Treat same as 'playerStats'.
    case 'playerStats':    return 's_tabPlayerStatsId';
    case 'playerRankings': return 's_tabPlayerRankingsId';
    case 'teamStats':      return 's_tabTeamStatsId';
    case 'teamRecords':    return 's_tabTeamRecordsId';
    case 'battPships':     return 's_tabBattPshipsId';
    default: error(  'Unexpected tabName "' + tabName
                   + '" in function \'icdb_data_retrieval_form.js\'::getS_tabElemId().');
   }
}

/*
 * Similar to above function but for 'Restrictions' sub-table.
 */
function getR_tabElemId(tabName)
{
   switch (tabName)
   {
    case 'none': // On initial load, tabName will be 'none'.  Treat same as 'Period'.
    case 'Period':     return 'r_tabPeriodId';
    case 'Opposition': return 'r_tabOppositionId';
    case 'Players':    return 'r_tabPlayersId';
    case 'Matches':    return 'r_tabMatchesId';
    case 'Overs':      return 'r_tabOversId';
    default: error(  'Unexpected tabName "' + tabName
                   + '" in filename \'icdb_data_retrieval_form.js\'::getR_tabElemId().');
   }
}

/*
 * Call the init() function for the currently selected tab
 * in the 'Selections' sub-table of the data retrieval form.
 */
function initSelectedS_tab()
{
   var selectedTabName = document.getElementById('s_tabSelectedTabId').value;

   switch (selectedTabName)
   {
    case 'playerStats':    initS_tabPlayerStats();    break;
    case 'playerRankings': initS_tabPlayerRankings(); break;
    case 'teamStats':      initS_tabTeamStats();      break;
    case 'teamRecords':    initS_tabTeamRecords();    break;
    case 'battPships':     initS_tabBattPships();     break;
    default: error(  'Unexpected selectedTabName "' + selectedTabName
                   + '" in filename \'icdb_data_retrieval_form.js\'::initSelectedS_tab().');
   }
}

/*
 * Call the init() function for the currently selected tab
 * in the 'Restrictions' sub-table of the data retrieval form.
 */
function initSelectedR_tab()
{
   var selectedTabName = document.getElementById('r_tabSelectedTabId').value;

   switch (selectedTabName)
   {
    case 'Period':     initR_tabPeriod();     break;
    case 'Opposition': initR_tabOpposition(); break;
    case 'Players':    initR_tabPlayers();    break;
    case 'Matches':    initR_tabMatches();    break;
    case 'Overs':      initR_tabOvers();      break;
    default: error(  'Unexpected selectedTabName "' + selectedTabName
                   + '" in function \'icdb_data_retrieval_form.js\'::initSelectedR_tab().');
   }
}

/*
 * PRIMARY
 */
function onClickR_tabCheckBox(tabName)
{
   // Test validity of 'tabName'.
   switch (tabName)
   {
    case 'Period': case 'Opposition': case 'Players': case 'Matches': case 'Overs': break; // OK
    default:
      error(  'Unexpected tabName "' + tabName
            + '" in function \'icdb_data_retrieval_form.js\'::onClickR_tabCheckbox(tabName).');
   }

   if (document.getElementById('r_tabSelectedTabId').value == tabName)
   {
      // The checkbox clicked is the currently selected one.
      // Steps: 1) Use the reset function for the appropriate tab.
      //           The reset function must reset the relevant main page hidden data.
      //        2) Uncheck the checkbox.
      //        3) Initialise the selected s_tab.

      // Step 1.
      switch (tabName)
      {
       case 'Period'    : resetR_tabPeriod();     break;
       case 'Opposition': resetR_tabOpposition(); break;
       case 'Players'   : resetR_tabPlayers();    break;
       case 'Matches'   : resetR_tabMatches();    break;
       case 'Overs'     : resetR_tabOvers();      break;
       // NOTE: Default case already checked above.
      }

      uncheckR_tabCheckbox(tabName); // Step 2.
      initSelectedS_tab();           // Step 3.
   }
   else
   {
      // The checkbox clicked does not belong to the currently selected 'r' tab.
      // Select the new 'r' tab, leaving its checkbox unchanged.

      // Checkbox has been clicked, and its value has already changed in response to the click.
      // Revert checkbox back to its pre-click value.
      document.getElementById('r_tab' + tabName + 'CheckboxId').checked
        = !document.getElementById('r_tab' + tabName + 'CheckboxId').checked;

      selectR_tab(tabName);
   }
}

/*
 *
 */
function checkR_tabCheckbox(tabName)
{
   switch (tabName)
   {
    case 'Period': case 'Opposition': case 'Players': case 'Matches': case 'Overs': break; // OK.
    default: error(  'Unexpected tabName "' + tabName
                   + '" in function \'icdb_data_retrieval_form.js\'::checkR_tabCheckbox(tabName).');
   }

   document.getElementById('r_tab' + tabName + 'CheckboxId').checked = true;
}

/*
 *
 */
function uncheckR_tabCheckbox(tabName)
{
   switch (tabName)
   {
    case 'Period': case 'Opposition': case 'Players': case 'Matches': case 'Overs': break; // OK.
    default:
      error(  'Unexpected tabName "' + tabName
            + '" in function \'icdb_data_retrieval_form.js\'::uncheckR_tabCheckbox(tabName).');
   }

   document.getElementById('r_tab' + tabName + 'CheckboxId').checked = false;
}

// Functions used by multiple other (icdb_data_retrieval_form...) files. ///////////////////////////

/*
 * 
 */
function getSelectedPeriodStartDateArray()
{
   dateArray = new Array(3); // Contents: startDay, startMonth, and startYear.

   dateArray[0] = document.getElementById('r_tabPeriodHiddenDataStartDayId'  ).value;
   dateArray[1] = document.getElementById('r_tabPeriodHiddenDataStartMonthId').value;
   dateArray[2] = document.getElementById('r_tabPeriodHiddenDataStartYearId' ).value;

   return dateArray;
}

/*
 * 
 */
function getSelectedPeriodFinishDateArray()
{
   dateArray = new Array(3); // Contents: finishDay, finishMonth, and finishYear.

   dateArray[0] = document.getElementById('r_tabPeriodHiddenDataFinishDayId'  ).value;
   dateArray[1] = document.getElementById('r_tabPeriodHiddenDataFinishMonthId').value;
   dateArray[2] = document.getElementById('r_tabPeriodHiddenDataFinishYearId' ).value;

   return dateArray;
}

/*
 * 
 */
function getSelectedPeriodStartTimeArray()
{
   timeArray = new Array(3); // Contents: startHour, startMinute, and startAMorPM.

   timeArray[0] = document.getElementById('r_tabPeriodHiddenDataStartHourId'  ).value;
   timeArray[1] = document.getElementById('r_tabPeriodHiddenDataStartMinuteId').value;
   timeArray[2] = document.getElementById('r_tabPeriodHiddenDataStartAMorPMId').value;

   return timeArray;
}

/*
 * 
 */
function getSelectedPeriodFinishTimeArray()
{
   timeArray = new Array(3); // Contents: finishHour, finishMinute, and finishAMorPM.

   timeArray[0] = document.getElementById('r_tabPeriodHiddenDataFinishHourId'  ).value;
   timeArray[1] = document.getElementById('r_tabPeriodHiddenDataFinishMinuteId').value;
   timeArray[2] = document.getElementById('r_tabPeriodHiddenDataFinishAMorPMId').value;

   return timeArray;
}

/*
 * Return an array containing the match numbers of
 * the first and last matches in the selected period.
 *
 * Used in files: "icdb_data_retrieval_form_r_tab_opposition.js",
 *                "icdb_data_retrieval_form_s_tab_player_stats.js".
 */
function getNosOfFirstAndLastMatchesInPeriod()
{
   var selectedPeriodStartDateArray  = getSelectedPeriodStartDateArray();
   var selectedPeriodFinishDateArray = getSelectedPeriodFinishDateArray();

   // Test that startDate is earlier than or equal to finishDate.
   if (compareDateArrays(selectedPeriodStartDateArray, selectedPeriodFinishDateArray) > 0)
     error(  'Start date (' + selectedPeriodStartDateArray
           + ') later than finish date (' + selectedPeriodFinishDateArray
           + ") in function 'icdb_data_retrieval_form'::getNosOfFirstAndLastMatchesInPeriod().");

   // NOTE: Selected dates may be outside the total database period.

   // Find no. of first match after (or on) startDate.
   if (compareDateArrays(selectedPeriodStartDateArray,
                         MATCHES_ARRAY[N_MATCHES - 1]['dateArray']) <= 0)
   {
      // StartDate is earlier than or equal to the last match date.

      var f = 0;
      while (compareDateArrays(MATCHES_ARRAY[f]['dateArray'], selectedPeriodStartDateArray) < 0)
        ++f;
   }
   else
     // StartDate is after the last match date, so no matches in period.
     return -1;

   // Find no. of last match before (or on) finishDate.
   if (compareDateArrays(selectedPeriodFinishDateArray, MATCHES_ARRAY[0]['dateArray']) >= 0)
   {
      // FinishDate is later than or equal to the first match date.

      var l = N_MATCHES - 1;
      while (compareDateArrays(MATCHES_ARRAY[l]['dateArray'], selectedPeriodFinishDateArray) > 0)
        --l;
   }
   else
     // FinishDate is before the first match date, so no matches in period.
     return -1;

   if (f <= l) return [f, l]; // (Array literal).
   else        return     -1; // No matches in period.
}

/*
 * Return the ID of the opposition team selected in the 'Opposition'
 * tab of the 'Restrictions' sub-form of the data retrieval form.
 */
function getSelectedOppTeamID()
{
   var selectedOppTeamName
     = document.getElementById('r_tabOppositionHiddenDataOppTeamNameSelectorId').value;

   if (selectedOppTeamName == 'All Opposition Teams')
     return -1;
   else
     return getOppTeamIdFromName(selectedOppTeamName);
}

/*
 *
 */
function playerExcludedByRestrictions(playerNo)
{
   var regularExcluded
     = (document.getElementById('r_tabPlayersHiddenDataRegular0or1Id').value == '0');
   if (regularExcluded && !PLAYERS_ARRAY[playerNo]['fillIn']) // Not fillIn implies regular.
     return true;

   var fillInsExcluded
     = (document.getElementById('r_tabPlayersHiddenDataFillin0or1Id' ).value == '0');
   if (fillInsExcluded && PLAYERS_ARRAY[playerNo]['fillIn'])
     return true;

   var currentExcluded
     = (document.getElementById('r_tabPlayersHiddenDataCurrent0or1Id').value == '0');
   if (currentExcluded && !PLAYERS_ARRAY[playerNo]['retired']) // Not retired implies current.
     return true;

   var retiredExcluded
     = (document.getElementById('r_tabPlayersHiddenDataRetired0or1Id').value == '0');
   if (regularExcluded && PLAYERS_ARRAY[playerNo]['retired'])
     return true;

   return false;
}

/*
 *
 */
function matchExcludedByRestrictions(matchNo)
{
   // Get selected opposition team ID.
   var selectedOppTeamID = getSelectedOppTeamID();

   // If match 'matchNo' is excluded by 'Opp. Team' restrictions...
   if ((selectedOppTeamID != -1 && MATCHES_ARRAY[matchNo]['oppTeamID'] != selectedOppTeamID)
       // OR match 'matchNo' is excluded by 'Matches' restrictions,
       || matchExcludedByMatchRestrictions(matchNo)
       // OR match 'matchNo' is excluded by 'Period' restrictions,
       || matchExcludedByPeriodRestrictions(matchNo)                                        )
     return true;
   else
     return false;
}

/*
 * Return true if match 'matchNo' is excluded by the
 * restrictions made by the user in the 'Matches' restrictions page.
 */
function matchExcludedByMatchRestrictions(matchNo)
{
   if (!document.getElementById('r_tabMatchesCheckboxId').checked)
     // 'Matches' restrictions are in the default state, so no matches are excluded.
     return false;

   // Test 'Match Result' criteria.
   var result = MATCHES_ARRAY[matchNo]['result'] // Get match result (expecting 'W', 'D', or 'L').
   switch (result)
   {
    case 'W':
      if (document.getElementById('r_tabMatchesHiddenDataWin0or1Id' ).value == '0')
        return true;
      break;
    case 'D':
      if (document.getElementById('r_tabMatchesHiddenDataDraw0or1Id').value == '0')
        return true;
      break;
    case 'L':
      if (document.getElementById('r_tabMatchesHiddenDataLoss0or1Id').value == '0')
        return true;
      break;
    default:
      error(  "Expected 'W', 'L', or 'D', recieved '" + result
            + "' in \"icdb_data_retrieval_form.php\"::matchExcludedByMatchRestrictions().");
   }

   // Test 'Innings Order' criteria.
   var teamBatted1st = MATCHES_ARRAY[matchNo]['teamBatted1st']; // Get teamBatted1st (expect bool).
   switch (teamBatted1st)
   {
    case true:
      if (document.getElementById('r_tabMatchesHiddenDataBatted1st0or1Id').value == '0')
        return true;
      break;
    case false:
      if (document.getElementById('r_tabMatchesHiddenDataBatted2nd0or1Id').value == '0')
        return true;
      break;
    default:
      error(  "Expected true or false, recieved '" + teamBatted1st
            + "' in \"icdb_data_retrieval_form.php\"::matchExcludedByMatchRestrictions().");
   }

   // Test 'Missing Players' criteria.
   var n_players = MATCHES_ARRAY[matchNo]['n_players'] // Get n_players (expecting integer [6, 8]).
   switch (n_players)
   {
    case N_PLAYERS_PER_TEAM:
      if (document.getElementById('r_tabMatchesHiddenDataFullTeam0or1Id').value == '0')
        return true;
      break;
    case N_PLAYERS_PER_TEAM - 1:
      if (document.getElementById('r_tabMatchesHiddenDataShortOne0or1Id').value == '0')
        return true;
      break;
    case N_PLAYERS_PER_TEAM - 2:
      if (document.getElementById('r_tabMatchesHiddenDataShortTwo0or1Id').value == '0')
        return true;
      break;
    default:
      error(  'Expected integer [' + N_PLAYERS_PER_TEAM - 2 + ', ' + N_PLAYERS_PER_TEAM + '], '
            + "recieved '" + n_players
            + "' in \"icdb_data_retrieval_form.php\"::matchExcludedByMatchRestrictions().");
   }

   // Test 'Match Type' criteria.
   var matchType = MATCHES_ARRAY[matchNo]['matchType'] // Get match type (expect 'R', 'I', or 'F').
   switch (matchType)
   {
    case 'R':
      if (document.getElementById('r_tabMatchesHiddenDataRegular0or1Id'  ).value == '0')
        return true;
      break;
    case 'I':
      if (document.getElementById('r_tabMatchesHiddenDataIrregular0or1Id').value == '0')
        return true;
      break;
    case 'F':
      if (document.getElementById('r_tabMatchesHiddenDataFinals0or1Id'   ).value == '0')
        return true;
      break;
    default:
      error(  "Expected 'R', 'I', or 'F', recieved '" + matchType
            + "' in \"icdb_data_retrieval_form.php\"::matchExcludedByMatchRestrictions().");
   }

   // All tests have been passed, so the match is not excluded.
   return false;
}

/*
 * Return true if match 'matchNo' is excluded by the
 * restrictions made by the user in the 'Period' restrictions page.
 */
function matchExcludedByPeriodRestrictions(matchNo)
{
   if (!document.getElementById('r_tabPeriodCheckboxId').checked)
     // 'Period' restrictions are in the default state, so no matches are excluded.
     return false;

   // Test date range restriction.
   var startDateArray  = Array(3);
   var finishDateArray = Array(3);
   startDateArray[0]  = document.getElementById('r_tabPeriodHiddenDataStartDayId'   ).value;
   startDateArray[1]  = document.getElementById('r_tabPeriodHiddenDataStartMonthId' ).value;
   startDateArray[2]  = document.getElementById('r_tabPeriodHiddenDataStartYearId'  ).value;
   finishDateArray[0] = document.getElementById('r_tabPeriodHiddenDataFinishDayId'  ).value;
   finishDateArray[1] = document.getElementById('r_tabPeriodHiddenDataFinishMonthId').value;
   finishDateArray[2] = document.getElementById('r_tabPeriodHiddenDataFinishYearId' ).value;
   // If (matchDate < startDate or finishDate < matchDate) then the match is excluded.
   if (   compareDateArrays(MATCHES_ARRAY[matchNo]['dateArray'], startDateArray ) < 0
       || compareDateArrays(finishDateArray, MATCHES_ARRAY[matchNo]['dateArray']) < 0)
     return true;

   // Test time range restriction if necessary.
   if (document.getElementById('r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id').value == 1)
   {
      var startTimeHour    = document.getElementById('r_tabPeriodHiddenDataStartHourId'   ).value;
      var startTimeMinute  = document.getElementById('r_tabPeriodHiddenDataStartMinuteId' ).value;
      var startTimeAMorPM  = document.getElementById('r_tabPeriodHiddenDataStartAMorPMId' ).value;
      var finishTimeHour   = document.getElementById('r_tabPeriodHiddenDataFinishHourId'  ).value;
      var finishTimeMinute = document.getElementById('r_tabPeriodHiddenDataFinishMinuteId').value;
      var finishTimeAMorPM = document.getElementById('r_tabPeriodHiddenDataFinishAMorPMId').value;
      var matchTimeHour    = MATCHES_ARRAY[matchNo]['timeArray'][0];
      var matchTimeMinute  = MATCHES_ARRAY[matchNo]['timeArray'][1];
      var matchTimeAMorPM  = MATCHES_ARRAY[matchNo]['timeArray'][2];

      // Convert times to 24 hour format.
      var startTimeHour24  = Number(startTimeHour ) + ((startTimeAMorPM  == 'PM')? 12: 0);
      var finishTimeHour24 = Number(finishTimeHour) + ((finishTimeAMorPM == 'PM')? 12: 0);
      var matchTimeHour24  = Number(matchTimeHour ) + ((matchTimeAMorPM  == 'PM')? 12: 0);

      // Compare match time with time range restriction.
      // If (matchTime < startTime or finishTime < matchTime) then the match is excluded.
      if (   compareTimes(matchTimeHour24 , matchTimeMinute , startTimeHour24, startTimeMinute) < 0
          || compareTimes(finishTimeHour24, finishTimeMinute, matchTimeHour24, matchTimeMinute) < 0)
        return true;
   }

   // Match is not excluded by period restrictions.
   return false;
}

/*
 * Return the opposition team ID corresponding to the opposition team name 'oppTeamName'.
 */
function getOppTeamIdFromName(oppTeamName)
{
   for (var i = 0; i < N_OPP_TEAMS; ++i)
     if (OPP_TEAMS_ARRAY[i]['oppTeamName'] == oppTeamName)
       return OPP_TEAMS_ARRAY[i]['oppTeamID'];

   error(  'Function \'icdb_data_retrieval_form.js\'::getOppTeamIdFromName()'
         + ' was used with invalid oppTeamName "' + oppTeamName +'".'        );
}

/*
 * Return whether:
 * the players referred to by 'playerOneId' and 'playerTwoId',
 * batted together in the match referred to by 'matchNo',
 * in the partnership referred to by 'pShipNo'.
 */
function playersBattedInPship(playerOneId, playerTwoId, matchNo, pShipNo)
{
   var posOne, posTwo;

   switch (pShipNo)
   {
    case 1: posOne = 1; posTwo = 2; break;
    case 2: posOne = 3; posTwo = 4; break;
    case 3: posOne = 5; posTwo = 6; break;
    case 4: posOne = 7; posTwo = 8; break;
   }

   if (   (   playerBattedInMatchAtPos(playerOneId, matchNo, posOne)
           && playerBattedInMatchAtPos(playerTwoId, matchNo, posTwo))
       || (   playerBattedInMatchAtPos(playerOneId, matchNo, posTwo)
           && playerBattedInMatchAtPos(playerTwoId, matchNo, posOne)))
     return true;
   else
     return false;
}

/*
 * Return whether:
 * the player referred to by 'playerId',
 * batted in the batting position 'battingPos',
 * in the match referred to by 'matchNo'.
 */
function playerBattedInMatchAtPos(playerId, matchNo, battingPos)
{
   if (playerId == -1)
     return true;

   if (MATCHES_ARRAY[matchNo]['batsmanIDarray'][battingPos - 1] == playerId)
     return true;
   else
     return false;
}

/*
 * Return the name of the player referred to by the player ID 'id'.
 */
function getPlayerNameFromId(id)
{
   for (var i = 0; i < N_PLAYERS; ++i)
     if (PLAYERS_ARRAY[i]['playerID'] == id)
       return PLAYERS_ARRAY[i]['playerName'];

   error(  'Function \'icdb_data_retrieval_form.js\'::getPlayerNameFromId(id)'
         + ' was used with invalid id.'                                       );
}

/*
 * Return the player ID of the player with the name 'name'.
 */
function getPlayerIDfromName(name)
{
   for (var i = 0; i < N_PLAYERS; ++i)
     if (PLAYERS_ARRAY[i]['playerName'] == name)
       return PLAYERS_ARRAY[i]['playerID'];

   error(  'Function \'icdb_data_retrieval_form.js\'::getPlayerIDfromName(name)'
         + ' was used with invalid name.'                                       );
}

/*
 * Return the name of the opposition team referred to by the ID 'id'.
 */
function getOppTeamNameFromId(id)
{
   for (var i = 0; i < N_OPP_TEAMS; ++i)
     if (OPP_TEAMS_ARRAY[i]['oppTeamID'] == id)
       return OPP_TEAMS_ARRAY[i]['oppTeamName'];

   error(  'Function \'icdb_data_retrieval_form.js\'::getOppTeamNameFromId(id)'
         + ' was used with invalid id.'                                       );
}

/*******************************************END*OF*FILE********************************************/
