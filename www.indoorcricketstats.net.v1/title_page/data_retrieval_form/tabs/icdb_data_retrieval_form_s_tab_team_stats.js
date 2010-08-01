/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_s_tab_form_team_stats.js"
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
function initS_tabTeamStats()
{
   // Get value of currentRadioButtonValue from main page hidden data and prepare 'checked' string.
   var currentRadioButtonValue = document.getElementById('s_tabTShiddenDataRadioButtonId').value;
   var checked = 'checked="checked" '; // String used to set checked radioButton.

   var i = '      ';

   document.getElementById('s_tabBodyTd').innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tr>\n'
     + i + '  <td colspan="5">\n'
     + i + '   <input type="radio" id="s_tabTSmatchScoreSheetRadioButton" '
         +     'name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'matchScoreSheet')? checked: '')
         +     'onClick="onClickS_tabTSradioButton(\'matchScoreSheet\')" '
         +     'value="matchScoreSheet" />\n'
     + i + '   Match Score Sheet\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td colspan="5" id="s_tabTSmatchSelectorTd" name="s_tabTSmatchSelectorTd">\n'
     + i + '   <select'
         + ((currentRadioButtonValue == 'matchScoreSheet')? '>': ' disabled="disabled">')
         +     '<option>Loading...</option> '
         +    '</select>\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + ' <tr>\n'
     + i + '  <td rowspan="4">\n'
     + i + '   <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'summary')? checked: '')
         +     'onclick="onClickS_tabTSradioButton(\'summary\')" value="summary" /><br />\n'
     + i + '   Summary<br />\n'
     + i + '   <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'history')? checked: '')
         +     'onclick="onClickS_tabTSradioButton(\'history\')" value="history" /><br />\n'
     + i + '   History\n'
     + i + '  </td>\n'
     + i + '  <td>\n'
     + i + '   <table width="100%">\n'
     + i + '    <tbody>\n'
     + i + '     <tr>\n'
     + i + '      <td width="25%">&nbsp;</td>\n'
     + i + '      <td width="25%">Table</td>\n'
     + i + '      <td width="25%">Chart</td>\n'
     + i + '      <td width="25%">Histogram</td>\n'
     + i + '     </tr>\n'
     + i + '     <tr>\n'
     + i + '      <td class="alignR">Scores</td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'scoresTable')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'scoresTable\')" value="scoresTable" />\n'
     + i + '      </td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'scoresChart')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'scoresChart\')" value="scoresChart" />\n'
     + i + '      </td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'scoresHist')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'scoresHist\')" value="scoresHist" />\n'
     + i + '      </td>\n'
     + i + '     </tr>\n'
     + i + '     <tr>\n'
     + i + '      <td class="alignR">Wickets</td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'wicketsTable')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'wicketsTable\')" value="wicketsTable" />\n'
     + i + '      </td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'wicketsChart')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'wicketsChart\')" value="wicketsChart" />\n'
     + i + '      </td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'wicketsHist')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'wicketsHist\')" value="wicketsHist" />\n'
     + i + '      </td>\n'
     + i + '     </tr>\n'
     + i + '     <tr>\n'
     + i + '      <td class="alignR">Margins</td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'marginsTable')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'marginsTable\')" value="marginsTable" />\n'
     + i + '      </td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'marginsChart')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'marginsChart\')" value="marginsChart" />\n'
     + i + '      </td>\n'
     + i + '      <td>\n'
     + i + '       <input type="radio" id="s_tabTSradioButtonId" name="s_tabTSradioButtonId" '
         + ((currentRadioButtonValue == 'marginsHist')? checked: '')
         +         'onclick="onClickS_tabTSradioButton(\'marginsHist\')" value="marginsHist" />\n'
     + i + '      </td>\n'
     + i + '     </tr>\n'
     + i + '    </tbody>\n'
     + i + '   </table>\n'
     + i + '  </td>\n'
     + i + ' </tr>\n'
     + i + '</table>\n';

   // Use setTimeout function to flush output before matchSelector is prepared.
   setTimeout('initS_tabTSmatchSelector("         ")', 0);

   // Set value of main page hidden data for selected match.
   // (Use of setTimeout is required here also to prevent
   //  accessing matchSelector element before it has been created.)
   setTimeout('onChangeS_tabMatchSelector()', 1);
}

