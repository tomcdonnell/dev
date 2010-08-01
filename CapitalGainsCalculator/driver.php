<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "driver.php"
*
* Project: Tax return for financial year 2007/2008.
*
* Purpose: Driver script for the file CapitalGainsCalculator.php.
*
* Author: Tom McDonnell 2008-09-16.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/CapitalGainsCalculator.php';

// Defines. ////////////////////////////////////////////////////////////////////////////////////////

define('N_EXPECTED_FIELDS', 10);

// Global variables. ///////////////////////////////////////////////////////////////////////////////

$DESCRIPTION = <<<STR

Display a summary of the capital gains/losses incurred over a given time period, using share trading data from a given CSV file.

Usage: calculateCapitalGains [<filename> [, <startDate>, <finishDate>]]

 * Filename is the name of a CSV file with the following columns:
   "Contract Number","Trade Date","Buy / Sell","Stock Code","Units",
   "Average Price","Brokerage","Net Proceeds","Settlement Date","Contract Status".

 * Dates are expected to be in the format YYYY-MM-DD.
   The period defined by the start and finish dates includes both dates.

 * If an incorrect number of arguments is supplied, this help message is displayed.


STR;

$EXPECTED_COLUMNS = array
(
   'contractNo'    ,
   'tradeDate'     ,
   'buyOrSell'     ,
   'stockCode'     ,
   'n_units'       ,
   'avg_price'     ,
   'brokerage'     ,
   'netProceeds'   ,
   'settlementDate',
   'contractStatus'
);

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   switch ($argc)
   {
    case 2:
      $filename   = $argv[1];
      $dateStart  = '1900-01-01';
      $dateFinish = '2100-12-31';
      break;
    case 4:
      $filename   = $argv[1];
      $dateStart  = $argv[2];
      $dateFinish = $argv[3];
      break;
    default:
      echo $DESCRIPTION;
      exit(0);
   }

   $rows = readDataFromFile($EXPECTED_COLUMNS, $filename);

   if ($rows === false)
   {
      echo "File '$filename' could not be opened.\n";
      exit(0);
   }

   $capGainsCalculator = new CapitalGainsCalculator($rows);

   $period = array
   (
      'start'  => mktime(0, 0, 0, 7,  1, 2007),
      'finish' => mktime(0, 0, 0, 6, 30, 2008)
   );

   echo 'Getting capital gains info for period ';
   echo '[', date('Y-m-d', $period['start']), ', ', date('Y-m-d', $period['finish']), "]...\n";
   $capGainsInfo = $capGainsCalculator->getCapitalGainsInfo($period['start'], $period['finish']);

   var_dump($capGainsInfo);
}
catch (Exception $e)
{
   echo $e;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function readDataFromFile($keys, $filename)
{
   $fileAsStr = file_get_contents($filename);

   if ($fileAsStr === false)
   {
      return false;
   }

   $lines = explode("\n", $fileAsStr);

   // Skip first line of array (first line is assumed to contain headings).
   array_shift($lines);

   $data  = array();
   $rowNo = 0;
   foreach ($lines as $line)
   {
      if ($line == "")
      {
         break;
      }

      ++$rowNo;

      $fields = explode(',', $line);

      if (count($fields) != count($keys))
      {
         throw new Exception
         (
            "Unexpected number of fields in row $rowNo." .
            '  Expected ' . count($keys) . ' found ' . count($fields) . '.'
         );
      }

      // Remove quotes and leading/trailing spaces from all fields.
      foreach ($fields as $key => $field)
      {
         $fields[$key] = trim(str_replace('"', '', $field));
      }

      $data[] = array_combine($keys, $fields);
   }

   return $data;
}

/*******************************************END*OF*FILE********************************************/
?>
