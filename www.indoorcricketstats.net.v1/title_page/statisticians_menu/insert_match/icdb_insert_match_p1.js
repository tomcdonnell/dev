/**************************************************************************************************\
*
* Filename: "icdb_insert_match_p1.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_insert_match_p1.php".
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

// GLOBAL VARIABLES ////////////////////////////////////////////////////////////////////////////////

var n_playersPerTeam = 8; // must match $_SESSION['n_playersPerTeam'] in PHP file

var selectOppTeamNameString = 'Select Opp. Team Name'; // must match value in PHP file
var enterOppTeamNameString  = 'Enter Opp. Team Name';
var selectPlayerNameString  = 'Select Player Name';    // must match value in PHP file
var enterPlayerNameString   = 'Enter Player Name';

var oppTeamNameTDisSelector;        // boolean
var oppTeamNameTDselectorInnerHTML; // string
var oppTeamNameTDtextInnerHTML;     // string

var playerNameTDisSelector        = new Array(n_playersPerTeam); // array of boolean
var playerNameTDselectorInnerHTML = new Array(n_playersPerTeam); // array of string
var playerNameTDtextInnerHTML     = new Array(n_playersPerTeam); // array of string

var existingOppTeamNamesArray;      // to be defined in init()
var existingPlayerNamesArray;       // to be defined in init()

var oldPlayerNameSelectedIndicesArray = new Array(n_playersPerTeam);

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

/*
 * Print error message if error in script.
 */
function handleErr(msg, url, l)
{
   var txt = "There was an error on this page.\n\n";
   txt += "Error: " + msg + "\n";
   txt += "URL: " + url + "\n";
   txt += "Line: " + l + "\n\n";
   txt += "Click OK to continue.\n\n";
   alert(txt);

   return true;
}

/*
 *
 */
function init()
{
   var i;   // counter
   var str; // temporary string variable

   // set date selector options in response to initial selection of today's date
   disableNonExistentDates(document.getElementById('daySelectorId'  ),
                           document.getElementById('monthSelectorId'),
                           document.getElementById('yearSelectorId' ) );

   // initialise oldPlayerNameSelectedIndicesArray
   for (i = 0; i < n_playersPerTeam; ++i)
     oldPlayerNameSelectedIndicesArray[i] = 0;

   // initialise oppTeamNameTD arrays
   oppTeamNameTDisSelector = true;
   oppTeamNameTDselectorInnerHTML = document.getElementById("oppTeamNameTD").innerHTML;
   str  = "<input type=\"text\" size=\"25\" maxLength=\"32\"";
   str += " value=\"" + enterOppTeamNameString + "\" id=\"oppTeamName\" name=\"oppTeamName\"";
   str += " onClick=\"clearOppTeamNameText()\" />";
   oppTeamNameTDtextInnerHTML = str;

   // initialise playerNameTD arrays
   for (i = 1; i <= n_playersPerTeam; ++i)
   {
      playerNameTDisSelector[i - 1] = true;
      playerNameTDselectorInnerHTML[i - 1]
        = document.getElementById("playerName" + i + "TD").innerHTML;

      (i < 7)? str = "": str = "* ";
      str += "<input type=\"text\"" + i + "\" size=\"19\" maxLength=\"24\"";
      str += " value=\"" + enterPlayerNameString + "\" id=\"playerName" + i + "\"";
      str += " name=\"playerName" + i + "\"";
      str += " onClick=\"clearPlayerNameText(" + i + ")\" />";
      (i < 7)? str += "": str += " *";

      playerNameTDtextInnerHTML[i - 1] = str;
   }

   // initialise existingOppTeamNamesArray
   existingOppTeamNamesArray = new Array(document.getElementById("oppTeamName").length);
   for (i = 0; i < existingOppTeamNamesArray.length; ++i)
     existingOppTeamNamesArray[i] = document.getElementById("oppTeamName").options[i].text;

   // initialise existingPlayerNamesArray
   existingPlayerNamesArray = new Array(document.getElementById("playerName1").length);
   for (i = 0; i < existingPlayerNamesArray.length; ++i)
     existingPlayerNamesArray[i] = document.getElementById("playerName1").options[i].text;
}

/*
 * Enable/disable select options for OTHER players
 * due to change of selection for player 'playerNo'.
 * The purpose is to ensure that a player can be selected in at most one position.
 */
function updateSelectPlayerNameOptions(playerNo)
{
   var i; // counter

   var newSelectedIndex = document.getElementById("playerName" + playerNo).selectedIndex
   var oldSelectedIndex = oldPlayerNameSelectedIndicesArray[playerNo - 1];

   // For each position:
   // * Enable  old selection in all other positions.
   // * Disable new selection in all other positions.
   for (i = 1; i <= n_playersPerTeam; ++i)
     if (i != playerNo && playerNameTDisSelector[i - 1])
     {
        var otherPlayer = document.getElementById("playerName" + i);

        if (oldSelectedIndex != 0) // 0 is index of instructions (availability should never change)
          otherPlayer.options[oldSelectedIndex].disabled = false; // enable  old

        if (newSelectedIndex != 0) // 0 is index of instructions (availability should never change)
          otherPlayer.options[newSelectedIndex].disabled = true;  // disable new
     }
     
   // new becomes old
   oldPlayerNameSelectedIndicesArray[playerNo - 1] = newSelectedIndex;
}

