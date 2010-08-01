####################################################################################################
#                                                                                                  #
# FILENAME: "icdb_create_views_script.sql"                                                         #
#                                                                                                  #
# PURPOSE: This file is a script that creates all views                                            #
#          for the database "indoor_cricket_database".                                             #
#          Use command "source <filename>".                                                        #
#                                                                                                  #
# AUTHOR: Tom McDonnell 2006                                                                       #
#                                                                                                  #
####################################################################################################

# Drop Views #######################################################################################

drop view if exists `view_teams_dates_opp_teams`;
drop view if exists `view_teams_dates_opp_teams_players`;
drop view if exists `view_teams_dates_opp_teams_batting_pairs`;
drop view if exists `view_innings`;
drop view if exists `view_overs`;
drop view if exists `view_team_scores`;        # - These three views are necessary at
drop view if exists `view_opp_team_scores`;    # - present to allow `view_matches` to
drop view if exists `view_n_players_in_match`; # - be created (see `view_matches`).
drop view if exists `view_matches`;
drop view if exists `view_batting_partnerships`;
drop view if exists `view_match_bowling_figures`;
drop view if exists `view_match_batting_figures`;
drop view if exists `view_match_contributions`;


# Create Views #####################################################################################

# view_teams_dates_opp_teams
create view view_teams_dates_opp_teams as
select team_id, match_date, opp_team_id
from matches
order by match_date asc;

# view_teams_dates_opp_teams_players
create view view_teams_dates_opp_teams_players as
select distinct matches.team_id, match_date, opp_team_id, player_id
from innings, matches
where innings.match_id = matches.match_id
order by match_date asc;

# view_teams_dates_opp_teams_batting_pairs
create view view_teams_dates_opp_teams_batting_pairs as
select matches.team_id, match_date, opp_team_id,
       i1.player_id as player_1_id,
       i2.player_id as player_2_id
from innings as i1, innings as i2, matches
where i1.match_id = i2.match_id
and   i1.match_id = matches.match_id
and (   i1.batting_pos = 1 and i2.batting_pos = 2
     or i1.batting_pos = 3 and i2.batting_pos = 4
     or i1.batting_pos = 5 and i2.batting_pos = 6
     or i1.batting_pos = 7 and i2.batting_pos = 8)
order by match_date asc;


# view_innings
create view view_innings as
select matches.team_id, matches.match_id, match_date, match_time,
       matches.opp_team_id, opp_team_name,
       batting_pos, players.player_id, first_name, last_name, wickets_lost, runs_scored
from matches, opp_teams, innings, players
where matches.match_id = innings.match_id
and   opp_teams.opp_team_id = matches.opp_team_id
and   innings.player_id = players.player_id
order by match_date asc, match_time asc;


# view_overs
create view view_overs as
select matches.team_id, matches.match_id, match_date, match_time,
       matches.opp_team_id, opp_team_name,
       over_no, players.player_id, first_name, last_name, wickets_taken, runs_conceded
from matches, opp_teams, overs, players
where matches.match_id = overs.match_id
and   opp_teams.opp_team_id = matches.opp_team_id
and   overs.player_id = players.player_id
order by match_date asc, match_time asc;

# view_matches
# NOTE: The 'create view' query below is not allowed in MySQL version 5.0,
#       despite the select query being legal.
#       The error message given is: 
#         "ERROR 1349 (HY000): Views SELECT contains a subquery in the FROM clause".
#       Hopefully a later version will allow this query to be used.
#       In the meantime, the solution employed is to create nested views.  See below.
#create view view_matches as
#select matches.team_id, matches.match_id, match_date, match_time,
#       matches.opp_team_id, opp_team_name, team_batted_1st, n_players, match_notes,
#       wickets_lost, team_score,
#       wickets_taken, opp_team_score,
#       team_score - opp_team_score as margin,
#       (
#          case
#           when team_score - opp_team_score > 0 then 'W'
#           when team_score - opp_team_score < 0 then 'L'
#           else 'D'
#          end
#       ) as result
#from matches, opp_teams,
#(
#   select team_id, match_id,
#          sum(runs_scored) as team_score,
#          sum(wickets_lost) as wickets_lost
#   from innings
#   group by team_id, match_id
#) as table1,
#(
#   select team_id, match_id,
#          sum(runs_conceded) as opp_team_score,
#          sum(wickets_taken) as wickets_taken
#   from overs
#   group by team_id, match_id
#) as table2,
#(
#   select team_id, match_id, count(distinct player_id) as n_players
#   from innings
#   group by team_id, match_id
#) as table3
#where matches.team_id = table1.team_id
#and   matches.team_id = table2.team_id
#and   matches.team_id = table3.team_id
#and   matches.match_id = table1.match_id
#and   matches.match_id = table2.match_id
#and   matches.match_id = table3.match_id
#and   matches.opp_team_id = opp_teams.opp_team_id
#order by match_date asc, match_time asc;
# Below are the four queries used to replace the above one.
# Query 1:
create view view_team_scores as
select team_id, match_id, sum(runs_scored) as team_score, sum(wickets_lost) as wickets_lost
from innings
group by team_id, match_id;
# Query 2:
create view view_opp_team_scores as
select team_id, match_id, sum(runs_conceded) as opp_team_score, sum(wickets_taken) as wickets_taken
from overs
group by team_id, match_id;
# Query 3:
create view view_n_players_in_match as
select team_id, match_id, count(distinct player_id) as n_players
from innings
group by team_id, match_id;
# Query 4:
create view view_matches as
select matches.team_id, matches.match_id, match_date, match_time, match_type,
       matches.opp_team_id, opp_team_name, team_batted_1st, n_players, match_notes,
       wickets_lost, team_score,
       wickets_taken, opp_team_score,
       team_score - opp_team_score as margin,
       (
          case
           when team_score - opp_team_score > 0 then 'W'
           when team_score - opp_team_score < 0 then 'L'
           else 'D'
          end
       ) as result
