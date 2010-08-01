<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "icdb_chart.php"
*
* Project: IndoorCricketStats.net.
*
* Purpose: Charting class using functions from the 'gd' library.
*
* Author: Tom McDonnell 2008-05-04.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once 'date_functions.php';
require_once 'image_functions.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/**
 * @param $testData {array}
 *    The following array shows the format expected and may be used as test data.
 *    array
 *    (
 *       'runsArray' = array
 *       (
 *          -5, -5, 19, -7, -8, -12, 1, 1, -4, 6, -6, 8, 15, 15, 15, 6, 22, -10, -10
 *       ),
 *       'datesArray' = array
 *       (
 *          array(16, 02, 2005), array(16, 02, 2005), array(23, 02, 2005), array(02, 03, 2005),
 *          array(08, 03, 2005), array(16, 03, 2005), array(23, 03, 2005), array(23, 03, 2005),
 *          array(13, 04, 2005), array(21, 04, 2005), array(27, 04, 2005), array(04, 05, 2005),
 *          array(11, 05, 2005), array(18, 05, 2005), array(18, 05, 2005), array(25, 05, 2005),
 *          array(31, 05, 2005), array(14, 06, 2005), array(14, 06, 2005)
 *       ),
 *       'horizStripeHeight'     = 10,
 *       'tableHeading1'         = 'Innings Chart',
 *       'tableHeading2'         = 'Subheading',
 *       'chartVertAxisHeading'  = 'Runs',
 *       'chartHorizAxisHeading' = 'Innings'
 *    );
 */
