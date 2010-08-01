/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_r_tab_period.js"
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

var R_TAB_BODY_PERIOD_HTML_TEXT = '';

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

/*
 * NOTE: This fuction will initialise the tab using the current main page hidden data.
 *       To reset the main page hidden data, 'use resetR_tabPeriod()'.
 */
function initR_tabPeriod()
{
   initR_TAB_BODY_PERIOD_HTML_TEXT();

   document.getElementById('r_tabBodyTd').innerHTML = R_TAB_BODY_PERIOD_HTML_TEXT;
}

/*
 * Resets all elements of the 'r_tabPeriod' page.
 * The period is reset to span the entire database.
 * Also updates the main page hidden data.
 */
function resetR_tabPeriod()
{
   resetR_tabPeriodDateAndSeasonSelectors();
   resetR_tabPeriodMatchTimeRangeSelectors();
}

/*
 * Resets the period (dates) selectors and season selector.
 * Updates the main page hidden data also.
 */
function resetR_tabPeriodDateAndSeasonSelectors()
{
   // init selected period (setPeriod() sets main page hidden data also)
   var n = N_MATCHES - 1;
   var sD = MATCHES_ARRAY[0]['dateArray'][0];
   var sM = MATCHES_ARRAY[0]['dateArray'][1];
   var sY = MATCHES_ARRAY[0]['dateArray'][2];
   var fD = MATCHES_ARRAY[n]['dateArray'][0];
   var fM = MATCHES_ARRAY[n]['dateArray'][1];
   var fY = MATCHES_ARRAY[n]['dateArray'][2];
   setPeriod(sD, sM, sY, fD, fM, fY);

   // reset season selector and main page hidden data
   document.getElementById('r_tabPeriodSeasonSelectorId').selectedIndex = 0;
   document.getElementById('r_tabPeriodHiddenDataSeasonId').value = 'Select Season';
}

/*
 * Resets the match time range checkbox and selectors.
 * Updates the main page hidden data also.
 */
function resetR_tabPeriodMatchTimeRangeSelectors()
{
   // reset match time range checkbox and main page hidden data
   document.getElementById('r_tabPeriodMatchTimeCheckboxId').checked = false;
   document.getElementById('r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id').value = 0;

   // disable match time range selectors
   setMatchTimeRangeSelectorsDisabled(true);

   // reset match time range selectors and main page hidden data
   document.getElementById('r_tabPeriodStartHourSelectorId'   ).selectedIndex = 11; // Index of 12
   document.getElementById('r_tabPeriodStartMinuteSelectorId' ).selectedIndex = 0;  // Index of 00.
   document.getElementById('r_tabPeriodStartAMorPMSelectorId' ).selectedIndex = 0;  // Index of AM.
   document.getElementById('r_tabPeriodFinishHourSelectorId'  ).selectedIndex = 10; // Index of 11.
   document.getElementById('r_tabPeriodFinishMinuteSelectorId').selectedIndex = 59; // Index of 59.
   document.getElementById('r_tabPeriodFinishAMorPMSelectorId').selectedIndex = 1;  // Index of PM.
   document.getElementById('r_tabPeriodHiddenDataStartHourId'   ).value = 12;
   document.getElementById('r_tabPeriodHiddenDataStartMinuteId' ).value = 0;
   document.getElementById('r_tabPeriodHiddenDataStartAMorPMId' ).value = 'AM';
   document.getElementById('r_tabPeriodHiddenDataFinishHourId'  ).value = 11;
   document.getElementById('r_tabPeriodHiddenDataFinishMinuteId').value = 59;
   document.getElementById('r_tabPeriodHiddenDataFinishAMorPMId').value = 'PM';
}

/*
 *
 */
function setMatchTimeRangeSelectorsDisabled(trueORfalse)
{
   document.getElementById('r_tabPeriodStartHourSelectorId'   ).disabled = trueORfalse;
   document.getElementById('r_tabPeriodStartMinuteSelectorId' ).disabled = trueORfalse;
   document.getElementById('r_tabPeriodStartAMorPMSelectorId' ).disabled = trueORfalse;
   document.getElementById('r_tabPeriodFinishHourSelectorId'  ).disabled = trueORfalse;
   document.getElementById('r_tabPeriodFinishMinuteSelectorId').disabled = trueORfalse;
   document.getElementById('r_tabPeriodFinishAMorPMSelectorId').disabled = trueORfalse;
}

