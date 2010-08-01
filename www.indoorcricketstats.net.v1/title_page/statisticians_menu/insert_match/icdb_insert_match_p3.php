<?php
require '../../../common/misc/icdb_functions.php';

connectToMySQL_icdb();

// Start transaction.
// NOTE: If any failure occurs between 'start transaction' and 'commit' later,
//       the MySQL database should be returned to its initial state (at 'start transaction'),
//       using the command 'rollback',
//       (or the function 'rollbackMySQLtransaction()' from the file "misc_functions.php").
if (!mysql_query('start transaction'))
{
   MySQLerror
   (
      "Could not start transaction in {$_SERVER['PHP_SELF']}.",
      mysql_errno(), mysql_error()
   );
}
else
{
   debugMsg('Transaction started OK.');
}

// Extract firstNames and lastNames from $_POSTed playerNames. /////////////////////////////////////

$batsmanNamesArray = array
(
   1 => explode(" ", $_POST['batsmanName1']),
   2 => explode(" ", $_POST['batsmanName2']),
   3 => explode(" ", $_POST['batsmanName3']),
   4 => explode(" ", $_POST['batsmanName4']),
   5 => explode(" ", $_POST['batsmanName5']),
   6 => explode(" ", $_POST['batsmanName6']),
   7 => explode(" ", $_POST['batsmanName7']),
   8 => explode(" ", $_POST['batsmanName8'])
);

$bowlerNamesArray = array
(
   1  => explode(" ", $_POST['bowlerName1' ]),
   2  => explode(" ", $_POST['bowlerName2' ]),
   3  => explode(" ", $_POST['bowlerName3' ]),
   4  => explode(" ", $_POST['bowlerName4' ]),
   5  => explode(" ", $_POST['bowlerName5' ]),
   6  => explode(" ", $_POST['bowlerName6' ]),
   7  => explode(" ", $_POST['bowlerName7' ]),
   8  => explode(" ", $_POST['bowlerName8' ]),
   9  => explode(" ", $_POST['bowlerName9' ]),
   10 => explode(" ", $_POST['bowlerName10']),
   11 => explode(" ", $_POST['bowlerName11']),
   12 => explode(" ", $_POST['bowlerName12']),
   13 => explode(" ", $_POST['bowlerName13']),
   14 => explode(" ", $_POST['bowlerName14']),
   15 => explode(" ", $_POST['bowlerName15']),
   16 => explode(" ", $_POST['bowlerName16'])
);

$_SESSION['wicketsLostArray'] = array
(
   1 => $_POST['wicketsLost1'],
   2 => $_POST['wicketsLost2'],
   3 => $_POST['wicketsLost3'],
   4 => $_POST['wicketsLost4'],
   5 => $_POST['wicketsLost5'],
   6 => $_POST['wicketsLost6'],
   7 => $_POST['wicketsLost7'],
   8 => $_POST['wicketsLost8']
);

$_SESSION['runsScoredArray'] = array
(
   1 => $_POST['runsScored1'],
   2 => $_POST['runsScored2'],
   3 => $_POST['runsScored3'],
   4 => $_POST['runsScored4'],
   5 => $_POST['runsScored5'],
   6 => $_POST['runsScored6'],
   7 => $_POST['runsScored7'],
   8 => $_POST['runsScored8']
);

$_SESSION['wicketsTakenArray'] = array
(
   1  => $_POST['wicketsTaken1' ],
   2  => $_POST['wicketsTaken2' ],
   3  => $_POST['wicketsTaken3' ],
   4  => $_POST['wicketsTaken4' ],
   5  => $_POST['wicketsTaken5' ],
   6  => $_POST['wicketsTaken6' ],
   7  => $_POST['wicketsTaken7' ],
   8  => $_POST['wicketsTaken8' ],
   9  => $_POST['wicketsTaken9' ],
   10 => $_POST['wicketsTaken10'],
   11 => $_POST['wicketsTaken11'],
   12 => $_POST['wicketsTaken12'],
   13 => $_POST['wicketsTaken13'],
   14 => $_POST['wicketsTaken14'],
   15 => $_POST['wicketsTaken15'],
   16 => $_POST['wicketsTaken16']
);

