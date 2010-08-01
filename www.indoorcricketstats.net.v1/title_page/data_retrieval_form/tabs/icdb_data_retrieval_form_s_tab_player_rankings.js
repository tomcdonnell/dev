/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_s_tab_player_rankings.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_data_retrieval_form.php".
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
function initS_tabPlayerRankings()
{
   // get value of perOverORperMatch from main page hidden data
   var perOverORperMatch = document.getElementById('s_tabPRhiddenDataBowlingToggleId').value;

   // check validity of perOverORperMatch and set displayString
   switch (perOverORperMatch)
   {
    case 'perOver':  displayString = '  Per  \nOver';  break;
    case 'perMatch': displayString = '  Per  \nMatch'; break;
    default: error(  'Expected "perOver" or "perMatch", received "'
                   + perOverORperMatch + '" in initS_tabPlayerRankings().');
   }

   // get value of currentRadioButtonValue from main page hidden data and prepare 'checked' string
   var currentRadioButtonValue = document.getElementById('s_tabPRhiddenDataRadioButtonId').value;
   var checked = 'checked="checked" '; // string used to set checked radioButton

   var i = '      ';

   document.getElementById("s_tabBodyTd").innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tr>\n'
     + i + '  <td rowspan="2">&nbsp;</td>\n'
     + i + '  <td colspan="2">Batting</td>\n'
     + i + '  <td colspan="2">Bowling</td>\n'
     + i + '  <td rowspan="2">Overall</td>\n'
     + i + '  <td rowspan="5">\n'
     + i + '   Toggle<br />&nbsp;Bowling&nbsp;<br />Stats<br />\n' // Spaces are to prevent resize
     + i + '   <input type="button" '                              // when button is toggled.
         +     'id="s_tabPRbowlingToggleId" name="s_tabPRbowlingToggleId" '
         +     'onclick="onClickPRbowlingToggle()" value="' + displayString + '" />\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td>Runs</td>\n'
     + i + '  <td>Wickets</td>\n'
     + i + '  <td>Runs</td>\n'
     + i + '  <td>Wickets</td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td class="alignR">Best</td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'bestRunsBatt')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'bestRunsBatt\')" value="bestRunsBatt" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'bestWicketsBatt')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'bestWicketsBatt\')" '
         +     'value="bestWicketsBatt" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'bestRunsBowl')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'bestRunsBowl\')" value="bestRunsBowl" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'bestWicketsBowl')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'bestWicketsBowl\')" '
         +     'value="bestWicketsBowl" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'bestOverall')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'bestOverall\')" value="bestOverall" />\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td class="alignR">Average</td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'avgRunsBatt')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'avgRunsBatt\')" value="avgRunsBatt" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'avgWicketsBatt')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'avgWicketsBatt\')" value="avgWicketsBatt" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'avgRunsBowl')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'avgRunsBowl\')" value="avgRunsBowl" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'avgWicketsBowl')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'avgWicketsBowl\')" value="avgWicketsBowl" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'avgOverall')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'avgOverall\')" value="avgOverall" />\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td class="alignR">Total</td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'totalRunsBatt')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'totalRunsBatt\')" value="totalRunsBatt" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'totalWicketsBatt')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'totalWicketsBatt\')" '
         +     'value="totalWicketsBatt" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'totalRunsBowl')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'totalRunsBowl\')" value="totalRunsBowl" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'totalWicketsBowl')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'totalWicketsBowl\')" '
         +     'value="totalWicketsBowl" />\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <input type="radio" id="s_tabPRradioButtonId" name="s_tabPRradioButtonId" '
         + ((currentRadioButtonValue == 'totalOverall')? checked: '')
         +     'onclick="onClickS_tabPRradioButton(\'totalOverall\')" value="totalOverall" />\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + '</table>\n';
}

/*
 *
 */
function onClickS_tabPRradioButton(newValue)
{
   // set value of radio button
   document.getElementById('s_tabPRradioButtonId').value = newValue;

   // set value of main page hidden data
   document.getElementById('s_tabPRhiddenDataRadioButtonId').value = newValue;
}

/*
 *
 */
function onClickPRbowlingToggle()
{
   // get current value from main page hidden data
   var perOverORperMatch = document.getElementById('s_tabPRhiddenDataBowlingToggleId').value;

   switch (perOverORperMatch)
   {
    // NOTE: the spaces around 'Per' are necessary to prevent the button resizing when clicked
    case 'perOver' : displayText = '  Per  \nMatch'; perOverORperMatch = 'perMatch'; break;
    case 'perMatch': displayText = '  Per  \nOver' ; perOverORperMatch = 'perOver' ; break;
    default: error(  'Expected "perOver" or "perMatch", received "'
                   + perOverORperMatch + '" in onClickPRbowlingToggle().');
   }

   // update button element
   document.getElementById('s_tabPRbowlingToggleId').value = displayText;

   // update main page hidden data
   document.getElementById('s_tabPRhiddenDataBowlingToggleId').value = perOverORperMatch;
}

/*******************************************END*OF*FILE********************************************/
