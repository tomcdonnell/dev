/**************************************************************************************************\
*
* Filename: "icdb_insert_match_p2.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_insert_match_p2.php".
*
* Author: Tom McDonnell 2006
*
\**************************************************************************************************/

// GLOBAL VARIABLES ////////////////////////////////////////////////////////////////////////////////

var n_playersPerTeam = 8; // must match $_SESSION['n_playersPerTeam'] in PHP file

var selectBatsmanNameString = 'Select Batsman Name'; // must match value in PHP file
var selectBowlerNameString  = 'Select Bowler Name';  // must match value in PHP file

var oldBatsmanNameSelectedIndicesArray = new Array(    n_playersPerTeam);
var oldBowlerNameSelectedIndicesArray  = new Array(2 * n_playersPerTeam);

var n_playersThisMatch; // n_playersPerTeam players in a full team,
                        // but a team may play if short up to 2 players.

var n_regularOvers; // If team is short one or two players, last two or last four overs
                    // are treated differently from the others.
                    // n_regularOvers is the last of the normal set.

// ERROR HANDLING FUNCTION /////////////////////////////////////////////////////////////////////////

/*
 * PRIMARY
 */
function init()
{
   var i;       // counter
   var element; // holder for DOM element

   // count team players participating in this match
   n_playersThisMatch = document.getElementById("batsmanName1").length - 1; // - instruction option

   // initialise oldBatsmanNameSelectedIndicesArray
   for (i = 1; i <= n_playersPerTeam; ++i)
   {
      element = document.getElementById("batsmanName" + i);
      oldBatsmanNameSelectedIndicesArray[i - 1] = element.selectedIndex;
   }

   // update select batsman name options
   // (since initially batsmen are selected in order entered in "icdb_insert_match_p1.php")
   for (i = 1; i <= n_playersPerTeam; ++i)
     updateSelectBatsmanNameOptions(i);

   // set no. of regular overs
   // (Each player normally must bowl two overs.  If the team is short one or two players,
   //  the oposition team must choose two bowlers to bowl the remaining two or four overs.
   //  the remaining overs are referred to as 'special' overs.  The others are regular.   )
   switch (n_playersThisMatch)
   {
    case n_playersPerTeam    : n_regularOvers = 2 * n_playersPerTeam    ; break;
    case n_playersPerTeam - 1: n_regularOvers = 2 * n_playersPerTeam - 2; break;
    case n_playersPerTeam - 2: n_regularOvers = 2 * n_playersPerTeam - 4; break;
    default: alert("Unexpected n_playersThisMatch detected (" + n_playersThisMatch + ")."); break;
   }
}

/*
 * PRIMARY
 */
function updateInningsOrderSelectors(battOrderChanged)
{
   var otherElem;

   switch (battOrderChanged)
   {
    case 'true':
      otherElem = document.getElementById("inningsOrderBowl");
      switch (document.getElementById("inningsOrderBatt").selectedIndex)
      {
       case 0: otherElem.selectedIndex = 0; break;
       case 1: otherElem.selectedIndex = 2; break;
       case 2: otherElem.selectedIndex = 1; break;
      }
      break;
    case 'false':
      otherElem = document.getElementById("inningsOrderBatt");
      switch (document.getElementById("inningsOrderBowl").selectedIndex)
      {
       case 0: otherElem.selectedIndex = 0; break;
       case 1: otherElem.selectedIndex = 2; break;
       case 2: otherElem.selectedIndex = 1; break;
      }
      break;
   }
}

/*
 * PRIMARY
 */
function clearWicketsLost(battingPos)
{
   document.getElementById("wicketsLost" + battingPos).value = "";
}

/*
 * PRIMARY
 */
function clearWicketsTaken(overNo)
{
   document.getElementById("wicketsTaken" + overNo).value = "";
}

/*
 * PRIMARY
 */
function clearTeamPenaltyRuns()
{
   var x = document.getElementById("teamPenaltyRuns");

   if (x.value == "0")
     x.value = ""; // PROBLEM: causes exception (see "Tools->JavaScript Console" on firefox browser)
}

/*
 * PRIMARY
 */
function clearOppTeamPenaltyRuns()
{
   var x = document.getElementById("oppTeamPenaltyRuns");

   if (x.value == "0")
     x.value = ""; // PROBLEM: causes exception (see "Tools->JavaScript Console" on firefox browser)
}

