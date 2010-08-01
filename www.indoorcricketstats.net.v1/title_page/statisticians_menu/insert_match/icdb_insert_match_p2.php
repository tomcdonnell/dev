<?php
 require '../../../common/misc/icdb_functions.php';

 // Initial (displayed) selections for HTML form selectors.
 // These will be removed from the selector lists
 // when the pull-down menus are selected (JavaScript).
 $_SESSION['selectBatsmanNameString'] = 'Select Batsman Name';
 $_SESSION['selectBowlerNameString' ] = 'Select Bowler Name';

 // Check that new match is not at same date & time as an existing match ///////////////////////////

 // Connect to database.
 connectToMySQL_icdb();

 // Build query substrings.
 if ($_POST['AM_PM'] == 'AM') $hour24 = $_POST['hour'];
 else                         $hour24 = $_POST['hour'] + 12;
 $timeString = "'{$hour24}:{$_POST['minute']}:00'";
 $dateString = "'{$_POST['yearSelectorId']}-{$_POST['monthSelectorId']}-{$_POST['daySelectorId']}'";

 // Build query.
 $qTestForDuplicateMatch =  "select @matchID := `match_id`\n"
                          . "from `matches`\n"
                          . "where `team_id` = {$_SESSION['teamID']}\n"
                          . "and   `match_date` = $dateString\n"
                          . "and   `match_time` = $timeString";
 // Execute query.
 $r = mysql_query($qTestForDuplicateMatch);

 // Test result.
 if (mysql_error() != '')
   MySQLerror("Could not test for duplicate match in {$_SESSION['PHP_SELF']}",
              mysql_errno(), mysql_error()                                    );
 else
 {
    switch (mysql_num_rows($r))
    {
     case 0: // Zero is the expected result.  Do nothing and continue.
       break;

     case 1: // One match record exists whose date and time are the same as the new match record.
       echoDoctypeXHTMLstrictString();
?>
<html>
 <body>
  <p>
   The database for team '<?php echo $_SESSION['teamName']; ?>' already contains a match on
   <?php printDateString($_POST['day'], $_POST['month'], $_POST['year']); ?> at
   <?php printTimeString($_POST['hour'], $_POST['minute'], $_POST['AM_PM']); ?>.
   <br /><br />
   Here is a summary of that match:
<?php
       echo '<p><br />This feature is not yet implemented.<br /></p>';
?>
   <br /><br />
   Since team '<?php echo $_SESSION['teamName']; ?>' could not have played two different matches
   at the same time, the database will not allow the creation of a new match record whose date and
   time are the same as an existing match record.
   <br /><br />
   You can either modify the existing match record, or delete the existing match
   record (enabling you to create a new match record at that date and time).
   <br /><br />
   <input type="button" value="Modify Existing Match Record">
   <input type="button" value="Delete Existing Match Record">
  </p>
 </body>
</html>
<?php
       exit(0); // Successful exit.
       break;

     default:  
       error(  "Expected 0 or 1 row in result from MySQL query in {$_SERVER['PHP_SELF']},"
             . ' received ' . mysql_num_rows($qCreateMatchIDvar) . ' row(s).'             );
       break;
    }
 }


 // Save $_POST[] variables to $_SESSION[]. ////////////////////////////////////////////////////////

 $_SESSION['oppTeamName'] = $_POST['oppTeamName'  ];
 $_SESSION['day'        ] = $_POST['daySelectorId'];
 switch ($_POST['monthSelectorId'])
 {
  case 'Jan': $_SESSION['month'] =  1; break;
  case 'Feb': $_SESSION['month'] =  2; break;
  case 'Mar': $_SESSION['month'] =  3; break;
  case 'Apr': $_SESSION['month'] =  4; break;
  case 'May': $_SESSION['month'] =  5; break;
  case 'Jun': $_SESSION['month'] =  6; break;
  case 'Jul': $_SESSION['month'] =  7; break;
  case 'Aug': $_SESSION['month'] =  8; break;
  case 'Sep': $_SESSION['month'] =  9; break;
  case 'Oct': $_SESSION['month'] = 10; break;
  case 'Nov': $_SESSION['month'] = 11; break;
  case 'Dec': $_SESSION['month'] = 12; break;
 }
 $_SESSION['year'       ] = $_POST['yearSelectorId'];
 $_SESSION['hour'       ] = $_POST['hour'          ];
 $_SESSION['minute'     ] = $_POST['minute'        ];
 $_SESSION['AM_PM'      ] = $_POST['AM_PM'         ];

 // Create player names array (of players playing in this match) from $_POSTed variables.
 for ($i = 0; $i < $_SESSION['n_playersPerTeam']; $i++)
 {
    $key = 'playerName' . ($i + 1);

    if (   $_POST[$key] != $_SESSION['selectPlayerNameString']
        && $_POST[$key] != $_SESSION['enterPlayerNameString'] )
      $_SESSION['playerNamesThisMatchArray'][$i] = $_POST[$key];
 }

 echoDoctypeXHTMLstrictString();
