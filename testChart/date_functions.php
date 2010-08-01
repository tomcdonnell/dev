<?php

require_once 'misc_functions.php';

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
 function getMonthName($monthNo)
 {
    switch ($monthNo)
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
       error(  "Invalid month number '$monthNo' received in"
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
?>