$_SESSION['runsConcededArray'] = array
(
   1  => $_POST['runsConceded1' ],
   2  => $_POST['runsConceded2' ],
   3  => $_POST['runsConceded3' ],
   4  => $_POST['runsConceded4' ],
   5  => $_POST['runsConceded5' ],
   6  => $_POST['runsConceded6' ],
   7  => $_POST['runsConceded7' ],
   8  => $_POST['runsConceded8' ],
   9  => $_POST['runsConceded9' ],
   10 => $_POST['runsConceded10'],
   11 => $_POST['runsConceded11'],
   12 => $_POST['runsConceded12'],
   13 => $_POST['runsConceded13'],
   14 => $_POST['runsConceded14'],
   15 => $_POST['runsConceded15'],
   16 => $_POST['runsConceded16']
);

// Insert oppTeamName into database, ///////////////////////////////////////////////////////////////
// and create MySQL variable for oppTeam_ID. ///////////////////////////////////////////////////////

// Build query.
$qCreateOppTeamIDvar =
(
   'select @oppTeamID := `opp_team_id`' . "\n" .
   'from `opp_teams`' . "\n" .
   'where `team_id` = ' . $_SESSION['teamID'] . "\n" .
   'and   `opp_team_name` = ' . $_SESSION['oppTeamName'] . "\n"
);

// Execute query.
$r = mysql_query($qCreateOppTeamIDvar);

// Test result.
if (mysql_error() != '')
{
   MySQLerrorRequiringMySQLrollback
   (
      'Could not create opp. team name variable (1) in ' . $_SERVER['PHP_SELF'] '.',
      mysql_errno(), mysql_error()
   );
}
else
{
   switch (mysql_num_rows($r))
   {
    case 1:
      debugMsg("Opp. team name already in database.");
      debugMsg("MySQL opp. team ID variable created.");
      break;

    case 0:
      debugMsg("Opp. team name not in database (must be inserted).");


      // Insert team name into database. /////////////////////////////////////////////////////

      // Build query.
      $q =
      (
         'insert into `opp_teams`' . "\n" .
         'set `team_id` = ' . $_SESSION['teamID'] . ",\n"
         '    `opp_team_name` = "' . $_SESSION['oppTeamName'] . '"'
      );

      // Execute query.
      mysql_query($q);

      // Test result.
      if (mysql_error() != '')
      {
         MySQLerrorRequiringMySQLrollback
         (
            'Could not insert opp. team name in "' . $_SERVER['PHP_SELF'] . ".\n",
            mysql_errno(), mysql_error()
         );
      }
      else
      {
         debugMsg("Opp. team name inserted into database.");
      }


      // Create mysql variables for oppTeamID. ///////////////////////////////////////////////

      // Execute query (built earlier).
      $r = mysql_query($qCreateOppTeamIDvar);

      // Test result of query
      if (mysql_error() != '')
      {
         MySQLerrorRequiringMySQLrollback
         (
            'Could not create opp team name variable in "' . $_SERVER['PHP_SELF'] . '" (2).',
            mysql_errno(), mysql_error()
         );
      }
      else
      {
         switch (mysql_num_rows($r))
         {
          case 1:
            debugMsg("MySQL opp. team ID variable created.");
            break;

          default:
            errorRequiringMySQLrollback
            (
               'Expected 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
               '" received ' . mysql_num_rows($r) . ' row(s).'
            );
            break;
         }
      }
      break;

    default:
      errorRequiringMySQLrollback
      (
         'Expected 0 or 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
         '" received ' . mysql_num_rows($r) . ' row(s).'
      );
      break;
   }
}


// Insert match title into database, ///////////////////////////////////////////////////////////////
// and create MySQL variable for matchID. //////////////////////////////////////////////////////////

// Build query substrings.
if ($_SESSION['AM_PM'] == 'AM') $hour24 = $_SESSION['hour'];
else                            $hour24 = $_SESSION['hour'] + 12;
$timeString = "'{$hour24}:{$_SESSION['minute']}:00'";
$dateString = "'{$_SESSION['year']}-{$_SESSION['month']}-{$_SESSION['day']}'";