/*
 * PRIMARY
 */
function updateTeamScore()
{
   // sum team total wickets lost and runs scored
   var teamW = 0;
   var teamR = 0;
   for (var i = 1; i <= n_playersPerTeam; ++i)
   {
      teamW += Number(document.getElementById("wicketsLost" + i).value);
      teamR += Number(document.getElementById("runsScored"  + i).value);
   }

   // subtract penalty runs from total runs scored
   teamR -= Number(document.getElementById("teamPenaltyRuns").value);

   // update team total (wickets lost / runs scored)
   document.getElementById("teamScoreTH").innerHTML = teamW + ' / ' + teamR;
}

/*
 * PRIMARY
 */
function updateTeamScoreAndTeamPship(pShipNo)
{
   // update team score
   updateTeamScore();

   // sum team partnersip wickets lost
   var pShipW = 0;
   pShipW  = Number(document.getElementById("wicketsLost" + ((pShipNo - 1) * 2 + 1)).value);
   pShipW += Number(document.getElementById("wicketsLost" + ((pShipNo - 1) * 2 + 2)).value);

   // sum team partnership runs scored
   var pShipR = 0;
   pShipR  = Number(document.getElementById("runsScored"  + ((pShipNo - 1) * 2 + 1)).value);
   pShipR += Number(document.getElementById("runsScored"  + ((pShipNo - 1) * 2 + 2)).value);

   // update team partnership (wickets lost / runs scored)
   document.getElementById("teamPship" + pShipNo + "TH").innerHTML = pShipW + ' / ' + pShipR;
}

/*
 * PRIMARY
 */
function updateOppTeamScore()
{
   // sum opposition team total wickets lost and runs scored
   var oppTeamW = 0;
   var oppTeamR = 0;
   for (var i = 1; i <= 16; ++i)
   {
      oppTeamW += Number(document.getElementById("wicketsTaken" + i).value);
      oppTeamR += Number(document.getElementById("runsConceded" + i).value);   
   }

   // subtract penalty runs from total runs scored
   oppTeamR -= Number(document.getElementById("oppTeamPenaltyRuns").value);

   // update opposition team total (wickets lost / runs scored)
   document.getElementById("oppTeamScoreTH").innerHTML = oppTeamW + ' / ' + oppTeamR;
}

/*
 * PRIMARY
 */
function updateOppTeamScoreAndOppTeamPship(pShipNo)
{
   // update opposition team score
   updateOppTeamScore();

   // sum opposition partnership wickets lost
   var pShipW = 0;
   pShipW  = Number(document.getElementById("wicketsTaken" + ((pShipNo - 1) * 4 + 1)).value);
   pShipW += Number(document.getElementById("wicketsTaken" + ((pShipNo - 1) * 4 + 2)).value);
   pShipW += Number(document.getElementById("wicketsTaken" + ((pShipNo - 1) * 4 + 3)).value);
   pShipW += Number(document.getElementById("wicketsTaken" + ((pShipNo - 1) * 4 + 4)).value);

   // sum opposition partnership runs scored
   var pShipR = 0;
   pShipR  = Number(document.getElementById("runsConceded" + ((pShipNo - 1) * 4 + 1)).value);
   pShipR += Number(document.getElementById("runsConceded" + ((pShipNo - 1) * 4 + 2)).value);
   pShipR += Number(document.getElementById("runsConceded" + ((pShipNo - 1) * 4 + 3)).value);
   pShipR += Number(document.getElementById("runsConceded" + ((pShipNo - 1) * 4 + 4)).value);

   // update opposition partnership (wickets lost / runs scored)
   document.getElementById("oppTeamPship" + pShipNo + "TH").innerHTML = pShipW + ' / ' + pShipR;
}

// Functions concerning update of select batsman name options. /////////////////////////////////////

/*
 * Return true if 'option' is selected in one or more
 * of the regular batting positions.  Return false otherwise.
 * (function is only useful when short one player, so regular set is all but last two positions)
 */
function selectedInRegularSet(option)
{
   for (var i = 1; i <= n_playersPerTeam - 2; ++i)
     if (document.getElementById("batsmanName" + i).selectedIndex == option)
       return true;

   return false;
}

