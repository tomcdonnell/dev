<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

/*
 *
 */
class MoveIndexInArrayTester
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public static function runTest($func, $n, $boolVerboseOutput, $echoProgressInterval = 1000)
   {
      $testArray = self::_buildTestArray($n);
      $origArray = $testArray;
      $ts        = microtime(true);

      echo "Testing function moveIndexInArray() for array of size $n.\n";

      if ($boolVerboseOutput)
      {
         echo "Original array:\n", self::_getArrayAsString($testArray);
      }

      for ($i = 0; $i < $n - 1; ++$i)
      {
         if ($i % $echoProgressInterval == 0) {echo '.';}

         $testArray = call_user_func($func, $testArray, $i, 'down');

         if ($boolVerboseOutput)
         {
            echo "Move array[$i] down:\n", self::_getArrayAsString($testArray);
         }
      }

      for ($i = $n - 1; $i > 0; --$i)
      {
         if ($i % $echoProgressInterval == 0) {echo '.';}

         $testArray = call_user_func($func, $testArray, $i, 'up');

         if ($boolVerboseOutput)
         {
            echo "Move array[$i] up:\n", self::_getArrayAsString($testArray);
         }
      }

      $testPassed  = self::_arraysAreEqual($origArray, $testArray);
      $timeElapsed = microtime(true) - $ts;

      echo 'Test ', (($testPassed)? 'passed': 'failed'), ".\n";
      echo 'Time elapsed: ', round($timeElapsed, 3), " milliseconds.\n";
      echo '(', round(($timeElapsed * 1000) / (2 * ($n - 1)), 3) . " microseconds per move)\n\n";
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function _buildTestArray($nElements)
   {
      $testArray = array();

      for ($i = 0; $i < $nElements; ++$i)
      {
         $testArray[] = array("item$i", "description$i", "amount$i");
      }

      return $testArray;
   }

   /*
    *
    */
   private static function _getArrayAsString($array)
   {
      $string = '';

      foreach ($array as $subArray)
      {
         $string .= '   {' . implode(',', $subArray) . "}\n";
         
      }

      return $string . "\n";
   }

   /*
    *
    */
   private static function _arraysAreEqual($array1, $array2)
   {
      if (count($array1) != count($array2))
      {
         return false;
      }

      for ($i = 0, $count = count($array1); $i < $count; ++$i)
      {
         $e1 = $array1[$i];
         $e2 = $array2[$i];

         if (gettype($e1) != gettype($e2))
         {
            return false;
         }

         if (gettype($e1) == 'array' && !self::_arraysAreEqual($e1, $e2))
         {
            return false;
         }
         else
         {
            if ($e1 != $e2)
            {
               return false;
            }
         }
      }

      return true;
   }
}
?>
