/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_batt_pships.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_data_retrieval_s_tab_form.php".
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

// GLOBAL VARIABLES ////////////////////////////////////////////////////////////////////////////////

// The global variables defined in "icdb_data_retrieval_form.js" are used in this file.
// They are capitalised eg. MATCHES_ARRAY[] for easy identification and should be treated as
// read-only in this file.

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function initS_tabBattPships()
{
   // get value of currentRadioButtonValue from main page hidden data and prepare 'checked' string
   var currentRadioButtonValue = document.getElementById('s_tabBPhiddenDataRadioButtonId').value;
   var checked = 'checked="checked" '; // string used to set checked radioButton

   var i = '      ';

   document.getElementById("s_tabBodyTd").innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tr>\n'
     + i + '  <td colspan="5" id="s_tabBPbatsmanOneSelectorTd" '
         +    'name="s_tabBPbatsmanOneSelectorTd">\n'
     + i + '   <select id="s_tabBPbatsmanOneSelectorId" name="s_tabBPbatsmanOneSelectorId">\n'
     + i + '    <option>Loading...</option>\n'
     + i + '   </select>\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td colspan="5" id="s_tabBPbatsmanTwoSelectorTd" '
         +    'name="s_tabBPbatsmanTwoSelectorTd">\n'
     + i + '   <select id="s_tabBPbatsmanTwoSelectorId" name="s_tabBPbatsmanTwoSelectorId">\n'
     + i + '    <option>Loading...</option>\n'
     + i + '   </select>\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td rowspan="4">\n'
     + i + '   <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'summary')? checked: '')
         +     'onclick="onClickS_tabBPradioButton(\'summary\')" value="summary" />\n'
     + i + '   <br />Summary<br />\n'
     + i + '   <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'history')? checked: '')
         +     'onclick="onClickS_tabBPradioButton(\'history\')" value="history" />\n'
     + i + '   <br />History\n'
     + i + '  </td>\n'
     + i + '  <td rowspan="4">\n'
     + i + '   <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'chart')? checked: '')
         +     'onclick="onClickS_tabBPradioButton(\'chart\')" value="chart" />\n'
     + i + '   <br />Chart<br />\n'
     + i + '   <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'histogram')? checked: '')
         +     'onclick="onClickS_tabBPradioButton(\'histogram\')" value="histogram" />\n'
     + i + '   <br />Histogram\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <table width="100%">\n'
     + i + '    <tr>\n'
     + i + '     <td width="34%">&nbsp;</td>\n'
     + i + '     <td width="33%">Runs</td>\n'
     + i + '     <td width="33%">Wickets</td>\n'
     + i + '    </tr>\n'
     + i + '    <tr>\n'
     + i + '     <td class="alignR">Best</td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'bestRuns')? checked: '')
         +        'onclick="onClickS_tabBPradioButton(\'bestRuns\')" value="bestRuns" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'bestWickets')? checked: '')
         +        'onclick="onClickS_tabBPradioButton(\'bestWickets\')" value="bestWickets" />\n'
     + i + '     </td>\n'
     + i + '    </tr>\n'
     + i + '    <tr>\n'
     + i + '     <td class="alignR">Average</td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'avgRuns')? checked: '')
         +        'onclick="onClickS_tabBPradioButton(\'avgRuns\')" value="avgRuns" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'avgWickets')? checked: '')
         +        'onclick="onClickS_tabBPradioButton(\'avgWickets\')" value="avgWickets" />\n'
     + i + '     </td>\n'
     + i + '    </tr>\n'
     + i + '    <tr>\n'
     + i + '     <td class="alignR">Total</td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'totalRuns')? checked: '')
         +        'onclick="onClickS_tabBPradioButton(\'totalRuns\')" value="totalRuns" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabBPradioButtonId" name="s_tabBPradioButtonId" '
         + ((currentRadioButtonValue == 'totalWickets')? checked: '')
         +        'onclick="onClickS_tabBPradioButton(\'totalWickets\')" value="totalWickets" />\n'
     + i + '     </td>\n'
     + i + '    </tr>\n'
     + i + '   </table>\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + '</table>\n';

   // Use setTimeout function to flush output before batsmanSelectors are prepared.
   setTimeout('initS_tabBPbatsmanSelectors()', 0);
}