// Build query.
$qCreateMatchIDvar =
(
   'select @matchID := `match_id`' . "\n" .
   'from `matches`' . "\n" .
   'where `team_id` = ' . $_SESSION['teamID'] . "\n" .
   'and   `opp_team_id` = @oppTeamID' . "\n" .
   'and   `match_date` = $dateString' . "\n" .
   'and   `match_time` = $timeString'
);

// Execute query.
$r = mysql_query($qCreateMatchIDvar);

// Test result.
if (mysql_error() != '')
{
   MySQLerrorRequiringMySQLrollback
   (
      'Could not select match_id in "' . $_SERVER['PHP_SELF'] . '" (1).',
      mysql_errno(), mysql_error()
   );
}
else
{
   switch (mysql_num_rows($r))
   {
    case 1:
      debugMsg("Match title already in database.");
      debugMsg("MySQL match ID variable created.");
      break;

    case 0:
      debugMsg('Match title not in database, so must be inserted.');

      // Insert match title into database. //////////////////////////////////////////////////////

      // Build query substring.
      if ($_POST['inningsOrderBatt'] == '1st') $teamBatted1stString = 'true' ;
      else                                     $teamBatted1stString = 'false';

      // Build query.
      $q =
      (
         'insert into `matches`' . "\n" .
         'set `team_id` = ' . $_SESSION['teamID'] . "\n" .
         '    `opp_team_id` = @oppTeamID,' . "\n" .
         '    `match_date` = $dateString,' . "\n" .
         '    `match_time` = $timeString,' . "\n" .
         '    `team_batted_1st` = $teamBatted1stString'
      );

      // Execute query.
      mysql_query($q);

      // Test result.
      if (mysql_error() != '')
      {
         
         MySQLerrorRequiringMySQLrollback
         (
            'Could not insert match title in "' . $_SERVER['PHP_SELF'] . '".',
            mysql_errno(), mysql_error()
         );
      }
      else
      {
         debugMsg('Match title inserted into database.');
      }


      // Create MySQL variable for matchID. /////////////////////////////////////////////////////

      // Execute query (built earlier).
      $r = mysql_query($qCreateMatchIDvar);

      // test result
      if (mysql_error() != '')
      {
         MySQLerrorRequiringMySQLrollback
         (
            'Could not select match_id in "' . $_SERVER['PHP_SELF'] . '" (2).',
            mysql_errno(), mysql_error()
         );
      }
      else
      {
         switch (mysql_num_rows($r))
         {
          case 1:
            debugMsg('MySQL match ID variable created.');
            break;

          default:
            errorRequiringMySQLrollback
            (
               'Expected 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
               '", received ' . mysql_num_rows($qCreateMatchIDvar) . ' row(s).'
            );
            break;
         }
      }
      break;

    default:
      errorRequiringMySQLrollback
      (
         'Expected 0 or 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
         '", received ' . mysql_num_rows($r) . ' row(s).'
      );
      break;
   }
}


// Insert players, player-team connections, ////////////////////////////////////////////////////////
// and batting scores into database, ///////////////////////////////////////////////////////////////