/*
 *
 */
function clearOppTeamNameText()
{
   var x = document.getElementById("oppTeamName");

   if (x.value === enterOppTeamNameString)
     x.value = ""; // PROBLEM: causes exception (see "Tools->JavaScript Console" on firefox browser)
}

/*
 *
 */
function clearPlayerNameText(playerNo)
{
   var x = document.getElementById("playerName" + playerNo);

   if (x.value === enterPlayerNameString)
     x.value = ""; // PROBLEM: causes exception (see "Tools->JavaScript Console" on firefox browser)
}

/*
 *
 */
function toggleOppTeamNameSelectorORtext()
{
   var x = document.getElementById("oppTeamNameTD");

   // swap oppTeamNameTDselectorInnerHTML with oppTeamNameTDtextInnerHTML
   if (oppTeamNameTDisSelector)
   {
      x.innerHTML = oppTeamNameTDtextInnerHTML;
      oppTeamNameTDisSelector = false;
   }
   else
   {
      x.innerHTML = oppTeamNameTDselectorInnerHTML;
      oppTeamNameTDisSelector = true;
   }
}

/*
 *
 */
function togglePlayerNameSelectorORtext(playerNo)
{
   var i; // counter
   var x = document.getElementById("playerName" + playerNo + "TD");

   // swap playerNameTDselectorInnerHTML[playerNo - 1] with playerNameTDtextInnerHTML[playerNo - 1]
   if (playerNameTDisSelector[playerNo - 1])
   {
      x.innerHTML = playerNameTDtextInnerHTML[playerNo - 1];
      playerNameTDisSelector[playerNo - 1] = false;

      // enable  other player's select options for playerNo's old selected player
      oldSelectedIndex = oldPlayerNameSelectedIndicesArray[playerNo - 1];
      if (oldSelectedIndex != 0)
        for (i = 1; i <= n_playersPerTeam; ++i)
          if (i != playerNo && playerNameTDisSelector[i - 1])
            document.getElementById("playerName" + i).options[oldSelectedIndex].disabled = false;
   }
   else
   {
      x.innerHTML = playerNameTDselectorInnerHTML[playerNo - 1];
      playerNameTDisSelector[playerNo - 1] = true;

      // disable playerNo's select options for other player's old selected players
      x = document.getElementById("playerName" + playerNo);
      var otherPlayerSelectedIndex;
      for (i = 1; i <= n_playersPerTeam; ++i)
      {
         otherPlayerSelectedIndex = document.getElementById("playerName" + i).selectedIndex;

         if (otherPlayerSelectedIndex != 0 && i != playerNo && playerNameTDisSelector[i - 1])
           x.options[otherPlayerSelectedIndex].disabled = true;
      }
   }
}

/*
 * Remove leading spaces, trailing spaces, and extra spaces between words from string str.
 * All whitespace characters are considered spaces for the purposes of this function.
 */
function removeExcessSpaces(str)
{
   // 1. Make a copy of 'str' called 'copy', and clear 'str'.
   // 2. Go through 'copy' character by character doing the following:
   //    If encounter characters other than whitespace, append to 'str'.
   //    If encounter space character (' ') following a non space character, append to 'str'.
   // 3. If last character of 'str' is now a space, remove this last character.

   // step 1
   var copy = str;
   str = "";

   // step 2
   var prevCharWasSpace = true;
   for (var i = 0; i < copy.length; ++i)
   {
      switch (copy[i] === ' ')
      {
       case false:
         str += copy[i];
         prevCharWasSpace = false;
         break;
       case true:
         if (!prevCharWasSpace)
           str += ' ';
         prevCharWasSpace = true;
         break;
      }
   }

   // step 3
   if (str[str.length - 1] === ' ')
     str = str.substring(0, str.length - 1);

   return str;
}

/*
 * Return the number of spaces in the string 'str'.
 */
function countSpaces(str)
{
   var n = 0;
   for (var i = 0; i < str.length; ++i)
     if (str[i] === ' ')
       ++n;

   return n;
}

/*
 * Test whether the form has been filled in correctly.
 * NOTES: * Team can play if short 1 or 2 players.
 *          Therefore player names 7 and/or 8 may be left blank.
 *        * Date and time are guaranteed to be correct (by implementation of date/time selectors).
 *          This means it is impossible to select an invalid date or time.
 * Need to ensure that:
 *   (0) All strings have no leading or trailing spaces,
         and consist of words separated by at most one space.
 *   (1) Opp. team name has been entered.
 *   (2) If opp. team name is entered as 'New',
 *       opp. team name is not in existing opp. team names list.
 * Need to ensure that first six player name strings:
 *   (3) Are not 'selectPlayerNameString' or 'enterPlayerNameString' or blank.
 *   (4) Consist of at most two words each.
 *   (5) If entered as 'New',
 *       are not in existing players names list,
 *       and are not identical to any other player names entered as 'New'.
 * Need to ensure that last two player name strings are:
 *   (6) Either ('selectPlayerNameString' or 'enterPlayerNameString' or blank)
 *           or (not repeated in any other name slot,
 *               and consist of at most two words each,
 *               and if entered as 'New',
 *                   are not in existing players list,
 *                   and are not identical to any other player names entered as 'New').
 */