/*
 * Initialise the HTML code text string for the 'period' tab of the 'restrictions' (r) table.
 * This function should be called from the body onload function of the main page
 * ("icdb_data_retrieval_form.php"), after the coded data has been extracted.
 */
function initR_TAB_BODY_PERIOD_HTML_TEXT()
{
   var i = '      ';

   R_TAB_BODY_PERIOD_HTML_TEXT
     = i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td width="50%">\n'
     + i + '    <select id="r_tabPeriodSeasonSelectorId" \n'
         +      'name="r_tabPeriodSeasonSelectorId" onchange="onChangeSeason()"'
         + ((N_SEASONS == 0)? ' disabled="disabled"': '') + '>\n'
     + i + '     <option selected="selected">Select Season</option>\n';

   var selectedSeasonStr = document.getElementById('r_tabPeriodHiddenDataSeasonId').value
   var seasonName;
   for (var s = 0; s < N_SEASONS; ++s)
   {
      seasonName = SEASONS_ARRAY[s]['seasonName']
      R_TAB_BODY_PERIOD_HTML_TEXT
        += i + '     <option'
             + ((selectedSeasonStr == seasonName)? ' selected="selected"': '') + '>'
             + seasonName + '</option>\n';
   }

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '    </select>\n'
     + i + '   </td>\n'
     + i + '   <td>&nbsp;</td>\n'
     + i + '   <td width="50%">\n';

   // get value of checkbox from main page hidden data
   var timeCheckboxChecked
     = document.getElementById('r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id').value;
   switch (timeCheckboxChecked)
   {
    case '0': timeCheckboxChecked = false; break;
    case '1': timeCheckboxChecked = true;  break;
    default: console.error(  'Expected either "0" or "1" in '
                   + 'addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
                   + 'received "' + timeCheckboxChecked + '".'         );
   }

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '    <input type="checkbox" ' + ((timeCheckboxChecked)? 'checked="checked"': '')
         +      'id="r_tabPeriodMatchTimeCheckboxId" '
         +      'name="r_tabPeriodMatchTimeCheckboxId" '
         +      'onclick="onClickR_tabPeriodMatchTimeCheckbox()" /><br />\n'
     + i + '    Specify Match<br />Time Range</td>\n'
     + i + '   <td>\n'
     + i + '  </tr>\n'
     + i + '  <tr>\n'
     + i + '   <td>\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n';

   addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(i, 'Start');

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '      </tr>\n'
     + i + '      <tr>\n';

   addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(i, 'Finish');

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '   <td>Start<br />Finish</td>\n'
     + i + '   <td width="50%">\n'
     + i + '    <table width="100%">\n'
     + i + '     <tbody>\n'
     + i + '      <tr>\n';

   addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(i, 'Start');

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '      </tr>\n'
     + i + '      <tr>\n';

   addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(i, 'Finish');

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '      </tr>\n'
     + i + '     </tbody>\n'
     + i + '    </table>\n'
     + i + '   </td>\n'
     + i + '  </tr>\n'
     + i + ' </tbody>\n'
     + i + '</table>\n'
}

/*
 *
 */
function addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(indent, StartOrFinish)
{
   // get selected start or finish date from main page hidden data
   switch (StartOrFinish)
   {
    case 'Start':  selectedDateArray = getSelectedPeriodStartDateArray();  break;
    case 'Finish': selectedDateArray = getSelectedPeriodFinishDateArray(); break;
    default: console.error('Expected "Start" or "Finish" in addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT()"'
                   + ', received "' + StartOrFinish + '".');
   }
console.debug(StartOrFinish);
   // check validity of selectedDateArray
   if (!(1 <= Number(selectedDateArray[0]) && Number(selectedDateArray[0]) <= 31))
     console.error(  'Expected integer [0, 31] in addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
           + 'received ' + Number(selectedDateArray[0]) + '.'                              );
   if (!(1 <= Number(selectedDateArray[1]) && Number(selectedDateArray[1]) <= 12))
     console.error(  'Expected integer [1, 12] in addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
           + 'received ' + Number(selectedDateArray[1]) + '.'                              );
   if (!(1900 <= Number(selectedDateArray[2]) && Number(selectedDateArray[2]) <= 2050))
     console.error(  'Expected integer [1900, 2050] in addDateSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
           + 'received ' + Number(selectedDateArray[2]) + '.'                                   );

   var i = indent; // abbreviation

   R_TAB_BODY_PERIOD_HTML_TEXT
     += i + '<td class="alignR">\n'
      + i + ' <select id="r_tabPeriod' + StartOrFinish + 'DaySelectorId" '
          +        'name="r_tabPeriod' + StartOrFinish + 'DaySelectorId" '
          +   'onChange="onChangePeriod(\'' + StartOrFinish + '\' , \'day\', '
          +                            'r_tabPeriodStartDaySelectorId, '
          +                            'r_tabPeriodStartMonthSelectorId, '
          +                            'r_tabPeriodStartYearSelectorId, '
          +                            'r_tabPeriodFinishDaySelectorId, '
          +                            'r_tabPeriodFinishMonthSelectorId, '
          +                            'r_tabPeriodFinishYearSelectorId)">\n';

  for (var d = 1; d <= 31; ++d)
     R_TAB_BODY_PERIOD_HTML_TEXT
       += i + '  <option' + ((d == selectedDateArray[0])? ' selected="selected"': '') + '>'
            + d + '</option>\n';

   R_TAB_BODY_PERIOD_HTML_TEXT
     += i + ' </select>\n'
      + i + '</td>\n'
      + i + '<td class="alignC">\n'
      + i + ' <select id="r_tabPeriod' + StartOrFinish + 'MonthSelectorId" '
          +        'name="r_tabPeriod' + StartOrFinish + 'MonthSelectorId" '
          +   'onChange="onChangePeriod(\'' + StartOrFinish + '\', \'month\', '
          +                            'r_tabPeriodStartDaySelectorId, '
          +                            'r_tabPeriodStartMonthSelectorId, '
          +                            'r_tabPeriodStartYearSelectorId, '
          +                            'r_tabPeriodFinishDaySelectorId, '
          +                            'r_tabPeriodFinishMonthSelectorId, '
          +                            'r_tabPeriodFinishYearSelectorId)">\n';

   for (var m = 1; m <= 12; ++m)
     R_TAB_BODY_PERIOD_HTML_TEXT
       += i + '  <option' + ((m == selectedDateArray[1])? ' selected="selected"': '') + '>'
            + getMonthAbbrev(m) + '</option>\n'; // getMonthAbbrev from "date_functions.js"

   R_TAB_BODY_PERIOD_HTML_TEXT
     += i + ' </select>\n'
      + i + '</td>\n'
      + i + '<td class="alignL">\n'
      + i + ' <select id="r_tabPeriod' + StartOrFinish + 'YearSelectorId" '
          +        'name="r_tabPeriod' + StartOrFinish + 'YearSelectorId" '
          +   'onChange="onChangePeriod(\'' + StartOrFinish + '\', \'year\', '
          +                            'r_tabPeriodStartDaySelectorId, '
          +                            'r_tabPeriodStartMonthSelectorId, '
          +                            'r_tabPeriodStartYearSelectorId, '
          +                            'r_tabPeriodFinishDaySelectorId, '
          +                            'r_tabPeriodFinishMonthSelectorId, '
          +                            'r_tabPeriodFinishYearSelectorId)">\n';

   for (var y = MATCHES_ARRAY[0]['dateArray'][2];
        y <= MATCHES_ARRAY[N_MATCHES - 1]['dateArray'][2]; ++y)
     R_TAB_BODY_PERIOD_HTML_TEXT
       += i + '  <option' + ((y == selectedDateArray[2])? ' selected="selected"': '') + '>'
            + y + '</option>\n';

   R_TAB_BODY_PERIOD_HTML_TEXT
     += i + ' </select>\n'
      + i + '</td>\n'
}

/*
 * NOTE: The select elements created in this function are aligned left because
 *       if aligned centre or right a problem occurs when the select element is disabled/enabled.
 *       The problem is, when the select element is disabled, the text alignment is done without
 *       taking into account the down arrow button at the right of the element (so some of the
 *       option text may appear behind the button instead of all to the left as should happen).
 */
function addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(indent, StartOrFinish)
{
   // get selected start or finish time from main page hidden data
   switch (StartOrFinish)
   {
    case 'Start':  selectedTimeArray = getSelectedPeriodStartTimeArray();  break;
    case 'Finish': selectedTimeArray = getSelectedPeriodFinishTimeArray(); break;
    default: console.error('Expected "Start" or "Finish" in addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT()"'
                   + ', received "' + StartOrFinish + '".');
   }

   // check validity of selectedTimeArray
   if (!(1 <= Number(selectedTimeArray[0]) && Number(selectedTimeArray[0]) <= 12))
     console.error(  'Expected integer [1, 12] in addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
           + 'received ' + selectedTimeArray[0] + '.'                               );
   if (!(0 <= Number(selectedTimeArray[1]) && Number(selectedTimeArray[1]) <= 59))
     console.error(  'Expected integer [0, 59] in addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
           + 'received ' + selectedTimeArray[1] + '.'                               );
   if (!('AM' == selectedTimeArray[2] || 'PM' == selectedTimeArray[2]))
     console.error(  'Expected either "AM" or "PM" in addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
           + 'received ' + selectedTimeArray[2] + '.'                                          );

   // get value of checkbox from main page hidden data
   var timeCheckboxChecked
     = document.getElementById('r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id').value;
   switch (timeCheckboxChecked)
   {
    case '0': timeCheckboxChecked = false; break;
    case '1': timeCheckboxChecked =  true; break;
    default: console.error(  'Expected either "0" or "1" in '
                   + 'addTimeSelectorToR_TAB_BODY_PERIOD_HTML_TEXT(), '
                   + 'received "' + timeCheckboxChecked + '".'         );
   }

   var i = indent;

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + '<td class="alignR">\n'
     + i + ' <select class="alignL"' + ((timeCheckboxChecked)? ' ': ' disabled="disabled" ')
         +     'id="r_tabPeriod' + StartOrFinish + 'HourSelectorId" '
         +   'name="r_tabPeriod' + StartOrFinish + 'HourSelectorId" '
         +   'onchange="onChangeR_tabPeriodTimeSelector(\'Hour\', \'' + StartOrFinish + '\')">\n';

   for (var hour = 1; hour <= 12; ++hour)
     R_TAB_BODY_PERIOD_HTML_TEXT
       += i + '  <option' + ((hour == selectedTimeArray[0])? ' selected="selected"': '') + '>'
            + ((hour <= 9)? '&nbsp;': '') + String(hour) + '</option>\n';

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + ' </select>\n'
     + i + '</td>\n'
     + i + '<td class="alignC">\n'
     + i + ' <select class="alignL"' + ((timeCheckboxChecked)? ' ': ' disabled="disabled" ')
         +     'id="r_tabPeriod' + StartOrFinish + 'MinuteSelectorId" '
         +   'name="r_tabPeriod' + StartOrFinish + 'MinuteSelectorId" '
         +   'onchange="onChangeR_tabPeriodTimeSelector(\'Minute\', \'' + StartOrFinish + '\')">\n';

   for (var minute = 0; minute <= 59; ++minute)
     R_TAB_BODY_PERIOD_HTML_TEXT
       += i + '  <option' + ((minute == selectedTimeArray[1])? ' selected="selected"': '') + '>'
            + ((minute <= 9)? '0': '') + String(minute) + '</option>\n';

   R_TAB_BODY_PERIOD_HTML_TEXT
    += i + ' </select>\n'
     + i + '</td>\n'
     + i + '<td class="alignL">\n'
     + i + ' <select class="alignL"' + ((timeCheckboxChecked)? ' ': ' disabled="disabled" ')
         +     'id="r_tabPeriod' + StartOrFinish + 'AMorPMSelectorId" '
         +   'name="r_tabPeriod' + StartOrFinish + 'AMorPMSelectorId" '
         +   'onchange="onChangeR_tabPeriodTimeSelector(\'AMorPM\', \'' + StartOrFinish + '\')">\n'
     + i + '  <option' + (('AM' == selectedTimeArray[2])? ' selected="selected"': '') + '>'
         +    'AM</option>\n'
     + i + '  <option' + (('PM' == selectedTimeArray[2])? ' selected="selected"': '') + '>'
         +    'PM</option>\n'
     + i + ' </select>\n'
     + i + '</td>\n';
}

/*
 * PRIMARY
 */
