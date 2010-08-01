/**************************************************************************************************\
*
* Filename: "icdb_title_page.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_title_page.php".
*
* Author: Tom McDonnell 2007
*
\**************************************************************************************************/

// GLOBAL VARIABLES ////////////////////////////////////////////////////////////////////////////////

// Globals have been capitalized to reduce chance of accidental use.

var CODED_DATA = ''; // Variable for large string made global for efficiency reasons.
var INDEX = 0;       // To be used in conjuction with string 'CODED_DATA' above.

// Totals which determine the size of global arrays below.
var N_COUNTRIES;
var N_STATES;
var N_CENTRES;
var N_TEAMS;

// Global arrays to be created in extractCodedData().
var COUNTRIES_ARRAY; // For each country: 'countryID', 'countryName'.
var STATES_ARRAY;    // For each state:   'countryID', 'stateID', 'stateName'.
var CENTRES_ARRAY;   // For each centre:  'countryID', 'stateID', 'centreID', 'centreName'.
var TEAMS_ARRAY;     // For each team:    'countryID', 'stateID', 'centreID', 'teamID',
                     //                   'n_matches', 'teamName'.

// FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////

// Initialisation functions. ---------------------------------------------------------------------//

/*
 * PRIMARY - <body onload=init()>
 */
function init()
{
   extractCodedData();

   createCountrySelector();
   createStateSelector();
   createCentreSelector();
   createTeamSelector();
}

// Functions concerning decoding of coded data. --------------------------------------------------//

/*
 *
 */
function extractCodedData()
{
   // Get global CODED_DATA string.
   CODED_DATA = document.getElementById('codedData').innerHTML;

   // Initialise INDEX of 'CODED_DATA' string (global variable).
   INDEX = 0;

   // Skip comment marker ('<!--').
   getNextWordFromCodedData();

   // Set global totals (NOTE: must be done in correct order).
   N_COUNTRIES = getNextWordFromCodedData();
   N_STATES    = getNextWordFromCodedData();
   N_CENTRES   = getNextWordFromCodedData();
   N_TEAMS     = getNextWordFromCodedData();

   // Create global arrays (NOTE: must be done in correct order).
   buildCountriesArray();
   buildStatesArray();
   buildCentresArray();
   buildTeamsArray();

   //testExtractCodedData();
}

/*
 * Return the next word from the global variable string 'CODED_DATA' starting from the
 * global variable 'INDEX'.
 * Words are strings of any non-whitespace characters bounded by whitespace characters.
 */
function getNextWordFromCodedData()
{
   if (INDEX > CODED_DATA.length)
     error("ERROR: Index out of range in function 'icdb_title_page.js'::getWord().");

   // Skip leading whitespace.
   eatWhiteSpaceFromCodedData();

   // Get word.
   var word = '';
   while (!isWhiteSpace(CODED_DATA.charAt(INDEX)))
     word += CODED_DATA.charAt(INDEX++);

   return word;
}

/*
 * Build 2D global array COUNTRIES_ARRAY[N_COUNTRIES][2].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_COUNTRIES' has been set.
 *  * 'INDEX' is in the correct position.
 */
