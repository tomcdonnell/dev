/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_r_tab_players.js"
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
function initR_tabPlayers()
{
   // get current values of players tab variables from main page hidden data
   var minMatches = document.getElementById('r_tabPlayersHiddenDataMinMatchesId').value; // integer
   var regular = (1 == document.getElementById('r_tabPlayersHiddenDataRegular0or1Id').value); //bool
   var current = (1 == document.getElementById('r_tabPlayersHiddenDataCurrent0or1Id').value); //bool
   var fillin  = (1 == document.getElementById('r_tabPlayersHiddenDataFillin0or1Id' ).value); //bool
   var retired = (1 == document.getElementById('r_tabPlayersHiddenDataRetired0or1Id').value); //bool

   var i = '      ';

   document.getElementById("r_tabBodyTd").innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr><td>Minimum<br />Number of<br />Matches</td></tr>\n'
     + i + '      <tr>\n'
     + i + '       <td>\n'
     + i + '        <input type="button" value="-" onClick="decrementMinMatchesPlayer()" />\n'
     + i + '        <input type="text" size="3" value="' + minMatches + '" '
         +          'onChange="onChangeMinMatchesPlayer()" '
         +          'onFocus="onFocusMinMatchesPlayer()" '
         +          'onBlur="onBlurMinMatchesPlayer()" '
         +          'id="r_tabPlayersMinMatchesId" name="r_tabPlayersMinMatchesId" />\n'
     + i + '        <input type="button" value="+" onClick="incrementMinMatchesPlayer()" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td>\n'
     + i + '        Regular<br />\n'
     + i + '        <input type="checkbox"' + ((regular)? ' checked="checked" ': ' ')
         +          'id="r_tabPlayersRegularCheckboxId" name="r_tabPlayersRegularCheckboxId" '
         +          'onclick="onClickR_tabPlayersPageCheckbox(\'Regular\')" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        Current<br />\n'
     + i + '        <input type="checkbox"' + ((current)? ' checked="checked" ': ' ')
         +          'id="r_tabPlayersCurrentCheckboxId" name="r_tabPlayersCurrentCheckboxId" '
         +          'onclick="onClickR_tabPlayersPageCheckbox(\'Current\')" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr>\n'
     + i + '       <td>\n'
     + i + '        Fill In<br />\n'
     + i + '        <input type="checkbox"' + ((fillin )? ' checked="checked" ': ' ')
         +          'id="r_tabPlayersFillinCheckboxId" name="r_tabPlayersFillinCheckboxId" '
         +          'onclick="onClickR_tabPlayersPageCheckbox(\'Fillin\')" />\n'
     + i + '       </td>\n'
     + i + '       <td>\n'
     + i + '        Retired<br />\n'
     + i + '        <input type="checkbox"' + ((retired)? ' checked="checked" ': ' ')
         +          'id="r_tabPlayersRetiredCheckboxId" name="r_tabPlayersRetiredCheckboxId" '
         +          'onclick="onClickR_tabPlayersPageCheckbox(\'Retired\')" />\n'
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
function resetR_tabPlayers()
{
   // reset r tab players main page hidden data
   document.getElementById('r_tabPlayersHiddenDataMinMatchesId' ).value = 1;
   document.getElementById('r_tabPlayersHiddenDataRegular0or1Id').value = 1;
   document.getElementById('r_tabPlayersHiddenDataFillin0or1Id').value = 1;
   document.getElementById('r_tabPlayersHiddenDataCurrent0or1Id').value = 1;
   document.getElementById('r_tabPlayersHiddenDataRetired0or1Id').value = 1;

   // initialise r tab players HTML elements
   document.getElementById('r_tabPlayersMinMatchesId'     ).value   = 1;
   document.getElementById('r_tabPlayersRegularCheckboxId').checked = true;
   document.getElementById('r_tabPlayersFillinCheckboxId' ).checked = true;
   document.getElementById('r_tabPlayersCurrentCheckboxId').checked = true;
   document.getElementById('r_tabPlayersRetiredCheckboxId').checked = true;
}

// Functions concerning the 'Minimum Number of Matches' buttons and text field. ////////////////////

/*
 *
 */
function incrementMinMatchesPlayer()
{
   var elem = document.getElementById('r_tabPlayersMinMatchesId');

   elem.value = Number(elem.value) + 1;

   // update main page hidden data
   document.getElementById('r_tabPlayersHiddenDataMinMatchesId').value = elem.value;

   initSelectedS_tab();

   // update tab checkbox
   updateR_tabPlayersCheckbox();
}


/*
 *
 */
function decrementMinMatchesPlayer()
{
   var elem = document.getElementById('r_tabPlayersMinMatchesId');

   if (elem.value > 1)
     elem.value = Number(elem.value) - 1;

   // update main page hidden data
   document.getElementById('r_tabPlayersHiddenDataMinMatchesId').value = elem.value;

   initSelectedS_tab();

   // update tab checkbox
   updateR_tabPlayersCheckbox();
}

/*
 *
 */
function onChangeMinMatchesPlayer()
{
   var elem = document.getElementById('r_tabPlayersMinMatchesId');

   if (isNaN(elem.value) || elem.value < 1)
     elem.value = 1;

   // update main page hidden data
   document.getElementById('r_tabPlayersHiddenDataMinMatchesId').value = elem.value;

   initSelectedS_tab();

   // update tab checkbox
   updateR_tabPlayersCheckbox();
}

/*
 *
 */
function onFocusMinMatchesPlayer()
{
   document.getElementById('r_tabPlayersMinMatchesId').value = '';
}

/*
 * This function is required so that the field may not be left blank.
 */
function onBlurMinMatchesPlayer()
{
   onChangeMinMatchesPlayer();
}

/*
 * NOTE: This function is for the checkboxes on the r_tab_players page, not the tab.
 *       Ie. either the 'Regular', 'Current', 'Fillin' or 'Retired' checkboxes.
 *       'checkboxName' in this function is one of: 'Regular', 'Current', 'Fillin' or 'Retired'.
 */
function onClickR_tabPlayersPageCheckbox(checkboxName)
{
   // check validity of checkboxName
   switch (checkboxName)
   {
    case 'Regular': case 'Fillin': case 'Current': case 'Retired': break; // OK
    default: error(  'Invalid checkboxName "' + checkboxName
                   + '" received in onClickR_tabPlayersPageCheckbox().');
   }

   // update main page hidden data
   document.getElementById('r_tabPlayersHiddenData' + checkboxName + '0or1Id').value
     = (document.getElementById('r_tabPlayers' + checkboxName + 'CheckboxId').checked == true)?
         1: 0;

   initSelectedS_tab();

   // update tab checkbox
   updateR_tabPlayersCheckbox();
}

/*
 * Check or uncheck the players tab checkbox depending on
 * whether the players tab HTML elements are in their default state.
 */
function updateR_tabPlayersCheckbox()
{
   if (r_tabPlayersElemsAreInDefaultState())
     uncheckR_tabCheckbox('Players');
   else
     checkR_tabCheckbox('Players');
}

/*
 *
 */
function r_tabPlayersElemsAreInDefaultState()
{
   if (   document.getElementById('r_tabPlayersMinMatchesId'     ).value   == 1
       && document.getElementById('r_tabPlayersRegularCheckboxId').checked == true
       && document.getElementById('r_tabPlayersFillinCheckboxId' ).checked == true
       && document.getElementById('r_tabPlayersCurrentCheckboxId').checked == true
       && document.getElementById('r_tabPlayersRetiredCheckboxId').checked == true)
     return true;
   else
     return false;
}

/*******************************************END*OF*FILE********************************************/
