/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_s_tab_team_records.js"
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
function initS_tabTeamRecords()
{
   // Get value of currentRadioButtonValue from main page hidden data and prepare 'checked' string.
   var currentRadioButtonValue = document.getElementById('s_tabTRhiddenDataRadioButtonId').value;
   var checked = 'checked="checked" '; // String used to set checked radioButton.

   var i = '      ';

   document.getElementById("s_tabBodyTd").innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tr>\n'
     + i + '  <td>\n'
     + i + '   <table width="100%">\n'
     + i + '    <tr>\n'
     + i + '     <td>&nbsp;</td>\n'
     + i + '     <td>Streaks</td>\n'
     + i + '     <td>Team<br />Scores</td>\n'
     + i + '     <td>Opposition<br />Scores</td>\n'
     + i + '     <td>Margins</td>\n'
     + i + '     <td>Wickets<br />Taken</td>\n'
     + i + '     <td>Wickets<br />Lost</td>\n'
     + i + '    </tr>\n'
     + i + '    <tr>\n'
     + i + '     <td class="alignR">Best</td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'bestStreak')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'bestStreak\')" value="bestStreak" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'bestTeamScore')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'bestTeamScore\')" '
         +        'value="bestTeamScore" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'bestOppScore')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'bestOppScore\')" value="bestOppScore" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'bestMargin')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'bestMargin\')" value="bestMargin" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'bestWicketsTaken')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'bestWicketsTaken\')" '
         +        'value="bestWicketsTaken" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'bestWicketsLost')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'bestWicketsLost\')" '
         +        'value="bestWicketsLost" />\n'
     + i + '     </td>\n'
     + i + '    </tr>\n'
     + i + '    <tr><td colspan="7">&nbsp;</td></tr>\n'
     + i + '    <tr>\n'
     + i + '     <td class="alignR">Worst</td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'worstStreak')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'worstStreak\')" value="worstStreak" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'worstTeamScore')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'worstTeamScore\')" '
         +        'value="worstTeamScore" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'worstOppScore')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'worstOppScore\')" '
         +        'value="worstOppScore" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'worstMargin')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'worstMargin\')" value="worstMargin" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'worstWicketsTaken')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'worstWicketsTaken\')" '
         +        'value="worstWicketsTaken" />\n'
     + i + '     </td>\n'
     + i + '     <td>\n'
     + i + '      <input type="radio" id="s_tabTRradioButtonId" name="s_tabTRradioButtonId" '
         + ((currentRadioButtonValue == 'worstWicketsLost')? checked: '')
         +        'onclick="onClickS_tabTRradioButton(\'worstWicketsLost\')" '
         +        'value="worstWicketsLost" />\n'
     + i + '     </td>\n'
     + i + '    </tr>\n'
     + i + '   </table>\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + '</table>\n'; 
}

/*
 *
 */
function onClickS_tabTRradioButton(newValue)
{
   // Set value of radio button.
   document.getElementById('s_tabTRradioButtonId').value = newValue;

   // Set value of main page hidden data.
   document.getElementById('s_tabTRhiddenDataRadioButtonId').value = newValue;
}

/*******************************************END*OF*FILE********************************************/
