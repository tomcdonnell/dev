<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "icdb_generate_chart.php"
*
* Project: IndoorCricketStats.net.
*
* Purpose: This file should be used as an image.
*
* Author: Tom McDonnell 2008-05-04.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once 'icdb_chart.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $testData = array
   (
      'runsArray'             => $_SESSION['runsArray'            ],
      'datesArray'            => $_SESSION['datesArray'           ],
      'horizStripeHeight'     => $_SESSION['horizStripeHeight'    ],
      'chartVertAxisHeading'  => $_SESSION['chartVertAxisHeading' ],
      'chartHorizAxisHeading' => $_SESSION['chartHorizAxisHeading']
   );

   $chart = new Chart($testData);
}
catch (Exception $e)
{
   echo $e;
}

/*******************************************END*OF*FILE********************************************/
?>
