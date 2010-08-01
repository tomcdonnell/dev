####################################################################################################
#                                                                                                  #
# FILENAME: "icdb_useful_queries.sql"                                                              #
#                                                                                                  #
# PURPOSE: This file contains useful MySQL queries to be used with the indoor_cricket_database.    #
#                                                                                                  #
# AUTHOR: Tom McDonnell 2006                                                                       #
#                                                                                                  #
####################################################################################################

# select query for finding match result streaks
select `result`,
       min(`match_date`) as `start_date`,
       max(`match_date`) as `finish_date`,
       count(*) as `n_matches`
from
(
   select `match_date`, `match_time`, `result`,
   (
      select count(*)
      from `view_matches` as `vm1`
      where `team_id` = 1
      and   `vm1`.`result` <> `vm2`.`result`
      and
      (
         `vm1`.`match_date` < `vm2`.`match_date`
         or
         (`vm1`.`match_date` = `vm2`.`match_date` and `vm1`.`match_time` < `vm2`.`match_time`)
      )
   ) as `streak_group`
   from `view_matches` as `vm2`
   where `team_id` = 1
) as `dummy`
group by `result`, `streak_group`
order by min(`match_date`), max(`match_date`);

# To rank winning or losing streaks, use:
select `start_date`, `finish_date`, `n_matches`
from (<INSERT ABOVE QUERY>)
where `result` = <INSERT 'W' OR 'L'>
order by `n_matches` desc;


# select query for finding wickets_lost streaks for all players
select `player_id`, `first_name`, `last_name`, `wickets_lost`,
       min(`match_date`) as `start_date`,
       max(`match_date`) as `finish_date`,
       count(*) as `n_innings`
from
(
   select `player_id`, `first_name`, `last_name`,
          `match_date`, `match_time`,
          `batting_pos`, `wickets_lost`,
   (
      select count(*)
      from `view_innings` as `vi1`
      where `team_id` = 1
      and   `vi1`.`player_id` = `vi2`.`player_id`
      and   `vi1`.`wickets_lost` <> `vi2`.`wickets_lost`
      and
      (
         `vi1`.`match_date` < `vi2`.`match_date`
         or
         (
            `vi1`.`match_date` = `vi2`.`match_date`
            and
            (
               `vi1`.`match_time` < `vi2`.`match_time`
               or
               (
                  `vi1`.`match_time` = `vi2`.`match_time`
                  and
                  `vi1`.`batting_pos` < `vi2`.`batting_pos`
               )
            )
         )
      )
   ) as `streak_group`
   from `view_innings` as `vi2`
   where `team_id` = 1
   order by `player_id`, `match_date`, `match_time`, `batting_pos`
) as `dummy`
group by `player_id`, `wickets_lost`, `streak_group`
order by min(`match_date`) asc, max(`match_date`) asc;

# to rank wickets lost streaks, use:
select `first_name`, `last_name`,
       `start_date`, `finish_date`,
       `n_innings`
from
(
   <INSERT ABOVE QUERY>
) as `dummy2`
where `wickets_lost` = 0
order by `n_innings` desc, `start_date` asc, `finish_date` asc;




# select query for finding batting partnerships
# NOTE: The code portion between the "case" and "end" statements for "partnership_group"
        computes a number which groups partnerships based on the IDs of the two batsmen.
        LIMITATION: Partnership_group collisions will occur if
                    there are more than 1000000 players in the team.
        Eg.  All partnerships involving players with player_ids 1 and 2,
             (regardless of batting positions)
             will be assigned a partnership_group of 2000001.
             All other partnerships will be assigned a different number.
             (1, 2): 1  < 2 so partnership_group = 1 + 1000000 * 2 = 2000001
             (2, 1): 2 !< 1 so partnership_group = 1 + 1000000 * 2 = 2000001
# NOTE: The function of the other case statements is to ensure that, for each partnership group,
        for the entire table, p1 always refers to the same batsman, and p2 always refers to the
        other batsman.  This ensures that the averages for p1 and p2 are calculated correctly.
select concat(p1_first_name, "<br />", p2_first_name) as f,
       concat(p1_last_name, "<br />", p2_last_name) as l,
       concat(avg(p1_wickets_lost), "<br />", avg(p2_wickets_lost)) as w,
       concat(avg(p1_runs_scored), "<br />", avg(p2_runs_scored)) as r,
       count(*), avg(partnership_wickets) as pw, avg(partnership_runs) as pr
from
(
   select team_id, match_date,
          # NOTE: Some extra columns are selected here (eg. player_ids).
          #       These are useful when extra restrictions are applied (eg. minimum matches)
          (case when p1_player_id < p2_player_id then p1_player_id    else p2_player_id    end)
          as p1_player_id,
          (case when p1_player_id < p2_player_id then p1_batting_pos  else p2_batting_pos  end)
          as p1_batting_pos,
          (case when p1_player_id < p2_player_id then p1_first_name   else p2_first_name   end)
          as p1_first_name,
          (case when p1_player_id < p2_player_id then p1_last_name    else p2_last_name    end)
          as p1_last_name,
          (case when p1_player_id < p2_player_id then p1_wickets_lost else p2_wickets_lost end)
          as p1_wickets_lost,
          (case when p1_player_id < p2_player_id then p1_runs_scored  else p2_runs_scored  end)
          as p1_runs_scored,
          (case when p1_player_id < p2_player_id then p2_player_id    else p1_player_id    end)
          as p2_player_id,
          (case when p1_player_id < p2_player_id then p2_batting_pos  else p1_batting_pos  end)
          as p2_batting_pos,
          (case when p1_player_id < p2_player_id then p2_first_name   else p1_first_name   end)
          as p2_first_name,
          (case when p1_player_id < p2_player_id then p2_last_name    else p1_last_name    end)
          as p2_last_name,
          (case when p1_player_id < p2_player_id then p2_wickets_lost else p1_wickets_lost end)
          as p2_wickets_lost,
          (case when p1_player_id < p2_player_id then p2_runs_scored  else p1_runs_scored  end)
          as p2_runs_scored,
          partnership_wickets, partnership_runs,
          (
             case
                when p1_player_id < p2_player_id
                then p1_player_id + 1000000 * p2_player_id
                else p2_player_id + 1000000 * p1_player_id
             end
          ) as partnership_group
   from view_batting_partnerships
   where team_id = 1
) as dummy
group by partnership_group
having count(*) > 10
order by avg(partnership_runs) desc;


# End of File ######################################################################################