/*
 * Return true if 'option' is selected in one or more
 * of the special batting positions.  Return false otherwise.
 * (function is only useful when short one player, so special set is last two positions)
 */
function selectedInSpecialSet(option)
{
   for (var i = n_playersPerTeam - 1; i <= n_playersPerTeam; ++i)
     if (document.getElementById("batsmanName" + i).selectedIndex == option)
       return true;

   return false;
}

/*
 * Return the index of a batsman who has been selected twice (in two different batting positions)
 * If no such batsman exits, return -1;
 */
function findBatsmanSelectedTwice()
{
   var i, j; // counters

   for (i = 1; i <= n_playersPerTeam - 1; ++i) // for each batting pos. except the last
   {
      batsmanIndex = document.getElementById("batsmanName" + i).selectedIndex;

      for (j = i + 1; j <= n_playersPerTeam; ++j) // for batting pos. greater than i
        if (document.getElementById("batsmanName" + j).selectedIndex == batsmanIndex)
          return batsmanIndex;
   }

   return -1;
}

/*
 * Update the available options in the batsmanName selectors in
 * respose to a change of the selected index for selector at battingPos.
 * This function is called by updateSelectBatsmanNameOptions().
 */
function updateBatsmanOptionsAfterSelectionChange(battingPos, newSelectedIndex, oldSelectedIndex)
{
   // set limits to start & finish of either regular or special set
   var start, finish;
   if (n_playersThisMatch == n_playersPerTeam)
   {
      // set limits to regular set containing all batting positions
      start  =                1;        // index 0 is instruction
      finish = n_playersPerTeam;        // regular set includes all batting positions
   }
   else // team is short one or two players
   {
      if (battingPos < n_playersPerTeam - 1)
      {
         // set limits to regular set
         start  =                    1; // index 0 is instruction
         finish = n_playersPerTeam - 2; // regular set includes all but last two batting positions
      }
      else
      {
         // set limits to special set
         start  = n_playersPerTeam - 1; // Special set includes last
         finish = n_playersPerTeam    ; // two batting positions only.
      }
   }

   // For the set relevant to battingPos (regular or special):
   // * Enable  old selection in all other positions.
   // * Disable new selection in all other positions.
   // (same as updateSelectPlayerOptions() in "icdb_insert_match_p1.js")
   for (i = start; i <= finish; ++i)
   {
      if (i != battingPos)
      {
         element = document.getElementById("batsmanName" + i);

         if (oldSelectedIndex != 0) // 0 is index of instructions (avail. should never change)
           element.options[oldSelectedIndex].disabled = false; // enable  old

         if (newSelectedIndex != 0) // 0 is index of instructions (avail. should never change)
           element.options[newSelectedIndex].disabled = true;  // disable new
      }
   }
}

/*
 *
 */
function selectBatsmenIfOnlyOnesEnabled(battingPos)
{
   var i, j; // counters
   var element, batsmanNo;

   // if any position has only one option enabled, select that option
   // unless it is the option most recently selected by the user (newSelectedIndex of battingPos)
   var n_found;
   for (i = 1; i <= n_playersPerTeam; ++i) // for each batting position
   {
      element = document.getElementById("batsmanName" + i);

      n_found = 0;
      for (j = 1; j <= n_playersThisMatch && n_found <= 1; ++j) // for each option
      {
         if (element.options[j].disabled == false) // if option is enabled
         {
            ++n_found;
            batsmanNo = j;
         }
      }

      if (n_found == 1)                         // 'option' is only option of element
        if (element.selectedIndex != batsmanNo) // option is not already selected
          if (i != battingPos)                  // option was not just selected by user
          {
             element.selectedIndex = batsmanNo; // select 'option'
             updateSelectBatsmanNameOptions(battingPos); // recursive call to update options
          }
   }
}

/*
 * PRIMARY
 *
 * The conditions that must be enforced here depend on whether the team is short 0, 1, or 2 players.
 * If the team is short 1 or 2 players,
 * 'special' positions are the last two positions, and 'regular' positions are all other positions.
 *
 * If short 0 players:
 * * Regular positions: No player already selected may be selected.
 *
 * If short 1 player:
 * * Regular positions: At most one batsman already selected in a special
 *                      position may be selected in a regular position.
 * * Special positions: At most one batsman already selected in a regular
 *                      position may be selected in a special position.
 *
 * If short 2 players:
 * * Regular positions: At most two batsmen already selected in a special
 *                      position may be selected in a regular position.
 * * Special positions: At most two batsmen already selected in a regular
 *                      position may be selected in a special position.
 * Since there are two special positions, for the case when short two players, regular and
 * special positions may be considered independently, and the only condition for both sets of
 * positions is that no batsman is selected twice.
 */
