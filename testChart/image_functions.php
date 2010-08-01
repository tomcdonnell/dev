<?php
 /*
  *
  */
 function printXcenteredHorizTextString($image, $fontSize, $minX, $maxX, $y, $textStr, $color)
 {
    // NOTE: The '+ 1' in the $x calculation below was found to be
    //       necessary to achieve proper centering by experiment.
    $x = ($minX + $maxX - strlen($textStr) * imagefontwidth($fontSize)) / 2 + 1;
    imagestring($image, $fontSize, $x, $y, $textStr, $color);
 }

 /*
  *
  */
 function printYcenteredVertTextString($image, $fontSize, $x, $minY, $maxY, $textStr, $color)
 {
    $y = ($minY + $maxY + strlen($textStr) * imagefontwidth($fontSize)) / 2;
    imagestringup($image, $fontSize, $x, $y, $textStr, $color);
 }
?>
