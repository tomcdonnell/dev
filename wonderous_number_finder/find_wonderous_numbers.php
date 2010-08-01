<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "find_wonderous_numbers.php"
*
* Project: From example given in Godel Escher Bach.
*
* Purpose: Find pattern in wonderous numbers.
*
* Author: Tom McDonnell 2009-09-22
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $stepsByI    = array();
   $n_stepsByI  = array();
   $maxN_steps  = 0;
   $maxN_stepsI = null;

   for ($i = 1; $i < 200000; ++$i)
   {
      $steps          = getWonderousSteps($i);
      $n_steps        = count($steps);
//      $n_stepsByI[$i] = $n_steps;

      if ($n_steps > $maxN_steps)
      {
         $maxN_steps  = $n_steps;
         $maxN_stepsI = $i;
      }

      echo "$i: (steps: ", count($steps), " max: ", max($steps), ")\n";
   }

   echo "maxN_steps: $maxN_steps (i = $maxN_stepsI).\n";
}
catch (Exception $e)
{
   echo $e->getMsg();
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function getWonderousSteps($i)
{
   $steps = array();

   while ($i != 1)
   {
      switch ($i % 2 == 0)
      {
       case true : $i = $i / 2    ; break;
       case false: $i = $i * 3 + 1; break;
      }

      $steps[] = $i;
   }

   return $steps;
}

/*******************************************END*OF*FILE********************************************/
?>
