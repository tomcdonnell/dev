<?php
 /*
  *
  */
 function displayMySQLchartORhist($chartORhist)
 {
    // Test $_SESSION[] variables to be used in this function.
    if (   !isset($_SESSION['MySQLselectExp'       ])
        || !isset($_SESSION['MySQLfromExp'         ])
        || !isset($_SESSION['MySQLwhereExp'        ])
        || !isset($_SESSION['MySQLorderByExp'      ])
        || !isset($_SESSION['horizStripeHeight'    ])
        || !isset($_SESSION['tableHeading1'        ])
        || !isset($_SESSION['tableHeading2'        ])
        || !isset($_SESSION['chartVertAxisHeading' ])
        || !isset($_SESSION['chartHorizAxisHeading']))
      error(  'Required $_SESSION[] variable not set in'
            . " {$_SERVER['PHP_SELF']}::displayMySQLcolumnChart().");

    // Build and execute query proper. //////////////////////////////////////////////////////////
    // Concatenate query clauses.
    $MySQLquery
      =  'select ' . $_SESSION['MySQLselectExp'] . "\n"
       . 'from '   . $_SESSION['MySQLfromExp'  ] . "\n"
       . 'where '  . $_SESSION['MySQLwhereExp' ] . "\n"
       . (($_SESSION['MySQLorderByExp'] != '')? "order by {$_SESSION['MySQLorderByExp']}\n": '');

    // Execute query.
    $qResult = mysql_query($MySQLquery);

    // Test result.
    if (mysql_error() != '')
    {
       MySQLerror
       (
          "Problem in {$_SERVER['PHP_SELF']}::displayMySQLchartORhist().",
          mysql_errno(), mysql_error()
       );
    }

    // Copy data from result of query to $_SESSION[].
    $n_rows = mysql_num_rows($qResult);
    $_SESSION['runsArray'      ] = array($n_rows);
    $_SESSION['wicketsArray'   ] = array($n_rows);
    $_SESSION['matchDatesArray'] = array($n_rows);
    for ($i = 0; $i < $n_rows; $i++)
    {
       // Get row.
       $dataRowArray = mysql_fetch_array($qResult, MYSQL_ASSOC);

       $_SESSION['runsArray'   ][$i] = $dataRowArray['runs'   ];
       $_SESSION['wicketsArray'][$i] = $dataRowArray['wickets'];

       $_SESSION['datesArray'][$i] = array(3);
       $_SESSION['datesArray'][$i][0] = $dataRowArray['day'    ];
       $_SESSION['datesArray'][$i][1] = $dataRowArray['month'  ];
       $_SESSION['datesArray'][$i][2] = $dataRowArray['year'   ];
    }

    mysql_free_result($qResult);

//var_dump($_SESSION['runsArray'            ]);
//var_dump($_SESSION['wicketsArray'         ]);
//var_dump($_SESSION['datesArray'           ]);
//var_dump($_SESSION['horizStripeHeight'    ]);
//var_dump($_SESSION['chartVertAxisHeading' ]);
//var_dump($_SESSION['chartHorizAxisHeading']);

    echoDoctypeXHTMLstrictString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   h1 {font-size: 20px; font-weight: bold;}
   h2 {font-size: 18px; font-weight: normal;}
  </style>
<?php
    $chartORhistStr = (($chartORhist == 'chart')? 'Column Chart': 'Histogram');
    $str = "Indoor Cricket Database ($chartORhistStr)";
?>
  <title><?php echo $str; ?></title>
 </head>
 <body>
  <h1><?php echo $_SESSION['tableHeading1']; ?></h1>
<?php
    if ($_SESSION['tableHeading2'] != '')
      echo '  <h2>', $_SESSION['tableHeading2'], "</h2>\n";

   $str = (($chartORhist == 'chart')? 'chart': 'histogram');

   $url = "icdb_generate_$str.php";
   $altMsg =  'The ' . $chartORhistStr . ' could not be displayed.  '
            . 'A problem has occurred in the \'icdb_generate_' . $str . '.php\' file.';
?>
  <p><img src="<?php echo $url; ?>" alt="<?php echo $altMsg; ?>" /></p>
  <p><img src="generate_image_test.php" alt="Problem" /></p>
  <p><img src="../common/php/generatedImages/columnChart.php" alt="Problem" /></p>
 </body>
</html>
<?php
 }
?>