function updateSelectBatsmanNameOptions(battingPos)
{
   var newSelectedIndex = document.getElementById("batsmanName" + battingPos).selectedIndex
   var oldSelectedIndex = oldBatsmanNameSelectedIndicesArray[battingPos - 1];

   // if full team or short two players, use slave function
   if (n_playersThisMatch == n_playersPerTeam || n_playersThisMatch == n_playersPerTeam - 2)
     updateBatsmanOptionsAfterSelectionChange(battingPos, newSelectedIndex, oldSelectedIndex);
   else
   {
      // Short one player.  Situation is more complicated.
      // Instead of enabling/disabling options based on most recent selection,
      // enable all to begin with, then disable based on all selections.

      var i, j;       // counters
      var element;    // holder for DOM element
      var takenIndex;

      // enable all options in both sets
      for (i = 1; i <= n_playersPerTeam; ++i) // for each batting position
      {
         element = document.getElementById("batsmanName" + i);

         for (j = 1; j <= n_playersThisMatch; ++j) // for each option
          element.options[j].disabled = false; // enable
      }

      // For each position in regular set,
      // disable all options that are selected in same set but different position.
      for (i = 1; i <= n_playersPerTeam - 2; ++i) // for each batting position in regular set
      {
         takenIndex = document.getElementById("batsmanName" + i).selectedIndex;

         if (takenIndex != 0)
           for (j = 1; j <= n_playersPerTeam - 2; ++j) // for each batting position in regular set
             if (i != j)
             {
                element = document.getElementById("batsmanName" + j)
                element.options[takenIndex].disabled = true; // disable
             }
      }
      // For each position in special set,
      // disable all options that are selected in same set but different position.
      for (i = n_playersPerTeam - 1; i <= n_playersPerTeam; ++i) // for each pos in special set
      {
         takenIndex = document.getElementById("batsmanName" + i).selectedIndex;

         if (takenIndex != 0)
           for (j = n_playersPerTeam - 1; j <= n_playersPerTeam; ++j) // for each pos in special set
             if (i != j)
             {
                element = document.getElementById("batsmanName" + j)
                element.options[takenIndex].disabled = true; // disable
             }
      }

      // now apply extra conditions
      selectedTwiceIndex = findBatsmanSelectedTwice();
      if (selectedTwiceIndex != -1)
      {
         // In regular set except where selectedTwiceIndex is selected,
         // disable all selected in special set.
         for (i = 1; i <= n_playersPerTeam - 2; ++i) // for each batting position in regular set
         {
            element = document.getElementById("batsmanName" + i);

            if (element.selectedIndex != selectedTwiceIndex)
              for (j = 1; j <= n_playersThisMatch; ++j) // for each index
                if (selectedInSpecialSet(j) && j != element.selectedIndex)
                  element.options[j].disabled = true; // disable
         }
         // In special set except where selectedTwiceIndex is selected,
         // disable all selected in regular set.
         for (i = n_playersPerTeam - 1; i <= n_playersPerTeam; ++i) // for each pos in special set
         {
            element = document.getElementById("batsmanName" + i);

            if (element.selectedIndex != selectedTwiceIndex)
              for (j = 1; j <= n_playersThisMatch; ++j) // for each index
                if (selectedInRegularSet(j) && j != element.selectedIndex)
                  element.options[j].disabled = true; // disable
         }
      }
   }

   selectBatsmenIfOnlyOnesEnabled(battingPos);

   // new becomes old
   oldBatsmanNameSelectedIndicesArray[battingPos - 1] = newSelectedIndex;
}

// Functions concerning update of select bowler name options. //////////////////////////////////////

/*
 * This function useful for debugging.  Not used in normal execution.
 */
