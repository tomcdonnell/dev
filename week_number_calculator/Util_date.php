<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "Util_date.php"
*
* Project: Library functions.
*
* Purpose: Functions for creating HTML date selectors and other miscellaneous date functions.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 * Namespace Util_date (contains only static functions).
 */
class Util_date
{
   /*
    *
    */
   public function __construct()
   {
      throw new Exception('This class is not intended to be instantiated.');
   }

   /*
    * @return {array}
    *   array(<int weekNo>, <int year>)
    *   Week number and year in which supplied $timeUnix falls.  Range of weekNo is [1, 53].
    *   If the day of $timeUnix is before the first $firstDayOfWeek of the year, then the weekNo
    *   is the last week of the previous year.
    *
    * @param $timeUnix {unix timestamp}
    *
    * @param $firstDayOfWeek {int}
    *    0 = Sunday, 1 = Monday, ...etc.
    */
   public static function getWeekNoYear($timeUnix, $firstDayOfWeek)
   {
// NOTE
// ----
// This effort was abondoned because the PHP date() function seems to give inconsistant results
// when queried for the week number (weeks starting Monday).  For example, in the transition from
// 1969 to 1970, week 1 starts on Monday 1969-12-29, but in the transition from 1970 to 1971, week
// 1 starts on Monday 1971-01-04 (why not Monday 1970-12-28?). 
      $year                 = (int)date('Y', $timeUnix);
      $month                = (int)date('n', $timeUnix);
      $dayOfWeek            = (int)date('w', $timeUnix);
      $dayOfYear            = (int)date('d', $timeUnix);
      $weekNoStartingMonday = (int)date('W', $timeUnix);
      $dayOfWeek01Jan       = (int)date('w', mktime(0, 0, 0, 1, 1, $year));

return array($weekNoStartingMonday, $year);

      switch ($firstDayOfWeek)
      {
       case 0: // Sunday.
         $increment = ($firstDayOfWeek == $dayOfWeek)? 1: 0;
         break;
       case 1: // Monday.
         $increment = 0;
         break;
       case 2: case 3: case 4: case 5: case 6: // All other days.
         $increment = (1 <= $dayOfWeek && $dayOfWeek < $firstDayOfWeek)? -1: 0;
         break;
       default:
         throw new Exception("Unknown day of week '$dayOfWeek'.");
      }

      if ($dayOfWeek01Jan == $firstDayOfWeek)
      {
         ++$increment;
      }

      $weekNoStartingRequestedDay = $weekNoStartingMonday + $increment;

      if ($weekNoStartingRequestedDay > 0 && !($weekNoStartingRequestedDay > 50 && $month == 1))
      {
         return array($weekNoStartingRequestedDay, $year);
      }

      list($weekNo31DecPrevYear, $prevYear) =
      (
         self::getWeekNoYear(mktime(0, 0, 0, 12, 31, $year - 1), $firstDayOfWeek)
      );

      switch ($weekNoStartingRequestedDay)
      {
       case 0:
         $weekNoStartingRequestedDay =
         (
            ($firstDayOfWeek == $dayOfWeek01Jan)? 1: $weekNo31DecPrevYear
         );
         break;
       case 51: case 52: case 53: case 54:
         if ($weekNoStartingRequestedDay != $weekNo31DecPrevYear)
         {
            $weekNoStartingRequestedDay = 1;
         }
         break;
       default:
         throw new Exception("Unexpected week number $weekNoStartingRequestedDay'.");
      }

      return array($weekNoStartingRequestedDay, $year);
   }

   // Class constants. //////////////////////////////////////////////////////////////////////////

   const N_SECONDS_IN_ONE_DAY  =  86400;
   const N_SECONDS_IN_ONE_WEEK = 604800;
}

/******************************************END*OF*FILE*********************************************/
?>