function buildCountriesArray()
{
   COUNTRIES_ARRAY = new Array(N_COUNTRIES);
   for (var i = 0; i < N_COUNTRIES; ++i)
   {
      COUNTRIES_ARRAY[i] = new Array(2); // Contents: 'countryID', 'countryName'.

      COUNTRIES_ARRAY[i]['countryID'  ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      COUNTRIES_ARRAY[i]['countryName'] = getRemainingLineFromCodedData();
   }
}

/*
 * Build 2D global array STATES_ARRAY[N_STATES][3].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_STATES' has been set.
 *  * 'INDEX' is in the correct position.
 */
function buildStatesArray()
{
   STATES_ARRAY = new Array(N_STATES);
   for (var i = 0; i < N_STATES; ++i)
   {
      STATES_ARRAY[i] = new Array(3); // Contents: 'countryID', 'stateID', 'stateName'.

      STATES_ARRAY[i]['countryID'] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      STATES_ARRAY[i]['stateID'  ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      STATES_ARRAY[i]['stateName'] = getRemainingLineFromCodedData();
   }
}

/*
 * Build 2D global array CENTRES_ARRAY[N_CENTRES][4].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_CENTRES' has been set.
 *  * 'INDEX' is in the correct position.
 */
function buildCentresArray()
{
   CENTRES_ARRAY = new Array(N_CENTRES);
   for (var i = 0; i < N_CENTRES; ++i)
   {
      CENTRES_ARRAY[i] = new Array(4); // Contents: 'countryID', 'stateID',
                                       //           'centreID', 'centreName'.

      CENTRES_ARRAY[i]['countryID' ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      CENTRES_ARRAY[i]['stateID'   ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      CENTRES_ARRAY[i]['centreID'  ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      CENTRES_ARRAY[i]['centreName'] = getRemainingLineFromCodedData();
   }
}

/*
 * Build 2D global array TEAMS_ARRAY[N_TEAMS][3].
 * Assumes:
 *  * 'CODED_DATA' string has been created,
 *  * 'N_CENTRES' has been set.
 *  * 'INDEX' is in the correct position.
 */
function buildTeamsArray()
{
   TEAMS_ARRAY = new Array(N_TEAMS);
   for (var i = 0; i < N_TEAMS; ++i)
   {
      TEAMS_ARRAY[i] = new Array(4); // Contents: 'centreID', 'teamID', 'teamName'.

      TEAMS_ARRAY[i]['centreID' ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      TEAMS_ARRAY[i]['teamID'   ] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      TEAMS_ARRAY[i]['n_matches'] = Number(getNextWordFromCodedData());
      eatWhiteSpaceFromCodedData();
      TEAMS_ARRAY[i]['teamName' ] = getRemainingLineFromCodedData();

      // Find Country and State in which this team's centre resides.
      var countryAndStateIDs = getCountryIdByCentreId(TEAMS_ARRAY[i]['centreID']);
      if (countryAndStateIDs == undefined)
        error('No centre found for given centreID in "icdb_title_page.php::buildTeamsArray()".');

      // Set Country and State ID.
      TEAMS_ARRAY[i]['countryID'] = countryAndStateIDs[0];
      TEAMS_ARRAY[i]['stateID'  ] = countryAndStateIDs[1];
   }
}

/*
 * Increment global variable 'INDEX' until 'CODED_DATA.charAt(INDEX)' is not a whitespace character.
 */
function eatWhiteSpaceFromCodedData()
{
   while (isWhiteSpace(CODED_DATA.charAt(INDEX)))
     ++INDEX;
}

/*
 * Get the substring in the global string 'CODED_DATA' from
 * position global variable 'INDEX' to the next newline character.
 */
function getRemainingLineFromCodedData()
{
   var line = '';
   while (CODED_DATA.charAt(INDEX) != '\n')
     line += CODED_DATA.charAt(INDEX++);

   return line;
}

/*
 * Test function used to ensure data has been decoded successfully.
 */
function testExtractCodedData()
{
   var txt;

   txt = "Countries (countryID, countryName)\n";
   for (i = 0; i < N_COUNTRIES; ++i)
     txt += COUNTRIES_ARRAY[i]['countryID'  ] + ', '
          + COUNTRIES_ARRAY[i]['countryName'] + '\n';
   alert(txt);

   txt = "States (countryID, stateID, stateName)\n";
   for (i = 0; i < N_STATES; ++i)
     txt += STATES_ARRAY[i]['countryID'] + ', '
          + STATES_ARRAY[i]['stateID'  ] + ', '
          + STATES_ARRAY[i]['stateName'] + '\n';
   alert(txt);

   txt = "Centres (countryID, stateID, centreID, centreName)\n";
   for (i = 0; i < N_CENTRES; ++i)
     txt += CENTRES_ARRAY[i]['countryID' ] + ', '
          + CENTRES_ARRAY[i]['stateID'   ] + ', '
          + CENTRES_ARRAY[i]['centreID'  ] + ', '
          + CENTRES_ARRAY[i]['centreName'] + '\n';
   alert(txt);

   txt = "Teams (countryID, stateID, centreID, teamID, n_matches, teamName)\n";
   for (i = 0; i < N_TEAMS; ++i)
     txt += TEAMS_ARRAY[i]['countryID' ] + ', '
          + TEAMS_ARRAY[i]['stateID'   ] + ', '
          + TEAMS_ARRAY[i]['centreID'  ] + ', '
          + TEAMS_ARRAY[i]['teamID'    ] + ', '
          + TEAMS_ARRAY[i]['n_matches' ] + ', '
          + TEAMS_ARRAY[i]['teamName'  ] + '\n';
   alert(txt);
}

/*
 *
 */
function createCountrySelector()
{
   var indent = '       ';

   var innerHTML
     = indent + '<select id="countrySelectorID" name="countrySelectorID"'
              + ' onchange="onChangeCountrySelector()">' + "\n"
     + indent + ' <option selected="selected">Select Country</option>' + "\n";

   for (var i = 0; i < N_COUNTRIES; ++i)
     innerHTML += indent + ' <option>' + COUNTRIES_ARRAY[i]['countryName'] + "</option>\n";

   innerHTML += '</select>';

   document.getElementById('countrySelectorTd').innerHTML = innerHTML;
}

/*
 * Create the state selector using the STATES_ARRAY.
 * If 'countryID' is supplied, include only states within that country.
 */
function createStateSelector(countryID)
{
   var indent = '       ';

   var innerHTML
     = indent + '<select id="stateSelectorID" name="stateSelectorID"'
              + ' onchange="onChangeStateSelector()">' + "\n"
     + indent + ' <option selected="selected">Select State/Territory/Region</option>' + "\n";

   for (var i = 0; i < N_STATES; ++i)
     if (countryID == undefined || STATES_ARRAY[i]['countryID'] == countryID)
      innerHTML += indent + ' <option>' + STATES_ARRAY[i]['stateName'] + "</option>\n";

   innerHTML += '</select>';

   document.getElementById('stateSelectorTd').innerHTML = innerHTML;
}

/*
 * Create the centre selector using the CENTRES_ARRAY.
 * If 'countryID' and/or 'stateID' is supplied,
 * include only centres within that country and/or state.
 */
function createCentreSelector(countryID, stateID)
{
   var indent = '       ';

   var innerHTML
     = indent + '<select id="centreSelectorID" name="centreSelectorID"'
              + ' onchange="onChangeCentreSelector()">' + "\n"
     + indent + ' <option selected="selected">Select Centre</option>' + "\n";

   for (var i = 0; i < N_CENTRES; ++i)
     if (   countryID == undefined || CENTRES_ARRAY[i]['countryID'] == countryID
         && stateID   == undefined || CENTRES_ARRAY[i]['stateID'  ] == stateID  )
       innerHTML += indent + ' <option>' + CENTRES_ARRAY[i]['centreName'] + "</option>\n";

   innerHTML += '</select>';

   document.getElementById('centreSelectorTd').innerHTML = innerHTML;
}

/*
 * Create the team selector using the TEAMS_ARRAY.
 * If 'countryID' and/or 'stateID' and/or 'centreID' is supplied,
 * include only teams within that country and/or state and/or centre.
 */
function createTeamSelector(countryID, stateID, centreID)
{
   var indent = '       ';

   var innerHTML
     = indent + '<select id="teamSelectorID" name="teamSelectorID"'
              + ' onchange="onChangeTeamSelector()">' + "\n"
     + indent + ' <option selected="selected">Select Team</option>' + "\n";

   for (var i = 0; i < N_TEAMS; ++i)
     if (   countryID == undefined || TEAMS_ARRAY[i]['countryID'] == countryID
         && stateID   == undefined || TEAMS_ARRAY[i]['stateID'  ] == stateID  
         && centreID  == undefined || TEAMS_ARRAY[i]['centreID' ] == centreID )
     innerHTML += indent + ' <option>' + TEAMS_ARRAY[i]['teamName']
                         + ' (' + TEAMS_ARRAY[i]['n_matches'] + " matches)</option>\n";

   innerHTML += '</select>';

   document.getElementById('teamSelectorTd').innerHTML = innerHTML;
}

// Event driven functions. -----------------------------------------------------------------------//

/*
 * PRIMARY
 */
function onClickEnterAsStatistician()
{
   document.getElementById('guestButtonId').disabled = true;
   document.getElementById('statisticianButtonId').disabled = true;

   document.getElementById('passwordTd').innerHTML
     =  'Enter Password:<br />\n'
      + '<input type="password" id="passwordId" name="passwordId" size="10" maxlength="16"/>\n'
      + '<br />\n'
      + '<input type="submit" value="Ok" onclick="onClickOk()" />\n'
      + '<input type="button" value="Cancel" onclick="onClickCancel()" />\n';
}

/*
 * PRIMARY
 */
function onClickCancel()
{
   document.getElementById('passwordTd').innerHTML = '';
   document.getElementById('guestButtonId').disabled = false;
   document.getElementById('statisticianButtonId').disabled = false;
}

/*
 * PRIMARY
 */
function onClickOk()
{
   document.getElementById('titlePageFormId').action
     = 'statisticians_menu/icdb_statisticians_menu_frameset.php';
}

/*
 * PRIMARY
 *
 * Rebuild options array for all selectors below 'country' in the selection hierarchy
 * (State, Centre, and Team), removing options not pertaining to the country selected.
 */
function onChangeCountrySelector()
{
   // Get ID of selected country.
   var elem = document.getElementById('countrySelectorID');
   if (selectedIndex != 0)
   {
      var countryName = elem.options[elem.selectedIndex].value;
      var countryID = getCountryIDfromName(countryName);
   }
   // Else no country is selected, so leave countryID undefined.

   // Rebuild selectors.
   createStateSelector(countryID);
   createCentreSelector(countryID);
   createTeamSelector(countryID);
}

/*
 * PRIMARY
 *
 * Rebuild options array for all selectors below 'state' in the selection hierarchy
 * (Centre and Team), removing options not pertaining to the country and state selected.
 * Also if a state has been selected, select the country corresponding to the selected state.
 * NOTE: UNFINISHED
 */
function onChangeStateSelector()
{
   // Get ID of selected country.
   var countryElem = document.getElementById('countrySelectorID');
   var countrySelectedIndex = countryElem.selectedIndex;
   if (countrySelectedIndex != 0)
   {
      var selectedCountryName = countryElem.options[countrySelectedIndex].value;
      var selectedCountryID = getCountryIDfromName(selectedCountryName);
   }
   // Else no country is selected, so leave selectedCountryID undefined.

   // Get ID of selected state.
   var stateElem = document.getElementById('stateSelectorID');
   var stateSelectedIndex = stateElem.selectedIndex;
   if (stateSelectedIndex != 0)
   {
      var selectedStateName = stateElem.options[stateSelectedIndex].value;
      var selectedStateID = getStateIDfromName(selectedStateName);
   }
   // Else no state is selected, so leave stateID undefined.

   // Rebuild selectors.
   createCentreSelector(selectedCountryID, selectedStateID);
   createTeamSelector(selectdCountryID, selectedStateID);

   // Select country corresponding to selected state.
   var requiredCountryName = getCountryNameFromStateID(selectedStateID);
   if (selectedCountryName != requiredCountryName)
   {
      for (var i = 0; i < countryElem.length; ++i)
      {
        if (countryElem.options[i].value == requiredCountryName)
        {
           countryElem.selectedIndex = i;
           break;
        }
      }
   }
}

/*
 * PRIMARY
 *
 * Rebuild options array for all selectors below 'centre' in the selection hierarchy
 * (only Team), removing options not pertaining to the country, state, and team selected.
 */
function onChangeCentreSelector()
{
   var elem, selectedIndex;

   // Get ID of selected country.
   elem = document.getElementById('countrySelectorID');
   if (selectedIndex != 0)
   {
      var countryName = elem.options[elem.selectedIndex].value;
      var countryID = getCountryIDfromName(countryName);
   }
   // Else no country is selected, so leave countryID undefined.

   // Get ID of selected state.
   elem = document.getElementById('stateSelectorID');
   if (selectedIndex != 0)
   {
      var stateName = elem.options[elem.selectedIndex].value;
      var stateID = getCountryIDfromName(countryName);
   }
   // Else no state is selected, so leave stateID undefined.

   // Get ID of selected centre.
   elem = document.getElementById('stateSelectorID');
   if (selectedIndex != 0)
   {
      var stateName = elem.options[elem.selectedIndex].value;
      var stateID = getCountryIDfromName(countryName);
   }
   // Else no centre is selected, so leave centreID undefined.

   // Rebuild selectors.
   createCentreSelector(countryID, stateID, centreID);
   createTeamSelector(countryID, stateID, centreID);
}

/*
 * PRIMARY
 */
function onChangeTeamSelector()
{
}

// Array lookup functions. -----------------------------------------------------------------------//

/*
 * Return a 2d array containing the countryID
 * and StateID corresponding to a given centreID.
 * If no centre corresponding to the given centreID is found, return undefined.
 */
function getCountryIdByCentreId(centreID)
{
   for (var i = 0; i < N_CENTRES; ++i)
     if (CENTRES_ARRAY[i]['centreID'] == centreID)
     {
        var countryAndStateIDs = Array(2);

        countryAndStateIDs[0] = CENTRES_ARRAY[i]['countryID'];
        countryAndStateIDs[1] = CENTRES_ARRAY[i]['stateID'  ];

        return countryAndStateIDs;
     }

   return undefined;
}

/*
 * Return the countryID corresponding to a given country name.
 * If no country corresponding to the given countryID is found, return undefined.
 */
function getCountryIDfromName(countryName)
{
   for (var i = 0; i < N_COUNTRIES; ++i)
     if (COUNTRIES_ARRAY[i]['countryName'] == countryName)
       return COUNTRIES_ARRAY[i]['countryID'];

   return undefined;
}

/*
 *
 */
function getCountryNameFromStateID(stateID)
{
   for (var i = 0; i < N_COUNTRIES; ++i)
     if (COUNTRIES_ARRAY[i]['stateID'] == stateID)
       return COUNTRIES_ARRAY[i]['countryName'];

   return undefined;
}

/*
 * Return the stateID corresponding to a given state name.
 * If no state corresponding to the given stateID is found, return undefined.
 */
function getStateIDfromName(stateName)
{
   for (var i = 0; i < N_STATES; ++i)
     if (STATES_ARRAY[i]['stateName'] == stateName)
       return STATES_ARRAY[i]['stateID'];

   return undefined;
}

/*******************************************END*OF*FILE********************************************/
