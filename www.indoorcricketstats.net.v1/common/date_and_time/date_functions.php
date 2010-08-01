<?php
 require_once dirname(__FILE__) . '/../misc/misc_functions.php';

 /*
  *
  */
 function getMonthOneLetterAbbrev($month)
 {
    switch ($month)
    {
     case  1: return 'J';
     case  2: return 'F';
     case  3: return 'M';
     case  4: return 'A';
     case  5: return 'M';
     case  6: return 'J';
     case  7: return 'J';
     case  8: return 'A';
     case  9: return 'S';
     case 10: return 'O';
     case 11: return 'N';
     case 12: return 'D';
     default:
       error(  "Invalid month number '$month' received in"
             . " {$_SERVER['PHP_SELF']}::getMonthOneLetterAbbrev().");
       break;
    }
 }

 /*
  *
  */
 function getMonthThreeLetterAbbrev($month)
 {
    switch ($month)
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
     default:
       error(  "Invalid month number '$month' received in"
             . " {$_SERVER['PHP_SELF']}::getMonthThreeLetterAbbrev().");
       break;
    }
 }

 /*
  *
  */
 function getMonthName($month)
 {
    switch ($month)
    {
     case  1: return 'January';
     case  2: return 'February';
     case  3: return 'March';
     case  4: return 'April';
     case  5: return 'May';
     case  6: return 'June';
     case  7: return 'July';
     case  8: return 'August';
     case  9: return 'September';
     case 10: return 'October';
     case 11: return 'November';
     case 12: return 'December';
     default:
       error(  "Invalid month number '$month' received in"
             . " {$_SERVER['PHP_SELF']}::getMonthName()." );
       break;
    }
 }

 /*
  * Print a date as text eg. "the 21st of June 2006".
  */
 function printDateString($day, $month, $year)
 {
    echo 'the ' , $day;
    switch ($day % 10)
    {
     case 1:  echo 'st'; break;
     case 2:  echo 'nd'; break;
     case 3:  echo 'rd'; break;
     default: echo 'th'; break;
    }
    echo ' of ', getMonthName($month), ' ', $year;
 }

 /*
  * Print a time as text eg. "7:30 pm".
  */
 function printTimeString($hour, $minute, $am_pm)
 {
    echo $hour , ':', $minute , ' ';

    switch ($am_pm)
    {
     case 'AM': case 'am': echo 'am'; break;
     case 'PM': case 'pm': echo 'pm'; break;
     default: error(  "Expected ('AM' or 'am' or 'PM' or 'pm'),"
                    . "recieved '$am_pm' in {$_SERVER['PHP_SELF']}.\n");
    }
 }

 /*
  * Create date selector inside an existing HTML table with separate selectors for
  * day, month, and year on the same row (row starts and ends not included).
  * Uses $_SESSION['maxYear'] and $_SESSION['minYear'] for range of year selector.
  * $defaultDate is an array with three keys: 'day', 'month', and 'year'; each holding an integer.
  * An onChange function may be supplied for each of the three selectors.
  * If any of the onChangeFunctions == 'default', a default onChange function will be used.
  * The default onChangeFunctions are:
  *   (no function for day)
  *   'updateDateSelectorsMonth(daySelectorId, monthSelectorId, yearSelectorId)',
  *   'updateDateSelectorsYear(daySelectorId, monthSelectorId, yearSelectorId)'.
  *   (all defined in file 'date_functions.js')
  *   These functions disable options for dates that do not exist (eg. feb 29 in non-leap years).
  */
 function dateSelector($indent, $defaultDate,
                       $daySelectorId, $monthSelectorId, $yearSelectorId,
                       $dayTdClass, $monthTdClass, $yearTdClass,
                       $onChangeDayFunction, $onChangeMonthFunction, $onChangeYearFunction)
 {
    if ($onChangeDayFunction == 'default')
      // Use default onChangeFunction.
      //   In normal circumstances, changing selected day does not require
      //   calling an onChange function.  This onChange function is only useful
      //   for special purpose date selectors (Eg. period selector)
      $onChangeDayFunction = '';

    if ($onChangeMonthFunction == 'default')
      // Use default onChangeFunction.
      //   Changing the month requires an onChange function to avoid
      //   allowing non-existant dates to be selected since months have
      //   different numbers of days.
      $onChangeMonthFunction
        = "onChangeMonth($daySelectorId, $monthSelectorId, $yearSelectorId)";

    if ($onChangeMonthFunction == 'default')
      // Use default onChangeFunction.
      //   Changing the month requires an onChange function to avoid
      //   allowing non-existant dates to be selected since in leap years,
      //   february has 29 days, not 28.
      $onChangeYearFunction
        = "onChangeYear($daySelectorId, $monthSelectorId, $yearSelectorId)";

    // Test whether $_SESSION['minYear'] and $_SESSION['maxYear'] are defined.
    // If either/both are not, set them to sensible values as required.
    $test = 0;
    if (isset($_SESSION['minYear'])) $test  =  1;
    if (isset($_SESSION['maxYear'])) $test += 10;
    switch ($test)
    {
     case 0:
       warning("\$_SESSION['minYear'] and \$_SESSION['maxYear'] not defined.  2000 and 2020 used.");
       $_SESSION['minYear'] = 2000;
       $_SESSION['maxYear'] = 2020;
       break;
     case 1:
       warning("\$_SESSION['minYear'] not defined.  \$_SESSION['maxYear'] - 20 used.");
       $_SESSION['minYear'] = $_SESSION['maxYear'] - 20;
       break;
     case 10:
       warning("\$_SESSION['maxYear'] not defined.  \$_SESSION['minYear'] + 20 used.");
       $_SESSION['maxYear'] = $_SESSION['minYear'] + 20;
       break;
     default:
       // both are defined.  Do nothing
       break;
    }

    // day selector
    echo $indent , "<td class=\"$dayTdClass\">\n";
    echo $indent , " <select id=\"$daySelectorId\" name=\"$daySelectorId\""
                 , " onChange=\"$onChangeDayFunction\">\n";
    for ($i = 1; $i <= 31; $i++)
    {
       echo $indent , '  <option';
       if ($defaultDate['day'] == $i)
         echo ' selected';
       echo ">$i</option>\n";
    }
    echo $indent , " </select>\n";
    echo $indent , "</td>\n";

    // month selector
    $monthNamesArray = array(1 => 'Jan',  2 => 'Feb',  3 => 'Mar',  4 => 'Apr',
                             5 => 'May',  6 => 'Jun',  7 => 'Jul',  8 => 'Aug',
                             9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );
    echo $indent , "<td class=\"$monthTdClass\">\n";
    echo $indent , " <select id=\"$monthSelectorId\" name=\"$monthSelectorId\""
                 , " onChange=\"$onChangeMonthFunction\">\n";
    for ($i = 1; $i <= 12; $i++)
    {
       echo $indent , '  <option';
       if ($defaultDate['month'] == $i)
         echo ' selected';
       echo ">$monthNamesArray[$i]</option>\n";
    }
    echo $indent , " </select>\n";
    echo $indent , "</td>\n";

    // year selector
    echo $indent , "<td class=\"$yearTdClass\">\n";
    echo $indent , " <select id=\"$yearSelectorId\" name=\"$yearSelectorId\""
                 , " onChange=\"$onChangeYearFunction\">\n";
    for ($i = $_SESSION['minYear']; $i <= $_SESSION['maxYear']; $i++)
    {
       echo $indent , '  <option';
       if ($defaultDate['year'] == $i)
         echo ' selected';
       echo ">$i</option>\n";
    }
    echo $indent , " </select>\n";
    echo $indent , "</td>\n";
 }

 /*
  * Create two date selectors inside an existing HTML table with separate selectors for
  * day, month, and year on the same row (row starts and ends not included).
  * If $restrictToDefaultPeriod (bool) is true, will disable options that would
  * result in a date outside the default period [$defaultStartDate, $defaultFinishDate].
  * $defaultDate is an array with three keys: 'day', 'month', and 'year'; each holding an integer.
  * NOTE: The javascript onChange function (onChangePeriod()) (named in this PHP function
  *       and defined in "dateFunctions.js") will call another javascript function:
  *       "additionalOnchangePeriod()".  This additional function is not defined in
  *       "dateFunctions.js" and should be supplied by the user of this date function library
  *       ("dateFunctions.php" & "dateFunctions.js").  The purpose of the additional function is to
  *       automate any application specific dependencies on the period selector.
  *       A function pointer supplied to this function would have been more ideal, but there are
  *       no function pointers in javascript.  So if there are no application specific dependencies
  *       on the period selector, an empty function must still be supplied.
  */
 function periodSelector($indent,
                         $defaultStartDate, $defaultFinishDate, $restrictToDefaultPeriod,
                         $startDaySelectorId,  $startMonthSelectorId,  $startYearSelectorId,
                         $finishDaySelectorId, $finishMonthSelectorId, $finishYearSelectorId)
 {
    // common start to all onChange function names to be defined
    $commonStartOfFunction = "onChangePeriod(";

    // common end to all onChange function names to be defined

    $commonEndOfFunction
     =  "'$restrictToDefaultPeriod', "
      . "{$defaultStartDate['day']}, {$defaultStartDate['month']}, {$defaultStartDate['year']}, "
      . "{$defaultFinishDate['day']}, {$defaultFinishDate['month']}, {$defaultFinishDate['year']}, "
      . "$startDaySelectorId, $startMonthSelectorId, $startYearSelectorId, "
      . "$finishDaySelectorId, $finishMonthSelectorId, $finishYearSelectorId)";

    // onChange function names (sdf = startDayFunction, fmy = finishYearFunction, etc.)
    $sdf = $commonStartOfFunction . "'start', 'day', "    . $commonEndOfFunction;
    $smf = $commonStartOfFunction . "'start', 'month', "  . $commonEndOfFunction;
    $syf = $commonStartOfFunction . "'start', 'year', "   . $commonEndOfFunction;
    $fdf = $commonStartOfFunction . "'finish', 'day', "   . $commonEndOfFunction;
    $fmf = $commonStartOfFunction . "'finish', 'month', " . $commonEndOfFunction;
    $fyf = $commonStartOfFunction . "'finish', 'year', "  . $commonEndOfFunction;

    // period start date selector
    dateSelector($indent, $defaultStartDate,
                 $startDaySelectorId, $startMonthSelectorId, $startYearSelectorId,
                 $sdf, $smf, $syf                                                 );

    // period finish date selector
    dateSelector($indent, $defaultFinishDate,
                 $finishDaySelectorId, $finishMonthSelectorId, $finishYearSelectorId,
                 $fdf, $fmf, $fyf                                                    );
 }
?>
