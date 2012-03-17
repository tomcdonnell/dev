<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap go-=b
*
* Filename: "add_database_connections.php"
*
* Project: Common.
*
* Purpose: Add connections to commonly accessed databases to the DatabaseManager.
*
* Author: Tom McDonnell 2010-06-15.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../lib/tom/php/database/DatabaseManager.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   DatabaseManager::add
   (
      array
      (
         'name'     => 'people'   ,
         'host'     => 'localhost',
         'user'     => 'root'     ,
         'password' => ''         ,
         'database' => 'people'
      )
   );
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*******************************************END*OF*FILE********************************************/
?>
