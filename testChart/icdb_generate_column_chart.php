<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "icdb_generate_column_chart.php"
*
* Project: IndoorCricketStats.net.
*
* Purpose: Generate a column chart image using functions from the 'gd' library.
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

define('IMAGE_HEIGHT', 480);
define('IMAGE_WIDTH' , 900);

define('CHART_WIDTH' , IMAGE_WIDTH  - 100);
define('CHART_HEIGHT', IMAGE_HEIGHT -  55);

define('IMAGE_MIN_X', 0);
define('IMAGE_MIN_Y', 0);
define('IMAGE_MAX_X', IMAGE_MIN_X + IMAGE_WIDTH  - 1);
define('IMAGE_MAX_Y', IMAGE_MIN_Y + IMAGE_HEIGHT - 1);

// Define chart extremities
// NOTE: For all drawing done relating to the chart, if the coordinate to be used is not
//       CHART_MIN_X or CHART_MIN_Y, scaled data units must be used so that rounding is done
//       consistently preventing 'out by one pixel' errors.
define('CHART_MAX_Y', IMAGE_MAX_Y - 50);
define('CHART_MIN_X', IMAGE_MIN_X + 50);
define('CHART_MAX_X', CHART_MIN_X + CHART_WIDTH);
define('CHART_MIN_Y', CHART_MAX_Y - CHART_HEIGHT);

// Analyse data for the purpose of charting.
define('N_DATA_VALUES', count($_SESSION['runsArray']));
define('DATA_MAX', max($_SESSION['runsArray']));
define('DATA_MIN', min($_SESSION['runsArray']));
define('DATA_RANGE', DATA_MAX - DATA_MIN);

// Define x and y scaling factors (to scale from data units to pixels).
define('Y_SCALING_FACTOR', CHART_HEIGHT / ((DATA_MIN > 0)? DATA_MAX: DATA_RANGE));
define('X_SCALING_FACTOR', CHART_WIDTH  / N_DATA_VALUES);

/*
 * Coordinate conversion functions.
 * (Need to save variables below to $_SESSION[] so that they are visible to functions.
 */
$_SESSION['chartMaxY'      ] = CHART_MAX_Y;
$_SESSION['chartMinX'      ] = CHART_MIN_X;
$_SESSION['dataMin'        ] = DATA_MIN;
$_SESSION['x_scalingFactor'] = X_SCALING_FACTOR;
$_SESSION['y_scalingFactor'] = Y_SCALING_FACTOR;

// Set stripe height (units are data units, not pixels).
define('STRIPE_CYCLE_HEIGHT', $_SESSION['horizStripeHeight']);
define('DARK_STRIPE_HEIGHT' , STRIPE_CYCLE_HEIGHT / 2);

define('FONT_SIZE', 5);
define('N_PIXELS_PER_CHAR_X', imagefontwidth(FONT_SIZE) );
define('N_PIXELS_PER_CHAR_Y', imagefontheight(FONT_SIZE));

// Global variables. ///////////////////////////////////////////////////////////////////////////////

/*
// Data for testing.
$_SESSION['runsArray'] = array
(
   -5, -5, 19, -7, -8, -12, 1, 1, -4, 6, -6, 8, 15, 15, 15, 6, 22, -10, -10
);
$_SESSION['datesArray'] = array
(
   array(16, 02, 2005), array(16, 02, 2005), array(23, 02, 2005), array(02, 03, 2005),
   array(08, 03, 2005), array(16, 03, 2005), array(23, 03, 2005), array(23, 03, 2005),
   array(13, 04, 2005), array(21, 04, 2005), array(27, 04, 2005), array(04, 05, 2005),
   array(11, 05, 2005), array(18, 05, 2005), array(18, 05, 2005), array(25, 05, 2005),
   array(31, 05, 2005), array(14, 06, 2005), array(14, 06, 2005)
);
*/

// Globally executed code. /////////////////////////////////////////////////////////////////////////

