<?php
 require_once 'misc_functions.php';

 echoDoctypeXHTMLStrictString();
?>
<html>
 <head>
  <!-- <link rel=StyleSheet href="style.css" type="text/css" /> --!>
  <title>Indoor Cricket Database</title>
 </head>
 <body>
  <form method="post" target="resultId" action="icdb_viewtablesResult.php">
   <table cellspacing="0" border="1" align="center">
    <tr><th colspan="10">Tables</th></tr>
    <tr>
     <th>centres</th>
     <th>countries</th>
     <th>innings</th>
     <th>matches</th>
     <th>opp_teams</th>
     <th>overs</th>
     <th>players</th>
     <th>seasons</th>
     <th>states</th>
     <th>teams</th>
    </tr>
    <tr>
     <td><input type=radio name="tableName" value="centres"></td>
     <td><input type=radio name="tableName" value="countries"></td>
     <td><input type=radio name="tableName" value="innings"></td>
     <td><input type=radio name="tableName" value="matches"></td>
     <td><input type=radio name="tableName" value="opp_teams"></td>
     <td><input type=radio name="tableName" value="overs"></td>
     <td><input type=radio name="tableName" value="players"></td>
     <td><input type=radio name="tableName" value="seasons"></td>
     <td><input type=radio name="tableName" value="states"></td>
     <td><input type=radio name="tableName" value="teams"></td>
    </tr>
    <tr><th colspan="10">Views</th></tr>
    <tr>
     <th>view_batting_partnerships</th>
     <th>view_innings</th>
     <th>view_match_batting_figures</th>
     <th>view_match_bowling_figures</th>
     <th>view_match_contributions</th>
     <th>view_matches</th>
     <th>view_overs</th>
     <th>view_teams_dates_opp_teams</th>
     <th>view_teams_dates_opp_teams_batting_pairs</th>
     <th>view_teams_dates_opp_teams_players</th>
    </tr>
    <tr>
     <td><input type=radio name="tableName" value="view_batting_partnerships"></td>
     <td><input type=radio name="tableName" value="view_innings"></td>
     <td><input type=radio name="tableName" value="view_match_batting_figures"></td>
     <td><input type=radio name="tableName" value="view_match_bowling_figures"></td>
     <td><input type=radio name="tableName" value="view_match_contributions"></td>
     <td><input type=radio name="tableName" value="view_matches"></td>
     <td><input type=radio name="tableName" value="view_overs"></td>
     <td><input type=radio name="tableName" value="view_teams_dates_opp_teams"></td>
     <td><input type=radio name="tableName" value="view_teams_dates_opp_teams_batting_pairs"></td>
     <td><input type=radio name="tableName" value="view_teams_dates_opp_teams_players"></td>
    </tr>
    <tr><th colspan="10">Rankings</th></tr>
    <tr>
     <th>Average Runs<br />Scored Per Innings</th>
     <th>Average Wickets<br />Lost Per Innings</th>
     <th>Average Runs<br />Conceded Per Over</th>
     <th>Average Wickets<br />Taken Per Over</th>
     <th>Average Match<br />Contribution</th>
     <th>Best Runs Scored<br />Per Innings</th>
     <th>Best Runs Scored<br />Per Match</th>
     <th>Best Runs Conceded<br />Per Over</th>
     <th>Best Runs Conceded<br />Per Match</th>
     <th>Best Batting Partnership</th>
    </tr>
    <tr>
     <td><input type=radio name="tableName" value="avg_runs_per_innings"></td>
     <td><input type=radio name="tableName" value="avg_wickets_per_innings"></td>
     <td><input type=radio name="tableName" value="avg_runs_per_over"></td>
     <td><input type=radio name="tableName" value="avg_wickets_per_over"></td>
     <td><input type=radio name="tableName" value="avg_match_contribution"></td>
     <td><input type=radio name="tableName" value="best_runs_per_innings"></td>
     <td><input type=radio name="tableName" value="best_match_batting_figures"></td>
     <td><input type=radio name="tableName" value="best_runs_per_over"></td>
     <td><input type=radio name="tableName" value="best_match_bowling_figures"></td>
     <td><input type=radio name="tableName" value="best_batting_pships"></td>
    </tr>
    <tr><th colspan="10"><input type="submit" value="Submit"></th></tr>
   </table>
  </form>
 </body>
</html>
