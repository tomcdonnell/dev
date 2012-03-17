<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

/*
 * Move the element of $array at $index one position either up or down.
 */
function moveIndexInArray($array, $index, $upOrDown)
{
   switch ($upOrDown)
   {
    case 'up':
      $destIndex = $index - 1;
      if ($destIndex < 0 || $index >= count($array)) {throw new Exception('Index out of range.');}
      break;

    case 'down':
      $destIndex = $index + 1;
      if ($index < 0 || $destIndex >= count($array)) {throw new Exception('Index out of range.');}
      break;

    default:
      throw new Exception("Expected 'up' or 'down'.  Received '$upOrDown'.");
   }

   // Swap element at $index with element at $destIndex.
   $temp              = $array[$destIndex];
   $array[$destIndex] = $array[$index    ];
   $array[$index    ] = $temp;

   // Swap element at $index with element at $destIndex.
   //$srcElem    = &$array[$index    ];
   //$dstElem    = &$array[$destIndex];
   //$temp0      = $dstElem[0];
   //$temp1      = $dstElem[1];
   //$temp2      = $dstElem[2];
   //$dstElem[0] = $srcElem[0];
   //$dstElem[1] = $srcElem[1];
   //$dstElem[2] = $srcElem[2];
   //$srcElem[0] = $temp0;
   //$srcElem[1] = $temp1;
   //$srcElem[2] = $temp2;

   return $array;
}