function onChangeSeason()
{
   var seasonNo = document.getElementById('r_tabPeriodSeasonSelectorId').selectedIndex;

   if (seasonNo == 0)
     resetR_tabPeriodDateAndSeasonSelectors(); // also resets the main page hidden data
   else
   {
      // get season start & finish dates
      var sD = SEASONS_ARRAY[seasonNo - 1]['startDateArray' ][0];
      var sM = SEASONS_ARRAY[seasonNo - 1]['startDateArray' ][1];
      var sY = SEASONS_ARRAY[seasonNo - 1]['startDateArray' ][2];
      var fD = SEASONS_ARRAY[seasonNo - 1]['finishDateArray'][0];
      var fM = SEASONS_ARRAY[seasonNo - 1]['finishDateArray'][1];
      var fY = SEASONS_ARRAY[seasonNo - 1]['finishDateArray'][2];

      // set period to season start & finish dates
      setPeriod(sD, sM, sY, fD, fM, fY);

      // set main page hidden data season variable
      document.getElementById('r_tabPeriodHiddenDataSeasonId').value
        = document.getElementById('r_tabPeriodSeasonSelectorId').options[seasonNo].text;
   }

   updateR_tabPeriodCheckbox();
}

/*
 * PRIMARY
 */
function onClickR_tabPeriodMatchTimeCheckbox()
{
   // get value of checkbox from main page hidden data   
   var checked = document.getElementById('r_tabPeriodMatchTimeCheckboxId').checked;

   if (checked)
     setMatchTimeRangeSelectorsDisabled(false);
   else
     resetR_tabPeriodMatchTimeRangeSelectors(); // also disables match time range selectors

   // update main page hidden data
   document.getElementById('r_tabPeriodHiddenDataMatchTimeCheckbox0or1Id').value
     = ((checked)? 1: 0);

   updateR_tabPeriodCheckbox();

   initSelectedS_tab();
}

/*
 * PRIMARY
 *
 * 'timeSelectorType' should be 'Hour', 'Minute' or 'AMorPM'.
 */
function onChangeR_tabPeriodTimeSelector(timeSelectorType, StartOrFinish)
{
   // test validity of timeSelectorType
   switch (timeSelectorType)
   {
    case 'Hour': case 'Minute': case 'AMorPM': break; // OK
    default:
      console.error(  'Expected "hour", "minute", or "AMorPM" in onChangeR_tabPeriodTimeSelector(), '
            + 'received "' + timeSelectorType + '".'                                         );
   }

   // test validity of StartOrFinish
   switch (StartOrFinish)
   {
    case 'Start': case 'Finish': break; // OK
    default: console.error(  'Expected "Start" or "Finish" in onChangeR_tabPeriodTimeSelector(), '
                   + 'received "' + StartOrFinish + '".'                                  );
   }

   ensureMatchTimeRangeIsValid(StartOrFinish);

   // update main page hidden data
   var elem
     = document.getElementById('r_tabPeriod' + StartOrFinish + timeSelectorType + 'SelectorId');
   document.getElementById('r_tabPeriodHiddenData' + StartOrFinish + timeSelectorType + 'Id').value
     = (timeSelectorType == 'AMorPM')?
         elem.options[elem.selectedIndex].text:
         Number(elem.options[elem.selectedIndex].text);
   // NOTE: the cast above is necessary because of space in the option text eg ' 7'

   initSelectedS_tab();
}

/*
 * This function is intended to be used after the selected match time
 * range has changed but before the main page hidden data is updated.
 */
