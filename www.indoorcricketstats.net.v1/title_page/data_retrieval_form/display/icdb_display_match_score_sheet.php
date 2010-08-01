<?php
 $_SESSION['n_playersPerTeam'] = 8;

 /*
  *
  */
 function displayMatchScoreSheet()
 {
    // Test $_SESSION[] variables to be used in this function.
    if (   !isset($_SESSION['teamName'          ])
        || !isset($_SESSION['oppTeamName'       ])
        || !isset($_SESSION['teamBatted1st'     ])
        || !isset($_SESSION['matchNotes'        ])
        || !isset($_SESSION['teamPenaltyRuns'   ])
        || !isset($_SESSION['oppTeamPenaltyRuns'])
        || !isset($_SESSION['day'               ])
        || !isset($_SESSION['month'             ])
        || !isset($_SESSION['year'              ])
        || !isset($_SESSION['hour'              ])
        || !isset($_SESSION['minute'            ])
        || !isset($_SESSION['AM_PM'             ])
        || !isset($_SESSION['innings'           ])
        || !isset($_SESSION['overs'             ]))
      error(  'Required $_SESSION[] variable not set in '
            . "{$_SERVER['PHP_SELF']}::displayMatchScoreSheet().");

    echoDoctypeXHTMLStrictString();
?>
<html>
 <head>
  <link rel=stylesheet href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   .batt {line-height: 300%;}
   .bowl {line-height: 100%;}
  </style>
  <title>Indoor Cricket Database (Match Score Sheet)</title>
 </head>
 <body>
  <table class="backh2"><?php /* NOTE: cellspacing="0" NOT required for outermost table. */ ?>
   <thead>
    <tr>
     <th class="h1 bordersTLRB" colspan="2">
<?php
    $indent = '      ';

    echo  $indent , "Team '{$_SESSION['teamName']}' Match Scores Sheet<br />\n"
        , $indent , "(Vs. '{$_SESSION['oppTeamName']}' "
                  , "{$_SESSION['day' ]}/{$_SESSION['month' ]}/{$_SESSION['year' ]} "
                  , "{$_SESSION['hour']}:{$_SESSION['minute']}{$_SESSION['AM_PM']})\n";
?>
     </th>
    </tr>
   </thead>
   <tbody>
    <tr>
     <td width="50%">
      <table class="bordersTLRB" width="100%" cellspacing="0"><!-- Batting Table Start --!>
<?php /* NOTE: cellspacing="0" in table declaration above is required by IE. */ ?>
       <thead>
        <tr><th class="h2 borders___B" colspan="5">Batting Scores</th></tr>
        <tr>
         <th class="h3" colspan="4">Batted</th>
         <td><?php echo (($_SESSION['teamBatted1st'])? '1st': '2nd'); ?></td>
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
         <th class="h3<?php echo $class; ?> batt"><?php echo $i; ?></th>
         <td class="<?php echo $class; ?>l">
<?php
       echo '          ',
            $_SESSION['innings'][$i - 1]['firstName'], ' ',
            $_SESSION['innings'][$i - 1]['lastName' ], "\n";
?>
         </td>
         <td class="<?php echo $class; ?>d">
<?php
       echo '          ', $_SESSION['innings'][$i - 1]['wicketsLost'], "\n";
?>
         </td>
         <td class="<?php echo $class; ?>l">
<?php
       echo '          ', $_SESSION['innings'][$i - 1]['runsScored'], "\n";
?>
         </td>
<?php
       if ($i % 2 == 1)
       {
?>
         <th class="h3<?php echo $class; ?>"
          rowspan="2" id="teamPship<?php echo ceil($i / 2); ?>TH">
<?php
          echo   '          ',
                 (  $_SESSION['innings'][$i    ]['wicketsLost']
                  + $_SESSION['innings'][$i - 1]['wicketsLost']), ' / ',
                 (  $_SESSION['innings'][$i    ]['runsScored' ]
                  + $_SESSION['innings'][$i - 1]['runsScored' ]), "\n";
?>
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
         <td class="l" colspan="2">(<?php echo $_SESSION['teamPenaltyRuns']; ?>)</td>
        </tr>
        <tr>
         <th class="h3d" colspan="3">
          '<?php echo $_SESSION['teamName']; ?>'<br />Total Score
         </th>
         <th class="h3l" colspan="2" id="teamScoreTH">
<?php
    // Count total wicketsLost and total runsScored.
    $totalWicketsLost = 0;
    $totalRunsScored  = 0;
    for ($i = 1; $i <= $_SESSION['n_playersPerTeam']; ++$i)
    {
       $totalWicketsLost += $_SESSION['innings'][$i - 1]['wicketsLost'];
       $totalRunsScored  += $_SESSION['innings'][$i - 1]['runsScored' ];
    }

    // Subtract teamPenaltyRuns to give the finalTeamScore.
    $finalTeamScore = $totalRunsScored - $_SESSION['teamPenaltyRuns'];

    // Echo totalWicketsLost and finalTeamScore.
    echo '          ', $totalWicketsLost, ' / ', $finalTeamScore, "\n";
?>
         </th>
        </tr>
       </tbody>
      </table>
     </td>
     <td width="50%">
      <table class="bordersTLRB" width="100%" cellspacing="0"><!-- Bowling Table Start --!>
<?php /* NOTE: cellspacing="0" in table declaration above is required by IE. */ ?>
       <thead>
        <tr><th class="h2 borders___B" colspan="5">Bowling Scores</th></tr>
        <tr>
         <th class="h3" colspan="4">Bowled</th>
         <td><?php echo (($_SESSION['teamBatted1st'])? '2nd': '1st'); ?></td>
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
       echo '          ',
            $_SESSION['overs'][$i - 1]['firstName'], ' ',
            $_SESSION['overs'][$i - 1]['lastName' ], "\n";
?>
         </td>
         <td class="<?php echo $class; ?>d">
<?php
       echo '          ', $_SESSION['overs'][$i - 1]['wicketsTaken'], "\n";
?>
         </td>
         <td class="<?php echo $class; ?>l">
<?php
       echo '          ', $_SESSION['overs'][$i - 1]['runsConceded'], "\n";
?>
         </td>
<?php
       if ($i % 4 == 1)
       {
?>
         <th class="h3<?php echo $class; ?>"
          rowspan="4" id="oppTeamPship<?php echo ceil($i / 4); ?>TH">
<?php
          echo   '          ',
                 (  $_SESSION['overs'][$i - 1]['wicketsTaken']
                  + $_SESSION['overs'][$i    ]['wicketsTaken']
                  + $_SESSION['overs'][$i + 1]['wicketsTaken']
                  + $_SESSION['overs'][$i + 2]['wicketsTaken']), ' / ',
                 (  $_SESSION['overs'][$i - 1]['runsConceded']
                  + $_SESSION['overs'][$i    ]['runsConceded']
                  + $_SESSION['overs'][$i + 1]['runsConceded']
                  + $_SESSION['overs'][$i + 2]['runsConceded']), "\n";
?>
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
         <td class="l" colspan="2">(<?php echo $_SESSION['oppTeamPenaltyRuns']; ?>)</td>
        </tr>
        <tr>
         <th class="h3d" colspan="3">
          '<?php echo $_SESSION['oppTeamName']; ?>'<br />Total Score
         </th>
         <th class="h3l" colspan="2" id="oppTeamScoreTH">
<?php
    // Count total wicketsLost and total runsScored.
    $totalWicketsTaken = 0;
    $totalRunsConceded = 0;
    for ($i = 1; $i <= 2 * $_SESSION['n_playersPerTeam']; ++$i)
    {
       $totalWicketsTaken += $_SESSION['overs'][$i - 1]['wicketsTaken'];
       $totalRunsConceded += $_SESSION['overs'][$i - 1]['runsConceded'];
    }

    // Subtract oppTeamPenaltyRuns to give the finalOppTeamScore.
    $finalOppTeamScore = $totalRunsConceded - $_SESSION['oppTeamPenaltyRuns'];

    // Echo totalWicketsTaken and finalOppTeamScore.
    echo '          ', $totalWicketsTaken, ' / ', $finalOppTeamScore, "\n";
?>
         </th>
        </tr>
       </tbody>
      </table>
     </td>
    </tr>
    <tr>
     <th class="h1 bordersTLRB" colspan="2">
<?php
    $margin = $finalTeamScore - $finalOppTeamScore;
    echo '      ';
    if ($margin == 0)
      echo 'The match was drawn.';
    else
    {
       if ($margin > 0)
         $result = 'won';
       else
       {
          $result = 'lost';
          $margin *= -1;
       }
       echo $_SESSION['teamName'], ' ', $result, ' by ', $margin, ' runs.';
    }
    echo "\n";
?>
     </th>
    </tr>
<?php
    if ($_SESSION['matchNotes'] != '')
    {
?>
    <tr>
     <td colspan="2">
      <table class="bordersTLRB" width="100%" cellspacing="0">
<?php /* NOTE: cellspacing="0" in table declaration above is required by IE. */ ?>
       <thead>
        <tr><th class="h2 borders___B">Match Notes:</th></tr>
        <tr>
         <th class="h3">
<?php
       echo '      ', $_SESSION['matchNotes'];
?>
         </th>
        </tr>
       </thead>
      </table>
     </td>
    </tr>
<?php
    }
?>
   </tbody>
  </table>
 </body>
</html>
<?php
}
?>
