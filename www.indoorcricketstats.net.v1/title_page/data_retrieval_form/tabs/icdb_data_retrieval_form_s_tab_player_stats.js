/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_s_tab_player_stats.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_data_retrieval_form.php".
*          This file is intended to be included after "icdb_data_retrieval_form.js".
*          The functions and variables in this file depend on those defined in above named file.
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

// GLOBAL VARIABLES ////////////////////////////////////////////////////////////////////////////////

// The global variables defined in "icdb_data_retrieval_form.js" may be used in this file.
// They are capitalised eg. MATCHES_ARRAY[] for easy identification and should be treated as
// read-only in this file.

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function initS_tabPlayerStats()
{
   // get value of currentRadioButtonValue from main page hidden data and prepare 'checked' string
   var currentRadioButtonValue = document.getElementById('s_tabPShiddenDataRadioButtonId').value;
   var checked = 'checked="checked" '; // string used to set checked radioButton

   var i = '      ';

   document.getElementById('s_tabBodyTd').innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td width="30%">\n'
     + i + '    <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'summary')? checked: '')
         +      'onclick="onClickS_tabPSradioButton(\'summary\')" value="summary" />\n'
     + i + '    Summary\n'
     + i + '   </td>\n'
     + i + '   <td width="70%" id="s_tabPSplayerSelectorTd" name="s_tabPSplayerSelectorTd">\n'
     + i + '    <select><option selected>Loading...</option></select>\n'
     + i + '   </td>\n'
     + i + '  </tr>\n'
     + i + ' </tbody>\n'
     + i + '</table>\n'
     + i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td width="55%">\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td width="25%">&nbsp;</td>\n'
     + i + '       <td width="25%">Table</td>\n'
     + i + '       <td width="25%">Chart</td>\n'
     + i + '       <td width="25%">Histogram</td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">Batting</td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'battTable')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'battTable\')" value="battTable" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'battChart')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'battChart\')" value="battChart" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'battHist')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'battHist\')" value="battHist" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">&nbsp;Bowling</td>\n' // Space to prevent 'B' touching border.
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'bowlTable')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'bowlTable\')" value="bowlTable" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'bowlChart')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'bowlChart\')" value="bowlChart" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'bowlHist')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'bowlHist\')" value="bowlHist" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">Overall</td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'overallTable')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'overallTable\')" '
         +          'value="overallTable" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'overallChart')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'overallChart\')" '
         +          'value="overallChart" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'overallHist')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'overallHist\')" value="overallHist" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td width="45%">\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td colspan="3">Statistics by<br />Over No. / Batting Pos.</td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td width="30%">&nbsp;</td>\n'
     + i + '       <td width="35%">Table</td>\n'
     + i + '       <td width="35%">Charts</td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">Innings</td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'statsByBattPosTable')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'statsByBattPosTable\')" '
         +          'value="statsByBattPosTable" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'statsByBattPosCharts')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'statsByBattPosCharts\')" '
         +          'value="statsByBattPosCharts" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">Overs</td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'statsByOverNoTable')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'statsByOverNoTable\')" '
         +          'value="statsByOverNoTable" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        <input type="radio" id="s_tabPSradioButtonId" name="s_tabPSradioButtonId" '
         + ((currentRadioButtonValue == 'statsByOverNoCharts')? checked: '')
         +          'onclick="onClickS_tabPSradioButton(\'statsByOverNoCharts\')" '
         +          'value="statsByOverNoCharts" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '  </tr>\n'
     + i + ' </tbody>\n'
     + i + '</table>\n';

   // Use setTimeout function to flush output (showing
   // "Loading..." message) before the matchSelector is prepared.
   setTimeout('initS_tabPSplayerSelector("         ")', 0);
}

/*
 * Swap inner HTML text of HTML DOM element 's_tabPSplayerSelectorTd'.
 */