/*
 *
 */
function initS_tabBPbatsmanSelectors()
{
   // Get selected batsmanOne and batsmanTwo names from main page hidden data.
   var batsmanOneName = document.getElementById('s_tabBPhiddenDataBatsmanOneNameSelectorId').value;
   var batsmanTwoName = document.getElementById('s_tabBPhiddenDataBatsmanTwoNameSelectorId').value;

   // Get playerIDs of batsmanOne and batsmanTwo.
   var batsmanOneID = ((batsmanOneName == 'All Partners')? -1: getPlayerIDfromName(batsmanOneName));
   var batsmanTwoID = ((batsmanTwoName == 'All Partners')? -1: getPlayerIDfromName(batsmanTwoName));

   // Initialise batsman name selectors 
   initS_tabBPbatsmanSelector('         ', 'One', batsmanOneID, -1);
   initS_tabBPbatsmanSelector('         ', 'Two', batsmanTwoID, -1);

   // Set value of main page hidden data for batsmanOne and batsmanTwo.
   setMainPageHiddenDataBatsmanNameSelector('One');
   setMainPageHiddenDataBatsmanNameSelector('Two');

   initS_tabBPbatsmanSelector('         ', 'One', -1);
   initS_tabBPbatsmanSelector('         ', 'Two', -1);
}

/*
 * Set the main page hidden data for the batsman name <One or Two> selector
 * to match the selected option of the local page HTML element.
 */
function setMainPageHiddenDataBatsmanNameSelector(OneOrTwo)
{
   // set value of main page hidden data for batsman<One or Two>
   var elem = document.getElementById('s_tabBPbatsman' + OneOrTwo + 'SelectorId');
   document.getElementById('s_tabBPhiddenDataBatsman' + OneOrTwo + 'NameSelectorId').value
     = removeBracketedExpFromString(elem.options[elem.selectedIndex].text);
}

/*
 * PRIMARY
 */
function onChangeS_tabBPbatsmanSelectors()
{
   // Enable/disable options depending on most recent selection.
   // NOTE: This must be done before setting values of main page hidden data variables.
   //       If not, problems will occur on 1st load.
   initS_tabBPbatsmanSelector('         ', 'One', -1);
   initS_tabBPbatsmanSelector('         ', 'Two', -1);

   // Set value of main page hidden data for batsmanOne and batsmanTwo.
   setMainPageHiddenDataBatsmanNameSelector('One');
   setMainPageHiddenDataBatsmanNameSelector('Two');
}

/*
 * The player ID of the currently selected batsmen (from main page hidden data))
 * should be supplied when this function is first called (when the page is initially loaded).
 * For subsequent calls (from function 'onChangeS_tabBPbatsmanSelectors()'), -1 should be supplied.
 */