function ensureMatchTimeRangeIsValid(StartOrFinish)
{
   // Get match time range elements.
   // NOTE: Do not use main page hidden data here
   var sHourElem   = document.getElementById('r_tabPeriodStartHourSelectorId'   );
   var sMinuteElem = document.getElementById('r_tabPeriodStartMinuteSelectorId' );
   var sAMorPMElem = document.getElementById('r_tabPeriodStartAMorPMSelectorId' );
   var fHourElem   = document.getElementById('r_tabPeriodFinishHourSelectorId'  );
   var fMinuteElem = document.getElementById('r_tabPeriodFinishMinuteSelectorId');
   var fAMorPMElem = document.getElementById('r_tabPeriodFinishAMorPMSelectorId');

   // Get selected match time range from elements.
   // NOTE: Must convert from string to number to avoid problems with '00'.
   var sHour   = Number(sHourElem.options[sHourElem.selectedIndex    ].text);
   var sMinute = Number(sMinuteElem.options[sMinuteElem.selectedIndex].text);
   var sAMorPM = sAMorPMElem.options[sAMorPMElem.selectedIndex].text;
   var fHour   = Number(fHourElem.options[fHourElem.selectedIndex    ].text);
   var fMinute = Number(fMinuteElem.options[fMinuteElem.selectedIndex].text);
   var fAMorPM = fAMorPMElem.options[fAMorPMElem.selectedIndex].text;

   // Convert to 24 hour format.
   var sHour24 = (sAMorPM == 'PM')? Number(sHour) + 12: sHour;
   var fHour24 = (fAMorPM == 'PM')? Number(fHour) + 12: fHour;

   // Ensure startTime is earlier or equal to finishTime.
   if (compareTimes(sHour24, sMinute, fHour24, fMinute) > 0) // If startTime > finishTime...
   {
      // Start is after finish.

      switch (StartOrFinish)
      {
       case 'Start':
         // Start was changed last, so change finish to equal start.

         // Update select elements.
         fHourElem.selectedIndex   = sHourElem.selectedIndex;
         fMinuteElem.selectedIndex = sMinuteElem.selectedIndex;
         fAMorPMElem.selectedIndex = sAMorPMElem.selectedIndex;

         // Update main page hidden data.
         document.getElementById('r_tabPeriodHiddenDataFinishHourId'  ).value = sHour;
         document.getElementById('r_tabPeriodHiddenDataFinishMinuteId').value = sMinute;
         document.getElementById('r_tabPeriodHiddenDataFinishAMorPMId').value = sAMorPM;
         break;
       case 'Finish':
         // Finish was changed last, so change start to equal finish.

         // Update select elements.
         sHourElem.selectedIndex   = fHourElem.selectedIndex;
         sMinuteElem.selectedIndex = fMinuteElem.selectedIndex;
         sAMorPMElem.selectedIndex = fAMorPMElem.selectedIndex;

         // Update main page hidden data.
         document.getElementById('r_tabPeriodHiddenDataStartHourId'  ).value = fHour;
         document.getElementById('r_tabPeriodHiddenDataStartMinuteId').value = fMinute;
         document.getElementById('r_tabPeriodHiddenDataStartAMorPMId').value = fAMorPM;
         break;
       default: console.error('Expected "Start" or "Finish" in ensureMatchTimeRangeIsValid().');
      }
   }
}

/*
 *
 */
function setPeriod(sD, sM, sY, fD, fM, fY)
{
   setDateSelectedIndices(sD, sM, sY,
                          'r_tabPeriodStartDaySelectorId',
                          'r_tabPeriodStartMonthSelectorId',
                          'r_tabPeriodStartYearSelectorId'  );
   setDateSelectedIndices(fD, fM, fY,
                          'r_tabPeriodFinishDaySelectorId',
                          'r_tabPeriodFinishMonthSelectorId',
                          'r_tabPeriodFinishYearSelectorId'  );

   // Set main page hidden data period variables.
   setMainPageHiddenDataR_tabPeriodVars(sD, sM, sY, fD, fM, fY);

   initSelectedS_tab();
}

/*
 * Called by onChangePeriod() after the regular
 * updates to the period selectors have been performed.
 */
function additionalOnChangePeriod()
{
   // Get new selected days & months.
   var sD = document.getElementById('r_tabPeriodStartDaySelectorId'   ).selectedIndex + 1;
   var sM = document.getElementById('r_tabPeriodStartMonthSelectorId' ).selectedIndex + 1;
   var fD = document.getElementById('r_tabPeriodFinishDaySelectorId'  ).selectedIndex + 1;
   var fM = document.getElementById('r_tabPeriodFinishMonthSelectorId').selectedIndex + 1;

   // Get new selected years.
   var sYelem = document.getElementById('r_tabPeriodStartYearSelectorId' );
   var fYelem = document.getElementById('r_tabPeriodFinishYearSelectorId');
   var sY = Number(sYelem.options[0].text) + sYelem.selectedIndex;
   var fY = Number(fYelem.options[0].text) + fYelem.selectedIndex;

   // Set main page hidden data period variables.
   setMainPageHiddenDataR_tabPeriodVars(sD, sM, sY, fD, fM, fY);

   // Reset season selector and main page hidden data season variable.
   document.getElementById('r_tabPeriodSeasonSelectorId').selectedIndex = 0;
   document.getElementById('r_tabPeriodHiddenDataSeasonId').value = 'Select Season';

   updateR_tabPeriodCheckbox();

   initSelectedS_tab();
}