function initS_tabPSplayerSelector(indent)
{
   var HTMLtext
     = indent + '<select id="s_tabPSplayerSelectorId" name="s_tabPSplayerSelectorId" '
              +  'onchange="onChangeS_tabPlayerSelector()">\n';

   // Get first match in period, last match in period.
   var firstAndLastMatchNosArray = getNosOfFirstAndLastMatchesInPeriod();

   // Get selected opposition team ID.
   var selectedOppTeamID = getSelectedOppTeamID();

   if (firstAndLastMatchNosArray != -1)
   {
      // Build n_matchesPerPlayer array.
      var n_matchesPerPlayerArray = new Array(N_PLAYERS);
      var playerNo;
      var matchNo;
      var battingPos;
      for (playerNo = 0; playerNo < N_PLAYERS; ++playerNo)
      {
         n_matchesPerPlayerArray[playerNo] = 0;

         if (!playerExcludedByRestrictions(playerNo))
         {
            playerId = PLAYERS_ARRAY[playerNo]['playerID'];

            for (matchNo  = firstAndLastMatchNosArray[0];
                 matchNo <= firstAndLastMatchNosArray[1]; ++matchNo)
            {
               if (!matchExcludedByRestrictions(matchNo))
               {
                  // If player 'playerNo' played, increment n_matchesPerPlayerArray[playerNo].
                  for (battingPos = 0; battingPos < N_PLAYERS_PER_TEAM; ++battingPos)
                    if (MATCHES_ARRAY[matchNo]['batsmanIDarray'][battingPos] == playerId)
                    {
                       ++n_matchesPerPlayerArray[playerNo];
                       break;
                    }
               }
            }
         }
      }

      // Count matches in period vs selected opposition.
      var n_matchesInPeriod = 0;
      for (matchNo  = firstAndLastMatchNosArray[0];
           matchNo <= firstAndLastMatchNosArray[1]; ++matchNo)
      {
         if (!matchExcludedByRestrictions(matchNo))
           ++n_matchesInPeriod;
      }

      // Get selected player name from main page hidden data.
      var selectedPlayerName
        = document.getElementById('s_tabPShiddenDataPlayerSelectorId').value;
      var foundSelectedPlayer = false;

      // Build 'All Players' option.
      var playerName = 'All Players';
      var selectedStr = '';
      if (playerName == selectedPlayerName)
      {
         selectedStr = ' selected';
         foundPrevSelectedPlayer = true;
      }
      HTMLtext += indent + ' <option' + selectedStr + '>' + playerName
                         + ' (' + n_matchesInPeriod + ' match'
                         + ((n_matchesInPeriod == 1)? '': 'es') + ')</option>\n';

      // Get minimum number of matches per player from main page hidden data.
      var minMatchesPerPlayer = document.getElementById('r_tabPlayersHiddenDataMinMatchesId').value;

      // Build all other options.
      selectedStr= '';
      var n;
      for (playerNo = 0; playerNo < N_PLAYERS; ++playerNo)
      {
         n = n_matchesPerPlayerArray[playerNo];

         if (n >= minMatchesPerPlayer)
         {
            playerName = PLAYERS_ARRAY[playerNo]['playerName'];

            if (!foundSelectedPlayer && playerName == selectedPlayerName)
            {
               selectedStr = ' selected';
               foundSelectedPlayer = true;
            }
            else
              selectedStr = '';

            HTMLtext += indent + ' <option' + selectedStr + '>' + playerName
                               + ' (' + n + ' match' + ((n == 1)? '': 'es') + ')</option>\n';
         }
      }
   }
   else
     // There are no matches in the selected period.
     HTMLtext += indent + ' <option selected>All Players (0 matches)</option>\n';

   HTMLtext += indent + '</select>\n';

   // Update playerSelectorTd.
   document.getElementById('s_tabPSplayerSelectorTd').innerHTML = HTMLtext;

   // Update main page hidden data if necessary.
   if (!foundSelectedPlayer)
     document.getElementById('s_tabPShiddenDataPlayerSelectorId').value = 'All Players';
}

/*
 *
 */
function onClickS_tabPSradioButton(newValue)
{
   // set value of radio button
   document.getElementById('s_tabPSradioButtonId').value = newValue;

   // set value of main page hidden data
   document.getElementById('s_tabPShiddenDataRadioButtonId').value = newValue;
}

/*
 *
 */
function onChangeS_tabPlayerSelector()
{
   // set value of main page hidden data
   var elem = document.getElementById('s_tabPSplayerSelectorId');
   document.getElementById('s_tabPShiddenDataPlayerSelectorId').value
     = removeBracketedExpFromString(elem.options[elem.selectedIndex].text);
}

/*******************************************END*OF*FILE********************************************/