function validate()
{
   var i;                  // counter
   var j;                  // counter
   var playerNameA;        // temp variable
   var playerNameB;        // temp variable
   var faultFound = false; // boolean

   var msg = "The form has been completed incorrectly.\n\n";

   // (0) Remove excess spaces for oppTeamName and playerName[1 - n_playersPerTeam]
   // (excess spaces are leading, trailing, and extras between words)
   if (!oppTeamNameTDisSelector)
     document.getElementById("oppTeamName").value
       = removeExcessSpaces(document.getElementById("oppTeamName").value);
   for (i = 1; i <= n_playersPerTeam; ++i)
     if (!playerNameTDisSelector[i - 1])
       document.getElementById("playerName" + i).value
         = removeExcessSpaces(document.getElementById("playerName" + i).value);

   // (1) Test oppTeamName for default and blank values.
   var oppTeamName = document.getElementById("oppTeamName").value;
   if (   oppTeamName === ""
       || oppTeamName === selectOppTeamNameString
       || oppTeamName === enterOppTeamNameString )
   {
      faultFound = true;
      msg += "An opposition team name must be selected or entered.\n";
   }

   // (2) Test that new oppTeamName is not in the existing oppTeamNames list.
   var oppTeamNameA;
   var oppTeamNameB;
   if (!oppTeamNameTDisSelector && !faultFound)
   {
      oppTeamNameA = document.getElementById("oppTeamName").value;
      for (i = 0; i < existingOppTeamNamesArray.length && !faultFound; ++i)
      {
         oppTeamNameB = existingOppTeamNamesArray[i];
         if (oppTeamNameA === oppTeamNameB)
         {
            faultFound = true;
            msg += "Opposition Team's names entered as new must not be in the existing opposition";
            msg += " teams list.\n";
            msg += "If a new opposition team happens to have the same name as an existing";
            msg += " opposition team, the new team's name must be altered in order to keep all";
            msg += " team's names unique."
         }
      }
   }

   // (3) Test playerName[1 - (n_playersPerTeam - 2)] for default and blank values
   for (i = 1; i <= n_playersPerTeam - 2 && !faultFound; ++i)
   {
      playerNameA = document.getElementById("playerName" + i).value;

      if (   playerNameA === ""
          || playerNameA === enterPlayerNameString
          || playerNameA === selectPlayerNameString)
      {
         faultFound = true;
         msg += "A player name must be selected or entered in each of the first ";
         msg += n_playersPerTeam - 2 + " positions.\n";
      }
   }

   // (4 & 6) Test playerName[1 - n_playersPerTeam] for more than 2 words.
   for (i = 1; i <= n_playersPerTeam && !faultFound; ++i)
   {
      playerNameA = document.getElementById("playerName" + i).value;

      if (i <= n_playersPerTeam - 2 || (   playerNameA != ""
                                        && playerNameA != enterPlayerNameString
                                        && playerNameA != selectPlayerNameString))
      {
         if (countSpaces(playerNameA) > 1)
         {
            faultFound = true;
            msg += "Player's names must consist of one or two words only.\n";
            msg += "Player " + i + " currently has more than two words in his/her name field.\n";
         }
      }
   }

   // (5 & 6) Test that new player names:
   //         (a) are not in existing player names list,
   //         (b) and are not identical to any other player names entered as 'New'.
   for (i = 1; i <= n_playersPerTeam && !faultFound; ++i)
   {
      if (!playerNameTDisSelector[i - 1])
      {
         playerNameA = document.getElementById("playerName" + i).value;

         for (j = 1; j <= existingPlayerNamesArray.length && !faultFound; ++j)
         {
            // (a)
            playerNameB = existingPlayerNamesArray[j - 1];
            if (playerNameA === playerNameB)
            {
               faultFound = true;
               msg += "Player's names entered as new must not be in the existing players list.\n";
               msg += "Player's name " + i + " is in the existing players list.\n\n";
               msg += "If a new player happens to have the same first and last names as an";
               msg += " existing player, the new player's name must be altered in order to keep";
               msg += " all player's names unique."
            }

            // (b)
            if (i != j && playerNameTDisSelector[j - 1] == false)
            {
               playerNameB = document.getElementById("playerName" + j).value;

               if (playerNameA === playerNameB)
               {
                  faultFound = true;
                  msg += "Player's names must be unique.\n";
                  msg += "Player's name " + i + " is the same as player's name " + j + ".\n\n";
                  msg += "If two players happen to have the same first and last names,";
                  msg += " one of the player's names must be altered in order to keep";
                  msg += " all player's names unique."
               }
            }
         }
      }
   }

   if (faultFound)
   {
      alert(msg);
      return false;
   }
   else
     return true;
}

/*******************************************END*OF*FILE********************************************/