for ($i = 1; $i <= 8; $i++)
{
   debugMsg("Player ($i) start."); // Separator line in debug output for easier reading.


   // Insert player into database. //////////////////////////////////////////////////////////////

   // Build query.
   $qCreatePlayerIDvar =
   (
      'select @playerID := `player_id`' . "\n" .
      'from `players`' . "\n" .
      'where `team_id` = "' . $_SESSION['teamID'] . "\"\n" .
      'and   `first_name` = "' . $batsmanNamesArray[$i][0] . "\"\n"
   );
   if (count($batsmanNamesArray[$i]) == 2)
   {
      $qCreatePlayerIDvar .= "and   `last_name` = \"{$batsmanNamesArray[$i][1]}\"";
   }
   else
   {
      $qCreatePlayerIDvar .= "and   `last_name` = \"\"";
   }

   // Execute query.
   $r = mysql_query($qCreatePlayerIDvar);

   // Test result.
   if (mysql_error() != '')
   {
      MySQLerrorRequiringMySQLrollback
      (
         'Could not create MySQL player (' . $i . ') ID variable in "' .
         $_SERVER['PHP_SELF'] . '"(1).',
         mysql_errno(), mysql_error()
      );
   }
   else
   {
      switch (mysql_num_rows($r))
      {
       case 1:
         debugMsg("Player ($i) already in database.");
         debugMsg("MySQL player ($i) ID variable created.");
         break;

       case 0:
         debugMsg("Player ($i) not in database (must be inserted).");

         // Build query
         $q =
         (
            'insert into `players`' . "\n" .
            'set `team_id` = ' . $_SESSION['teamID'] . ",\n" .
            '    `first_name` = "' . $batsmanNamesArray[$i][0] . "\"" // First name.
         );
         if (count($batsmanNamesArray[$i]) == 2)
         {
            $q .= ",   `last_name` = \"{$batsmanNamesArray[$i][1]}\"";   // Last name.
         }
         else
         {
            $q .= ",   `last_name` = \"\"";
         }

         // Execute query.
         mysql_query($q);

         // Test result.
         if (mysql_error() != '')
         {
            MySQLerrorRequiringMySQLrollback
            (
               'Could not insert player in "' . $_SERVER['PHP_SELF'] . '".',
               mysql_errno(), mysql_error()
            );
         }
         else
         {
            debugMsg("Player ($i) inserted into database.");
         }


         // Create MySQL playerID variable. /////////////////////////////////////////////////////

         // Execute query (built earlier).
         $r = mysql_query($qCreatePlayerIDvar);

         // Test result.
         if (mysql_error() != '')
         {
            MySQLerrorRequiringMySQLrollback
            (
               'Could not create MySQL player (' . $i . ') ' .
               'ID variable in "' . $_SERVER['PHP_SELF'] . '" (2).',
               mysql_errno(), mysql_error()
            );  
         }
         else
         {
            switch (mysql_num_rows($r))
            {
             case 1:
               debugMsg("MySQL player ($i) ID variable created.");
               break;

             default:
               errorRequiringMySQLrollback
               (
                  'Expected 1 row in result from MySQL query in "' . $_SESSION['PHP_SELF'] .
                  '", received ' . mysql_num_rows($r) . ' row(s).'
               );
               break;
            }
         }
         break;

       default:
         errorRequiringMySQLrollback
         (
            'Expected 0 or 1 row in result from MySQL query in "' . $_SESSION['PHP_SELF'] .
            '", received ' . mysql_num_rows($r) . ' row(s).'
         );
         break;
      }
   }


   // Insert batting innings into database. /////////////////////////////////////////////////////

   // Build and execute query (to test whether batting innings is already in database)
   mysql_query
   (
      'select * from `innings`' . "\n" .
      'where `team_id` = ' . $_SESSION['teamID'] . "\n" .
      'and   `match_id` = @matchID' . "\n" .
      'and   `batting_pos` = ' . $i
   );

   // Test result.
   if (mysql_error() != '')
   {
      MySQLerrorRequiringMySQLrollback
      (
         'Could not select * from innings in "' . $_SESSION['PHP_SELF'] . '".',
         mysql_errno(), mysql_error()
      );
   }
   else
   {
      switch (mysql_num_rows($r))
      {
       case 1:
         debugMsg("Batting innings ($i) already in database.");
         break;

       case 0:
         debugMsg("Batting innings ($i) not in database (must be inserted).");

         // Build and execute query.
         mysql_query
         (
            'insert into `innings`' . "\n" .
            'set `team_id` = ' . $_SESSION['teamID'] . "\",\n" .
            '    `match_id` = @matchID,' . "\n" .
            '    `player_id` = @playerID,' . "\n" .
            '    `batting_pos` = $i,' . "\n" .
            '    `wickets_lost` = ' . $_SESSION['wicketsLostArray'][$i] . ",\n" .
            '    `runs_scored` = ' . $_SESSION['runsScoredArray'][$i]
         );

         // Test result
         if (mysql_error() != '')
         {
            MySQLerrorRequiringMySQLrollback
            (
               'Could not insert into innings in "' . $_SERVER['PHP_SELF'] . '".',
               mysql_errno(), mysql_error()
            );
         }
         else
         {
            debugMsg("Batting innings ($i) inserted into database.");
         }
         break;

       default:
         errorRequiringMySQLrollback
         (
            'Expected 0 or 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
            '", received ' . mysql_num_rows($r) . ' row(s).'
         );
      }
   }
}


