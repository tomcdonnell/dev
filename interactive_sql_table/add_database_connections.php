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

require_once dirname(__FILE__) . '/../../common/php/database/DatabaseManager.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   DatabaseManager::addMany
   (
      array
      (
         array
         (
            'name'     => 'information_schema',
            'host'     => 'localhost'         ,
            'user'     => 'root'              ,
            'password' => 'igaiasmaroot'      ,
            'database' => 'information_schema'
         ),
         array
         (
            'name'     => 'root_read'   ,
            'host'     => 'localhost'   ,
            'user'     => 'root_read'   ,
            'password' => 'igaiasmaroot',
            'database' => 'sugarcrm'
         )
      )
   );
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*******************************************END*OF*FILE********************************************/
?>
