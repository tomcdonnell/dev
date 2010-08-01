<?php
 require_once 'icdb_functions.php';
 require_once 'icdb_display_MySQL_table.php';

 $_SESSION['databaseName'] = 'indoor_cricket_database';

 $start_date_string  = "'1900-01-01'";
 //$start_date_string  = "'2005-10-20'";
 //$finish_date_string = "'2006-03-30'";
 //$start_date_string  = "'2006-07-20'";
 $finish_date_string = "'2020-01-01'";

 connectToMySQL_icdb()

 echoDoctypeXHTMLStrictString();

 if (isset($_POST['tableName'])) ///////////////////////////////////////////////////////////////////
 {
?>
<html>
 <head>
  <!-- <link rel=StyleSheet href="style.css" type="text/css" /> --!>
  <title>Indoor Cricket Database</title>
 </head>
 <body>
<?php
    $indent = '  ';

    switch ($_POST['tableName'])
    {
     // Tables //////////////////////////////////////////////////////////////////////////////////

     case 'centres':
       $MySQLquery =  "select `centre_id`, `centre_name`, `country_id`, `state_id`, `address`\n"
                    . "from `centres`\n"
                    . "order by `centre_id` asc";
       $heading = 'centres';
       $colHeadingsArray = array(0 => 'centre_id', 1 => 'centre_name',
                                 2 => 'country_id', 3 => 'state_id', 4 => 'address');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'countries':
       $MySQLquery =  "select `country_id`, `country_name` from countries\n"
                    . "order by `country_id` asc";
       $heading = 'countries';
       $colHeadingsArray = array(0 => 'country_id', 1 => 'country_name');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'innings':
       $MySQLquery =  "select `match_id`, `player_id`, `batting_pos`,\n"
                    . "       `runs_scored`, `wickets_lost`, `team_id`\n"
                    . "from `innings`\n"
                    . "order by `team_id` asc, `match_id` asc, `batting_pos` asc";
       $heading = 'innings';
       $colHeadingsArray = array(0 => 'match_id', 1 => 'player_id', 2 => 'batting_pos',
                                 3 => 'runs_scored', 4 => 'wickets_lost', 5 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'matches':
       $MySQLquery =  "select `match_id`, `opp_team_id`, `match_date`, `match_time`,\n"
                    . "       `team_batted_1st`, `match_notes`, `team_id`\n"
                    . "from `matches`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc";
       $heading = 'matches';
       $colHeadingsArray = array(0 => 'match_id', 1 => 'opp_team_id',
                                 2 => 'match_date', 3 => 'match_time',
                                 4 => 'team_batted_1st', 5 => 'match_notes', 6 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'opp_teams':
       $MySQLquery =  "select `opp_team_id`, `opp_team_name`, `retired`, `team_id`\n"
                    . "from `opp_teams`\n"
                    . "order by `team_id` asc, `opp_team_id` asc";
       $heading = 'opp_teams';
       $colHeadingsArray = array(0 => 'opp_team_id', 1 => 'opp_team_name',
                                 2 => 'retired', 3 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'overs':
       $MySQLquery =  "select `match_id`, `player_id`, `over_no`, `wickets_taken`,\n"
                    . "       `runs_conceded`, `team_id`\n"
                    . "from `overs`\n"
                    . "order by `team_id` asc, `match_id` asc, `over_no` asc";
       $heading = 'overs';
       $colHeadingsArray = array(0 => 'match_id', 1 => 'player_id', 2 => 'over_no',
                                 3 => 'wickets_taken', 4 => 'runs_conceded', 5 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'players':
       $MySQLquery =  "select `player_id`, `first_name`, `last_name`,\n"
                    . "       `retired`, `fill_in`, `team_id`\n"
                    . "from `players`\n"
                    . "order by `team_id` asc, `player_id` asc";
       $heading = 'players';
       $colHeadingsArray = array(0 => 'player_id', 1 => 'first_name', 2 => 'last_name',
                                 3 => 'retired', 4 => 'fill_in', 5 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'seasons':
       $MySQLquery =  "select `season_id`, `season_name`,\n"
                    . "       `start_date`, `finish_date`, `team_id`\n"
                    . "from `seasons`\n"
                    . "order by `team_id` asc, `season_id` asc";
       $heading = 'seasons';
       $colHeadingsArray = array(0 => 'season_id', 1 => 'season_name', 2 => 'start_date',
                                 3 => 'finish_date', 4 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'states':
       $MySQLquery =  "select `state_id`, `state_name`, `country_id` from `states`\n"
                    . "order by `team_id` asc, `state_id` asc";
       $heading = 'states';
       $colHeadingsArray = array(0 => 'state_id', 1 => 'state_name', 2 => 'country_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'teams':
       $MySQLquery =  "select `team_id`, `team_name`, `centre_id`, `retired` from `countries`\n"
                    . "order by `team_id` asc";
       $heading = 'teams';
       $colHeadingsArray = array(0 => 'team_id', 1 => 'team_name',
                                 2 => 'centre_id', 3 => 'retired');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     // Views ///////////////////////////////////////////////////////////////////////////////////

     case 'view_batting_partnerships':
       $MySQLquery =  "select `team_id`, `match_id`, `match_date`, `match_time`,\n"
                    . "       `opp_team_id`, `opp_team_name`,\n"
                    . "       `p1_batting_pos`, `p1_player_id`,\n"
                    . "       `p1_first_name`, `p1_last_name`,\n"
                    . "       `p1_runs_scored`, `p1_wickets_lost`,\n"
                    . "       `p2_batting_pos`, `p2_player_id`,\n"
                    . "       `p2_first_name`, `p2_last_name`,\n"
                    . "       `p2_runs_scored`, `p2_wickets_lost`,\n"
                    . "       `partnership_wickets`, `partnership_runs`\n"
                    . "from `view_batting_partnerships`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc";
       $heading = "view_batting_partnerships";
       $colHeadingsArray = array(0 => 'team_id',
                                 1 => 'match_id', 2 => 'match_date', 3 => 'match_time',
                                 4 => 'opp_team_id', 5 => 'opp_team_name',
                                 6 => 'p1_batting_pos', 7 => 'p1_player_id',
                                 8 => 'p1_first_name', 9 => 'p1_last_name',
                                 10 => 'p1_runs_scored', 11 => 'p1_wickets_lost',
                                 12 => 'p2_batting_pos', 13 => 'p2_player_id',
                                 14 => 'p2_first_name', 15 => 'p2_last_name',
                                 16 => 'p2_runs_scored', 17 => 'p2_wickets_lost',
                                 18 => 'partnership_wickets', 19 => 'partnership_runs');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_innings':
       $MySQLquery =  "select `match_id`, `player_id`, `batting_pos`,\n"
                    . "       `runs_scored`, `wickets_lost`, `team_id`\n"
                    . "from   `view_innings`\n"
                    . "order by `team_id` asc, `runs_scored` desc,\n"
                    . "         `match_date` asc, `batting_pos` asc";
       $heading = 'view_innings';
       $colHeadingsArray = array(0 => 'match_id', 1 => 'player_id',
                                 2 => 'batting_pos', 3 => 'runs_scored',
                                 4 => 'wickets_lost', 5 => 'team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_match_batting_figures':
       $MySQLquery =  "select `team_id`, `match_id`, `match_date`, `match_time`,\n"
                    . "       `opp_team_id`, `opp_team_name`,\n"
                    . "       `player_id`, `first_name`, `last_name`, `n_innings`,\n"
                    . "       `avg_wickets_lost_per_innings`,\n"
                    . "       `avg_runs_scored_per_innings`\n"
                    . "from `view_match_batting_figures`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc";
       $heading = "view_match_batting_figures";
       $colHeadingsArray = array(0 => 'team_id',
                                 1 => 'match_id', 2 => 'match_date', 3 => 'match_time',
                                 4 => 'opp_team_id', 5 => 'opp_team_name',
                                 6 => 'player_id', 7 => 'first_name', 8 => 'last_name',
                                 9 => 'n_innings', 10 => 'avg_wickets_lost_per_innings',
                                 11 => 'avg_runs_scored_per_innings');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_match_bowling_figures':
       $MySQLquery =  "select `team_id`, `match_id`, `match_date`, `match_time`,\n"
                    . "       `opp_team_id`, `opp_team_name`,\n"
                    . "       `player_id`, `first_name`, `last_name`, `n_overs`,\n"
                    . "       `avg_wickets_taken_per_2_overs`,\n"
                    . "       `avg_runs_conceded_per_2_overs`\n"
                    . "from `view_match_bowling_figures`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc";
       $heading = "view_match_bowling_figures";
       $colHeadingsArray = array(0 => 'team_id',
                                 1 => 'match_id', 2 => 'match_date', 3 => 'match_time',
                                 4 => 'opp_team_id', 5 => 'opp_team_name',
                                 6 => 'player_id', 7 => 'first_name', 8 => 'last_name',
                                 9 => 'n_overs', 10 => 'avg_wickets_taken_per_2_overs',
                                 11 => 'avg_runs_conceded_per_2_overs');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_match_contributions':
       $MySQLquery =  "select `team_id`, `match_id`, `match_date`, `match_time`,\n"
                    . "       `opp_team_id`, `opp_team_name`,\n"
                    . "       `player_id`, `first_name`, `last_name`,\n"
                    . "       `n_innings`, `avg_runs_scored_per_innings`,\n"
                    . "       `n_overs`, `avg_runs_conceded_per_2_overs`,\n"
                    . "       `match_contribution`\n"
                    . "from `view_match_contributions`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc";
       $heading = "view_match_contributions";
       $colHeadingsArray = array(0 => 'team_id',
                                 1 => 'match_id', 2 => 'match_date', 3 => 'match_time',
                                 4 => 'opp_team_id', 5 => 'opp_team_name',
                                 6 => 'player_id', 7 => 'first_name', 8 => 'last_name',
                                 9 => 'n_innings', 10 => 'avg_runs_scored_per_innings',
                                 11 => 'n_overs', 12 => 'avg_runs_conceded_per_2_overs',
                                 13 => 'match_contribution');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_matches':
       $MySQLquery =  "select `team_id`, `match_id`, `match_date`, `match_time`,\n"
                    . "       `opp_team_id`, `opp_team_name`, `team_batted_1st`, `match_notes`\n"
                    . "from `view_matches`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc";
       $heading = "view_matches";
       $colHeadingsArray = array(0 => 'team_id',
                                 1 => 'match_id', 2 => 'match_date', 3 => 'match_time',
                                 4 => 'opp_team_id', 5 => 'opp_team_name',
                                 6 => 'team_batted_1st', 7 => 'match_notes');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_overs':
       $MySQLquery =  "select `team_id`, `match_id`, `match_date`, `match_time`,\n"
                    . "       `opp_team_id`, `opp_team_name`, `over_no`,\n"
                    . "       `player_id`, `first_name`, `last_name`,\n"
                    . "       `wickets_taken`, `runs_conceded`\n"
                    . "from `view_overs`\n"
                    . "order by `team_id` asc, `match_date` asc, `match_time` asc, `over_no` asc";
       $heading = "view_overs";
       $colHeadingsArray = array(0 => 'team_id',
                                 1 => 'match_id', 2 => 'match_date', 3 => 'match_time',
                                 4 => 'opp_team_id', 5 => 'opp_team_name', 6 => 'over_no',
                                 7 => 'player_id', 8 => 'first_name', 9 => 'last_name',
                                 10 => 'wickets_taken', 11 => 'runs_conceded');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_teams_dates_opp_teams':
       $MySQLquery =  "select `team_id`, `match_date`, `opp_team_id`\n"
                    . "from `view_teams_dates_opp_teams`\n"
                    . "order by `team_id` asc, `match_date` asc";
       $heading = "view_teams_dates_opp_teams";
       $colHeadingsArray = array(0 => 'team_id', 1 => 'match_date', 2 => 'opp_team_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_teams_dates_opp_teams_batting_pairs':
       $MySQLquery =  "select `team_id`, `match_date`, `opp_team_id`,\n"
                    . "       `player_1_id`, `player_2_id`\n"
                    . "from `view_teams_dates_opp_teams_batting_pairs`\n"
                    . "order by `team_id` asc, `match_date` asc";
       $heading = "view_teams_dates_opp_teams";
       $colHeadingsArray = array(0 => 'team_id', 1 => 'match_date', 2 => 'opp_team_id',
                                 3 => 'player_1_id', 4 => 'player_2_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'view_teams_dates_opp_teams_players':
       $MySQLquery =  "select `team_id`, `match_date`, `opp_team_id`, `player_id`\n"
                    . "from `view_teams_dates_opp_teams_players`\n"
                    . "order by `team_id` asc, `match_date` asc";
       $heading = "view_teams_dates_opp_teams";
       $colHeadingsArray = array(0 => 'team_id', 1 => 'match_date', 2 => 'opp_team_id',
                                 3 => 'player_id');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     // Rankings ////////////////////////////////////////////////////////////////////////////////

     case 'avg_runs_per_innings':
       $MySQLquery =  "select first_name, last_name,\n"
                    . "       count(distinct match_id), count(*), avg(runs_scored)\n"
                    . "from view_innings\n"
                    . "where team_id = 1\n"
//                    . "and   count(*) >= 4\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "group by player_id\n"
                    . "order by avg(runs_scored) desc";
       $heading =  "Two Dogs<br />"
                 . "Average Runs Scored Per Innings Rankings<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
//                 . "Minimum Matches = 4";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name', 2 => 'Matches',
                                 3 => 'Innings', 4 => 'Avg. Runs<br />Per Innings');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'avg_wickets_per_innings':
       $MySQLquery =  "select first_name, last_name,\n"
                    . "       count(distinct match_id), count(*), avg(wickets_lost)\n"
                    . "from view_innings\n"
                    . "where team_id = 1\n"
//                    . "and   matches >= 4\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "group by player_id\n"
                    . "order by avg(wickets_lost) asc";
       $heading =  "Two Dogs<br />"
                 . "Average Wickets Lost Per Innings Rankings<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
//                 . "Minimum Matches = 4";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Matches', 3 => 'Overs',
                                 4 => 'Avg. Wickets<br />Per Innings');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'avg_runs_per_over':
       $MySQLquery =  "select first_name, last_name,\n"
                    . "       count(distinct match_id), count(*), avg(runs_conceded)\n"
                    . "from view_overs\n"
                    . "where team_id = 1\n"
//                    . "and   matches >= 4\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "group by player_id\n"
                    . "order by avg(runs_conceded) asc";
       $heading =  "Two Dogs<br />"
                 . "Average Runs Conceded Per Over Rankings<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
//                 . "Minimum Matches = 4";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Matches', 3 => 'Overs', 4 => 'Avg. Runs<br />Per Over');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'avg_wickets_per_over':
       $MySQLquery =  "select first_name, last_name,\n"
                    . "       count(distinct match_id), count(*), avg(wickets_taken)\n"
                    . "from view_overs\n"
                    . "where team_id = 1\n"
//                    . "and   matches >= 4\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "group by player_id\n"
                    . "order by avg(wickets_taken) desc";
       $heading =  "Two Dogs<br />"
                 . "Average Wickets Taken Per Over Rankings<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
//                 . "Minimum Matches = 4";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Matches', 3 => 'Overs',
                                 4 => 'Avg. Wickets<br />Per Over');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'avg_match_contribution':
       $MySQLquery =  "select first_name, last_name, count(*), avg(match_contribution)\n"
                    . "from view_match_contributions\n"
                    . "where team_id = 1\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "group by player_id\n"
                    . "order by avg(match_contribution) desc";
       $heading =  "Two Dogs<br />"
                 . "Average Match Contribution Rankings<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Matches', 3 => 'Avg. Match<br />Contribution');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'best_runs_per_innings':
       $MySQLquery =  "select first_name, last_name, match_date,\n"
                    . "       opp_team_name, batting_pos, wickets_lost, runs_scored\n"
                    . "from view_innings\n"
                    . "where team_id = 1\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "order by runs_scored desc, match_date asc, batting_pos asc";
       $heading =  "Two Dogs<br />"
                          . 'Best Innings Rankings<br />';
//                          . "Period: [30/03/2006 - 19/07/2006]";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Match<br />Date', 3 => 'Opp. Team Name',
                                 4 => 'Batting<br />Position', 5 => 'Wickets<br />Lost',
                                 6 => 'Runs<br />Scored');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'best_match_batting_figures':
       $MySQLquery =  "select first_name, last_name, match_date,\n"
                    . "       opp_team_name, n_innings,\n"
                    . "       avg_wickets_lost_per_innings, avg_runs_scored_per_innings\n"
                    . "from   view_match_batting_figures\n"
                    . "where  match_date >= $start_date_string\n"
                    . "and    match_date <= $finish_date_string\n"
                    . "order by avg_runs_scored_per_innings desc";
       $heading =  "Two Dogs<br />"
                 . "Best Match Batting Figures<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Date', 3 => 'Opp. Team Name',
                                 4 => 'No.<br />Innings', 5 => 'Wickets Lost<br />Per Innings',
                                 6 => 'Runs Scored<br />Per Innings');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'best_runs_per_over':
       $MySQLquery =  "select first_name, last_name, match_date,\n"
                    . "       opp_team_name, over_no, wickets_taken,\n"
                    . "       runs_conceded\n"
                    . "from view_overs\n"
                    . "where team_id = 1\n"
                    . "and   match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "order by runs_conceded asc, match_date asc, over_no asc";
       $heading =  "Two Dogs<br />"
                 . 'Best Overs Rankings<br />';
//                 . "Period: [30/03/2006 - 19/07/2006]";
       $colHeadingsArray = array(0 => 'First<br />Name',
                                 1 => 'Last<br />Name', 2 => 'Date',
                                 3 => 'Opp. Team Name', 4 => 'Over<br />Number',
                                 5 => 'Wickets<br />Taken', 6 => 'Runs<br />Conceded' );
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'best_match_bowling_figures':
       $MySQLquery =  "select first_name, last_name, match_date,\n"
                    . "       opp_team_name, n_overs,\n"
                    . "       avg_wickets_taken_per_2_overs, avg_runs_conceded_per_2_overs\n"
                    . "from   view_match_bowling_figures\n"
                    . "where  match_date >= $start_date_string\n"
                    . "and    match_date <= $finish_date_string\n"
                    . "order by avg_runs_conceded_per_2_overs asc";
       $heading =  "Two Dogs<br />"
                 . "Best Match Bowling Figures<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
       $colHeadingsArray = array(0 => 'First<br />Name', 1 => 'Last<br />Name',
                                 2 => 'Date', 3 => 'Opp. Team Name',
                                 4 => 'No.<br />Overs', 5 => 'Wickets Taken<br />Per Two Overs',
                                 6 => 'Runs Conceded<br />Per Two Overs');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;

     case 'best_batting_pships':
       $MySQLquery =  "select match_date, opp_team_name,\n"
                    . "       p1_batting_pos, p1_first_name, p1_last_name,\n"
                    . "       p1_wickets_lost, p1_runs_scored,\n"
                    . "       p2_batting_pos, p2_first_name, p2_last_name,\n"
                    . "       p2_wickets_lost, p2_runs_scored,\n"
                    . "       partnership_wickets, partnership_runs\n"
                    . "from view_batting_partnerships\n"
                    . "where match_date >= $start_date_string\n"
                    . "and   match_date <= $finish_date_string\n"
                    . "order by partnership_runs desc";
       $heading =  "Two Dogs<br />"
                 . "Best Batting Partnerships<br />";
//                 . "Period: [30/03/2006 - 19/07/2006]";
       $colHeadingsArray = array(0 => 'Date', 1 => 'Opp. Team Name',
                                 2 => 'Batting<br />Pos', 3 => 'First<br />Name',
                                 4 => 'Last<br />Name', 5 => 'Wickets<br />Lost',
                                 6 => 'Runs<br />Scored',
                                 7 => 'Batting<br />Pos', 8 => 'First<br />Name',
                                 9 => 'Last<br />Name', 10 => 'Wickets<br />Lost',
                                 11 => 'Runs<br />Scored',
                                 12 => 'Partnership<br />Wickets Lost',
                                 13 => 'Partnership<br />Runs<br />Scored');
       displayMySQLtable($MySQLquery, $heading, $colHeadingsArray, $indent);
       break;
    }
?>
 </body>
</html>
<?php
 }
?>