// NOTE: This file requires that the following $_SESSION[] variables be set:
//         $_SESSION['runsArray'            ],
//         $_SESSION['wicketsArray'         ],
//         $_SESSION['datesArray'           ],
//         $_SESSION['horizStripeHeight'    ],
//         $_SESSION['chartVertAxisHeading' ],
//         $_SESSION['chartHorizAxisHeading'].

try
{
   // Define and create .PNG image.
   header('Content-type: image/png');

   $image = imagecreate(IMAGE_WIDTH, IMAGE_HEIGHT);

   // Create colors.
   $colors = array
   (
      'darkBlue'   => imagecolorallocate($image, 0x00, 0x61, 0xc2), // Color of td.d in "style.css".
      'lightBlue'  => imagecolorallocate($image, 0x00, 0x71, 0xe2), // Color of td.l in "style.css".
      'darkGreen'  => imagecolorallocate($image, 0x48, 0x90, 0x00), // Color of h3.d in "style.css".
      'lightGreen' => imagecolorallocate($image, 0x50, 0xa0, 0x00), // Color of h3.l in "style.css".
      'red'        => imagecolorallocate($image, 0xff, 0x00, 0x00),
      'black'      => imagecolorallocate($image, 0x00, 0x00, 0x00),
      'white'      => imagecolorallocate($image, 0xff, 0xff, 0xff),
      'yellow'     => imagecolorallocate($image, 0xff, 0xff, 0x00)
   );

   // Draw image background.
   imagefilledrectangle
   (
      $image,
      IMAGE_MIN_X, IMAGE_MIN_Y,
      IMAGE_MAX_X, IMAGE_MAX_Y,
      $colors['white']
   );

   drawChartBackground($image, $colors);
   drawChartText($image, $colors);
   drawChart($image, $colors);

   // Output graph and clear image from memory.
   imagepng($image);
   imagedestroy($image);
}
catch (Exception $e)
{
   echo $e;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function convChartYtoImageY($y)
{
   $offset = ($_SESSION['dataMin'] < 0)? $_SESSION['dataMin']: 0;

   return $_SESSION['chartMaxY'] - $_SESSION['y_scalingFactor'] * ($y - $offset);
}

/*
 *
 */
function convChartXtoImageX($x)
{
   return $_SESSION['chartMinX'] + $_SESSION['x_scalingFactor'] * $x;
}

/*
 *
 */
function drawChartBackground($image, $colors)
{
   imagefilledrectangle
   (
      $image,
      CHART_MIN_X, convChartYtoImageY(DATA_MAX),
      CHART_MAX_X, CHART_MAX_Y,
      $colors['lightBlue']
   );

   // Draw dark blue stripes for portion of chart in which y > 0.
   for ($i = 0; $i <= DATA_MAX; $i += STRIPE_CYCLE_HEIGHT)
   {
      // Calculate stripe position in chart data units and chart coords.
      $stripeBottom = $i;
      $stripeTop    = $i + DARK_STRIPE_HEIGHT;

      if ($stripeTop > DATA_MAX)
      {
         $stripeTop = DATA_MAX;
      }

      // Calculate stripe position in pixels and screen coords.
      $y_stripeBottom = convChartYtoImageY($stripeBottom);
      $y_stripeTop    = convChartYtoImageY($stripeTop   );

      imagefilledrectangle
      (
         $image,
         CHART_MIN_X, $y_stripeTop,
         CHART_MAX_X, $y_stripeBottom,
         $colors['darkBlue']
      );
   }

   // Draw dark blue stripes for portion of graph in which y < 0.
   for ($i = -DARK_STRIPE_HEIGHT; $i >= DATA_MIN; $i -= STRIPE_CYCLE_HEIGHT)
   {
      // Calculate stripe position in chart data units and chart coords.
      $stripeTop    = $i;
      $stripeBottom = $i - DARK_STRIPE_HEIGHT;

      if ($stripeBottom < DATA_MIN)
      {
         $stripeBottom = DATA_MIN;
      }

      // Calculate stripe position in pixels and screen coords.
      $y_stripeTop    = convChartYtoImageY($stripeTop   );
      $y_stripeBottom = convChartYtoImageY($stripeBottom);

      imagefilledrectangle
      (
         $image,
         CHART_MIN_X, $y_stripeTop,
         CHART_MAX_X, $y_stripeBottom,
         $colors['darkBlue']
      );
   }
}

/*
 *
 */
function drawChartText($image, $colors)
{
   // Draw horizontal axis label.
   printXcenteredHorizTextString
   (
      $image, FONT_SIZE,
      CHART_MIN_X, CHART_MAX_X, CHART_MAX_Y + 30,
      $_SESSION['chartHorizAxisHeading'], $colors['black']
   );

   drawVerticalAxisLabels($image, $colors);
   drawVerticalAxisNumbers($image, $colors);

   // Draw horizontal axis month and year descriptions and dividing lines.
   $prevMonth       = -1;
   $currMonth       = -1;
   $prevYear        = -1;
   $currYear        = -1;
   $prevMonthStartX = CHART_MIN_X;
   $prevYearStartX  = CHART_MIN_X;
   for ($i = 0; $i <= N_DATA_VALUES; ++$i)
   {
      // Get current month.
      if ($i < N_DATA_VALUES)
      {
         $currMonth = $_SESSION['datesArray'][$i][1];
      }
      // Else the previous value is used.

      if ($currMonth != $prevMonth || $i == N_DATA_VALUES)
      {
         // A new month has been discovered, or the last data value has been reached.
         // In either case, we want to draw a dividing line, and draw the month name
         // for the previous month in the center of the space between dividing lines.

         // Draw dividing line at start of new month.
         $x_newMonthLine = convChartXtoImageX($i);
         $y              = CHART_MAX_Y + 15;
         if ($i == 0)
         {
            // Must also draw new year dividing line.
            $y += 15;

            // Update $prevYearStartX and $currYear.
            $currYear = $_SESSION['datesArray'][$i][2];
            $prevYearStartX = $x_newMonthLine;
         }
         imageline
         (
            $image, $x_newMonthLine, CHART_MAX_Y, $x_newMonthLine, $y, $colors['black']
         );

         if ($i > 0)
         {
            // Create a month abbreviation string that will fit in the available space.
            $textStr = getMonthAbbrevStrForWidth($prevMonth, $x_newMonthLine - $prevMonthStartX);

            // Draw month abbreviation at center of previous month.
            printXcenteredHorizTextString
            (
               $image, FONT_SIZE,
               $prevMonthStartX, $x_newMonthLine, CHART_MAX_Y,
               $textStr, $colors['black']
            );

            // Update $prevMonthStartX.
            $prevMonthStartX = $x_newMonthLine;

            // Get current year.
            if ($i < N_DATA_VALUES)
            {
               $currYear = $_SESSION['datesArray'][$i][2];
            }
            // Else the previous value is used.

            // Test for new year and draw lines and text if necessary.
            if ($currYear != $prevYear || $i == N_DATA_VALUES)
            {
               // Draw dividing line at start of new year.
               $x_newYearLine = convChartXtoImageX($i);
               imageline
               (
                  $image,
                  $x_newMonthLine, CHART_MAX_Y + 16,
                  $x_newMonthLine, CHART_MAX_Y + 30,
                  $colors['black']
               );

               if ($i > 0)
               {
                  // Create a year abbreviation string that will fit in the available space.
                  $textStr = getYearAbbrevForWidth($prevYear, $x_newYearLine - $prevYearStartX);

                  // Draw year text at center of previous year.
                  printXcenteredHorizTextString
                  (
                     $image, FONT_SIZE,
                     $prevYearStartX, $x_newMonthLine,
                     CHART_MAX_Y + N_PIXELS_PER_CHAR_Y,
                     $textStr, $colors['black']
                  );

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

   // Draw black line at top of year text.
   imageline
   (
      $image,
      CHART_MIN_X, CHART_MAX_Y + 16,
      CHART_MAX_X, CHART_MAX_Y + 16,
      $colors['black']
   );

   // Draw black line at bottom of year text.
   imageline
   (
      $image,
      CHART_MIN_X, CHART_MAX_Y + 30,
      CHART_MAX_X, CHART_MAX_Y + 30,
      $colors['black']
   );
}

/*
 *
 */
function getMonthAbbrevStrForWidth($monthNo, $width)
{
   $monthName = getMonthName($monthNo);

   $limit1 = strlen($monthName) * N_PIXELS_PER_CHAR_X + 10;
   $limit2 = 3 * N_PIXELS_PER_CHAR_X + 5;
   $limit3 = N_PIXELS_PER_CHAR_X + 2;

   return
   (
      ($width > $limit1)? $monthName:
      (
         ($width > $limit2)? getMonthThreeLetterAbbrev($monthNo):
         (
            ($width > $limit3)? getMonthOneLetterAbbrev($monthNo): ''
         )
      )
   );
}

/*
 *
 */
function getYearAbbrevForWidth($year, $width)
{
   $yearStr = (string)$year;
   assert('strlen($yearStr) == 4');

   $limit1 = 4 * N_PIXELS_PER_CHAR_X + 10;
   $limit2 = 3 * N_PIXELS_PER_CHAR_X + 2;
   $limit3 = 2 * N_PIXELS_PER_CHAR_X + 1;

   return
   (
      ($width > $limit1)? $yearStr: // Eg. "2006".
      (
         ($width > $limit2)? "'" . substr($yearStr, 2, 2): // Eg. "'06".
         (
            ($width > $limit3)? "'" . substr($yearStr, 3, 1): '' // Eg."'6".
         )
      )
   );
}

/*
 *
 */
function drawVerticalAxisLabels($image, $colors)
{
   // Draw left axis label.
   printYcenteredVertTextString
   (
      $image, FONT_SIZE,
      CHART_MIN_X - 50, CHART_MIN_Y, CHART_MAX_Y,
      $_SESSION['chartVertAxisHeading'], $colors['black']
   );

   // Draw right axis label (for cumulative average line).
   $textStr = 'Cumulative Average ';
   $n_charsInTextStr = strlen($textStr);
   $y = CHART_MIN_Y + (CHART_HEIGHT + $n_charsInTextStr * N_PIXELS_PER_CHAR_X) / 2 + 0.5;
   $y -= 1 * N_PIXELS_PER_CHAR_X; // Correction for inclusion of line key indicator.
   $x = CHART_MAX_X + N_PIXELS_PER_CHAR_Y; // Right of chart.
   imagestringup($image, FONT_SIZE, $x, $y, $textStr, $colors['black']);

   // Draw cumulative average line key indicator.
   $y_top    = $y     + N_PIXELS_PER_CHAR_X;
   $y_bottom = $y_top + N_PIXELS_PER_CHAR_X;
   $x_left   = $x;
   $x_middle = $x + N_PIXELS_PER_CHAR_Y / 2;
   $x_right  = $x + N_PIXELS_PER_CHAR_Y;
   imagefilledrectangle($image, $x_left, $y_top, $x_right, $y_bottom, $colors['darkGreen']);
   $x += N_PIXELS_PER_CHAR_Y / 2;
   imageline($image, $x_middle, $y_top, $x_middle, $y_bottom, $colors['red']);

   // Draw right axis label (for exponential moving average line).
   $textStr = 'Exp. Moving Average';
   $n_charsInTextStr = strlen($textStr);
   $y = CHART_MIN_Y + (CHART_HEIGHT + $n_charsInTextStr * N_PIXELS_PER_CHAR_X) / 2 + 0.5;
   $y -= 1 * N_PIXELS_PER_CHAR_X; // Correction for inclusion of line key indicator.
   $x = CHART_MAX_X + 2 * N_PIXELS_PER_CHAR_Y; // Right of chart.
   imagestringup($image, FONT_SIZE, $x, $y, $textStr, $colors['black']);

   // Draw exponential moving average line key indicator.
   $y_top    = $y     + N_PIXELS_PER_CHAR_X;
   $y_bottom = $y_top + N_PIXELS_PER_CHAR_X;
   $x_left   = $x;
   $x_middle = $x + N_PIXELS_PER_CHAR_Y / 2;
   $x_right  = $x + N_PIXELS_PER_CHAR_Y;
   imagefilledrectangle($image, $x_left, $y_top, $x_right, $y_bottom, $colors['darkGreen']);
   $x += N_PIXELS_PER_CHAR_Y / 2;
   imageline($image, $x_middle, $y_top, $x_middle, $y_bottom, $colors['yellow']);
}

/*
 *
 */
function drawVerticalAxisNumbers($image, $colors)
{
   // Draw vertical axis numbers for data >= 0 on left side.
   for ($i = 0; $i <= DATA_MAX; $i += STRIPE_CYCLE_HEIGHT / 2)
   {
      $textStr = (string)$i;
      $n_charsInTextStr = strlen($textStr);
      $x = CHART_MIN_X - 5 - N_PIXELS_PER_CHAR_X * $n_charsInTextStr;
      $y = convChartYtoImageY($i) - N_PIXELS_PER_CHAR_Y / 2;
      imagestring($image, FONT_SIZE, $x, $y, $textStr, $colors['black']);
   }

   // Draw vertical axis numbers for data < 0 on left side.
   $stepSize = STRIPE_CYCLE_HEIGHT / 2;
   for ($i = -$stepSize; $i >= DATA_MIN; $i -= $stepSize)
   {
      $textStr = (string)$i;
      $n_charsInTextStr = strlen($textStr);
      $x = CHART_MIN_X - 5 - N_PIXELS_PER_CHAR_X * $n_charsInTextStr;
      $y = convChartYtoImageY($i) - N_PIXELS_PER_CHAR_Y / 2;
      imagestring($image, FONT_SIZE, $x + 1, $y, $textStr, $colors['black']);
   }
}

/*
 *
 */
function drawChart($image, $colors)
{
   $y_dataZero      = convChartYtoImageY(0); // Y coordinate of data zero level.
   $cumulativeTotal = 0;
   $exponentialAvg  = $_SESSION['runsArray'][0]; // Exponential average initial value.
   $maxAlpha        = 0.95;

   foreach ($_SESSION['runsArray'] as $key => $value)
   {
      // Draw column.
      $color   = ($key % 2 == 0)? $colors['darkGreen']: $colors['lightGreen'];
      $x_l     = convChartXtoImageX($key    );
      $x_r     = convChartXtoImageX($key + 1);
      $y_value = convChartYtoImageY($value  );
      // NOTE: Must use different rectangle descriptions below depending on
      //       whether $value is positive because of bug in PHP on web host's server.
      //       When PHP is upgraded, check whether still needed.
      switch ($value > 0)
      {
       case true : imagefilledrectangle($image, $x_l, $y_value, $x_r, $y_dataZero, $color); break;
       case false: imagefilledrectangle($image, $x_l, $y_dataZero, $x_r, $y_value, $color); break;
      }

      // Draw cumulative average line.
      $cumulativeTotal += $value;
      $cumulativeAvg    = $cumulativeTotal / ($key + 1);
      $y_avg            = convChartYtoImageY($cumulativeAvg);
      imageline($image, $x_l, $y_avg, $x_r, $y_avg , $colors['red']);

      // Draw exponential moving average line.
      $alpha            = $maxAlpha * ($key / ($key + 1));
      $exponentialAvg   = ($alpha * $exponentialAvg) + (1 - $alpha) * $value;
      $y_avg            = convChartYtoImageY($exponentialAvg);
      imageline($image, $x_l, $y_avg, $x_r, $y_avg , $colors['yellow']);
   }

   // Draw black border at edge of chart.
   imagerectangle
   (
      $image,
      CHART_MIN_X, CHART_MIN_Y,
      CHART_MAX_X, CHART_MAX_Y,
      $colors['black']
   );
}

/*******************************************END*OF*FILE********************************************/
?>
