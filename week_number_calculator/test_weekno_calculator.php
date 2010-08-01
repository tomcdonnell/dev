<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "test_weekno_calculator.php"
*
* Project: Test.
*
* Purpose: Test functions dealing with week numbers.
*
* Author: Tom McDonnell 2009-11-17.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/Util_date.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   for ($y = 1970; $y < 2000; ++$y)
   {
      $yearStartUnix = mktime(0, 0, 0, 1, 1, $y);

      for ($d = -10; $d < 10; ++$d)
      {
         $dayStartUnix = $yearStartUnix + $d * Util_date::N_SECONDS_IN_ONE_DAY;

         echo date('Y-m-d     D', $dayStartUnix);

         for ($wd = 0; $wd < 7; ++$wd)
         {
            list($weekNo, $year) = Util_date::getWeekNoYear($dayStartUnix, $wd);
            printf('     %02d', $weekNo);
         }

         echo "\n";
      }

      echo "\n";
   }
}
catch (Exception $e)
{
   echo $e->getMessage(), "\n";
   exit(0);
}

/******************************************END*OF*FILE*********************************************/
?>