function initS_tabBPbatsmanSelector(indent, OneOrTwo, selectedBatsmanId)
{
   if (!(OneOrTwo == 'One' || OneOrTwo == 'Two'))
     error(  'Function initS_tabBPbatsmanSelector(indent, '
           + "OneOrTwo) called with neither 'One' nor 'Two'");

   if (selectedBatsmanId == -1)
     selectedBatsmanId = getIdOfSelectedBatsman(OneOrTwo);

   var otherOneOrTwo = (OneOrTwo == 'One')? 'Two': 'One';
   otherSelectedBatsmanId = getIdOfSelectedBatsman(otherOneOrTwo);

   var HTMLtext
     = indent + '<select id="s_tabBPbatsman' + OneOrTwo + 'SelectorId" '
              +       'name="s_tabBPbatsman' + OneOrTwo + 'SelectorId" '
              +  'onChange=\"onChangeS_tabBPbatsmanSelectors()">\n';

   // get first match in period, last match in period
   var firstAndLastMatchNosArray = getNosOfFirstAndLastMatchesInPeriod();


   if (firstAndLastMatchNosArray != -1)
   {
      // build n_inningsInPshipPerPlayer array
      // (no. of innings option player has batted in partnership with otherSelectedBatsman)
      var n_inningsInPshipPerPlayerArray = new Array(N_PLAYERS);
      var selectedOppTeamID = selectedOppTeamID = getSelectedOppTeamID();
      var playerNo;
      var matchNo;
      var pShipNo;
      var playerID;
      for (playerNo = 0; playerNo < Number(N_PLAYERS) + 1; ++playerNo) // + 1 for all pships option.
      {
         n_inningsInPshipPerPlayerArray[playerNo] = 0;

         if (   (playerNo < N_PLAYERS && !playerExcludedByRestrictions(playerNo))
             || playerNo == N_PLAYERS                                            )
         {
            // get playerID (-1 is wildcard (any player) for function playersBattedInPship(...))
            if (playerNo < N_PLAYERS) playerID = PLAYERS_ARRAY[playerNo]['playerID'];
            else                      playerID = -1;

            for (matchNo  = firstAndLastMatchNosArray[0];
                 matchNo <= firstAndLastMatchNosArray[1]; ++matchNo)
            {
               if (!matchExcludedByRestrictions(matchNo))
               {
                  for (pShipNo = 1; pShipNo <= 4; ++pShipNo)
                    if (playersBattedInPship(playerID, otherSelectedBatsmanId, matchNo, pShipNo))
                      ++n_inningsInPshipPerPlayerArray[playerNo];
               }
            }
         }
      }

      // get minimum number of matches per player from main page hidden data
      var minMatchesPerPlayer = document.getElementById('r_tabPlayersHiddenDataMinMatchesId').value;

      // create 'All Partners' HTML option for new select element
      var selected = '';
      if (selectedBatsmanId == -1)
        selected = ' selected="selected"';
      var n = n_inningsInPshipPerPlayerArray[N_PLAYERS]; // 'All Partners' option
      HTMLtext += indent + ' <option' + selected
                         + '>All Partners (' + n + " p'ships" + ')</option>\n';

      // create HTML options for new select element
      // (one option for each player with 1 or more inningsInPship)
      for (playerNo = 0; playerNo < N_PLAYERS; ++playerNo)
      {
         n = n_inningsInPshipPerPlayerArray[playerNo];

         if (n >= minMatchesPerPlayer)
         {
            selected = ''
            if (selectedBatsmanId == PLAYERS_ARRAY[playerNo]['playerID'])
              selected = ' selected="selected"';

            HTMLtext += indent + ' <option' + selected + '>'
                               + PLAYERS_ARRAY[playerNo]['playerName']
                               + ' (' + n + ((n > 1)? " p'ships": " p'ship") + ')</option>\n';
         }
      }
   }
   else
     // no matches in period
     HTMLtext += indent + ' <option selected>All Partners (0 innings)</option>\n';

   HTMLtext += indent + '</select>\n';

   // update playerSelectorTd
   document.getElementById('s_tabBPbatsman' + OneOrTwo + 'SelectorTd').innerHTML = HTMLtext;
}

/*
 *
 */
function getIdOfSelectedBatsman(OneOrTwo)
{

   var elem = document.getElementById('s_tabBPbatsman' + OneOrTwo + 'SelectorId');

   var optionText = elem.options[elem.selectedIndex].text;

   // Deal with case of initial loading.
   if (optionText == 'Loading...')
     return -1;

   // Remove no. of innings from end of optionText string
   // (eg. "Tom McDonnell (100 innings)" => "Tom McDonnell").
   var i = optionText.length - 10;
   while (optionText[i] != '(')
     i--;
   optionText = optionText.substring(0, i - 1);

   // Deal with case of initial loading.
   if (optionText == 'All Partners')
     return -1;

   for (i = 0; i < N_PLAYERS; ++i)
     if (PLAYERS_ARRAY[i]['playerName'] == optionText)
       return PLAYERS_ARRAY[i]['playerID'];

   error('Player Id not found in s_tab_batt_pships::getIdOfSelectedBatsman().');
}

/*
 * PRIMARY
 */
function onClickS_tabBPradioButton(newValue)
{
   // Set value of radio button.
   document.getElementById('s_tabBPradioButtonId').value = newValue;

   // Set value of main page hidden data.
   document.getElementById('s_tabBPhiddenDataRadioButtonId').value = newValue;
}

/*******************************************END*OF*FILE********************************************/