function printHypBowlerNameSelectorArray(hypA)
{
   var i, j; // counters
   var string = "HypBowlerNameSelectorArray\n\n";

   for (i = 1; i <= n_regularOvers + 1; ++i)
     if (hypA[i] instanceof Array)
     {
        string += ("Over " + i + ":\tNo option selected.  Available options are: ");
        for (j = 1; j <= n_playersThisMatch; ++j)
          if (!hypA[i][j]) string += (j + " ");
          else             string += "- ";

        string += "\n";
     }
     else
       string += ("Over " + i + ": Option " + hypA[i] + " selected.\n");

   alert(string);
}

/*
 * Test whether bowler at 'index' is selected twice in the regular overs set.
 */
function bowlerSelectedTwiceInRegularOversHyp(hypBowlerNameSelectorArray, playerNo)
{
   var count = 0;

   for (var i = 1; i <= n_regularOvers; ++i)
     if (   !(hypBowlerNameSelectorArray[i] instanceof Array)
         &&   hypBowlerNameSelectorArray[i] == playerNo      )
       ++count;

   switch (count)
   {
    case 0: case 1: return false; break;
    case 2:         return true;  break;
    default: alert("Bowler selected more than twice in regular overs set."); // should never happen
   }
}

/*
 *
 */
function copyHypBowlerNameSelectorArray(hypBowlerNameSelectorArray, arrayCopy)
{
   var i, j;

   for (i = 1; i <= hypBowlerNameSelectorArray.length; ++i)
   {
      if (hypBowlerNameSelectorArray[i] instanceof Array)
      {
         arrayCopy[i] = new Array(n_playersThisMatch);

         for (j = 1; j <= n_playersThisMatch; ++j)
           arrayCopy[i][j] = hypBowlerNameSelectorArray[i][j];
      }
      else arrayCopy[i] = hypBowlerNameSelectorArray[i];
   }
}

/*
 * Test whether bowler at 'index' is selected twice in the regular overs set.
 */
function bowlerSelectedTwiceInRegularOvers(index)
{
   var count = 0;

   for (var i = 1; i <= n_regularOvers; ++i)
     if (document.getElementById("bowlerName" + i).selectedIndex == index)
       ++count;

   switch (count)
   {
    case 0:
    case 1: return false;
    case 2: return true;
    default: alert("Bowler selected more than twice in regular overs set."); // should never happen
   }
}

/*
 *
 */
function countUnassignedRegularOvers()
{
   var count = 0;
   for (var i = 1; i <= n_regularOvers; ++i)
     if (document.getElementById("bowlerName" + i).selectedIndex == 0)
       ++count;

   return count;
}

/*
 *
 */
function enableAllBowlersInAllOvers()
{
   // Enable all bowlers in all overs
   for (var i = 1; i <= 2 * n_playersPerTeam; ++i) // for each over (regular and special)
     for (var j = 1; j <= n_playersThisMatch; ++j) // for each bowler
       document.getElementById("bowlerName" + i).options[j].disabled = false; // enable
}

/*
 * For each over
 *   If bowler selected
 *     Disable that bowler in overs before and after
 * (ensuring that no bowler bowls two consecutive overs).
 */
function ensureNoBowlerCanBowlConsecutiveOvers()
{
   var i, j; // i counter, j variable

   for (i = 1; i <= 2 * n_playersPerTeam; ++i) // for each over (regular and special)
   {
      element = document.getElementById("bowlerName" + i);
      if (element.selectedIndex != 0) // if over assigned to bowler
      {
         // disable bowler of over 'i' in overs before and after over 'i'
         j = element.selectedIndex;

         if (i > 1)
           document.getElementById("bowlerName" + (i - 1)).options[j].disabled = true; // disable

         if (i < 2 * n_playersPerTeam)
           document.getElementById("bowlerName" + (i + 1)).options[j].disabled = true; // disable
      }
   }
}

/*
 * For each bowler
 *   If that bowler selected twice in regular overs
 *     Disable that bowler in all other regular overs
 * (ensuring that no bowler bowls more than two regular overs).
 */
function ensureNoBowlerBowlsMoreThanTwoRegOvers()
{
   var i, j; // counters

   for (i = 1; i <= n_playersThisMatch; ++i)
     if (bowlerSelectedTwiceInRegularOvers(i))
       // disable bowler 'i' in all overs in which bowler 'i' is not selected
       for (j = 1; j <= n_regularOvers; ++j)
       {
          element = document.getElementById("bowlerName" + j);
          if (element.selectedIndex != i)
            element.options[i].disabled = true; // disable
       }
}