/*
 *
 */
function setMainPageHiddenDataR_tabPeriodVars(sD, sM, sY, fD, fM, fY)
{
   // Set main page hidden data period variables.
   document.getElementById('r_tabPeriodHiddenDataStartDayId'   ).value = sD;
   document.getElementById('r_tabPeriodHiddenDataStartMonthId' ).value = sM;
   document.getElementById('r_tabPeriodHiddenDataStartYearId'  ).value = sY;
   document.getElementById('r_tabPeriodHiddenDataFinishDayId'  ).value = fD;
   document.getElementById('r_tabPeriodHiddenDataFinishMonthId').value = fM;
   document.getElementById('r_tabPeriodHiddenDataFinishYearId' ).value = fY;
}

/*
 * Check or uncheck the period tab checkbox depending on
 * whether the period tab HTML elements are in their default state.
 */
function updateR_tabPeriodCheckbox()
{
   if (r_tabPeriodElemsAreInDefaultState())
     uncheckR_tabCheckbox('Period');
   else
     checkR_tabCheckbox('Period');
}

/*
 *
 */
function r_tabPeriodElemsAreInDefaultState()
{
   var allAreDefault = true;

   // Test season selector, time range checkbox, and time range selectors.
   if (!(   document.getElementById('r_tabPeriodSeasonSelectorId'       ).selectedIndex == 0
         && document.getElementById('r_tabPeriodMatchTimeCheckboxId'    ).checked       == false
         && document.getElementById('r_tabPeriodStartHourSelectorId'    ).selectedIndex == 11
         && document.getElementById('r_tabPeriodStartMinuteSelectorId'  ).selectedIndex == 0
         && document.getElementById('r_tabPeriodStartAMorPMSelectorId'  ).selectedIndex == 0
         && document.getElementById('r_tabPeriodFinishHourSelectorId'   ).selectedIndex == 10
         && document.getElementById('r_tabPeriodFinishMinuteSelectorId' ).selectedIndex == 59
         && document.getElementById('r_tabPeriodFinishAMorPMSelectorId' ).selectedIndex == 1    ))
     allAreDefault = false;

   // Get default start and finish dates from global array.
   var n = N_MATCHES - 1;
   var defSd = MATCHES_ARRAY[0]['dateArray'][0];
   var defSm = MATCHES_ARRAY[0]['dateArray'][1];
   var defSy = MATCHES_ARRAY[0]['dateArray'][2];
   var defFd = MATCHES_ARRAY[n]['dateArray'][0];
   var defFm = MATCHES_ARRAY[n]['dateArray'][1];
   var defFy = MATCHES_ARRAY[n]['dateArray'][2];

   // Get period selector elements.
   var elemSd = document.getElementById('r_tabPeriodStartDaySelectorId'   );
   var elemSm = document.getElementById('r_tabPeriodStartMonthSelectorId' );
   var elemSy = document.getElementById('r_tabPeriodStartYearSelectorId'  );
   var elemFd = document.getElementById('r_tabPeriodFinishDaySelectorId'  );
   var elemFm = document.getElementById('r_tabPeriodFinishMonthSelectorId');
   var elemFy = document.getElementById('r_tabPeriodFinishYearSelectorId' );

   // Test period selectors.
   if (!(   elemSd.options[elemSd.selectedIndex].text == defSd
         && elemSm.options[elemSm.selectedIndex].text == getMonthAbbrev(defSm)
         && elemSy.options[elemSy.selectedIndex].text == defSy
         && elemFd.options[elemFd.selectedIndex].text == defFd
         && elemFm.options[elemFm.selectedIndex].text == getMonthAbbrev(defFm)
         && elemFy.options[elemFy.selectedIndex].text == defFy                ))
     allAreDefault = false;

   return allAreDefault;
}

/*******************************************END*OF*FILE********************************************/
