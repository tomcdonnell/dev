<?php
 require_once '../../../common/date_and_time/date_functions.php';
 require_once '../../../common/misc/image_functions.php';

 // Test that the required $_SESSION[] variables are set:
 $sessionVarsSet = true;
 if (   !isset($_SESSION['runsArray'            ])
     || !isset($_SESSION['wicketsArray'         ])
     || !isset($_SESSION['datesArray'           ])
     || !isset($_SESSION['horizStripeHeight'    ])
     || !isset($_SESSION['chartVertAxisHeading' ])
     || !isset($_SESSION['chartHorizAxisHeading']))
   $sessionVarsSet = false;

 // Define image position and dimensions.
 // (Minimums for image will always be zero but are included here for uniformity.)
 $imageWidth  = 900; $imageMinX = 0; $imageMaxX = $imageMinX + $imageWidth  - 1;
 $imageHeight = 480; $imageMinY = 0; $imageMaxY = $imageMinY + $imageHeight - 1;

 // Define and create .PNG image.
 header('Content-type: image/png');
 $image = imagecreate($imageWidth, $imageHeight);

 // Create colors.
 $colorDarkBlue   = imagecolorallocate($image, 0x00, 0x61, 0xc2); // Color of td.d from "style.css".
 $colorLightBlue  = imagecolorallocate($image, 0x00, 0x71, 0xe2); // Color of td.l from "style.css".
 $colorDarkGreen  = imagecolorallocate($image, 0x48, 0x90, 0x00); // Color of h3.d from "style.css".
 $colorLightGreen = imagecolorallocate($image, 0x50, 0xa0, 0x00); // Color of h3.l from "style.css".
 $colorRed        = imagecolorallocate($image, 0xff, 0x00, 0x00);
 $colorBlack      = imagecolorallocate($image, 0x00, 0x00, 0x00);
 $colorWhite      = imagecolorallocate($image, 0xff, 0xff, 0xff);
 $colorYellow     = imagecolorallocate($image, 0xff, 0xff, 0x00);

 // Draw image background.
 imagefilledrectangle($image, $imageMinX, $imageMinY,
                              $imageMaxX, $imageMaxY, $colorWhite);

 // If required $_SESSION[] variables are not set, display error message in image and exit.
 if (!$sessionVarsSet)
 {

    $errMsg = 'ERROR: One or more of the required $_SESSION[] vars was not set.';
    imagestring($image, 5, 5, 5, $errMsg, $colorBlack);

    // Output image and clear image from memory.
    imagepng($image);
    imagedestroy($image);

    // Exit.
    exit(0);
 }