from matches, opp_teams, view_team_scores, view_opp_team_scores, view_n_players_in_match
where matches.team_id = view_team_scores.team_id
and   matches.team_id = view_opp_team_scores.team_id
and   matches.team_id = view_n_players_in_match.team_id
and   matches.match_id = view_team_scores.match_id
and   matches.match_id = view_opp_team_scores.match_id
and   matches.match_id = view_n_players_in_match.match_id
and   matches.opp_team_id = opp_teams.opp_team_id
order by match_date asc, match_time asc;


# view_batting_partnerships
create view view_batting_partnerships as
select matches.team_id,
       matches.match_id, match_date, match_time,
       opp_teams.opp_team_id, opp_team_name,
       i1.batting_pos as p1_batting_pos, p1.player_id as p1_player_id,
       p1.first_name as p1_first_name, p1.last_name as p1_last_name,
       i1.runs_scored as p1_runs_scored, i1.wickets_lost as p1_wickets_lost,
       i2.batting_pos as p2_batting_pos, p2.player_id as p2_player_id,
       p2.first_name as p2_first_name, p2.last_name as p2_last_name,
       i2.runs_scored as p2_runs_scored, i2.wickets_lost as p2_wickets_lost,
       i1.wickets_lost + i2.wickets_lost as partnership_wickets,
       i1.runs_scored + i2.runs_scored as partnership_runs
from innings as i1, innings as i2,
     matches, opp_teams,
     players as p1, players as p2
where i1.match_id = i2.match_id
and   i1.match_id = matches.match_id
and   matches.opp_team_id = opp_teams.opp_team_id
and   i1.player_id = p1.player_id
and   i2.player_id = p2.player_id
and   (   i1.batting_pos = 1 and i2.batting_pos = 2
       or i1.batting_pos = 3 and i2.batting_pos = 4
       or i1.batting_pos = 5 and i2.batting_pos = 6
       or i1.batting_pos = 7 and i2.batting_pos = 8)
order by match_date asc, match_time asc;


# view_match_bowling_figures
create view view_match_bowling_figures as
select team_id, match_id, match_date, match_time, opp_team_id, opp_team_name,
       player_id, first_name, last_name, count(*) as n_overs_in_match,
       sum(wickets_taken) as wickets_taken_in_match,
       sum(runs_conceded) as runs_conceded_in_match
from view_overs
group by match_id, player_id
order by match_date asc, match_time asc;


# view_match_batting_figures
create view view_match_batting_figures as
select team_id, match_id, match_date, match_time, opp_team_id, opp_team_name,
       player_id, first_name, last_name, count(*) as n_innings_in_match,
       sum(wickets_lost) as wickets_lost_in_match,
       sum(runs_scored) as runs_scored_in_match
from view_innings
group by match_id, player_id
order by match_date asc, match_time asc;


# view_match_contributions
create view view_match_contributions as
select view_match_bowling_figures.team_id,
       view_match_bowling_figures.match_id,
       view_match_bowling_figures.match_date,
       view_match_bowling_figures.match_time,
       view_match_bowling_figures.opp_team_id,
       view_match_bowling_figures.opp_team_name,
       view_match_bowling_figures.player_id,
       view_match_bowling_figures.first_name,
       view_match_bowling_figures.last_name,
       n_innings_in_match, wickets_lost_in_match, runs_scored_in_match,
       n_overs_in_match, wickets_taken_in_match, runs_conceded_in_match,
       wickets_taken_in_match - wickets_lost_in_match as net_wickets_taken_in_match,
       runs_scored_in_match - runs_conceded_in_match as net_runs_scored_in_match
from view_match_bowling_figures, view_match_batting_figures
where view_match_bowling_figures.match_id = view_match_batting_figures.match_id
and   view_match_bowling_figures.player_id = view_match_batting_figures.player_id
group by match_id, player_id
order by match_date asc, match_time asc;


# End of File ######################################################################################
