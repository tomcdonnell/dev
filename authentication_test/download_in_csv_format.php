<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap go-=b
*
* Filename: "download_in_csv_format.php"
*
* Project: Earth Resources SugarCRM Cases Report.
*
* Purpose: Allow the user to download the report as a file in CSV format.
*
* Author: Tom McDonnell 2010-06-17.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

// NOTE: This file must be the first to be included.
require_once dirname(__FILE__) .
(
   '/../../common/php/pages/restrict_access/require_username_and_password.php'
);

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_STRICT);
session_start();

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   header('Content-type: text/csv');
   header('Content-Disposition: attachment; filename="database_browser_export.csv"');

   if (!array_key_exists('report_as_csv', $_SESSION))
   {
      throw new Exception('CSV version of report not found.');
   }

   echo $_SESSION['report_as_csv'];
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*******************************************END*OF*FILE********************************************/
?>
