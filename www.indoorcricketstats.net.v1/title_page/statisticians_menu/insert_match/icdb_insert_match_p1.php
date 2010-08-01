<?php
 require_once '../../../common/misc/icdb_functions.php';
 require_once '../../../common/misc/misc_functions.php';
 require_once '../../../common/date_and_time/date_functions.php';

 // Clear $_SESSION[] variables.
 $_SESSION = array();

 $_SESSION['n_playersPerTeam'] = 8;

 // These variables should be read from database on team selection from main page.
 $_SESSION['teamID'] = 1;
 $_SESSION['databaseName'] = 'indoor_cricket_database';

 // Initial (displayed) selections for HTML form selectors.
 $_SESSION['selectOppTeamNameString'] = 'Select Opp. Team Name';
 $_SESSION['enterOppTeamNameString' ] = 'Enter Opp. Team Name';
 $_SESSION['selectPlayerNameString' ] = 'Select Player Name';
 $_SESSION['enterPlayerNameString'  ] = 'Enter Player Name';

 // Connect to database.
 connectToMySQL_icdb();

 // Read selected info from DB.
 readTeamName($_SESSION['teamID']);
 readPlayerNames($_SESSION['teamID']);
 readOppTeamNames($_SESSION['teamID']);

 echoDoctypeXHTMLstrictString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../../common/css/style.css" type="text/css" />
  <title>Indoor Cricket Database (Insert Match Part 1)</title>
  <script type="text/javascript" src="../../../common/date_and_time/date_functions.js"></script>
  <script type="text/javascript" src="icdb_insert_match_p1.js"></script>
 </head>
 <body onLoad="init()">
  <form method="POST" action="icdb_insert_match_p2.php" onsubmit="return validate()">
   <table class="backh2">
    <thead>
     <tr>
      <th class="h1 bordersTLRB">Team '<?php echo $_SESSION['teamName']; ?>' Match Players List</th>
     </tr>
    </thead>
    <tbody>
     <tr>
      <td>
       <table class="bordersTLRB">
        <tr><th class="h2 borders___B" colspan="2">Date / Time</th></tr>
        <tr>
         <td width="50%">
          <table width="100%">
           <tr>
            <th class="h3l" width="33%">Day</th>
            <th class="h3d" width="34%">Month</th>
            <th class="h3l borders__R_" width="33%">Year</th>
           </tr>
           <tr>
<?php
 $today = getDate();
 $defaultDate = array('day' => $today['mday'], 'month' => $today['mon'], 'year' => $today['year']);

 $_SESSION['maxYear'] = $today['year'];
 $_SESSION['minYear'] = $_SESSION['maxYear'] - 20;

 $indent = '            ';
 dateSelector($indent, $defaultDate,
              'daySelectorId',
              'monthSelectorId',
              'yearSelectorId',
              'l', 'd', 'l borders__R_',   // Class of HTML td element.
              true, true, true          ); // Use default onchange functions.
?>
           </tr>
          </table>
         </td>
         <td width="50%">
          <table width="100%">
           <tr>
            <th class="h3l" width="33%">Hour</th>
            <th class="h3d" width="34%">Minute</th>
            <th class="h3l" width="33%">AM/PM</th>
           </tr>
           <tr>
<?php
 timeSelector($indent, 'hour', 'minute', 'AM_PM', 'l', 'd', 'l');
?>
           </tr>
          </table>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td>
       <table class="bordersTLRB" width="100%">
        <tr><th class="h2 borders___B" colspan="6">Opposition Team Name</th></tr>
<?php
/*
    if (true)
    {
?>
        <tr>
         <th class="h3d borders___B" colspan="6">
          <input type="checkbox" onClick="removeRetiredOppTeamOptionsFromSelect()">
          Clear Retired
         </th>
        </tr>
<?php
    }
*/
?>
        <tr>
         <td width="25%">
          <input type="checkbox" value="checked"
           id="OppTeamCheckbox" onClick="toggleOppTeamNameSelectorORtext()">
          New
         </td>
         <td id="oppTeamNameTD">
<?php
 $indent = '          ';
 genericSelector('oppTeamName',                         // ID.
                 $indent, 0, -1,                        // Indent, selectedIndex, onChangeFunction.
                 $_SESSION['oppTeamNamesArray'],        // Options array.
                 $_SESSION['selectOppTeamNameString']); // Default string.
?>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td>
       <table class="bordersTLRB" width="100%">
        <!-- <tcaption>Fields marked with * are optional.</tcaption> --!>
        <tr><th class="h2 borders___B" colspan="3">Players List</th></tr>
<?php
/*
 $teamHasRetiredPlayers = true;//teamHasRetiredPlayers();
 $teamHasFillInPlayers  = true;//teamHasFillInPlayers();
 if ($teamHasRetiredPlayers || $teamHasFillInPlayers)
 {
    echo "        <tr>\n";

    if ($teamHasRetiredPlayers)
    {
?>
      <th class="h3" colspan="<?php echo ($teamHasFillInPlayers)? 2: 3; ?>">
       <input type="checkbox" onClick="removeRetiredPlayerOptionsFromSelect()">
       Clear Retired
      </th>
<?php
    }
    if ($teamHasFillInPlayers)
    {
?>
      <th class="h3" colspan="<?php echo ($teamHasRetiredPlayers)? 2: 3; ?>">
       Clear Fill-Ins
       <input type="checkbox" onClick="removeFillInPlayerOptionsFromSelect()">
      </th>
<?php
    }

    echo "        </tr>\n";
 }
*/
?>
<?php
 for ($i = 1; $i <= $_SESSION['n_playersPerTeam']; $i++)
 {
    if (($i - 1) % 4 <= 1) $class = 'l';
    else                   $class = 'd';
?>
        <tr>
         <th class="h3<?php echo $class; ?>"><?php echo $i; ?></th>
         <td class="<?php echo $class; ?>" width="25%">
          <input type="checkbox" onClick="togglePlayerNameSelectorORtext(<?php echo $i; ?>)">
          New
         </td>
         <td class="<?php echo $class; ?>" id="playerName<?php echo $i; ?>TD">
<?php
    // Asterix to indicate optional fields.
    if ($i > $_SESSION['n_playersPerTeam'] - 2)
      echo "          *\n";

    // Indent still 7 spaces.
    genericSelector('playerName' . $i,                             // ID.
                    $indent,                                       // Indent.
                    0,                                             // Selected index.
                    'updateSelectPlayerNameOptions(' . $i . ')',   // OnChange function.
                    $_SESSION['playerNamesArray'],                 // Options array.
                    $_SESSION['selectPlayerNameString']         ); // Default string.

    // Asterix to indicate optional fields.
    if ($i > $_SESSION['n_playersPerTeam'] - 2)
      echo "          *\n";
?>
         </td>
        </tr>
<?php
 }
?>
       </table>
      </td>
     <tr><th class="h1 bordersTLRB"><input value="Continue" type="submit"></th></tr>
    </tbody>
   </table>
  </form>
 </body>
</html>
