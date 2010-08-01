/**************************************************************************************************\
*
* Filename: "icdb_data_retrieval_form_r_tab_opposition.js"
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

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

/*
 * NOTE: This fuction will initialise the tab using the current main page hidden data.
 *       To reset the main page hidden data, 'use resetR_tabPeriod()'.
 */
function initR_tabOpposition()
{
   var i = '      ';

   document.getElementById("r_tabBodyTd").innerHTML
     = i + '<table width="100%">\n'
     + i + ' <tbody>\n'
     + i + '  <tr>\n'
     + i + '   <td id="r_tabOppTeamSelectorTd" name="r_tabOppTeamSelectorTd">\n'
     + i + '    <select>\n'
     + i + '     <option>Loading...</option>\n'
     + i + '    </select>\n'
     + i + '   </td>\n'
     + i + '  </tr>\n'
     + i + ' </tbody>\n'
     + i + '</table>\n';

   // Use setTimeout function to flush output before batsmanSelectors are prepared.
   setTimeout('initOppTeamSelector(1)', 0);
}

/*
 * Resets the main page hidden data (OppTeamNameSelector) to 'All Opposition Teams',
 * then initialises the opposition teams selector.
 */
function resetR_tabOpposition()
{
   // reset main page hidden data
   document.getElementById('r_tabOppositionHiddenDataOppTeamNameSelectorId').value
     = 'All Opposition Teams';

   // initialise selector
   initOppTeamSelector(1);

   initSelectedS_tab();
}

/*
 * Swap the innerHTML of <td id="r_tabOppTeamSelectorTd" ...>
 * element with new HTML oppTeams selector.
 */
function initOppTeamSelector(minMatches)
{
   var indent = '         ';

   var HTMLtext = indent + '<select onchange="onChangeOppTeamSelector()" '
                         +  'id="r_tabOppTeamSelectorId" name="r_tabOppTeamSelectorId">\n';

   // get first match in period, last match in period
   var firstAndLastMatchNosArray = getNosOfFirstAndLastMatchesInPeriod();

   if (firstAndLastMatchNosArray != -1)
   {
      // build n_matchesPerOppTeam array
      var n_matchesPerOppTeamArray = new Array(N_OPP_TEAMS);
      var oppTeamNo;
      var matchNo;
      var n_matchesInPeriod = 0;
      for (oppTeamNo = 0; oppTeamNo < N_OPP_TEAMS; ++oppTeamNo)
      {
         n_matchesPerOppTeamArray[oppTeamNo] = 0;

         for (matchNo  = firstAndLastMatchNosArray[0];
              matchNo <= firstAndLastMatchNosArray[1]; ++matchNo)
           if (!matchExcludedByRestrictions(matchNo)
               && MATCHES_ARRAY[matchNo]['oppTeamID'] == OPP_TEAMS_ARRAY[oppTeamNo]['oppTeamID'])
           {
              ++n_matchesPerOppTeamArray[oppTeamNo];
              ++n_matchesInPeriod;
           }
      }

      // get main page hidden data
      var selectedOption
        = document.getElementById('r_tabOppositionHiddenDataOppTeamNameSelectorId').value;

      // build oppTeam selector default option
      var oppTeamName = 'All Opposition Teams'; // default selection
      var selected = ((selectedOption == oppTeamName)? ' selected': '');
      HTMLtext += indent + ' <option' + selected + '>All Opposition Teams (' + n_matchesInPeriod
                         +  ' matches)</option>\n';

      // build oppTeam selector other options
      var n;
      for (oppTeamNo = 0; oppTeamNo < N_OPP_TEAMS; ++oppTeamNo)
      {
         n = n_matchesPerOppTeamArray[oppTeamNo];

         oppTeamName = OPP_TEAMS_ARRAY[oppTeamNo]['oppTeamName'];
         selected = ((selectedOption == oppTeamName)? ' selected': '');

         if (n >= minMatches)
           HTMLtext += indent + ' <option' + selected + '>' + oppTeamName
                              +  ' (' + n + ' match' + ((n > 1)? 'es': '') + ')</option>\n';
      }
      HTMLtext += indent + '</select>\n';
   }
   else
     // no matches in period
     HTMLtext += indent + ' <option selected>All Opposition Teams (0 matches)</option>\n'
                        + '</select>\n';

   // update oppTeamSelectorTd
   document.getElementById('r_tabOppTeamSelectorTd').innerHTML = HTMLtext;

   // update main page hidden data
   var elem = document.getElementById('r_tabOppTeamSelectorId');
   document.getElementById('r_tabOppositionHiddenDataOppTeamNameSelectorId').value
     = removeBracketedExpFromString(elem.options[elem.selectedIndex].text);
}

/*
 * PRIMARY
 */
function onChangeOppTeamSelector()
{
   // update main page hidden data
   var elem = document.getElementById('r_tabOppTeamSelectorId');
   document.getElementById('r_tabOppositionHiddenDataOppTeamNameSelectorId').value
     = removeBracketedExpFromString(elem.options[elem.selectedIndex].text);

   if (elem.selectedIndex == 0)
     uncheckR_tabCheckbox('Opposition');
   else
     checkR_tabCheckbox('Opposition');

   initSelectedS_tab();
}

/*******************************************END*OF*FILE********************************************/