?>
<html>
 <head>
  <link rel=StyleSheet href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   .batt {line-height: 300%;}
   .bowl {line-height: 100%;}
  </style>
  <title>Indoor Cricket Database (Insert Match Part 2)</title>
  <script type="text/javascript" src="icdb_insert_match_p2.js"></script>
 </head>
 <body onLoad="init()">
  <form method="POST" action="icdb_insert_match_p3.php" onsubmit="return validate()">
   <table class="backh2">
    <thead>
     <tr>
      <th class="h1 bordersTLRB" colspan="2">
<?php
 $indent = '       ';

 echo  $indent , "Team '{$_SESSION['teamName']}' Match Scores Sheet<br />\n"
     , $indent , "(Vs. '{$_SESSION['oppTeamName']}' "
               , "{$_SESSION['day' ]}/{$_SESSION['month' ]}/{$_SESSION['year' ]} "
               , "{$_SESSION['hour']}:{$_SESSION['minute']} {$_SESSION['AM_PM']})\n";
?>
      </th>
     </tr>
    </thead>
    <tbody>
     <tr>
      <td width="50%">
       <table class="bordersTLRB" width="100%"><!-- Batting Table Start --!>
        <thead>
         <tr><th class="h2 borders___B" colspan="5">Batting Scores</th></tr>
         <tr>
          <th class="h3" colspan="4">Batted</th>
          <td>
           <select id="inningsOrderBatt" name="inningsOrderBatt" 
            onChange="updateInningsOrderSelectors('true')">
            <option selected>Select</option>
            <option>1st</option>
            <option>2nd</option>
           </select>
          </td>
         </tr>
         <tr>
          <th class="h3d">Batting<br />Position</th>
          <th class="h3l">Name</th>
          <th class="h3d">Wickets<br />Lost</th>
          <th class="h3l">Runs<br />Scored</th>
          <th class="h3d">P'ship</th>
         </tr>
        </thead>
        <tbody>
<?php
 for ($i = 1; $i <= $_SESSION['n_playersPerTeam']; $i++)
 {
    if (($i - 1) % 4 <= 1) $class = 'l';
    else                   $class = 'd';
?>
         <tr>
          <th class="h3<?php echo $class; ?>" batt><?php echo $i; ?></th>
          <td class="<?php echo $class; ?>l">
<?php
    $indent = '           ';
    genericSelector('batsmanName' . $i,                             // ID.
                    $indent,                                        // Indent.
                    $i,                                             // Selected index.
                    'updateSelectBatsmanNameOptions(' . $i . ')',   // OnChange function.
                    $_SESSION['playerNamesThisMatchArray'],         // Options array.
                    $_SESSION['selectBatsmanNameString']         ); // Default string.
?>
          </td>
          <td class="<?php echo $class; ?>d">
           <input type="text" size="2" maxLength="3" value="0"
            id="wicketsLost<?php echo $i; ?>" name="wicketsLost<?php echo $i; ?>"
            onFocus="clearWicketsLost(<?php echo $i; ?>)"
            onKeyUp="updateTeamScoreAndTeamPship(<?php echo ceil($i / 2); ?>)">
          </td>
          <td class="<?php echo $class; ?>l">
           <input type="text" size="2" maxLength="3"
            id="runsScored<?php echo $i; ?>" name="runsScored<?php echo $i; ?>"
            onKeyUp="updateTeamScoreAndTeamPship(<?php echo ceil($i / 2); ?>)">
          </td>
<?php
    if ($i % 2 == 1)
    {
?>
          <th class="h3<?php echo $class; ?>"
           rowspan="2" id="teamPship<?php echo ceil($i / 2); ?>TH">
           0 / 0
          </th>
<?php
    }
?>
         </tr>
<?php
 }
