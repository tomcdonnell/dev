<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap go-=b
*
* Filename: "display_interactive_sql_table.php"
*
* Project: General.
*
* Purpose: Display an interactive SQL table.  Search and sort functions are available for each
*          column.  Output is similar to that displayed by phpMyAdmin.
*
* Author: Tom McDonnell 2010-07-04.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../common/php/utils/Utils_dbSchema.php';
require_once dirname(__FILE__) . '/../../common/php/utils/Utils_database.php';
require_once dirname(__FILE__) . '/../../common/php/classes/sql_table_relationships_finder/specific_naming_conventions/SqlTableRelationshipsFinderNcTom.php';
require_once dirname(__FILE__) . '/add_database_connections.php';

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_STRICT);

// Global variables. ///////////////////////////////////////////////////////////////////////////////

$DBC     = null;
$ROW     = null;
$REL_OBJ = null;
$REL_ARR = null;

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   Utils_validator::checkArray
   (
      $_GET, array
      (
         'databaseName'      => 'string',
         'tableName'         => 'string',
         'uniqueColumnName'  => 'string',
         'uniqueColumnValue' => 'string',
         'expandLinkedRows'  => 'string'
      )
   );

   $DBC = DatabaseManager::get('root_read');
   $DBC->selectDatabase($_GET['databaseName']);
   $ROW = getRow();

   $REL_OBJ = new SqlTableRelationshipsFinderNcTom($DBC, $_GET['tableName']);
   $REL_ARR = $REL_OBJ->getAsArray();
}
catch (Exception $e)
{
   //echo $e->getMessage();
   echo $e;
   exit(0);
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function getRow()
{
   global $DBC;

   $rows = Utils_database::getRowsFromTable
   (
      $DBC, $_GET['tableName'], array($_GET['uniqueColumnName'] => $_GET['uniqueColumnValue'])
   );

   return (count($rows) == 0)? null: $rows[0];
}

/*
 *
 */
function echoTableHeadingHtml($row, $indent)
{
   switch ($row === null)
   {
    case true:
      $columnHeadings = Utils_dbSchema::getColumnHeadings($_GET['databaseName'],$_GET['tableName']);
      break;
    case false:
      $columnHeadings = array_keys($row);
      break;
   }

   $i = &$indent;

   echo "$i<thead>\n";
   echo "$i <tr>\n";

   foreach ($columnHeadings as $columnHeading)
   {
      echo "$i  <th>\n";
      echo "$i   $columnHeading\n";
      echo "$i  </th>\n";
   }

   echo "$i </tr>\n";
   echo "$i</thead>\n";
}

/*
 *
 */
function echoTableBodyHtml($row, $indent)
{
   $i = &$indent;

   echo "$i<tbody>\n";
   echo "$i <tr>";

   foreach ($row as $key => $value)
   {
      echo "<td>$value</td>";
   }

   echo    "</tr>\n";
   echo "$i</tbody>\n";
}

/*
 *
 */
function echoDirectLinksFromTableHtml($indent)
{
   global $ROW;
   global $REL_ARR;

   $i = &$indent;

   $iframeSrcPartial =
   (
      "display_interactive_sql_table.php?databaseName={$_GET['databaseName']}&expandLinkedRows=1"
   );

   foreach ($REL_ARR['directLinksFromTableByTableLinkCol'] as $tableLinkCol => $linkInfo)
   {
      extract($linkInfo);

      $iframeSrc =
      (
         "$iframeSrcPartial&tableName=$linkedTableName&uniqueColumnName=$linkedTableLinkCol" .
         "&uniqueColumnValue={$ROW[$tableLinkCol]}"
      );

      echo "$i<span>`$linkedTableName` (linked from `$tableLinkCol`)</span><br/>\n";
      echo "$i<iframe id='linkedFrom1' name='linkedFrom1' src='$iframeSrc'>\n";
      echo "$i</iframe><br/>\n";
   }
}

/*
 *
 */
function echoDirectLinksToTableHtml($indent)
{
   global $ROW;
   global $REL_ARR;

   $i = &$indent;

   $iframeSrcPartial =
   (
      "display_interactive_sql_table.php?databaseName={$_GET['databaseName']}&expandLinkedRows=0"
   );

   foreach ($REL_ARR['directLinksToTableByLinkedTableName'] as $linkedTableName => $linkInfo)
   {
      extract($linkInfo);

      $iframeSrc =
      (
         "$iframeSrcPartial&tableName=$linkedTableName&uniqueColumnName=$linkedTableLinkCol" .
         "&uniqueColumnValue={$ROW[$tableLinkCol]}"
      );

      echo "$i<span>`$linkedTableName` (linked to `$tableLinkCol`)</span><br/>\n";
      echo "$i<iframe id='linkedTo1' name='linkedTo1' src='$iframeSrc'>\n";
      echo "$i</iframe><br/>\n";
   }
}

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <link rel='stylesheet' type='text/css' href='style.css'/>
  <title>Interactive SQL Table</title>
 </head>
 <body>
  <table>
<?php
echoTableheadingHtml($ROW, '   ');
echoTableBodyHtml($ROW, '   ');
?>
  </table>
<?php
if ($_GET['expandLinkedRows'] == '1')
{
   echoDirectLinksFromTableHtml('  ');
   echoDirectLinksToTableHtml('  ');
}
?>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