/*
 * If four or less unassigned overs remain, selection         / over  1: * Jason or Glen
 * of an option could possibly lead to deadlock               | over  2: Jay
 * because consecutive overs must then be bowled by           | over  3: John
 * the same bowler (not allowed by rules).                    | over  4: Koges
 *                                                            | over  5: Luke
 * Example: In situation at right,                            | over  6: John
 *          if Jason is selected in over 1,           Regular-| over  7: Koges
 *          deadlock since otherwise Glen                     | over  8: Luke
 *          inevitably must be selected                       | over  9: Jason
 *          in two consecutive overs.                         | over 10: * Jay or Glen
 *                                                            | over 11: * Jason or Jay or Glen
 *                                                            \ over 12: * Jason or Jay
 *                                                    Special-- over 13: Glen
 */
function preventDeadlockInSelectionOfBowlers()
{
   if (countUnassignedRegularOvers() <= 4)
   {
      // Carry out recursive hypothetical selections of every option not disabled to see
      // whether selection of option leads to inevitable consecutive overs by same bowler.
      // If does, disable option.
      var upperLimit = (n_playersThisMatch < n_playersPerTeam)? n_regularOvers + 1: n_regularOvers;
      for (i = 1; i <= upperLimit; ++i)
      {
         element = document.getElementById("bowlerName" + i)

         if (element.selectedIndex == 0)               // if bowler not selected for over i
           for (j = 1; j <= n_playersThisMatch; ++j)   //   for each option
             if (element.options[j].disabled == false) //     if option not disabled
               if (!testSelectionForLegality(i, j))    //       if selection of opt. illegal
               {
                  element.options[j].disabled = true;  //         disable option

                  // If 'i' is first special over and team is short two players,
                  // must also disable option in third special over.
                  if (i == n_regularOvers + 1 && n_playersThisMatch == n_playersPerTeam - 2)
                    document.getElementById("bowlerName" + (i + 2)).options[j].disabled = true; 
               }
      }
   }
}

/*
 *
 */
function selectBowlersIfOnlyOnesEnabled(mostRecentlySelectedOver)
{
   // if any over has only one bowler enabled, select that bowler
   var n_newlySelected = 0;
   var n_found;
   var bowler;
   for (i = 1; i <= 2 * n_playersPerTeam; ++i) // for each over
   {
      element = document.getElementById("bowlerName" + i);

      n_found = 0;
      for (j = 1; j <= n_playersThisMatch && n_found <= 1; ++j) // for each player
      {
         if (element.options[j].disabled == false) // if player is enabled
         {
            ++n_found;
            bowler = j;
         }
      }

      if (n_found == 1 && element.selectedIndex != bowler && i != mostRecentlySelectedOver)
      {
         element.selectedIndex = bowler;
         ++n_newlySelected;
      }
   }

   if (n_newlySelected > 0) return true;
   else                     return false;
}

/*
 *
 */
function testSelectionForLegality(overNo, playerNo)
{
   var i, j,    // counters
       element; // holder for DOM element

   // create hypothetical bowler name selector array
   // (first special over must be included to prevent problems with last regular over)
   var arraySize = (n_playersThisMatch < n_playersPerTeam)? n_regularOvers + 1: n_regularOvers;
   hypBowlerNameSelectorArray = new Array(arraySize);
   for (i = 1; i <= arraySize; ++i)
   {
      element = document.getElementById("bowlerName" + i)
      if (element.selectedIndex == 0)
      {
         hypBowlerNameSelectorArray[i] = new Array(n_playersThisMatch); // indices 1-n_playersTh...

         for (j = 1; j <= n_playersThisMatch; ++j)
           hypBowlerNameSelectorArray[i][j] = element.options[j].disabled;
      }
      else
        hypBowlerNameSelectorArray[i] = element.selectedIndex;
   }

   // call recursive slave function
   recursionDepth = 0;
   return testSelectionForLegalityRecursively(hypBowlerNameSelectorArray, overNo, playerNo);
}

/*
 * Test selection for legality.
 * If selection is legal,
 *  * Make selection.
 *  * Test all possible further selections for legality.
 *    If none are legal, return false.
 *    Else return true.
 * Else return false.
 */