?>
         <tr>
          <th class="h3l" colspan="3">
           Penalty Runs Against<br />'<?php echo $_SESSION['teamName']; ?>'
          </th>
          <td class="l" colspan="2">
           (
           <input type="text" size="2" maxLength="3" value="0"
            id="teamPenaltyRuns" name="teamPenaltyRuns"
            onFocus="clearTeamPenaltyRuns()" onKeyUp="updateTeamScore()">
           )
          </td>
         </tr>
         <tr>
          <th class="h3d" colspan="3">
           '<?php echo $_SESSION['teamName']; ?>'<br />Total Score
          </th>
          <th class="h3l" colspan="2" id="teamScoreTH">0 / 0</th>
         </tr>
        </tbody>
       </table>
      </td>
      <td width="50%">
       <table class="bordersTLRB" width="100%"> <!-- Bowling Table Start --!>
        <thead>
         <tr><th class="h2 borders___B" colspan="5">Bowling Scores</th></tr>
         <tr>
          <th class="h3" colspan="4">Bowled</th>
          <td>
           <select id="inningsOrderBowl" name="inningsOrderBowl" 
            onChange="updateInningsOrderSelectors('false')">
            <option selected>Select</option>
            <option>1st</option>
            <option>2nd</option>
           </select>
          </td>
         </tr>
         <tr>
          <th class="h3d">Over<br />Number</th>
          <th class="h3l">Name</th>
          <th class="h3d">Wickets<br />Taken</th>
          <th class="h3l">Runs<br />Conceded</th>
          <th class="h3d">Opp.<br />P'ship</th>
         </tr>
        </thead>
        <tbody>
<?php
 for ($i = 1; $i <= 2 * $_SESSION['n_playersPerTeam']; $i++)
 {
    if (($i - 1) % 8 <= 3) $class = 'l';
    else                   $class = 'd';
?>
         <tr>
          <th class="h3<?php echo $class; ?> bowl"><?php echo $i; ?></th>
          <td class="<?php echo $class; ?>l">
<?php
    $indent = '           ';
    genericSelector('bowlerName' . $i,                             // id
                    $indent,                                       // indent
                    -1,                                            // selected index
                    'updateSelectBowlerNameOptions(' . $i . ')',   // onChange function
                    $_SESSION['playerNamesThisMatchArray'],        // options array
                    $_SESSION['selectBowlerNameString']         ); // default string
?>
          </td>
          <td class="<?php echo $class; ?>d">
           <input type="text" size="2" maxLength="3" value="0"
            id="wicketsTaken<?php echo $i; ?>" name="wicketsTaken<?php echo $i; ?>"
            onFocus="clearWicketsTaken(<?php echo $i; ?>)"
            onKeyUp="updateOppTeamScoreAndOppTeamPship(<?php echo ceil($i / 4); ?>)">
          </td>
          <td class="<?php echo $class; ?>l">
           <input type="text" size="2" maxLength="3"
            id="runsConceded<?php echo $i; ?>" name="runsConceded<?php echo $i; ?>"
            onKeyUp="updateOppTeamScoreAndOppTeamPship(<?php echo ceil($i / 4); ?>)">
          </td>
<?php
    if ($i % 4 == 1)
    {
?>
          <th class="h3<?php echo $class; ?>"
           rowspan="4" id="oppTeamPship<?php echo ceil($i / 4); ?>TH">
           0 / 0
          </th>
<?php
    }
?>
         </tr>
<?php
 }
?>
         <tr>
          <th class="h3l" colspan="3">
           Penalty Runs Against<br />'<?php echo $_SESSION['oppTeamName']; ?>'
          </th>
          <td class="l" colspan="2">
           (
           <input type="text" size="2" maxLength="3" value="0"
            id="oppTeamPenaltyRuns" name="oppTeamPenaltyRuns"
            onFocus="clearOppTeamPenaltyRuns()" onKeyUp="updateOppTeamScore()">
           )
          </td>
         </tr>
         <tr>
          <th class="h3d" colspan="3">
           '<?php echo $_SESSION['oppTeamName']; ?>'<br />Total Score
          </th>
          <th class="h3l" colspan="2" id="oppTeamScoreTH">0 / 0</th>
         </tr>
        </tbody>
       </table>
      </td>
     </tr>
     <tr><th class="h1 bordersTLRB" colspan="2"><input value="Continue" type="submit"></th></tr>
    </tbody>
   </table>
  </form>
 </body>
</html>
