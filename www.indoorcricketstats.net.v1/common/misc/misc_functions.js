/**************************************************************************************************\
*
* Filename: "misc_functions.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Miscellaneous Javascripts.
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

function error(msg) {alert("ERROR: " + msg);}

/*
 * Test whether character 'c' is a whitespace character.
 */
function isWhiteSpace(c)
{
   if (c == ' ' || c == '\f' || c == '\n' || c == '\r' || c == '\t' || c == '\v')
     return true;
   else
     return false;
}

/*
 *
 */
function convMySQLdateStringToIntArray(dateString)
{
   dateIntArray = new Array(3); // Contents: [0] day, [1] month, [2] year.

   dateIntArray[0] = Number(  dateString.charAt(8) + dateString.charAt(9)); // Day.
   dateIntArray[1] = Number(  dateString.charAt(5) + dateString.charAt(6)); // Month.
   dateIntArray[2] = Number(  dateString.charAt(0) + dateString.charAt(1)
                            + dateString.charAt(2) + dateString.charAt(3)); // Year.

   return dateIntArray;
}

/*
 *
 */
function convMySQLtimeStringToIntArray(timeString)
{
   timeIntArray = new Array(3); // Contents: [0] hour, [1] minute, [2] 'am' or 'pm'.

   var hour = Number(timeString.charAt(0) + timeString.charAt(1));

   // Convert 'hour' from 24 hour to 12 hour format.
   var amORpm = 'am';
   if (hour > 11) amORpm = 'pm'; // Set 'amORpm' (accounting for 12 midday being 12pm).
   if (hour > 12) hour  -=   12;

   timeIntArray[0] = hour
   timeIntArray[1] = Number(timeString.charAt(3) + timeString.charAt(4));
   timeIntArray[2] = amORpm;

   return timeIntArray;
}

/*
 * Return a string containing the last whitespace-separated word from the string 'str'.
 * If 'str' ends with a whitespace character, return an empty string.
 * If 'str' contains no whitespace characters, return 'str'.
 */
function getLastWord(str)
{
   var strLength = str.length;

   for (var i = strLength; !isWhiteSpace(str.charAt(i)) && i >= 0; --i);

   return str.substring(i + 1, strLength);
}

/*
 * Remove a bracketed substring from a given string and return the remainder.
 * Eg. string: "Tom McDonnell (94 matches)"
 *     becomes "Tom McDonnell"
 * NOTE: This function assumes:
 *        * That a bracketed substring exists at the end of the given string.
 *        * That the substring to be removed is at least 10 characters
 *          (for the case "... (1 match)")
 *                            |<------>|
 */
function removeBracketedExpFromString(str)
{
   // Steps:
   //   1: Look for '(' starting 9 chars from end of string.
   //   2: Return the substring beginning at start and ending 2 chars before '('.

   var len = str.length;

   // Test whether '$str' is too short.
   if (len < 9)
     error('String too short in removeBracketedExpFromString().');

   // Step 1.
   var i = len - 9;
   while (i >= 0 && str.charAt(i) != '(')
     i--;

   // Test whether '(' was found.
   if (i < 0)
     error('Character "(" not found in removeBracketedExpFromString().');

   // Step 2.
   return str.substring(0, i - 1);
}

/*******************************************END*OF*FILE********************************************/
