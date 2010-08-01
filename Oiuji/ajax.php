<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "ajax.php"
*
* Project: Oiuji.
*
* Purpose: Server-side ajax message processing.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

session_start();

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   DatabaseManager::add('icdb', 'localhost', 'tom', 'igaiasma', 'icdb');

   $msg = json_decode(file_get_contents('php://input'));

   if (!is_array($msg) || count($msg) != 2)
   {
      throw new Exception('This page has been used incorrectly.');
   }

   $header  = $msg[0];
   $payload = $msg[1];

   switch ($header)
   {
    case 'request_options_season'  : $reply = getSeasons() ; break;
    case 'request_options_opp_team': $reply = getOppTeams(); break;
    case 'request_options_player'  : $reply = getPlayers() ; break;

    default:
      throw new Exception("Unknown message header '$header' received.");
   }

   echo json_encode($reply);
}
catch (Exception $e)
{
   echo $e;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 * Required for 'Period' tab.
 */
function getSeasons()
{
   $rows = DatabaseManager::get('icdb')->query
   (
      'SELECT `id`, `name`, `dateStart`, `dateFinish`
       FROM `season`
       WHERE `idTeam`=?
       ORDER BY `dateStart` DESC',
      array($_SESSION['idTeam'])
   );

   $seasons = array();
   foreach ($rows as $row)
   {
      $seasons[] = array
      (
         'id'   => $row['id'  ],
         'name' => $row['name']
      );
   }

   return array('season', $seasons);
}

/*
 * Required for 'Opposition' tab.
 */
function getOppTeams()
{
   $rows = DatabaseManager::get('icdb')->query
   (
      'SELECT `id`, `name`
       FROM `oppTeam`
       WHERE `idTeam`=?
       ORDER BY `name` ASC',
      array($_SESSION['idTeam'])
   );

   $oppTeams = array();
   foreach ($rows as $row)
   {
      $oppTeams[] = array
      (
         'id'   => $row['id'  ],
         'name' => $row['name']
      );
   }

   return array('oppTeam', $oppTeams);
}

/*
 * Required for 'Players' tab.
 */
function getPlayers()
{
   $rows = DatabaseManager::get('icdb')->query
   (
      'SELECT `id`, CONCAT(`nameFirst`, " ", `nameLast`) AS `name`
       FROM `player`
       WHERE `idTeam`=?
       ORDER BY `name` ASC',
      array($_SESSION['idTeam'])
   );

   $players = array();
   foreach ($rows as $row)
   {
      $players[] = array
      (
         'id'   => $row['id'  ],
         'name' => $row['name']
      );
   }

   return array('player', $players);
}

/*******************************************END*OF*FILE********************************************/
?>
