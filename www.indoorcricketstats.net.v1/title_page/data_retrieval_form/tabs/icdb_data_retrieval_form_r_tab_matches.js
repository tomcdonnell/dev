/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_r_tab_matches.js"
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

// The global variables defined in "icdb_data_retrieval_form.js" are used in this file.
// They are capitalised eg. MATCHES_ARRAY[] for easy identification and should be treated as
// read-only in this file.

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function initR_tabMatches()
{
   // get 'match result' vars from main page hidden data (all boolean)
   var win  = (1 == document.getElementById('r_tabMatchesHiddenDataWin0or1Id' ).value);
   var draw = (1 == document.getElementById('r_tabMatchesHiddenDataDraw0or1Id').value);
   var loss = (1 == document.getElementById('r_tabMatchesHiddenDataLoss0or1Id').value);

   // get 'innings order' vars from main page hidden data (all boolean)
   var battedFirst  = (1 == document.getElementById('r_tabMatchesHiddenDataBatted1st0or1Id').value);
   var battedSecond = (1 == document.getElementById('r_tabMatchesHiddenDataBatted2nd0or1Id').value);

   // get 'missing players' vars from main page hidden data (all boolean)
   var fullTeam = (1 == document.getElementById('r_tabMatchesHiddenDataFullTeam0or1Id').value);
   var shortOne = (1 == document.getElementById('r_tabMatchesHiddenDataShortOne0or1Id').value);
   var shortTwo = (1 == document.getElementById('r_tabMatchesHiddenDataShortTwo0or1Id').value);

   // get 'match type' vars from main page hidden data (all boolean)
   var regular   = (1 == document.getElementById('r_tabMatchesHiddenDataRegular0or1Id'  ).value);
   var irregular = (1 == document.getElementById('r_tabMatchesHiddenDataIrregular0or1Id').value);
   var finals    = (1 == document.getElementById('r_tabMatchesHiddenDataFinals0or1Id'   ).value);

   var i = '      ';

   document.getElementById("r_tabBodyTd").innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <thead><tr><td class="uline" colspan="2">Match<br />Result</td></tr></thead>\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR" width="60%">Win&nbsp;</td>\n'
     + i + '       <td class="alignL" width="40%">\n'
     + i + '        <input type="checkbox"' + ((win)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesWinCheckboxId" name="r_tabMatchesWinCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Win\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>'
     + i + '       <td class="alignR">Draw&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((draw)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesDrawCheckboxId" name="r_tabMatchesDrawCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Draw\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>'
     + i + '       <td class="alignR">Loss&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((loss)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesLossCheckboxId" name="r_tabMatchesLossCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Loss\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <thead><tr><td class="uline" colspan="2">Innings<br />Order</td></tr></thead>\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR nowrap" width="73%">Batted 1st&nbsp;</td>\n'
     + i + '       <td class="alignL" width="27%">\n'
     + i + '        <input type="checkbox"' + ((battedFirst)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesBatted1stCheckboxId" name="r_tabMatchesBatted1stCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Batted1st\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR nowrap">Batted 2nd&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((battedSecond)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesBatted2ndCheckboxId" name="r_tabMatchesBatted2ndCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Batted2nd\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr><td>&nbsp;</td><td>&nbsp;</td></tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <thead><tr><td class="uline" colspan="2">Missing<br />Players</td></tr></thead>\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR nowrap" width="73%">Full Team&nbsp;</td>\n'
     + i + '       <td class="alignL" width="27%">\n'
     + i + '        <input type="checkbox"' + ((fullTeam)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesFullTeamCheckboxId" name="r_tabMatchesFullTeamCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'FullTeam\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR nowrap">Short One&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((shortOne)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesShortOneCheckboxId" name="r_tabMatchesShortOneCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'ShortOne\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR nowrap">Short Two&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((shortTwo)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesShortTwoCheckboxId" name="r_tabMatchesShortTwoCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'ShortTwo\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <thead><tr><td class="uline" colspan="2">Match<br />Type</td></tr></thead>\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR" width="70%">Regular&nbsp;</td>\n'
     + i + '       <td class="alignL" width="30%">\n'
     + i + '        <input type="checkbox"' + ((regular)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesRegularCheckboxId" name="r_tabMatchesRegularCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Regular\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">Irregular&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((irregular)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesIrregularCheckboxId" name="r_tabMatchesIrregularCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Irregular\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td class="alignR">Finals&nbsp;</td>\n'
     + i + '       <td class="alignL">\n'
     + i + '        <input type="checkbox"' + ((finals)? ' checked="checked" ': ' ')
         +          'id="r_tabMatchesFinalsCheckboxId" name="r_tabMatchesFinalsCheckboxId" '
         +          'onclick="onClickR_tabMatchesPageCheckbox(\'Finals\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '  </tr>\n'
     + i + ' </tbody>\n'
     + i + '</table>\n';
}

/*
 *
 */
function resetR_tabMatches()
{
   // reset r tab matches main page hidden data
   document.getElementById('r_tabMatchesHiddenDataWin0or1Id' ).value = 1;
   document.getElementById('r_tabMatchesHiddenDataDraw0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataLoss0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataBatted1st0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataBatted2nd0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataFullTeam0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataShortOne0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataShortTwo0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataRegular0or1Id'  ).value = 1;
   document.getElementById('r_tabMatchesHiddenDataIrregular0or1Id').value = 1;
   document.getElementById('r_tabMatchesHiddenDataFinals0or1Id'   ).value = 1;

   // initialise r tab matches HTML elements
   document.getElementById('r_tabMatchesWinCheckboxId' ).checked = true;
   document.getElementById('r_tabMatchesDrawCheckboxId').checked = true;
   document.getElementById('r_tabMatchesLossCheckboxId').checked = true;
   document.getElementById('r_tabMatchesBatted1stCheckboxId').checked = true;
   document.getElementById('r_tabMatchesBatted2ndCheckboxId').checked = true;
   document.getElementById('r_tabMatchesFullTeamCheckboxId').checked = true;
   document.getElementById('r_tabMatchesShortOneCheckboxId').checked = true;
   document.getElementById('r_tabMatchesShortTwoCheckboxId').checked = true;
   document.getElementById('r_tabMatchesRegularCheckboxId'  ).checked = true;
   document.getElementById('r_tabMatchesIrregularCheckboxId').checked = true;
   document.getElementById('r_tabMatchesFinalsCheckboxId'   ).checked = true;

   initSelectedS_tab();
}

/*
 * NOTE: This function is for the checkboxes on the r_tab_matches page, not the tab.
 *       Ie. checkboxes in one of the categories:
 *         'Match Result', 'Innings Order', 'Missing Players' or 'Match Type'
 *
 *       Hence 'checkboxName' in this function is one of:
 *         'Win', 'Draw', 'Loss',              ('Match Result'    category)
 *         'Batted1st', 'Batted2nd',           ('Innings Order'   category)
 *         'FullTeam', 'ShortOne', 'ShortTwo', ('Missing Players' category)
 *         'Regular', 'Irregular', 'Finals'    ('Match Type'      category).
 */
function onClickR_tabMatchesPageCheckbox(checkboxName)
{
   // check validity of checkboxName
   switch (checkboxName)
   {
    case 'Win': case 'Draw': case 'Loss':              // ('Match Result'    category)
    case 'Batted1st': case 'Batted2nd':                // ('Innings Order'   category)
    case 'FullTeam': case 'ShortOne': case 'ShortTwo': // ('Missing Players' category)
    case 'Regular': case 'Irregular': case 'Finals':   // ('Match Type'      category)
      break; // OK
    default: error(  'Invalid checkboxName "' + checkboxName
                   + '" received in onClickR_tabMatchesPageCheckbox().');
   }

   // update main page hidden data
   document.getElementById('r_tabMatchesHiddenData' + checkboxName + '0or1Id').value
     = (document.getElementById('r_tabMatches' + checkboxName + 'CheckboxId').checked == true)?
         1: 0;

   // update tab checkbox
   updateR_tabMatchesCheckbox();

   initSelectedS_tab();
}

/*
 * Check or uncheck the matches tab checkbox depending on
 * whether the matches tab HTML elements are in their default state.
 */
function updateR_tabMatchesCheckbox()
{
   if (r_tabMatchesElemsAreInDefaultState())
     uncheckR_tabCheckbox('Matches');
   else
     checkR_tabCheckbox('Matches');
}

/*
 *
 */
function r_tabMatchesElemsAreInDefaultState()
{
   if (   document.getElementById('r_tabMatchesWinCheckboxId'      ).checked == true
       && document.getElementById('r_tabMatchesDrawCheckboxId'     ).checked == true
       && document.getElementById('r_tabMatchesLossCheckboxId'     ).checked == true
       && document.getElementById('r_tabMatchesBatted1stCheckboxId').checked == true
       && document.getElementById('r_tabMatchesBatted2ndCheckboxId').checked == true
       && document.getElementById('r_tabMatchesFullTeamCheckboxId' ).checked == true
       && document.getElementById('r_tabMatchesShortOneCheckboxId' ).checked == true
       && document.getElementById('r_tabMatchesShortTwoCheckboxId' ).checked == true
       && document.getElementById('r_tabMatchesRegularCheckboxId'  ).checked == true
       && document.getElementById('r_tabMatchesIrregularCheckboxId').checked == true
       && document.getElementById('r_tabMatchesFinalsCheckboxId'   ).checked == true)
     return true;
   else
     return false;
}

/*******************************************END*OF*FILE********************************************/
