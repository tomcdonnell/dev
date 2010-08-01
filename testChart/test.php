<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "test.php"
*
* Project: IndoorCricketStats.net.
*
* Purpose: To test the charting code from the old version of IndoorCricketStats.net (v1).
*
* Author: Tom McDonnell 2008-02-27.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once 'icdb_display_MySQL_chartORhist.php';
require_once 'date_functions.php';
require_once 'icdb_functions.php';

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

ini_set('display_errors'        , '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $_SESSION['MySQLselectExp'       ] =
   (
      ' DAYOFMONTH(`view_matches`.`match_date`) AS `day`,
        MONTH(`view_matches`.`match_date`) AS `month`,
        YEAR(`view_matches`.`match_date`) AS `year`,
        HOUR(`view_matches`.`match_time`) AS `hour`,
        MINUTE(`view_matches`.`match_time`) AS `minute`,
        `view_innings`.`wickets_lost` AS `wickets`, `runs_scored` AS `runs` '
   );
   $_SESSION['MySQLfromExp'         ] =
   (
      ' `view_innings`
        JOIN `view_matches` ON (`view_innings`.`match_id`=`view_matches`.`match_id`) '
   );
   $_SESSION['MySQLwhereExp'        ] = '`view_matches`.`team_id`=1';
   $_SESSION['MySQLorderByExp'      ] =
   (
      '`view_matches`.`match_date` ASC, `view_matches`.`match_time` ASC, `batting_pos` DESC'
   );
   $_SESSION['horizStripeHeight'    ] = 10;
   $_SESSION['tableHeading1'        ] = 'Innings Chart';
   $_SESSION['tableHeading2'        ] = 'Subheading';
   $_SESSION['chartVertAxisHeading' ] = 'Runs';
   $_SESSION['chartHorizAxisHeading'] = 'Innings';

   $_SESSION['debug'] = true;

   connectToMySQL_icdb();
echo 'Calling displayChartOrHist()', "\n";
   displayMySQLchartORhist('chart');
}
catch (Exception $e)
{
   echo $e;
}

// HTML. ///////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <script src="../common/js/utils/json.js"></script>
  <script src="../common/js/utils/utils.js"></script>
  <script src="../common/js/utils/utilsDOM.js"></script>
  <script src="../common/js/utils/AjaxPort.js"></script>
  <script src="../common/js/3rdParty/utils/DomBuilder.js"></script>
<?php
/*
 foreach ($filesJS  as $file) {echo '  <script src="', $file, '"></script>'         , "\n";}
 foreach ($filesCSS as $file) {echo '  <link rel="stylesheet" href="', $file, '" />', "\n";}
*/
?>
  <title>IndoorCricketStats.net</title>
 </head>
 <body></body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
