<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "icdb_generate_histogram.php"
*
* Project: IndoorCricketStats.net.
*
* Purpose: Generate a histogram image using functions from the 'gd' library.
*
* Author: Tom McDonnell 2008-05-04.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

ini_set('display_errors'        , '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once 'date_functions.php';
require_once 'image_functions.php';

// Defines. ////////////////////////////////////////////////////////////////////////////////////////

// Define image position and dimensions.
// (Minimums for image will always be zero but are included here for uniformity.)
$imageWidth  = 900; $imageMinX = 0; $imageMaxX = $imageMinX + $imageWidth  - 1;
$imageHeight = 480; $imageMinY = 0; $imageMaxY = $imageMinY + $imageHeight - 1;

// Global variables. ///////////////////////////////////////////////////////////////////////////////

$colors = array
(
   'darkBlue'   => imagecolorallocate($image, 0x00, 0x61, 0xc2), // Color of td.d from "style.css".
   'lightBlue'  => imagecolorallocate($image, 0x00, 0x71, 0xe2), // Color of td.l from "style.css".
   'darkGreen'  => imagecolorallocate($image, 0x48, 0x90, 0x00), // Color of h3.d from "style.css".
   'lightGreen' => imagecolorallocate($image, 0x50, 0xa0, 0x00), // Color of h3.l from "style.css".
   'red'        => imagecolorallocate($image, 0xff, 0x00, 0x00),
   'black'      => imagecolorallocate($image, 0x00, 0x00, 0x00),
   'white'      => imagecolorallocate($image, 0xff, 0xff, 0xff),
   'yellow'     => imagecolorallocate($image, 0xff, 0xff, 0x00)
);

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

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   // If required $_SESSION[] variables are not set, display error message in image and exit.
   if
   (
      !
      (
         isset($_SESSION['runsArray'            ]) &&
         isset($_SESSION['wicketsArray'         ]) &&
         isset($_SESSION['datesArray'           ]) &&
         isset($_SESSION['horizStripeHeight'    ]) &&
         isset($_SESSION['chartVertAxisHeading' ]) &&
         isset($_SESSION['chartHorizAxisHeading'])
      )
   )
   {
      $errMsg = 'ERROR: One or more of the required $_SESSION[] vars was not set.';
      imagestring($image, 5, 5, 5, $errMsg, $colors['black']);

      // Output image and clear image from memory.
      imagepng($image);
      imagedestroy($image);

      exit(0);
   }

   // Define and create .PNG image.
   header('Content-type: image/png');
   $image = imagecreate($imageWidth, $imageHeight);

   // Draw image background.
   imagefilledrectangle($image, $imageMinX, $imageMinY, $imageMaxX, $imageMaxY, $colors['white']);

/*
   // Define chart dimensions.
   $chartWidth  = $imageWidth  - 100;
   $chartHeight = $imageHeight -  55;
  
   // Analyse data.
   $n_dataValues  = count($_SESSION['runsArray']);
   $dataTotal = 0;
   $dataMax = $_SESSION['runsArray'][0];
   $dataMin = $_SESSION['runsArray'][0];
   foreach ($_SESSION['runsArray'] as $value)
   {
      $dataTotal += $value;
  
      // NOTE: Must use ceil & floor functions to avoid fractional amounts in column range limits.
      if (ceil($value)  > $dataMax) $dataMax = ceil($value);
      if (floor($value) < $dataMin) $dataMin = floor($value);
   }
   $dataMean      = $dataTotal / $n_dataValues;
   $dataRange     = ($dataMax == $dataMin)? $dataMax: $dataMax - $dataMin;
   $dataFreqArray = array_count_values($_SESSION['runsArray']);
   $dataMaxFreq   = max($dataFreqArray);
  
   // Create a new array $modifiedDataFreqArray containing: for each histogram column,
   // the number of data values falling within the allowed range for that column.
   // The range for each column is given by $dataRangePerColumn, and is calculated using a
   // function designed to give a nice bell curve for varying $dataMaxFreq and $dataRange values.
   $dataRangePerColumn = (int)($dataRange * exp(-4)) + 1;
   $n_columns = ceil(($dataRange + 1) / $dataRangePerColumn);
   $modifiedDataFreqArray = array($n_columns + 1);
   for ($i = 0; $i < $n_columns; ++$i)
   {
      $dataColumnMin = $dataMin + $i * $dataRangePerColumn;
      $dataColumnMax = $dataColumnMin + $dataRangePerColumn;
  
      $modifiedDataFreqArray[$i] = 0;
      foreach ($dataFreqArray as $key => $value)
        if ($dataColumnMin <= $key and $key < $dataColumnMax)
          $modifiedDataFreqArray[$i] += $value;
   }
   $modifiedDataMaxFreq = max($modifiedDataFreqArray);
  
   // Define x and y scaling factors (to scale from data units to pixels).
   $x_scalingFactor = $chartWidth  / $n_columns;
   $y_scalingFactor = $chartHeight / $modifiedDataMaxFreq;
  
   // Define chart extremities.
   // NOTE: For all drawing done relating to the chart, if the coordinate to be used is not
   //       $chartMinX or $chartMinY, scaled data units must be used so that rounding is done
   //       consistently preventing 'out by one pixel' errors.
   $chartMinX = $imageMinX + 50; $chartMaxX = $chartMinX + $n_columns           * $x_scalingFactor;
   $chartMaxY = $imageMaxY - 50; $chartMinY = $chartMaxY - $modifiedDataMaxFreq * $y_scalingFactor;
  
   /*
    * Coordinate conversion functions.
    * (Need to save variables below to $_SESSION[] so that they are visible to functions.
    *
   $_SESSION['chartMaxY'      ] = $chartMaxY;
   $_SESSION['chartMinX'      ] = $chartMinX;
   $_SESSION['dataMin'        ] = $dataMin;
   $_SESSION['x_scalingFactor'] = $x_scalingFactor;
   $_SESSION['y_scalingFactor'] = $y_scalingFactor;
   function convChartYtoImageY($y)
   {
      return $_SESSION['chartMaxY'] - $_SESSION['y_scalingFactor'] * $y;
   }
   function convChartXtoImageX($x)
   {
      return $_SESSION['chartMinX'] + $_SESSION['x_scalingFactor'] * $x;
   }
  
  
   // Start: Draw chart background. ///////////////////////////////////////////////////////////////
  
   // Draw light blue background over entire chart.
   imagefilledrectangle($image, $chartMinX, $chartMinY,
                                $chartMaxX, $chartMaxY, $colors['lightBlue']);
  
   // Set stripe height (units are data units, not pixels).
   $stripeCycleHeight = round($modifiedDataMaxFreq / 5);//$_SESSION['horizStripeHeight'];
   if ($stripeCycleHeight == 0)
     $stripeCycleHeight = 2;
   else
     if ($stripeCycleHeight % 2 != 0)
       ++$stripeCycleHeight;
   $darkStripeHeight = $stripeCycleHeight / 2;
  
   // Draw dark blue stripes for portion of chart in which y > 0.
   for ($i = 0; $i <= $modifiedDataMaxFreq; $i += $stripeCycleHeight)
   {
      // Calculate stripe position in chart data units and chart coords.
      $stripeBottom = $i;
      $stripeTop    = $i + $darkStripeHeight;
      if ($stripeTop > $modifiedDataMaxFreq)
        $stripeTop = $modifiedDataMaxFreq;
  
      // Calculate stripe position in pixels and screen coords.
      $y_stripeBottom = convChartYtoImageY($stripeBottom);
      $y_stripeTop    = convChartYtoImageY($stripeTop   );
  
      imagefilledrectangle($image, $chartMinX, $y_stripeTop,
                                   $chartMaxX, $y_stripeBottom, $colors['darkBlue']);
   }
  
   // Finish: Draw chart background. //////////////////////////////////////////////////////////////
  
  
   // Start: Draw chart text. /////////////////////////////////////////////////////////////////////
  
   $fontSize         = 5;
   $n_pixelsPerCharX = imagefontwidth($fontSize);
   $n_pixelsPerCharY = imagefontheight($fontSize);
  
  
   // Start: Draw horizontal axis labels. /////////////////////////////////////////////////
  
   // Draw horizontal axis label.
   // NOTE: $_SESSION['chartVertAxisHeading'] is used here because the horizontal axis heading
   //       of the histogram is the same as the vertical axis heading of the column chart.
   printXcenteredHorizTextString($image, $fontSize,
                                 $chartMinX, $chartMaxX, $chartMaxY + 30,
                                 $_SESSION['chartVertAxisHeading'], $colors['black']);
  
   // Draw short vertical lines at bottom of columns.
   for ($i = 0; $i <= $n_columns; ++$i)
   {
      $x = convChartXtoImageX($i);
      $y = $chartMaxY + 5;
      imageline($image, $x, $chartMaxY, $x, $y, $colors['black']);
   }
  
   // Calculate width of columns.
   $columnWidth = $x_scalingFactor;
  
   // Find the minimum number of column widths required for the column text labels.
   $intDataMin = (int)$dataMin;
   $intDataMax = (int)$dataMax;
   $testStringWidth1 = (strlen("($intDataMin - $intDataMin)") + 1) * $n_pixelsPerCharX;
   $testStringWidth2 = (strlen("($intDataMax - $intDataMax)") + 1) * $n_pixelsPerCharX;
   $n_columnsPerLabel = 1;
   while (   $n_columnsPerLabel * $columnWidth < $testStringWidth1
          || $n_columnsPerLabel * $columnWidth < $testStringWidth2)
     ++$n_columnsPerLabel;
  
   // Draw column labels at base of one in every $n_columns columns.
   $startColumn = (int)(($n_columns % $n_columnsPerLabel) / 2 + $n_columnsPerLabel / 2);
   for ($i = $startColumn; $i < $n_columns; $i += $n_columnsPerLabel)
   {
      $colRangeMin = $dataMin + $i * $dataRangePerColumn;
      $colRangeMax = $colRangeMin + $dataRangePerColumn;
      $textString = "[$colRangeMin to $colRangeMax)";
      $x = convChartXtoImageX($i) + ($columnWidth - strlen($textString) * $n_pixelsPerCharX) / 2 + 1;
      $y = $chartMaxY + 5;
      printXcenteredHorizTextString($image, $fontSize,
                                    convChartXToImageX($i    ),
                                    convChartXToImageX($i + 1),
                                    $chartMaxY + 5, $textString, $colors['black']);
   }
  
   // Finish: Draw horizontal axis labels. /////////////////////////////////////////////////
  
   // Start: Draw vertical axis labels. ////////////////////////////////////////////////////
  
   // Draw left axis label.
   printYcenteredVertTextString($image, $fontSize,
                                $chartMinX - 50, $chartMinY, $chartMaxY,
                                'Frequency', $colors['black']                );
  
   // Draw vertical axis numbers for data >= 0 on left side.
   $fontSize = 5;
   for ($i = 0; $i <= $modifiedDataMaxFreq; $i += $stripeCycleHeight / 2)
   {
      $textStr = (string)$i;
      $n_charsInTextStr = strlen($textStr);
      $x = $chartMinX - 5 - $n_pixelsPerCharX * $n_charsInTextStr;
      $y = convChartYtoImageY($i) - $n_pixelsPerCharY / 2;
      imagestring($image, $fontSize, $x, $y, $textStr, $colors['black']);
   }
  
   // Draw right axis label (for data mean line).
   $textStr = sprintf('Average (mean) = %.3f', $dataMean);
   $n_charsInTextStr = strlen($textStr);
   $y = $chartMinY + ($chartHeight + $n_charsInTextStr * $n_pixelsPerCharX) / 2 + 0.5;
   $y -= 1 * $n_pixelsPerCharX; // Correction for inclusion of line key indicator.
   $x = $chartMaxX + $n_pixelsPerCharY; // Right of chart.
   imagestringup($image, $fontSize, $x, $y, $textStr, $colors['Black']);
  
   // Draw data mean line key indicator.
   $y_top    = $y     + $n_pixelsPerCharX;
   $y_bottom = $y_top + $n_pixelsPerCharX;
   $x_left   = $x;
   $x_middle = $x + $n_pixelsPerCharY / 2;
   $x_right  = $x + $n_pixelsPerCharY;
   imagefilledrectangle($image, $x_left, $y_bottom, $x_right, $y_top, $colors['darkGreen']);
   $x += $n_pixelsPerCharY / 2;
   imageline($image, $x_middle, $y_top, $x_middle, $y_bottom, $colors['red']);
  /*
   // Draw right axis label (for standard deviation line).
   $textStr = 'Standard Deviation';
   $n_charsInTextStr = strlen($textStr);
   $y = $chartMinY + ($chartHeight + $n_charsInTextStr * $n_pixelsPerCharX) / 2 + 0.5;
   $y -= 1 * $n_pixelsPerCharX; // Correction for inclusion of line key indicator.
   $x = $chartMaxX + 2 * $n_pixelsPerCharY; // Right of chart.
   imagestringup($image, $fontSize, $x, $y, $textStr, $colors['black']);
  
   // Draw standard deviation line key indicator.
   $y_top    = $y     + $n_pixelsPerCharX;
   $y_bottom = $y_top + $n_pixelsPerCharX;
   $x_left   = $x;
   $x_middle = $x + $n_pixelsPerCharY / 2;
   $x_right  = $x + $n_pixelsPerCharY;
   imagefilledrectangle($image, $x_left, $y_top, $x_right, $y_bottom, $colors['darkGreen']);
   $x += $n_pixelsPerCharY / 2;
   imageline($image, $x_middle, $y_top, $x_middle, $y_bottom, $colors['yellow']);
  *
   // Finish: Draw vertical axis labels. //////////////////////////////////////////////////
  
   // Finish: Draw chart text. ////////////////////////////////////////////////////////////////////
  
   // Draw chart.
   foreach ($modifiedDataFreqArray as $key => $value)
   {
      // Draw column.
      $color   = ($key % 2 == 0)? $colors['darkGreen']: $colors['lightGreen'];
      $x_left  = convChartXtoImageX($key    );
      $x_right = convChartXtoImageX($key + 1);
      $y_data  = convChartYtoImageY($value);
      // NOTE: Must use particular rectangle description below
      //       because of bug in PHP on web host's server.
      //       Must use (left, top, right, bottom) and not (left, bottom, right, top).
      //       When PHP is upgraded, check whether still needed.
      imagefilledrectangle($image, $x_left, $y_data, $x_right, $chartMaxY , $color);
   }
  
   // Draw vertical lines for mean and +/-standard deviation.
   $x_dataMean = convChartXtoImageX((-$dataMin + $dataMean) / $dataRangePerColumn);
   imageline($image, $x_dataMean, $chartMinY, $x_dataMean, $chartMaxY, $colors['red']);
  
   // Draw black border at edge of chart.
   imagerectangle($image, $chartMinX, $chartMinY,
                          $chartMaxX, $chartMaxY, $colors['black']);
*/
   // Output graph and clear image from memory.
   imagepng($image);
   imagedestroy($image);
}
catch (Exception $e)
{
   echo $e;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*******************************************END*OF*FILE********************************************/
?>