// Insert overs into database. //////////////////////////////////////////////////////////////////

for ($i = 1; $i <= 16; $i++)
{
   debugMsg("Player ($i) start."); // Separator line in debug output for easier reading.

   // Build query (player already in database (tested earlier), so create MySQL variable).
   $qCreatePlayerIDvar =
   (
      'select @playerID := `player_id`' . "\n" .
      'from `players`' . "\n" .
      'where `team_id` = ' . $_SESSION['teamID'] . "\n" .
      'and   `first_name` = "' . $bowlerNamesArray[$i][0] . "\"\n"
   );
   if (count($bowlerNamesArray[$i]) == 2)
   {
      $qCreatePlayerIDvar .= 'and    `last_name` = "' . $bowlerNamesArray[$i][1] . '"';
   }
   else
   {
      $qCreatePlayerIDvar .= 'and    `last_name` = ""';
   }

   // Execute query.
   $r = mysql_query($qCreatePlayerIDvar);

   // Test result
   if (mysql_error() != '')
   {
      MySQLerrorRequiringMySQLrollback
      (
         'Could not create player ID (' . $i . ') variable in ' . $_SERVER['PHP_SELF'] . ' (1).',
         mysql_errno(), mysql_error()
      );
   }
   else
   {
      switch (mysql_num_rows($r))
      {
       case 1:
         debugMsg("Player ID ($i) variable created.");
         break;

       default:
         errorRequiringMySQLrollback
         (
            'Expected 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
            '", received ' . mysql_num_rows($r) . ' row(s).'
         );
         break;
      }

      // Build and execute query (to test whether over is already in database).
      mysql_query
      (
         'select * from `overs`' . "\n" .
         'where `team_id` = ' . $_SESSION['teamID'] . "\n" .
         'and   `match_id` = @matchID' . "\n" .
         'and   `player_id` = @playerID' . "\n" .
         'and   `over_no` = ' . $i
      );

      // Test result of query.
      if (mysql_error() != '')
      {
         MySQLerrorRequiringMySQLrollback
         (
            'Could not select * from overs (where over_no = ' . $i . ') in "' .
            $_SESSION['PHP_SELF'] . '".',
            mysql_errno(), mysql_error()
         );
      }
      else
      {
         switch (mysql_num_rows($r))
         {
          case 1:
            debugMsg("Over ($i) already in database.");
            break;

          case 0:
            debugMsg("Over ($i) not in database (must be inserted).");

            // Build query.
            mysql_query
            (
               'insert into `overs`' . "\n" .
               'set `team_id` = ' . $_SESSION['teamID'] ",\n" .
               '    `match_id` = @matchID,' . "\n" .
               '    `player_id` = @playerID,' . "\n" .
               '    `over_no` = ' . $i . ",\n" .
               '    `wickets_taken` = ' . $_SESSION['wicketsTakenArray'][$i] ",\n" .
               '    `runs_conceded` = ' . $_SESSION['runsConcededArray'][$i]
            );

            // Test result.
            if (mysql_error() != '')
            {
               MySQLerrorRequiringMySQLrollback
               (
                  'Could not insert into overs in "' . $_SERVER['PHP_SELF'] . '".';
                  $errMsg, mysql_errno(), mysql_error()
               );
            }
            else
              debugMsg("Over ($i) inserted into database.");
            break;

          default:
            errorRequiringMySQLrollback
            (
               'Expected 0 or 1 row in result from MySQL query in "' . $_SERVER['PHP_SELF'] .
               '", received ' . mysql_num_rows($r) . ' row(s).'
            );
            break;
         }
      }
   }
}

// Commit transaction.
if (!mysql_query('commit'))
{
   MySQLerrorRequiringMySQLrollback
   (
      'Could not commit transaction in "' . $_SERVER['PHP_SELF'] . '".',
      mysql_errno(), mysql_error()
   );

   // Rollback transaction.
   rollbackMySQLtransaction();
}
else
{
   debugMsg('Transaction committed OK.');
}
?>
<html>
 <head><title>Indoor Cricket Database (Insert Match Part 3)</title></head>
 <body></body>
</html>