/*
 * Swap inner HTML text of HTML DOM element 's_tabTSmatchSelectorTd'.
 */
function initS_tabTSmatchSelector(indent)
{
   // Get value of currentRadioButtonValue from main page hidden data.
   var currentRadioButtonValue = document.getElementById('s_tabTShiddenDataRadioButtonId').value;

   var HTMLtext
     = indent + '<select class="alignL" '
              +  'id="s_tabTSmatchSelectorId" name="s_tabTSmatchSelectorId" '
              +  'onchange="onChangeS_tabMatchSelector()"'
              + ((currentRadioButtonValue == 'matchScoreSheet')? '>': ' disabled="disabled">')
              + '\n';

   // Get first and last matches in period.
   var firstAndLastMatchNosArray = getNosOfFirstAndLastMatchesInPeriod();

   if (firstAndLastMatchNosArray != -1)
   {
      var selectedOppTeamID = getSelectedOppTeamID();

      // Build new HTML match selector.
      var matchNo;
      for (matchNo  = firstAndLastMatchNosArray[1];
           matchNo >= firstAndLastMatchNosArray[0]; --matchNo)
      {
         if (!matchExcludedByRestrictions(matchNo))
         {
           var day    = MATCHES_ARRAY[matchNo]['dateArray'][0];
           var month  = MATCHES_ARRAY[matchNo]['dateArray'][1];
           var year   = MATCHES_ARRAY[matchNo]['dateArray'][2];
           var hour   = MATCHES_ARRAY[matchNo]['timeArray'][0];
           var minute = MATCHES_ARRAY[matchNo]['timeArray'][1];
           var amORpm = MATCHES_ARRAY[matchNo]['timeArray'][2];

           HTMLtext += indent + ' <option>'
                              + ((day    <= 9)? '0'     : '') + day   + '/'
                              + ((month  <= 9)? '0'     : '') + month + '/'
                              +                                 year  + ' '
                              + ((hour   <= 9)? '&nbsp;': '') + hour  + ':'
                              + ((minute <= 9)? '0'     : '') + minute
                              +                            amORpm
                              + ' (Vs. '
                              + getOppTeamNameFromId(MATCHES_ARRAY[matchNo]['oppTeamID'])
                              + ')'
                              + '</option>\n';
         }
      }
   }

   HTMLtext += indent + '</select>\n';

   // Update matchSelectorTd.
   document.getElementById('s_tabTSmatchSelectorTd').innerHTML = HTMLtext;
}

/*
 *
 */
function onClickS_tabTSradioButton(newValue)
{
   // Set value of radio button.
   document.getElementById('s_tabTSradioButtonId').value = newValue;

   // Set value of main page hidden data.
   document.getElementById('s_tabTShiddenDataRadioButtonId').value = newValue;

   // Enable match selector if 'matchScoresSheetRadioButton' is checked.
   var elem = document.getElementById('s_tabTSmatchSelectorId');
   if (document.getElementById('s_tabTSmatchScoreSheetRadioButton').checked)
     elem.disabled = false;
   else
     elem.disabled = true;
}

/*
 *
 */
function onChangeS_tabMatchSelector()
{
   // Set value of main page hidden data.
   var elem = document.getElementById('s_tabTSmatchSelectorId');
   if (elem.length > 0)
     document.getElementById('s_tabTShiddenDataMatchSelectorId').value
       = elem.options[elem.selectedIndex].text;
   else
     document.getElementById('s_tabTShiddenDataMatchSelectorId').value
       = -1;
}

/*******************************************END*OF*FILE********************************************/
