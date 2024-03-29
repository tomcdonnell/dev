<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "Utils_array.php"
*
* Project: Utilities.
*
* Purpose: Utilities pertaining to arrays.
*
* Author: Tom McDonnell 2010-06-18.
*
\**************************************************************************************************/

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class Utils_array
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct()
   {
      throw new Exception('This class is not intended to be instantiated.');
   }

   /*
    *
    */
   public static function arraysAreEqual($a, $b)
   {
      assert('is_array($a) && is_array($b)');

      if (count($a) != count($b))
      {
         return false;
      }

      $count = count($a);

      for ($i = 0; $i < $count; ++$i)
      {
         $ai = $a[$i];
         $bi = $b[$i];

         if (gettype($ai) != gettype($bi))
         {
            return false;
         }

         $valuesAreEqual = (is_array($ai))? self::arraysAreEqual($ai, $bi): ($ai == $bi);

         if (!$valuesAreEqual)
         {
            return false;
         }
      }

      return true;
   }

   /*
    *
    */
   public static function arraysAreEqualWhenSorted($a, $b)
   {
      sort($a);
      sort($b);

      return self::arraysAreEqual($a, $b);
   }

   /*
    *
    */
   public static function rtrim($a, $blankValue = '', $boolPreserveKeys = false)
   {
      assert('is_array($a)');

      for ($i = count($a) - 1; $i >= 0; --$i)
      {
         if ($a[$i] != $blankValue)
         {
            break;
         }
      }

      return array_slice($a, 0, $i, $boolPreserveKeys);
   }

   /*
    * @param $maxRowsForSingleColumn
    * @param $maxColumns
    *    See code for how table dimensions are affected by these two parameters.
    */
   public static function getStringsAsHtmlTable
   (
      $strings, $indent, $maxRowsForSingleColumn = 5, $maxColumns = 5,
      $className = 'tableColsSameNoHeading'
   )
   {
      if (count($strings) == 0)
      {
         return '';
      }

      $nStrings  = count($strings);
      $tableType =
      (
         ($nStrings <= $maxRowsForSingleColumn)? 'small':
         (
            ($nStrings <= $maxRowsForSingleColumn * $maxColumns)? 'medium': 'large'
         )
      );

      switch ($tableType)
      {
       case 'small':
         $nCols = 1;
         $nRows = $nStrings;
         break;
       case 'medium':
         $nRows = ceil(sqrt($nStrings));
         $nCols = $nRows;
         break;
       case 'large':
         $nCols = $maxColumns;
         $nRows = ceil($nStrings / $nCols);
         break;
       default:
         throw new Exception('Unexpected case.');
      }

      $twoDimStringsArray = self::fill2dArrayMaintainingColumnOrder($strings, $nRows, $nCols);

      $i     = &$indent;
      $html  = "$i<table class='$className'>\n";
      $html .= "$i <tbody>\n";

      foreach ($twoDimStringsArray as $strings)
      {
         $html .= "$i  <tr>";

         foreach ($strings as $string)
         {
            $html .= "<td>$string</td>";
         }

         $html .= "</tr>\n";
      }

      $html .= "$i </tbody>\n";
      $html .= "$i</table>\n";

      return $html;
   }

   /*
    * Given a two dimensional array having continuous integer keys starting at zero, the arrays
    * inside which also meet this restriction, return a new array which is the given array with
    * rows swapped with columns.
    */
   public static function transpose($arrayIn)
   {
      if (count($arrayIn) == 0)
      {
         return array();
      }

      $firstRow = $arrayIn[0];
      $nRows    = count($arrayIn );
      $nCols    = count($firstRow);
      $arrayOut = array_fill(0, $nCols, array());

      for ($r = 0; $r < $nRows; ++$r)
      {
         for ($c = 0; $c < $nCols; ++$c)
         {
            $arrayOut[$c][$r] = $arrayIn[$r][$c];
         }
      }

      return $arrayOut;
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    * Eg. Given $array       = array('one', 'two', 'three', 'four', 'five');
    *           $nRows       = 2;
    *           $nCols       = 2;
    *           $fillerValue = '';
    *
    *     Return array
    *     (
    *        array('one'  , 'four'),
    *        array('two'  , 'five'),
    *        array('three', ''    )
    *     );
    *
    */
   private static function fill2dArrayMaintainingColumnOrder
   (
      $values, $nRows, $nCols, $fillerValue = ''
   )
   {
      $valuesArray = array();
      $nValues     = count($values);
      $n           = -1;

      if ($nValues > $nRows * $nCols)
      {
         throw new Exception('Too many values for given array dimensions.');
      }

      for ($c = 0; $c < $nCols; ++$c)
      {
         $valuesArray[$c] = array();

         for ($r = 0; $r < $nRows; ++$r)
         {
            $valuesArray[$c][$r] = (++$n < $nValues)? $values[$n]: $fillerValue;
         }
      }

      return self::transpose($valuesArray);
   }
}

/*******************************************END*OF*FILE********************************************/
?>