/*
 // Data for testing.
 $_SESSION['runsArray']
   = array(-5, -5, 19, -7, -8, -12, 1, 1, -4, 6, -6, 8, 15, 15, 15, 6, 22, -10, -10);
 $_SESSION['datesArray']
   = array(array(16, 02, 2005), array(16, 02, 2005), array(23, 02, 2005), array(02, 03, 2005),
           array(08, 03, 2005), array(16, 03, 2005), array(23, 03, 2005), array(23, 03, 2005),
           array(13, 04, 2005), array(21, 04, 2005), array(27, 04, 2005), array(04, 05, 2005),
           array(11, 05, 2005), array(18, 05, 2005), array(18, 05, 2005), array(25, 05, 2005),
           array(31, 05, 2005), array(14, 06, 2005), array(14, 06, 2005)                      );
*/

 // Define chart dimensions.
 $chartWidth  = $imageWidth  - 100;
 $chartHeight = $imageHeight -  55;

 // Analyse data for the purpose of charting.
 $n_dataValues = count($_SESSION['runsArray']);
 $dataMax      =   max($_SESSION['runsArray']);
 $dataMin      =   min($_SESSION['runsArray']);
 $dataRange    = $dataMax - $dataMin;

 // Define x and y scaling factors (to scale from data units to pixels).
 $y_scalingFactor = $chartHeight / (($dataMin > 0)? $dataMax: $dataRange);
 $x_scalingFactor = $chartWidth  / $n_dataValues;

 // Define chart extremities.
 // NOTE: For all drawing done relating to the chart, if the coordinate to be used is not
 //       $chartMinX or $chartMinY, scaled data units must be used so that rounding is done
 //       consistently preventing 'out by one pixel' errors.
 $chartMinX = $imageMinX + 50; $chartMaxX = $chartMinX + $chartWidth;
 $chartMaxY = $imageMaxY - 50; $chartMinY = $chartMaxY - $chartHeight;

 /*
  * Coordinate conversion functions.
  * (Need to save variables below to $_SESSION[] so that they are visible to functions.
  */
 $_SESSION['chartMaxY'      ] = $chartMaxY;
 $_SESSION['chartMinX'      ] = $chartMinX;
 $_SESSION['dataMin'        ] = $dataMin;
 $_SESSION['x_scalingFactor'] = $x_scalingFactor;
 $_SESSION['y_scalingFactor'] = $y_scalingFactor;
 function convChartYtoImageY($y)
 {
    $offset = ($_SESSION['dataMin'] < 0)? $_SESSION['dataMin']: 0;

    return $_SESSION['chartMaxY'] - $_SESSION['y_scalingFactor'] * ($y - $offset);
 }
 function convChartXtoImageX($x)
 {
    return $_SESSION['chartMinX'] + $_SESSION['x_scalingFactor'] * $x;
 }


 // Start: Draw chart background. ///////////////////////////////////////////////////////////////

 // Draw light blue background over entire chart.
 imagefilledrectangle($image, $chartMinX, convChartYtoImageY($dataMax),
                              $chartMaxX, $chartMaxY, $colorLightBlue);

 // Set stripe height (units are data units, not pixels).
 $stripeCycleHeight = $_SESSION['horizStripeHeight'];
 $darkStripeHeight  = $stripeCycleHeight / 2;

 // Draw dark blue stripes for portion of chart in which y > 0.
 for ($i = 0; $i <= $dataMax; $i += $stripeCycleHeight)
 {
    // Calculate stripe position in chart data units and chart coords.
    $stripeBottom = $i;
    $stripeTop    = $i + $darkStripeHeight;
    if ($stripeTop > $dataMax)
      $stripeTop = $dataMax;

    // Calculate stripe position in pixels and screen coords.
    $y_stripeBottom = convChartYtoImageY($stripeBottom);
    $y_stripeTop    = convChartYtoImageY($stripeTop   );

    imagefilledrectangle($image, $chartMinX, $y_stripeTop,
                                 $chartMaxX, $y_stripeBottom, $colorDarkBlue);
 }

 // Draw dark blue stripes for portion of graph in which y < 0.
 for ($i = -$darkStripeHeight; $i >= $dataMin; $i -= $stripeCycleHeight)
 {
    // Calculate stripe position in chart data units and chart coords.
    $stripeTop    = $i;
    $stripeBottom = $i - $darkStripeHeight;
    if ($stripeBottom < $dataMin)
      $stripeBottom = $dataMin;

    // Calculate stripe position in pixels and screen coords.
    $y_stripeTop    = convChartYtoImageY($stripeTop   );
    $y_stripeBottom = convChartYtoImageY($stripeBottom);

    imagefilledrectangle($image, $chartMinX, $y_stripeTop,
                                 $chartMaxX, $y_stripeBottom, $colorDarkBlue);
 }

 // Finish: Draw chart background. //////////////////////////////////////////////////////////////


 // Start: Draw chart text. /////////////////////////////////////////////////////////////////////

 $fontSize = 5; // Size of default font to be used for all text strings printed on image.
 $n_pixelsPerCharX = imagefontwidth($fontSize);
 $n_pixelsPerCharY = imagefontheight($fontSize);

 // Draw horizontal axis label.
 printXcenteredHorizTextString($image, $fontSize,
                               $chartMinX, $chartMaxX, $chartMaxY + 30,
                               $_SESSION['chartHorizAxisHeading'], $colorBlack);

 // Start: Draw vertical axis labels. ////////////////////////////////////////////////////////

 // Draw left axis label.
 printYcenteredVertTextString($image, $fontSize,
                              $chartMinX - 50, $chartMinY, $chartMaxY,
                              $_SESSION['chartVertAxisHeading'], $colorBlack);

 // Draw right axis label (for cumulative average line).
 $textStr = 'Cumulative Average ';
 $n_charsInTextStr = strlen($textStr);
 $y = $chartMinY + ($chartHeight + $n_charsInTextStr * $n_pixelsPerCharX) / 2 + 0.5;
 $y -= 1 * $n_pixelsPerCharX; // Correction for inclusion of line key indicator.
 $x = $chartMaxX + $n_pixelsPerCharY; // Right of chart.
 imagestringup($image, $fontSize, $x, $y, $textStr, $colorBlack);

 // Draw cumulative average line key indicator.
 $y_top    = $y     + $n_pixelsPerCharX;
 $y_bottom = $y_top + $n_pixelsPerCharX;
 $x_left   = $x;
 $x_middle = $x + $n_pixelsPerCharY / 2;
 $x_right  = $x + $n_pixelsPerCharY;
 imagefilledrectangle($image, $x_left, $y_top, $x_right, $y_bottom, $colorDarkGreen);
 $x += $n_pixelsPerCharY / 2;
 imageline($image, $x_middle, $y_top, $x_middle, $y_bottom, $colorRed);

 // Draw right axis label (for exponential moving average line).
 $textStr = 'Exp. Moving Average';
 $n_charsInTextStr = strlen($textStr);
 $y = $chartMinY + ($chartHeight + $n_charsInTextStr * $n_pixelsPerCharX) / 2 + 0.5;
 $y -= 1 * $n_pixelsPerCharX; // Correction for inclusion of line key indicator.
 $x = $chartMaxX + 2 * $n_pixelsPerCharY; // Right of chart.
 imagestringup($image, $fontSize, $x, $y, $textStr, $colorBlack);

 // Draw exponential moving average line key indicator.
 $y_top    = $y     + $n_pixelsPerCharX;
 $y_bottom = $y_top + $n_pixelsPerCharX;
 $x_left   = $x;
 $x_middle = $x + $n_pixelsPerCharY / 2;
 $x_right  = $x + $n_pixelsPerCharY;
 imagefilledrectangle($image, $x_left, $y_top, $x_right, $y_bottom, $colorDarkGreen);
 $x += $n_pixelsPerCharY / 2;
 imageline($image, $x_middle, $y_top, $x_middle, $y_bottom, $colorYellow);

 // Finish: Draw vertical axis labels. ///////////////////////////////////////////////////////


 // Draw vertical axis numbers for data >= 0 on left side.
 $fontSize = 5;
 for ($i = 0; $i <= $dataMax; $i += $stripeCycleHeight / 2)
 {
    $textStr = (string)$i;
    $n_charsInTextStr = strlen($textStr);
    $x = $chartMinX - 5 - $n_pixelsPerCharX * $n_charsInTextStr;
    $y = convChartYtoImageY($i) - $n_pixelsPerCharY / 2;
    imagestring($image, $fontSize, $x, $y, $textStr, $colorBlack);
 }

 // Draw vertical axis numbers for data < 0 on left side.
 $fontSize = 5;
 $stepSize = $stripeCycleHeight / 2;
 for ($i = -$stepSize; $i >= $dataMin; $i -= $stepSize)
 {
    $textStr = (string)$i;
    $n_charsInTextStr = strlen($textStr);
    $x = $chartMinX - 5 - $n_pixelsPerCharX * $n_charsInTextStr;
    $y = convChartYtoImageY($i) - $n_pixelsPerCharY / 2;
    imagestring($image, $fontSize, $x + 1, $y, $textStr, $colorBlack);
 }

 // Draw horizontal axis month and year descriptions and dividing lines.
 $prevMonth       = -1;
 $currMonth       = -1;
 $prevYear        = -1;
 $currYear        = -1;
 $prevMonthStartX = $chartMinX;
 $prevYearStartX  = $chartMinX;
 for ($i = 0; $i <= $n_dataValues; ++$i)
 {
    // Get current month.
    if ($i < $n_dataValues)
      $currMonth = $_SESSION['datesArray'][$i][1];
    // Else the previous value is used.

    if ($currMonth != $prevMonth || $i == $n_dataValues)
    {
       // A new month has been discovered, or the last data value has been reached.
       // In either case, we want to draw a dividing line, and draw the month name
       // for the previous month in the center of the space between dividing lines.

       // Draw dividing line at start of new month.
       $x_newMonthLine = convChartXtoImageX($i);
       $y              = $chartMaxY + 15;
       if ($i == 0)
       {
          // Must also draw new year dividing line.
          $y += 15;

          // Update $prevYearStartX and $currYear.
          $currYear = $_SESSION['datesArray'][$i][2];
          $prevYearStartX = $x_newMonthLine;
       }
       imageline($image, $x_newMonthLine, $chartMaxY,
                         $x_newMonthLine,         $y, $colorBlack);

       if ($i > 0)
       {
          // Create a month abbreviation string that will fit in the available space.
          $monthWidth = $x_newMonthLine - $prevMonthStartX;
          $monthNameStr = getMonthName($prevMonth);
          if ($monthWidth > strlen($monthNameStr) * $n_pixelsPerCharX + 10)
            $textStr = $monthNameStr;
          else
          {
             if ($monthWidth > 3 * $n_pixelsPerCharX + 5)
               $textStr = getMonthThreeLetterAbbrev($prevMonth);
             else
             {
                if ($monthWidth > $n_pixelsPerCharX + 2)
                  $textStr = getMonthOneLetterAbbrev($prevMonth);
                else
                  $textStr = '';
             }
          }
          $n_charsInTextStr = strlen($textStr);

          // Draw month abbreviation at center of previous month.
          printXcenteredHorizTextString($image, $fontSize,
                                        $prevMonthStartX, $x_newMonthLine, $chartMaxY,
                                        $textStr, $colorBlack                         );

          // Update $prevMonthStartX.
          $prevMonthStartX = $x_newMonthLine;

          // Get current year.
          if ($i < $n_dataValues)
            $currYear = $_SESSION['datesArray'][$i][2];
          // Else the previous value is used.

          // Test for new year and draw lines and text if necessary.
          if ($currYear != $prevYear || $i == $n_dataValues)
          {
             // Draw dividing line at start of new year.
             $x_newYearLine = convChartXtoImageX($i);
             imageline($image, $x_newMonthLine, $chartMaxY + 16,
                               $x_newMonthLine, $chartMaxY + 30, $colorBlack);

             if ($i > 0)
             {
                // Create a year abbreviation string that will fit in the available space.
                $yearWidth = $x_newYearLine - $prevYearStartX;
                if ($yearWidth > 4 * $n_pixelsPerCharX + 10)
                  $textStr = (string)$prevYear;
                else
                {
                   if ($yearWidth > 3 * $n_pixelsPerCharX + 2)
                     $textStr = "'" . (string)$prevYear[2]  // "'" + Last two digits of $prevYear
                                    . (string)$prevYear[3]; // eg. "'06".
                   else
                   {
                      if ($yearWidth > 2 * $n_pixelsPerCharX + 1)
                        $textStr =  (string)$prevYear[2]  // Last two digits of $prevYear eg."'6".
                                  . (string)$prevYear[3]; // eg. "06".
                      else
                        $textStr = '';
                   }
                }
                $n_charsInTextStr = strlen($textStr);

                // Draw year text at center of previous year.
                printXcenteredHorizTextString($image, $fontSize,
                                              $prevYearStartX, $x_newMonthLine,
                                              $chartMaxY + $n_pixelsPerCharY,
                                              $textStr, $colorBlack            );

                // Update $prevYearStartX.
                $prevYearStartX = $x_newYearLine;
             }
          }
       }

       // Update $prevMonth and $prevYear.
       $prevMonth = $currMonth;
       $prevYear  = $currYear;
    }
 }
 // Finish: Draw chart text. ////////////////////////////////////////////////////////////////////

 // Draw chart.
 $y_dataZero = convChartYtoImageY(0); // Y coordinate of data zero level.
 $cumulativeTotal = 0;
 $exponentialAvg = $_SESSION['runsArray'][0]; // Exponential average initial value.
 $maxAlpha = 0.95;
 foreach ($_SESSION['runsArray'] as $key => $value)
 {
    // Draw column.
    $color   = ($key % 2 == 0)? $colorDarkGreen: $colorLightGreen;
    $x_left  = convChartXtoImageX($key    );
    $x_right = convChartXtoImageX($key + 1);
    $y_data  = convChartYtoImageY($value);
    // NOTE: Must use different rectangle descriptions below depending on
    //       whether $value is positive because of bug in PHP on web host's server.
    //       When PHP is upgraded, check whether still needed.
    if ($value > 0)
      imagefilledrectangle($image, $x_left, $y_data, $x_right, $y_dataZero, $color);
    else
      imagefilledrectangle($image, $x_left, $y_dataZero, $x_right, $y_data, $color);

    // Draw cumulative average line.
    $cumulativeTotal += $value;
    $cumulativeAvg = $cumulativeTotal / ($key + 1);
    $y_avg = convChartYtoImageY($cumulativeAvg);
    imageline($image, $x_left, $y_avg, $x_right, $y_avg , $colorRed);

    // Draw exponential moving average line.
    $alpha = $maxAlpha * ($key / ($key + 1));
    $exponentialAvg = ($alpha * $exponentialAvg) + (1 - $alpha) * $value;
    $y_avg = convChartYtoImageY($exponentialAvg);
    imageline($image, $x_left, $y_avg, $x_right, $y_avg , $colorYellow);
 }

 // Draw black border at edge of chart.
 imagerectangle($image, $chartMinX, $chartMinY,
                        $chartMaxX, $chartMaxY, $colorBlack);

 // Draw black lines at top and bottom of year text.
 imageline($image, $chartMinX, $chartMaxY + 16, $chartMaxX, $chartMaxY + 16, $colorBlack);
 imageline($image, $chartMinX, $chartMaxY + 30, $chartMaxX, $chartMaxY + 30, $colorBlack);

 // Output graph and clear image from memory.
 imagepng($image);
 imagedestroy($image);
?>