class Chart
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct($data)
   {
      assert('is_array($data)');
      assert('array_key_exists("runsArray"            , $data)');
      assert('array_key_exists("datesArray"           , $data)');
      assert('array_key_exists("horizStripeHeight"    , $data)');
      assert('array_key_exists("chartVertAxisHeading" , $data)');
      assert('array_key_exists("chartHorizAxisHeading", $data)');

      // Define and create .PNG image.
      header('Content-type: image/png');

      $this->image = imagecreate(self::imageWidth, self::imageHeight);

      $this->initPrivateVariables($data);

      $this->drawImageBackground();
      $this->drawChartBackground();
      $this->drawAxisLabels($data['chartHorizAxisHeading'], $data['chartVertAxisHeading']);
      $this->drawHorizontalAxisTimeScale($data['datesArray']);
      $this->drawVerticalAxisNumbers();
      $this->drawVerticalAxisKey();
      $this->drawColumnsAndAverageLines($data['runsArray']);

      // Output graph and clear image from memory.
      imagepng($this->image);
      imagedestroy($this->image);
   }

   // Private Functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private function convChartYtoImageY($y)
   {
      $offset = ($this->dataMin < 0)? $this->dataMin: 0;

      return $this->chartMaxY - $this->scalingFactorY * ($y - $offset);
   }

   /*
    *
    */
   private function convChartXtoImageX($x)
   {
      return $this->chartMinX + $this->scalingFactorX * $x;
   }

   /*
    *
    */
   function drawImageBackground()
   {
      imagefilledrectangle
      (
         $this->image,
         $this->imageMinX, $this->imageMinY,
         $this->imageMaxX, $this->imageMaxY,
         $this->colors['white']
      );
   }

   /*
    *
    */
   private function drawChartBackground()
   {
      imagefilledrectangle
      (
         $this->image,
         $this->chartMinX, $this->convChartYtoImageY($this->dataMax),
         $this->chartMaxX, $this->chartMaxY,
         $this->colors['lightBlue']
      );

      // Draw dark blue stripes for portion of chart in which y > 0.
      for ($i = 0; $i <= $this->dataMax; $i += $this->stripeCycleHeight)
      {
         // Calculate stripe position in chart data units and chart coords.
         $stripeBottom = $i;
         $stripeTop    = $i + $this->darkStripeHeight;

         if ($stripeTop > $this->dataMax)
         {
            $stripeTop = $this->dataMax;
         }

         // Calculate stripe position in pixels and screen coords.
         $y_stripeBottom = $this->convChartYtoImageY($stripeBottom);
         $y_stripeTop    = $this->convChartYtoImageY($stripeTop   );

         imagefilledrectangle
         (
            $this->image,
            $this->chartMinX, $y_stripeTop,
            $this->chartMaxX, $y_stripeBottom,
            $this->colors['darkBlue']
         );
      }

      // Draw dark blue stripes for portion of graph in which y < 0.
      for ($i = -$this->darkStripeHeight; $i >= $this->dataMin; $i -= $this->stripeCycleHeight)
      {
         // Calculate stripe position in chart data units and chart coords.
         $stripeTop    = $i;
         $stripeBottom = $i - $this->darkStripeHeight;

         if ($stripeBottom < $this->dataMin)
         {
            $stripeBottom = $this->dataMin;
         }

         // Calculate stripe position in pixels and screen coords.
         $y_stripeTop    = $this->convChartYtoImageY($stripeTop   );
         $y_stripeBottom = $this->convChartYtoImageY($stripeBottom);

         imagefilledrectangle
         (
            $this->image,
            $this->chartMinX, $y_stripeTop,
            $this->chartMaxX, $y_stripeBottom,
            $this->colors['darkBlue']
         );
      }
   }

   /*
    *
    */
   private function drawAxisLabels($hAxisLabel, $vAxisLabel)
   {
      // Draw horizontal axis label.
      printXcenteredHorizTextString
      (
         $this->image, $this->fontSize,
         $this->chartMinX, $this->chartMaxX, $this->chartMaxY + 30,
         $hAxisLabel, $this->colors['black']
      );

      // Draw vertical axis label.
      printYcenteredVertTextString
      (
         $this->image, $this->fontSize,
         $this->chartMinX - 50, $this->chartMinY, $this->chartMaxY,
         $vAxisLabel, $this->colors['black']
      );
   }

   /*
    *
    */
   private function drawHorizontalAxisTimeScale($datesArray)
   {
      // Draw horizontal axis month and year descriptions and dividing lines.
      $prevMonth       = -1;
      $currMonth       = -1;
      $prevYear        = -1;
      $currYear        = -1;
      $prevMonthStartX = $this->chartMinX;
      $prevYearStartX  = $this->chartMinX;

      for ($i = 0; $i <= $this->n_dataValues; ++$i)
      {
         // Get current month.
         if ($i < $this->n_dataValues)
         {
            $currMonth = $datesArray[$i][1];
         }
         // Else the previous value is used.

         if ($currMonth != $prevMonth || $i == $this->n_dataValues)
         {
            // A new month has been discovered, or the last data value has been reached.
            // In either case, draw a dividing line, and draw the month name for the
            // previous month in the center of the space between the dividing lines.

            // Draw dividing line at start of new month.
            $x_newMonthLine = $this->convChartXtoImageX($i);
            $y              = $this->chartMaxY + 15;
            if ($i == 0)
            {
               // Must also draw new year dividing line.
               $y += 15;

               // Update $prevYearStartX and $currYear.
               $currYear = $datesArray[$i][2];
               $prevYearStartX = $x_newMonthLine;
            }

            imageline
            (
               $this->image,
               $x_newMonthLine, $this->chartMaxY,
               $x_newMonthLine, $y,
               $this->colors['black']
            );

            if ($i > 0)
            {
               // Create a month abbreviation string that will fit in the available space.
               $textStr = $this->getMonthAbbrevStrForWidth
               (
                  $prevMonth, $x_newMonthLine - $prevMonthStartX
               );

               // Draw month abbreviation at center of previous month.
               printXcenteredHorizTextString
               (
                  $this->image, $this->fontSize,
                  $prevMonthStartX, $x_newMonthLine, $this->chartMaxY,
                  $textStr, $this->colors['black']
               );

               // Update $prevMonthStartX.
               $prevMonthStartX = $x_newMonthLine;

               // Get current year.
               if ($i < $this->n_dataValues)
               {
                  $currYear = $datesArray[$i][2];
               }
               // Else the previous value is used.

               // Test for new year and draw lines and text if necessary.
               if ($currYear != $prevYear || $i == $this->n_dataValues)
               {
                  // Draw dividing line at start of new year.
                  $x_newYearLine = $this->convChartXtoImageX($i);
                  imageline
                  (
                     $this->image,
                     $x_newMonthLine, $this->chartMaxY + 16,
                     $x_newMonthLine, $this->chartMaxY + 30,
                     $this->colors['black']
                  );

                  if ($i > 0)
                  {
                     // Create a year abbreviation string that will fit in the available space.
                     $textStr = $this->getYearAbbrevForWidth
                     (
                        $prevYear, $x_newYearLine - $prevYearStartX
                     );

                     // Draw year text at center of previous year.
                     printXcenteredHorizTextString
                     (
                        $this->image, $this->fontSize,
                        $prevYearStartX, $x_newMonthLine,
                        $this->chartMaxY + $this->n_pixelsPerCharY,
                        $textStr, $this->colors['black']
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
         $this->image,
         $this->chartMinX, $this->chartMaxY + 16,
         $this->chartMaxX, $this->chartMaxY + 16,
         $this->colors['black']
      );

      // Draw black line at bottom of year text.
      imageline
      (
         $this->image,
         $this->chartMinX, $this->chartMaxY + 30,
         $this->chartMaxX, $this->chartMaxY + 30,
         $this->colors['black']
      );
   }

   /*
    *
    */
   private function getMonthAbbrevStrForWidth($monthNo, $width)
   {
      $monthName = getMonthName($monthNo);

      $limit1 = strlen($monthName) * $this->n_pixelsPerCharX + 10;
      $limit2 = 3 * $this->n_pixelsPerCharX + 5;
      $limit3 = $this->n_pixelsPerCharX + 2;

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
   private function getYearAbbrevForWidth($year, $width)
   {
      $yearStr = (string)$year;
      assert('strlen($yearStr) == 4');

      $limit1 = 4 * $this->n_pixelsPerCharX + 10;
      $limit2 = 3 * $this->n_pixelsPerCharX + 2;
      $limit3 = 2 * $this->n_pixelsPerCharX + 1;

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
   private function drawVerticalAxisKey()
   {
      // Draw right axis label (for cumulative average line).
      $textStr = 'Cumulative Average ';
      $n_charsInTextStr = strlen($textStr);
      $y = $this->chartMinY +
      (
         ($this->chartHeight + $n_charsInTextStr * $this->n_pixelsPerCharX) / 2 + 0.5
      );
      $y -= 1 * $this->n_pixelsPerCharX; // Correction for inclusion of line key indicator.
      $x = $this->chartMaxX + $this->n_pixelsPerCharY; // Right of chart.
      imagestringup($this->image, $this->fontSize, $x, $y, $textStr, $this->colors['black']);

      // Draw cumulative average line key indicator.
      $y_top    = $y     + $this->n_pixelsPerCharX;
      $y_bottom = $y_top + $this->n_pixelsPerCharX;
      $x_left   = $x;
      $x_middle = $x + $this->n_pixelsPerCharY / 2;
      $x_right  = $x + $this->n_pixelsPerCharY;
      imagefilledrectangle
      (
         $this->image,
         $x_left , $y_top,
         $x_right, $y_bottom,
         $this->colors['darkGreen']
      );
      $x += $this->n_pixelsPerCharY / 2;
      imageline($this->image, $x_middle, $y_top, $x_middle, $y_bottom, $this->colors['red']);

      // Draw right axis label (for exponential moving average line).
      $textStr = 'Exp. Moving Average';
      $n_charsInTextStr = strlen($textStr);
      $y = $this->chartMinY +
      (
         ($this->chartHeight + $n_charsInTextStr * $this->n_pixelsPerCharX) / 2 + 0.5
      );
      $y -= 1 * $this->n_pixelsPerCharX; // Correction for inclusion of line key indicator.
      $x = $this->chartMaxX + 2 * $this->n_pixelsPerCharY; // Right of chart.
      imagestringup($this->image, $this->fontSize, $x, $y, $textStr, $this->colors['black']);

      // Draw exponential moving average line key indicator.
      $y_top    = $y     + $this->n_pixelsPerCharX;
      $y_bottom = $y_top + $this->n_pixelsPerCharX;
      $x_left   = $x;
      $x_middle = $x + $this->n_pixelsPerCharY / 2;
      $x_right  = $x + $this->n_pixelsPerCharY;
      imagefilledrectangle
      (
         $this->image,
         $x_left , $y_top,
         $x_right, $y_bottom,
         $this->colors['darkGreen']
      );
      $x += $this->n_pixelsPerCharY / 2;
      imageline($this->image, $x_middle, $y_top, $x_middle, $y_bottom, $this->colors['yellow']);
   }

   /*
    *
    */
   private function drawVerticalAxisNumbers()
   {
      // Draw vertical axis numbers for data >= 0 on left side.
      for ($i = 0; $i <= $this->dataMax; $i += $this->stripeCycleHeight / 2)
      {
         $textStr = (string)$i;
         $n_charsInTextStr = strlen($textStr);
         $x = $this->chartMinX - 5 - $this->n_pixelsPerCharX * $n_charsInTextStr;
         $y = $this->convChartYtoImageY($i) - $this->n_pixelsPerCharY / 2;
         imagestring($this->image, $this->fontSize, $x, $y, $textStr, $this->colors['black']);
      }

      // Draw vertical axis numbers for data < 0 on left side.
      $stepSize = $this->stripeCycleHeight / 2;
      for ($i = -$stepSize; $i >= $this->dataMin; $i -= $stepSize)
      {
         $textStr = (string)$i;
         $n_charsInTextStr = strlen($textStr);
         $x = $this->chartMinX - 5 - $this->n_pixelsPerCharX * $n_charsInTextStr;
         $y = $this->convChartYtoImageY($i) - $this->n_pixelsPerCharY / 2;
         imagestring($this->image, $this->fontSize, $x + 1, $y, $textStr, $this->colors['black']);
      }
   }

   /*
    *
    */
   private function drawColumnsAndAverageLines($runsArray)
   {
      $y_dataZero      = $this->convChartYtoImageY(0); // Y coordinate of data zero level.
      $cumulativeTotal = 0;
      $exponentialAvg  = $runsArray[0]; // Exponential average initial value.
      $maxAlpha        = 0.95;

      foreach ($runsArray as $key => $value)
      {
         // Draw column.
         $this->color   = ($key % 2 == 0)? $this->colors['darkGreen']: $this->colors['lightGreen'];
         $x_l     = $this->convChartXtoImageX($key    );
         $x_r     = $this->convChartXtoImageX($key + 1);
         $y_value = $this->convChartYtoImageY($value  );

         // NOTE: Must use different rectangle descriptions below depending on
         //       whether $value is positive because of bug in PHP on web host's server.
         //       When PHP is upgraded, check whether still needed.
         switch ($value > 0)
         {
          case true:
            imagefilledrectangle($this->image, $x_l, $y_value, $x_r, $y_dataZero, $this->color);
            break;
          case false:
            imagefilledrectangle($this->image, $x_l, $y_dataZero, $x_r, $y_value, $this->color);
            break;
         }

         // Draw cumulative average line.
         $cumulativeTotal += $value;
         $cumulativeAvg    = $cumulativeTotal / ($key + 1);
         $y_avg            = $this->convChartYtoImageY($cumulativeAvg);
         imageline($this->image, $x_l, $y_avg, $x_r, $y_avg , $this->colors['red']);

         // Draw exponential moving average line.
         $alpha            = $maxAlpha * ($key / ($key + 1));
         $exponentialAvg   = ($alpha * $exponentialAvg) + (1 - $alpha) * $value;
         $y_avg            = $this->convChartYtoImageY($exponentialAvg);
         imageline($this->image, $x_l, $y_avg, $x_r, $y_avg , $this->colors['yellow']);
      }

      // Draw black border at edge of chart.
      imagerectangle
      (
         $this->image,
         $this->chartMinX, $this->chartMinY,
         $this->chartMaxX, $this->chartMaxY,
         $this->colors['black']
      );
   }

   /*
    *
    */
   private function initPrivateVariables($data)
   {
      assert('is_array($data)');

      // Create colors.
      $this->colors = array
      (
         'darkBlue'   => imagecolorallocate($this->image, 0x00, 0x61, 0xc2), // td.d in "style.css".
         'lightBlue'  => imagecolorallocate($this->image, 0x00, 0x71, 0xe2), // td.l in "style.css".
         'darkGreen'  => imagecolorallocate($this->image, 0x48, 0x90, 0x00), // h3.d in "style.css".
         'lightGreen' => imagecolorallocate($this->image, 0x50, 0xa0, 0x00), // h3.l in "style.css".
         'red'        => imagecolorallocate($this->image, 0xff, 0x00, 0x00),
         'black'      => imagecolorallocate($this->image, 0x00, 0x00, 0x00),
         'white'      => imagecolorallocate($this->image, 0xff, 0xff, 0xff),
         'yellow'     => imagecolorallocate($this->image, 0xff, 0xff, 0x00)
      );

      // Chart dimensions.
      $this->chartWidth  = self::imageWidth - 100;
      $this->chartHeight = self::imageHeight - 55;

      // Image extremities.
      $this->imageMinX = 0;
      $this->imageMinY = 0;
      $this->imageMaxX = $this->imageMinX + self::imageWidth  - 1;
      $this->imageMaxY = $this->imageMaxY + self::imageHeight - 1;

      // Chart extremities.
      // NOTE: For all drawing done relating to the chart, if the coordinate to be used is not
      //       $chartMinX or $chartMinY, scaled data units must be used so that rounding is done
      //       consistently preventing 'out by one pixel' errors.
      $this->chartMaxY = $this->imageMaxY - 50;
      $this->chartMinX = $this->imageMinX + 50;
      $this->chartMaxX = $this->chartMinX + $this->chartWidth;
      $this->chartMinY = $this->chartMaxY - $this->chartHeight;

      // Data range constants.
      $this->n_dataValues = count($data['runsArray']);
      $this->dataMax      = max($data['runsArray']);
      $this->dataMin      = min($data['runsArray']);
      $this->dataRange    = $this->dataMax - $this->dataMin;

      // Define x and y scaling factors (to scale from data units to pixels).
      $this->scalingFactorX = $this->chartWidth / $this->n_dataValues;
      $this->scalingFactorY =
      (
         $this->chartHeight / (($this->dataMin > 0)? $this->dataMax: $this->dataRange)
      );

      // Set stripe height (units are data units, not pixels).
      $this->stripeCycleHeight = $data['horizStripeHeight'];
      $this->darkStripeHeight  = $this->stripeCycleHeight / 2;

      $this->fontSize         = 5;
      $this->n_pixelsPerCharX = imagefontwidth($this->fontSize);
      $this->n_pixelsPerCharY = imagefontheight($this->fontSize);
   }

   // Private Constants. ////////////////////////////////////////////////////////////////////////

   // Image dimensions.
   const imageHeight = 480;
   const imageWidth  = 900;

   // Private Variables. ////////////////////////////////////////////////////////////////////////

   private $image  = null;
   private $colors = null;

   // Chart dimensions.
   private $chartWidth  = null;
   private $chartHeight = null;

   // Image extremities.
   private $imageMinX = null;
   private $imageMinY = null;
   private $imageMaxX = null;
   private $imageMaxY = null;

   // Chart extremities.
   // NOTE: For all drawing done relating to the chart, if the coordinate to be used is not
   //       $chartMinX or $chartMinY, scaled data units must be used so that rounding is done
   //       consistently preventing 'out by one pixel' errors.
   private $chartMaxY = null;
   private $chartMinX = null;
   private $chartMaxX = null;
   private $chartMinY = null;

   // Data range constants.
   private $n_dataValues = null;
   private $dataMax      = null;
   private $dataMin      = null;
   private $dataRange    = null;

   // Define x and y scaling factors (to scale from data units to pixels).
   private $scalingFactorY = null;
   private $scalingFactorX = null;

   // Set stripe height (units are data units, not pixels).
   private $stripeCycleHeight = null;
   private $darkStripeHeight  = null;

   private $fontSize         = null;
   private $n_pixelsPerCharX = null;
   private $n_pixelsPerCharY = null;
}

/*******************************************END*OF*FILE********************************************/
?>
