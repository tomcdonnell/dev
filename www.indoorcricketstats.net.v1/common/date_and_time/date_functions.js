/**************************************************************************************************\
*
* Filename: "icdb_date_functions.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts relating to date and time.
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

function error(msg) {alert('Error: ' + msg);}

/*
 *
 */
function getMonthAbbrev(m)
{
   switch (m)
   {
    case  1: return 'Jan';
    case  2: return 'Feb';
    case  3: return 'Mar';
    case  4: return 'Apr';
    case  5: return 'May';
    case  6: return 'Jun';
    case  7: return 'Jul';
    case  8: return 'Aug';
    case  9: return 'Sep';
    case 10: return 'Oct';
    case 11: return 'Nov';
    case 12: return 'Dec';
   }
}

/*
 * Return true if year is leap year, false otherwise.
 */
function isLeapYear(y)
{
   return (y % 4 == 0 && !((y % 100 == 0) && (y % 400 != 0)));
}

/*
 * If A < B return -1
 * If A = B return  0
 * If A > B return +1
 *
 * NOTE: Casts to number are done to avoid problems when numeric strings are compared.
 *       Eg. ('7' < '11') will evaluate to false.
 */
function compareDates(dA, mA, yA, dB, mB, yB)
{
   return cmp =
   (
      (yA == yB)?
      (
         (mA == mB)?
         (
            (dA == dB)?
            0:
            ((Number(dA) > Number(dB))? 1: -1)
         ):
         ((Number(mA) > Number(mB))? 1: -1)
      ):
      ((Number(yA) > Number(yB))? 1: -1)
   );
}

/*
 * If A < B return -1
 * If A = B return  0
 * If A > B return +1
 *
 * NOTE: 24 hour format must be used.
 *
 * NOTE: Casts to number are done to avoid problems when numeric strings are compared.
 *       Eg. ('7' < '11') will evaluate to false.
 */
function compareTimes(hA, mA, hB, mB)
{
   return cmp =
   (
      (hA == hB)?
      (
         (mA == mB)?
         0:
         ((Number(mA) > Number(mB))? 1: -1)
      ):
      ((Number(hA) > Number(hB))? 1: -1)
   );
}

/*
 * Same as above, but with dates stored in arrays ([0] = day, [1] = month, [2] = year)
 */
function compareDateArrays(dA, dB)
{
   return compareDates
   (
      dA[0], dA[1], dA[2],
      dB[0], dB[1], dB[2]
   );
}

/*
 * Enable/disable day selector options for days 29-31
 * (indices 28-30) depending on whether year is leap year.
 */
function setDayOptionsWhenMonthIsFeb(year, daySelector)
{
   var isLeapYear  = isLeapYear(year);
   var maxDayIndex = (isLeapYear)? 28: 27;

   daySelector.options[30].disabled = true;
   daySelector.options[29].disabled = true;
   daySelector.options[28].disabled = isLeapYear;

   if (daySelector.selectedIndex > maxDayIndex)
   {
      daySelector.selectedIndex = maxDayIndex;
   }
}

/*
 * Disable/enable day options in day selector in response to change of selected year.
 */
function onChangeYear(dSelector, mSelector, ySelector)
{
   if (mSelector.selectedIndex == 1)
   {
      setDayOptionsWhenMonthIsFeb(ySelector.options[ySelector.selectedIndex].text, dSelector);
   }
}

/*
 *
 */
function onChangeMonth(dSelector, mSelector, ySelector)
{
   disableNonExistentDates(dSelector, mSelector, ySelector);
}

/*
 *
 */
function disableNonExistentDates(dSelector, mSelector, ySelector)
{
   // NOTE: Indices start at zero, so January is zero, February one etc.
   switch (mSelector.selectedIndex)
   {
    // Feruary (28 days, except in leapyears in which case 29) 
    case 1:
      setDayOptionsWhenMonthIsFeb(ySelector.options[ySelector.selectedIndex].text, dSelector);
      break;

    // Months with 30 days.
    case 3: case 5: case 8: case 10:
      dSelector.options[28].disabled = false;
      dSelector.options[29].disabled = false;
      dSelector.options[30].disabled = true;

      if (dSelector.selectedIndex > 29)
      {
         daySelectElem.selectedIndex = 29;
      }

      break;

    // Months with 31 days.
    case 0: case 2: case 4: case 6: case 7: case 9: case 11:
      dSelector.options[28].disabled = false;
      dSelector.options[29].disabled = false;
      dSelector.options[30].disabled = false;
      break;
   }
}

/*
 * Set date selectedIndices to a particular date, and disable
 * options that if selected would lead to a non-existent date.
 */
function setDateSelectedIndices(d, m, y, dSelector, mSelector, ySelector)
{
   dSelector.selectedIndex = d - 1;
   mSelector.selectedIndex = m - 1;
   ySelector.selectedIndex = y - ySelector.options[0].text;

   disableNonExistentDates(dSelector, mSelector, ySelector);
}

/*
 *
 */
function onChangePeriod
(
   startORfinish, dayORmonthORyear,
   sdSelector, smSelector, sySelector,
   fdSelector, fmSelector, fySelector
)
{
   var sd = sdSelector.selectedIndex + 1;
   var sm = smSelector.selectedIndex + 1;
   var sy = sySelector.options[sySelector.selectedIndex].text;
   var fd = fdSelector.selectedIndex + 1;
   var fm = fmSelector.selectedIndex + 1;
   var fy = fySelector.options[fySelector.selectedIndex].text;

   // Ensure that the start date is before or equal to the finish date.
   if (compareDates(sd, sm, sy, fd, fm, fy) > 0)
   {
      // The start date is after the finish date.

      switch (startORfinish)
      {
       case 'start':
         // The start date was changed last, so change the finish date to equal the start.
         fdSelector.selectedIndex = sdSelector.selectedIndex;
         fmSelector.selectedIndex = smSelector.selectedIndex;
         fySelector.selectedIndex = sySelector.selectedIndex;
         break;
       case 'finish':
         // The finish date was changed last, so change the start date to equal the finish.
         startDaySelectElem.selectedIndex   = finishDaySelectElem.selectedIndex;
         startMonthSelectElem.selectedIndex = finishMonthSelectElem.selectedIndex;
         startYearSelectElem.selectedIndex  = finishYearSelectElem.selectedIndex;
         break;
       default: error("Expected 'Start' or 'Finish' in onChangePeriod() (2).");
      }
   }

   // disable non-existent dates (eg. Feb 29 in non-leap years)
   switch (StartOrFinish)
   {
    case 'Start':
      switch (dayMonthOrYear)
      {
       case 'day': break;
       case 'month': case 'year':
         disableNonExistentDates(startDaySelectElem, startMonthSelectElem, startYearSelectElem);
         break;
       default: error("Expected 'day', 'month', or 'year' in onChangePeriod() (1).");
      }
      break;
    case 'Finish':
      switch (dayMonthOrYear)
      {
       case 'day': break;
       case 'month': case 'year':
         disableNonExistentDates(finishDaySelectElem, finishMonthSelectElem, finishYearSelectElem);
         break;
       default: error("Expected 'day', 'month', or 'year' in onChangePeriod() (2).");
      }
      break;
    default: error("Expected 'Start' or 'Finish' in onChangePeriod() (1).");
   }

   // call additional onChangePeriod()
   additionalOnChangePeriod();
}

/*******************************************END*OF*FILE********************************************/
