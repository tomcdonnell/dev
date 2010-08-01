<?php
 session_start();

 // NOTE: Since this file is required directly or indirectly by all other .php files,
 //       this is the only file where it is necessary to start a session.
 //       For the same reason, this is the only file where it is necessary to set
 //       error_reporting(E_ALL);

 error_reporting(E_ALL);          // PROBLEM: These lines do not cause error messages
 ini_set('display_errors', true); //          to be displayed in the browser window.

 /*
  *
  */
 function echoDoctypeXHTMLstrictString()
 {
    echo  '<!DOCTYPE html PUBLIC' . "\n"
        . ' "-//W3C//DTD XHTML 1.0 Strict//EN"' . "\n"
        . ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
 }

 /*
  *
  */
 function echoDoctypeXHTMLframesetString()
 {
    echo  '<!DOCTYPE html PUBLIC' . "\n"
        . ' "-//W3C//DTD XHTML 1.0 Frameset//EN"' . "\n"
        . ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">' . "\n";
 }

 /*
  *
  */
 function echoDoctypeXHTMLtransitionalString()
 {
    echo  '<!DOCTYPE html PUBLIC' . "\n"
        . ' "-//W3C//DTD XHTML 1.0 Transitional//EN"' . "\n"
        . ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
 }

 /*
  *
  */
 function debugMsg($msg)
 {
    if ($_SESSION['debug'])
      echo "<p>DEBUG MESSAGE: {$msg}</p>\n";
 }

 /*
  *
  */
 function warning($warnMsg)
 {
    if ($_SESSION['debug'])
      echo "<p>WARNING: {$warnMsg}</p>\n";
 }

 /*
  *
  */
 function error($errMsg)
 {
    if ($_SESSION['debug'])
      echo "<p>ERROR: {$errMsg}</p>\n";

    exit();
 }

 /*
  *
  */
 function errorRequiringMySQLrollback($errMsg)
 {
    if ($_SESSION['debug'])
      echo "<p>ERROR: {$errMsg}</p>\n";

    rollbackMySQLtransaction();

    exit();
 }

 /*
  *
  */
 function MySQLerror($errMsg, $MySQLerrNo, $MySQLerrMsg)
 {
    if ($_SESSION['debug'])
      echo   "<p>\n"
           , " MySQL ERROR: $errMsg<br />"
           , " MySQL error number: $MySQLerrNo<br />"
           , " MySQL error message: $MySQLerrMsg\n"
           , "</p>\n";

    exit();
 }

 /*
  *
  */
 function MySQLerrorRequiringMySQLrollback($errMsg, $MySQLerrNo, $MySQLerrMsg)
 {
    if ($_SESSION['debug'])
      echo   "<p>\n"
           , " MySQL ERROR: $errMsg<br />"
           , " MySQL error number: $MySQLerrNo<br />"
           , " MySQL error message: $MySQLerrMsg\n"
           , "</p>\n";

    rollbackMySQLtransaction();

    exit();
 }

 /*
  * Function to rollback a series of MySQL transactions
  * that begun with the MySQL command 'start transaction'.
  */
 function rollbackMySQLtransaction()
 {
    // rollback transaction
    if (!mysql_query('rollback'))
      MySQLerror(  'Could not rollback transaction in'
                 . " {$_SERVER['PHP_SELF']}::rollbackMySQLtransaction().",
                 mysql_errno(), mysql_error()                             );
    else
      debugMsg('Transaction rolled back OK.');
 }

 /*
  *
  */
 function is_integerString($var)
 {
    if (ctype_digit($var))
      return true;
    else
    {
       if ($var[0] == '-')
       {
          $var[0] = '0';

          if (ctype_digit($var))
            return true;
          else
            return false;
       }
       else
         return false;
    }
 }

 /*
  * Convert integer sub-string of $str starting at
  * $startIndex and finishing at $finishIndex to an integer.
  */
 function strToInt($str, $startIndex, $finishIndex)
 {
    $numStr = '';
    for ($i = $startIndex; $i <= $finishIndex; $i++)
      $numStr .= $str[$i];

    if (ctype_digit($numStr))
      return intval($numStr);
    else
      error(  'Attempted to convert non integer string'
            . " '$numStr' to integer in {$_SERVER['PHP_SELF']}::strToInt().");
 }

 /*
  *
  */
 function convMySQLdateStringToIntArray($dateString)
 {
    $dateIntArray = array(3);

    $dateIntArray['day'  ] = strToInt($dateString, 8, 9);
    $dateIntArray['month'] = strToInt($dateString, 5, 6);
    $dateIntArray['year' ] = strToInt($dateString, 0, 3);

    return $dateIntArray;
 }

 /*
  * Remove a bracketed substring from a given string and return the remainder.
  * Eg. string: "Tom McDonnell (94 matches)"
  *     becomes "Tom McDonnell"
  * NOTE: This function assumes:
  *        * That a bracketed substring exists at the end of the given string.
  *        * That the substring to be removed is at least 10 characters
  *          (for the case "... (1 match)")
  */
 function removeBracketedExpFromString($str)
 {
    // Steps:
    //   1: Look for '(' starting 9 chars from end of string.
    //   2: Return the substring beginning at start and ending 2 chars before '('.

    $len = strlen($str);

    // test whether '$str' is too short
    if ($len < 9)
      error("String too short in {$_SERVER['PHP_SELF']}::removeBracketedExpFromString().");

    // Step 1
    $i = $len - 9;
    while ($i >= 0 && $str[$i] != '(')
      $i--;

    // test whether '(' was found
    if ($i < 0)
      error("Character '(' not found in {$_SERVER['PHP_SELF']}::removeBracketedExpFromString().");

    // Step 2
    return substr($str, 0, $i - 1);
 }

 /*
  *
  */
 function genericSelector($id, $indent, $selectedIndex,
                          $onChangeFunction, $optionsArray, $defaultString)
 {
    // <select ...>
    echo $indent , "<select id=\"$id\" name=\"$id\"";
    if ($onChangeFunction != -1)
      echo " onChange=\"$onChangeFunction\"";
    echo ">\n";

    // default option string (if provided)
    if ($defaultString != -1)
    {
       echo $indent , ' <option';
       if ($selectedIndex == -1 || $selectedIndex == 0)
         echo ' selected';
       echo ">$defaultString</option>\n";

       $start = 1;
    }
    else
      $start = 0;

    // rest of options
    $i = $start;
    foreach ($optionsArray as $value)
    {
       echo $indent , ' <option';
       if ($i++ == $selectedIndex)
         echo ' selected';
       echo '>' , $value , "</option>\n";
    }

    // </select>
    echo $indent , "</select>\n";
 }

 /*
  * Create and return a text string of the words in array '$wordsList', with correct grammer.
  * Eg.  If $wordsList = ('one', 'two', 'three', 'four'),
  *      Function returns 'one, two, three, and four'
  */
 function createTextListWithCorrectGrammer($wordsList)
 {
    $count = count($wordsList);

    if ($count == 0)
      error(  "Function {$_SERVER['PHP_SELF']}::createTextListWithCorrectGrammer($wordsList)"
            . ' was called with an array of size 0.'                                         );

    $str = $wordsList[0];
    for ($pos = 1; $pos < $count; ++$pos)
    {
       // NOTE: If there are only two words, no comma is required.
       //       Eg. "one and two" as opposed to "one, two, and three".
       $str .= (($pos < $count - 1)? ', ': (($count > 2)? ', and ': ' and ')) . $wordsList[$pos];
    }

    return $str;
 }

 /*
  * Given an array containing only 0s and 1s, Create and return a text string
  * describing the indices of the array '$array' whose corresponding value is 1.
  * NOTE: For the purposes of this function, array indices start at 1.
  * Eg.  If $array = (0, 1, 1, 1, 1, 0, 1, 1, 0, 1)
  *      Function returns '2-5, 7, 8, and 10'
  */
 function createTextIndicesExpWithCorrectGrammer($array)
 {
    $count = count($array); // No. of elements in array.

    if ($count == 0)
      error(  "Function {$_SERVER['PHP_SELF']}::createTextListWithCorrectGrammer($wordsList)"
            . ' was called with an array of size 0.'                                         );

    $str = '';
    $inSeries = false;
    $n = 0;
    $n_checked = 0;
    while ($n < $count)
    {
       if ($array[$n])
       {
          // Element $n + 1 is checked.
          ++$n_checked;

          if ($inSeries)
            ++$n;
          else
          {
             // Element $n + 1 is not in the middle or end of a series (it may be at the start).

             if ($str != '')
               $str .= ', ';

            $str .= $n + 1;

             if ($n <= $count - 3 && $array[$n + 1] && $array[$n + 2])
             {
                // The next two elements are also checked (elements $n + 2 and $n + 3),
                // so element $n + 1 is the start of a series.

                $inSeries = true;
                $str .= '-';
                $n_checked += 2; // Increment n_counted (1st in series has already been counted).
                $n += 3; // Proceed to next unknown element.
             }
             else
               // Element $n + 1 is not any part of a series.
               ++$n;
          } 
       }
       else
       {
          // Element $n + 1 is not checked.

          if ($inSeries)
          {
             // The previous element (element $n) was the last of a series.
             $inSeries = false;
             $str .= $n;
          }
          ++$n;
       }
    }
    if ($inSeries)
      $str .= $n;

    // Insert the word 'and' into $str before the last element.
    // NOTE: If there are only two elements, no comma is required.
    //       Eg. "1 and 2" as opposed to "1, 2, and 3".
    for ($i = strlen($str) - 1; $i > 0 && $str[$i] != ','; --$i);
    if ($i > 0)
    {
       if ($n_checked > 2)
         // Insert the string ' and' after the last comma in $str.
         $str = substr_replace($str, ' and', $i + 1, 0);
       else
         // Replace the last comma in $str with the string ' and'.
         $str = substr_replace($str, ' and', $i, 1);
    }

    return $str;
 }

 /*
  * Convert numeric string to whole number if exact or round to 3 digits if not.
  */
 function convToIntOr3DigitDec($numStr)
 {
    if (is_numeric($numStr))
    {
       if ((int)$numStr != 0)
       {
          if ((float)$numStr / (int)$numStr == 1)
            return (int)$numStr;
          else
            return sprintf('%.3f', $numStr);
       }
       else
         return ((float)$numStr == 0)? 0: sprintf('%.3f', $numStr);
    }
    else
      error(  "Expected numeric string, received '$numStr'"
            . "' in {$_SERVER['PHP_SELF']}::convToIntOr3DigitDec().");
 }


 /*
  *
  */
 function getTextExp($number)
 {
    switch ((int)$number)
    {
     case 1: return 'Once';
     case 2: return 'Twice';
     default: return (getIntAsWords($number) . ' times');
    }
 }

 /*
  *
  */
 function getIntAsWords($integer)
 {
    switch ($integer)
    {
     case  1: return 'One';
     case  2: return 'Two';
     case  3: return 'Three';
     case  4: return 'Four';
     case  5: return 'Five';
     case  6: return 'Six';
     case  7: return 'Seven';
     case  8: return 'Eight';
     case  9: return 'Nine';
     case 10: return 'Ten';
     case 11: return 'Eleven';
     case 12: return 'Twelve';
     case 13: return 'Thirteen';
     case 14: return 'Fourteen';
     case 15: return 'Fifteen';
     case 16: return 'Sixteen';
     case 17: return 'Seventeen';
     case 18: return 'Eighteen';
     case 19: return 'Nineteen';
     case 20: return 'Twenty';
     default:
       if (is_int($number))
         return $number; // Numbers greater than twenty are not converted to words.
       else
         error("Non integer recieved in {$_SERVER['PHP_SELF']}::getIntAsWords().");
    }
 }
?>
