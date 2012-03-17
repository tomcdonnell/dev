<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

require_once 'move_index_in_array.php';
require_once 'MoveIndexInArrayTester.php';

ini_set('soap.wsdl_cache_enabled', '0');

try
{
   echo "\n";
   echo "--- Running Non-Soap Tests ----------------------------------------------------\n\n";
   MoveIndexInArrayTester::runTest('moveIndexInArray',    4, true);
   MoveIndexInArrayTester::runTest('moveIndexInArray', 2000, false, 200);

   echo "--- Running Soap Tests --------------------------------------------------------\n\n";
   $soapClient = new SoapClient('http://localhost/emersion_programming_test/interface.wsdl');
   MoveIndexInArrayTester::runTest(array($soapClient, 'moveIndexInArray'),   4, true);
   MoveIndexInArrayTester::runTest(array($soapClient, 'moveIndexInArray'), 200, false, 10);
}
catch (Exception $e)
{
   echo $e->getMessage(), "\n";
}
?>