function testSelectionForLegalityRecursively(hypBowlerNameSelectorArray, overNo, playerNo)
{
   var i, j; // counters

   if (hypBowlerNameSelectorArray[overNo] instanceof Array)
   {
      // test whether hypothetically selected option was disabled
      if (hypBowlerNameSelectorArray[overNo][playerNo] == true)
        // playerNo was disabled.  Erroneous selection
        alert(  "An erroneous hypothetical selection was made.\n"
              + "Player number " + playerNo + " was disabled for over " + overNo + ".");
      else
      {
         // PlayerNo was enabled, so selection not known to be illegal,
         // so we make the selection, then test for illegality.

         // make selection
         hypBowlerNameSelectorArray[overNo] = playerNo;

         if (bowlerSelectedTwiceInRegularOversHyp(hypBowlerNameSelectorArray, playerNo))
         {
            // disable playerNo in all unassigned regular overs
            for (i = 1; i <= n_regularOvers; ++i)
              if (hypBowlerNameSelectorArray[i] instanceof Array)
                hypBowlerNameSelectorArray[i][playerNo] = true; // disable playerNo
         }
         else
         {
            // disable playerNo in over before overNo
            if (   overNo > 1
                && hypBowlerNameSelectorArray[overNo - 1] instanceof Array)
              hypBowlerNameSelectorArray[overNo - 1][playerNo] = true; // disable playerNo

            // disable playerNo in over after overNo (must include first special over)
            if (   overNo < n_regularOvers + 1 && overNo <= 2 * n_playersPerTeam
                && hypBowlerNameSelectorArray[overNo + 1] instanceof Array      )
              hypBowlerNameSelectorArray[overNo + 1][playerNo] = true; // disable playerNo
         }

         // test all possible further selections for legality
         var foundEmptyOver = false;
         var foundEnabledOption;
         for (i = 1; i <= n_regularOvers + 1; ++i)
         {
            if (hypBowlerNameSelectorArray[i] instanceof Array)
            {
               // found over without bowler selected
               foundEmptyOver = true;

               // test all options not disabled for legality
               foundEnabledOption = false;
               for (j = 1; j <= n_playersThisMatch; ++j)
                 if (hypBowlerNameSelectorArray[i][j] == false) // if option j is not disabled
                 {
                    // found option not disabled
                    foundEnabledOption = true;

                    // make copy of hypBowlerNameSelectorArray
                    arrayCopy = new Array(hypBowlerNameSelectorArray.length);
                    copyHypBowlerNameSelectorArray(hypBowlerNameSelectorArray, arrayCopy);

                    if (testSelectionForLegalityRecursively(arrayCopy, i, j))
                      // legal selection found
                      return true;
                 }

               if (!foundEnabledOption)
                 // no legal selection for over 'i'
                 return false;
            }
         }

         // No legal selection was found.
         // If an empty over was found, then have reached deadlock.
         // Else all overs have been assigned a bowler successfully.
         if (foundEmptyOver) {/*alert("hello");*/ return false;}
         else                return true;
      }
   }
   else
   {
      // a player was already selected for that over
      alert(  "An erroneous hypothetical selection was made.\n"
            + "A player was already selected for over " + overNo + ".");
   }
}

/*
 * PRIMARY
 *
 * The conditions that must be enforced here depend on whether the team is short 0, 1, or 2 players.
 * If the team is short one player , 'special' overs are the last two  overs.
 * If the team is short two players, 'special' overs are the last four overs.
 * 'Regular' overs are all other overs.
 *
 * If short 0 players:
 * * Regular overs: No bowler already selected twice may be selected, and no
 *                  bowler selected in the previous or next over may be selected.
 *
 * If short 1 player:
 * * Regular overs: As above.
 * * Special overs: Any two different bowlers may be selected.
 *
 * If short 2 players:
 * * Regular overs: As above.
 * * Special overs: Any two different bowlers may be selected to bowl the four overs,
 *                  no two consecutive overs may be bowled by the same bowler.
 */
