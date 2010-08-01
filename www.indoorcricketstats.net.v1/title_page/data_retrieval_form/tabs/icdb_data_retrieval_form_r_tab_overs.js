/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_r_tab_overs.js"
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
function initR_tabOvers()
{
   // get 'Bowling (overs)' vars from main page hidden data (all boolean)
   var oversCheckedArray = Array(16);
   for (var i = 0; i < 16; ++i)
     oversCheckedArray[i]
       = (1 == document.getElementById('r_tabOversHiddenDataOver'
                                       + String(i + 1) + '_0or1Id').value);

   // get 'Batting (innings)' vars from main page hidden data (all boolean)
   var inningsCheckedArray = Array(8);
   for (var i = 0; i < 8; ++i)
     inningsCheckedArray[i]
       = (1 == document.getElementById('r_tabOversHiddenDataInnings'
                                       + String(i + 1) + '_0or1Id').value);

   var i = '      '; // indent

   var innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td width="50%">\n'
     + i + '    <table width="100%">\n'
     + i + '     <thead><tr><td class="uline" colspan="8">Bowling (Overs)</td></tr></thead>\n'
     + i + '     <tbody>\n';

   // create overs checkboxes (4 rows of 4)
   var row, col;
   var widthStringOne, widthStringTwo;
   var overNo;
   for (row = 0; row < 4; ++row)
   {
      innerHTML
        += i + '      <tr>\n';

      for (col = 0; col < 4; ++col)
      {
         overNo = row * 4 + col + 1;

         // set column widths (widthOne is % width of text label, widthTwo is % width of checkbox)
         // NOTE: The total of all widths in switch statement below must be 100.
         switch (overNo)
         {
          case 1: widthStringOne = 'width="20%"'; widthStringTwo = 'width="10%"'; break;
          case 2: // same as case 3
          case 3: widthStringOne = 'width="10%"'; widthStringTwo = 'width="10%"'; break;
          case 4: widthStringOne = 'width="10%"'; widthStringTwo = 'width="20%"'; break;
          default: widthStringOne = '';           widthStringTwo = '';            break;
         }

         innerHTML
           += i + '       <td ' + widthStringOne + ' class="alignR">'
                + ((overNo <= 9)? '0': '') + String(overNo) + '</td>\n'
            + i + '       <td ' + widthStringTwo + ' class="alignL">\n'
            + i + '        <input type="checkbox"'
                + ((oversCheckedArray[overNo - 1])? ' checked="checked" ': ' ')
                +          'id="r_tabOversBowl' + String(overNo) + 'Id" '
                +          'name="r_tabOversBowl' + String(overNo) + 'Id" '
                +          'onclick="onClickR_tabOversPageOversCheckbox(' + overNo + ')" />\n'
            + i + '       </td>\n';
      }

      innerHTML
        += i + '      </tr>\n';
   }

   innerHTML
    += i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td width="20%">\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n'
     + i + '       <td>\n'
     + i + '        <input type="button" value="<- Invert\nSelection" '
         +          'onclick="onClickInvertBowlingSelection()" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '      <tr><td>&nbsp;</td></tr>\n'
     + i + '      <tr>\n'
     + i + '       <td>\n'
     + i + '        <input type="button" value="Invert ->\nSelection" '
         +          'onclick="onClickInvertBattingSelection()" />\n'
     + i + '       </td>\n'
     + i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td width="30%">\n'
     + i + '    <table width="100%">\n'
     + i + '     <thead><tr><td class="uline" colspan="4">Batting (Innings)</td></tr></thead>\n'
     + i + '     <tbody>\n';

   // create innings checkboxes (4 rows of 2)
   var inningsNo
   for (row = 0; row < 4; ++row)
   {
      innerHTML
        += i + '      <tr>\n';

      for (col = 0; col < 2; ++col)
      {
         inningsNo = row * 2 + col + 1;

         // set column widths (widthOne is % width of text label, widthTwo is % width of checkbox)
         // NOTE: The total of all widths in switch statement below must be 100.
         switch (inningsNo)
         {
          case 1:  widthStringOne = 'width="34%"'; widthStringTwo = 'width="12%"'; break;
          case 2:  widthStringOne = 'width="12%"'; widthStringTwo = 'width="42%"'; break;
          default: widthStringOne = '';            widthStringTwo = '';            break;
         }

         innerHTML
           += i + '       <td ' + widthStringOne + ' class="alignR">'
                + String(inningsNo) + '</td>\n'
            + i + '       <td ' + widthStringTwo + ' class="alignL">\n'
            + i + '        <input type="checkbox"'
                + ((inningsCheckedArray[inningsNo - 1])? ' checked="checked" ': ' ')
                +          'id="r_tabOversBatt' + String(inningsNo) + 'Id" '
                +          'name="r_tabOversBatt' + String(inningsNo) + 'Id" '
                +          'onclick="onClickR_tabOversPageInningsCheckbox(' + inningsNo + ')" />\n'
            + i + '       </td>\n';
      }

      innerHTML
        += i + '      </tr>\n';
   }

   innerHTML
    += i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '  </tr>\n'
     + i + ' </tbody>\n'
     + i + '</table>\n';

   document.getElementById('r_tabBodyTd').innerHTML = innerHTML;
}

/*
 *
 */
function resetR_tabOvers()
{
   var i; // counter

   // reset r tab overs main page hidden data
   for (i = 1; i <= 16; ++i)
     document.getElementById('r_tabOversHiddenDataOver'    + i + '_0or1Id' ).value = 1;
   for (i = 1; i <=  8; ++i)
     document.getElementById('r_tabOversHiddenDataInnings' + i + '_0or1Id' ).value = 1;

   // initialise r tab overs HTML elements
   for (i = 1; i <= 16; ++i)
     document.getElementById('r_tabOversBowl' + i + 'Id' ).checked = true;
   for (i = 1; i <=  8; ++i)
     document.getElementById('r_tabOversBatt' + i + 'Id' ).checked = true;
}

/*
 *
 */
function onClickInvertBowlingSelection()
{
   var elem;

   for (var i = 1; i <= 16; ++i)
   {
      // invert checkboxes
      elem = document.getElementById('r_tabOversBowl' + String(i) + 'Id');
      elem.checked = !elem.checked;

      // invert main page hidden data
      elem = document.getElementById('r_tabOversHiddenDataOver' + String(i) + '_0or1Id');
      switch (elem.value)
      {
       case '0': elem.value = '1'; break;
       case '1': elem.value = '0'; break;
       default: error('Expected "0" or "1" in onClickInvertBowlingSelection(), received "'
                      + elem.value + '".'                                                 );
      }
   }

   // update tab checkbox
   updateR_tabOversCheckbox();
}

/*
 *
 */
function onClickInvertBattingSelection()
{
   var elem;

   for (var i = 1; i <= 8; ++i)
   {
      // invert checkboxes
      elem = document.getElementById('r_tabOversBatt' + String(i) + 'Id');
      elem.checked = !elem.checked;

      // invert main page hidden data
      elem = document.getElementById('r_tabOversHiddenDataInnings' + String(i) + '_0or1Id');
      switch (elem.value)
      {
       case '0': elem.value = '1'; break;
       case '1': elem.value = '0'; break;
       default: error('Expected "0" or "1" in onClickInvertBowlingSelection(), received "'
                      + elem.value + '".'                                                 );
      }
   }

   // update tab checkbox
   updateR_tabOversCheckbox();
}

/*
 * NOTE: This function is for the checkboxes on the r_tab_overs page, not the tab.
 */
function onClickR_tabOversPageOversCheckbox(overNo)
{
   // check validity of overNo
   if (!(1 <= overNo && overNo <= 16))
     error(  'Expected integer [1, 16], recieved "' + overNo
           + '" in onClickR_tabOversPageCheckbox().');

   // update main page hidden data
   document.getElementById('r_tabOversHiddenDataOver' + overNo + '_0or1Id').value
     = (document.getElementById('r_tabOversBowl' + overNo + 'Id').checked == true)? 1: 0;

   // update tab checkbox
   updateR_tabOversCheckbox();
}

/*
 * NOTE: This function is for the checkboxes on the r_tab_overs page, not the tab.
 */
function onClickR_tabOversPageInningsCheckbox(inningsNo)
{
   // check validity of inningsNo
   if (!(1 <= inningsNo && inningsNo <= 8))
     error(  'Expected integer [1, 8], recieved "' + inningsNo
           + '" in onClickR_tabInningsPageCheckbox().');

   // update main page hidden data
   document.getElementById('r_tabOversHiddenDataInnings' + inningsNo + '_0or1Id').value
     = (document.getElementById('r_tabOversBatt' + inningsNo + 'Id').checked == true)? 1: 0;

   // update tab checkbox
   updateR_tabOversCheckbox();
}

/*
 * Check or uncheck the matches tab checkbox depending on
 * whether the players tab HTML elements are in their default state.
 */
function updateR_tabOversCheckbox()
{
   if (r_tabOversElemsAreInDefaultState())
     uncheckR_tabCheckbox('Overs');
   else
     checkR_tabCheckbox('Overs');
}

/*
 * The default state is all checkboxes being checked.
 */
function r_tabOversElemsAreInDefaultState()
{
   var i;
   var allChecked = true; // boolean

   // search for unchecked bowling checkboxes
   for (i = 1; i <= 16; ++i)
     if (document.getElementById('r_tabOversBowl' + i + 'Id').checked == false)
       allChecked = false;

   // search for unchecked batting checkboxes
   for (i = 1; i <=  8; ++i)
     if (document.getElementById('r_tabOversBatt' + i + 'Id').checked == false)
       allChecked = false;

   return allChecked;
}

/*******************************************END*OF*FILE********************************************/