function updateSelectBowlerNameOptions(overNo)
{
   // if team short two players and selection just made was for a special over,
   // selection must be duplicated in the other special over two positions away
   // from overNo.  (Reason is the four special overs must be bowled by two bowlers,
   // and bowlers must not bowl consecutive overs).
   if (n_playersThisMatch == n_playersPerTeam - 2 && overNo > n_regularOvers)
   {
      var index = document.getElementById("bowlerName" + overNo).selectedIndex;
      switch (overNo)
      {
       case 13: document.getElementById("bowlerName" + 15).selectedIndex = index; break;
       case 14: document.getElementById("bowlerName" + 16).selectedIndex = index; break;
       case 15: document.getElementById("bowlerName" + 13).selectedIndex = index; break;
       case 16: document.getElementById("bowlerName" + 14).selectedIndex = index; break;
      }
   }

   enableAllBowlersInAllOvers();

   ensureNoBowlerCanBowlConsecutiveOvers();

   ensureNoBowlerBowlsMoreThanTwoRegOvers();

   preventDeadlockInSelectionOfBowlers();

   if (selectBowlersIfOnlyOnesEnabled(overNo))
     updateSelectBowlerNameOptions(overNo);
}

// Form validaion function. ////////////////////////////////////////////////////////////////////////

/*
 * PRIMARY
 *
 * Test whether the form has been filled in correctly.
 * Need to ensure that: Each integer field contains an integer in the expected range.
 *                      No player has batted more than once or bowled more than twice.
 *                      No bowler has bowled two overs in a row.
 */
function validate()
{
   var faultFound = false;
   var batsmanName;
   var bowlerName;
   var msg = "The form has been completed incorrectly.\n\n";

   // test whether innings order has been selected
   if (   document.getElementById("inningsOrderBatt").selectedIndex == 0
       || document.getElementById("inningsOrderBowl").selectedIndex == 0)
   {
         faultFound = true;
         msg += "The innings order must be specified.\n"
             +  "Select '1st' or '2nd' in the 'Batted' and 'Bowled'"
             +  " selectors at the left and right near the top of the form.\n";
   }

   // test runsScored[1-n_playersPerTeam] and wicketsLost[1-n_players_perTeam] for NaN and ""
   for (i = 1; i <= n_playersPerTeam && !faultFound; ++i)
   {
      var wicketsLost = document.getElementById("wicketsLost" + i).value;
      var runsScored  = document.getElementById("runsScored"  + i).value;

      if (   wicketsLost == "" || isNaN(wicketsLost)
          || runsScored  == "" || isNaN(runsScored ))
      {
         faultFound = true;
         msg += "An integer must be entered in the 'Wickets Lost'"
             +  " and 'Runs Scored' field for each batsman.\n";
      }
      else
        if (wicketsLost < 0)
        {
           faultFound = true;
           msg += "Positive integers must be entered in the"
               +  " 'Wickets Lost' field for each batsman.\n";
        }
   }

   // test runsConceded[1-2*n_players_perTeam] and wicketsTaken[1-2*n_playersPerTeam] for NaN and ""
   for (i = 1; i <= 2 * n_playersPerTeam && !faultFound; ++i)
   {
      var wicketsTaken = document.getElementById("wicketsTaken" + i).value;
      var runsConceded = document.getElementById("runsConceded" + i).value;

      if (   wicketsTaken == "" || isNaN(wicketsTaken)
          || runsConceded == "" || isNaN(runsConceded))
      {
         faultFound = true;
         msg += "An integer must be entered in the 'Wickets Taken'";
         msg += " and 'Runs Conceded' field for each bowler.\n";
      }
   }

   // Test batsmanName[1-n_playersPerTeam] for default and blank values
   for (i = 1; i <= n_playersPerTeam && !faultFound; ++i)
   {
      batsmanName = document.getElementById("batsmanName" + i).value;

      if (   batsmanName == ""
          || batsmanName == selectBatsmanNameString)
      {
         faultFound = true;
         msg += "A batsman's name must be selected in the 'Name' field for each batsman.\n";
      }
   }

   // Test bowlerName[1-2*n_playersPerTeam] for default and blank values
   for (i = 1; i <= 2 * n_playersPerTeam && !faultFound; ++i)
   {
      bowlerName = document.getElementById("bowlerName" + i).value;

      if (   bowlerName == ""
          || bowlerName == selectBowlerNameString)
      {
         faultFound = true;
         msg += "A bowler's name must be selected in the 'Name' field for each bowler.\n";
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
