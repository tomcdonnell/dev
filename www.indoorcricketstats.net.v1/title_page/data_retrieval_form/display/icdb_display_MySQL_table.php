<?php
 /*
  *
  */
 function displayMySQLtable($rank)
 {
    // test $_SESSION[] variables to be used in this function
    if (   !isset($_SESSION['MySQLselectExp'       ])
        || !isset($_SESSION['MySQLselectRankAsExp' ])
        || !isset($_SESSION['MySQLfromExp'         ])
        || !isset($_SESSION['MySQLwhereExp'        ])
        || !isset($_SESSION['MySQLgroupByExp'      ])
        || !isset($_SESSION['MySQLorderByExp'      ])
        || !isset($_SESSION['MySQLrankExp'         ])
        || !isset($_SESSION['MySQLrankAscOrDesc'   ])
        || !isset($_SESSION['MySQLlimit'           ])
        || !isset($_SESSION['MySQLoffset'          ])
        || !isset($_SESSION['tableHeading1'        ])
        || !isset($_SESSION['tableHeading2'        ])
        || !isset($_SESSION['tableHeading3'        ])
        || !isset($_SESSION['tableColHeadingsArray']))
      error(  'Required $_SESSION[] variable not set in'
            . " {$_SERVER['PHP_SELF']}::displayMySQLtable().");

    // get number of columns in table (not including 'rank' column if applicable
    $n_cols = count($_SESSION['tableColHeadingsArray']);

    // Build and execute row count query. ///////////////////////////////////////////////////////
    switch ($_SESSION['MySQLgroupByExp'])
    {
     case '':
       // No 'group by' clause, so count is just count of query proper,
       // but without 'limit' and 'offset' and 'order by' clauses.
       $MySQLrowCountQuery
         =  "select count(*)\n"
          . 'from '  . $_SESSION['MySQLfromExp' ] . "\n"
          . 'where ' . $_SESSION['MySQLwhereExp'] . "\n";
       break;
     default:
       // A 'group by' clause has been used, so the query is an aggregate one,
       // and the count is the count of the distinct variable(s) given in the group by clause.
       $MySQLrowCountQuery
         =  "select count(distinct {$_SESSION['MySQLgroupByExp']})\n"
          . 'from '  . $_SESSION['MySQLfromExp' ] . "\n"
          . 'where ' . $_SESSION['MySQLwhereExp'] . "\n";
       break;
    }

    // execute query
    $qRowCountResult = mysql_query($MySQLrowCountQuery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::displayMySQLtable() (1).",
                 mysql_errno(), mysql_error()                                  );

    $nTotalRows = mysql_result($qRowCountResult, 0, 0);

    // ensure offset <= nTotalRows - limit
    $_SESSION['MySQLoffset']
      = min($_SESSION['MySQLoffset'], max($nTotalRows - $_SESSION['MySQLlimit'], 0));

    if ($nTotalRows > 0)
      $_SESSION['tableHeading3']
        .= 'Rows ' . ($_SESSION['MySQLoffset'] + 1)
                   . ' to '
                   . min(($_SESSION['MySQLoffset'] + $_SESSION['MySQLlimit']), $nTotalRows)
                   . ' of '  . $nTotalRows;
    else
      $_SESSION['tableHeading3'] .= 'No Rows Returned';

    // Build and execute query proper. //////////////////////////////////////////////////////////
    // concatenate query clauses
    $MySQLquery
      =  'select ' . $_SESSION['MySQLselectExp']
       . (($_SESSION['MySQLselectRankAsExp'] != '')? ", {$_SESSION['MySQLselectRankAsExp']}": '')
       . "\n"
       . 'from '   . $_SESSION['MySQLfromExp'  ] . "\n"
       . 'where '  . $_SESSION['MySQLwhereExp' ] . "\n"
       . (($_SESSION['MySQLgroupByExp'] != '')? "group by {$_SESSION['MySQLgroupByExp']}\n": '')
       . (($_SESSION['MySQLorderByExp'] != '')? "order by {$_SESSION['MySQLorderByExp']}\n": '')
       . 'limit '  . $_SESSION['MySQLlimit' ] . "\n"
       . 'offset ' . $_SESSION['MySQLoffset'] . "\n";
var_dump($MySQLquery);
    // execute query
    $qResult = mysql_query($MySQLquery);

    // test result
    if (mysql_error() != '')
      MySQLerror("Problem in {$_SERVER['PHP_SELF']}::displayMySQLtable() (2).",
                 mysql_errno(), mysql_error()                                  );

    // Build and execute query to find first rank (if necessary). ///////////////////////////////
    $alreadyFetchedFirstRow = false; // May need to fetch 1st row at this step
    if ($rank)                       // and must remember if do fetch first row.
    {
       if ($_SESSION['MySQLoffset'] == 0)
         $firstRank = 1;
       else
       {
          // Get 1st value of ranking variable (assumed to be rightmost)
          // and note that first row of $qResult has been fetched.
          $dataRowArray = mysql_fetch_array($qResult, MYSQL_NUM);
          $firstValueOfRankingVar = $dataRowArray[$n_cols - 1];
          $alreadyFetchedFirstRow = true;

          // this test required because ranked variable may have value NULL
          if ($firstValueOfRankingVar == NULL)
            $firstValueOfRankingVar = 0;

          // build query
          $MySQLfirstRankQuery
            =  "select count(*) from\n"
             . "(\n"
             . "   select * from\n"
             . "   (\n"
             . "      select distinct * from\n"
             . "      (\n"
             . "         select {$_SESSION['MySQLselectRankAsExp']}\n"
             . "         from     {$_SESSION['MySQLfromExp'   ]}\n"
             . "         where    {$_SESSION['MySQLwhereExp'  ]}\n"
             . (($_SESSION['MySQLgroupByExp'] != '')?
               "         group by {$_SESSION['MySQLgroupByExp']}\n": '')
             . (($_SESSION['MySQLorderByExp'] != '')?
               "         order by {$_SESSION['MySQLorderByExp']}\n": '')
             . "      ) as tab1\n"
             . "   ) as tab2\n"
             . "   where {$_SESSION['MySQLrankExp']}";
          switch ($_SESSION['MySQLrankAscOrDesc'])
          {
           case 'Asc' : $MySQLfirstRankQuery .= ' < '; break;
           case 'Desc': $MySQLfirstRankQuery .= ' > '; break;
           default:
             error(  "Expected 'Asc' or 'Desc', received '{$_SESSION['MySQLrankAscOrDesc']}'"
                   . " in {$_SERVER['PHP_SELF']}::displayMySQLtable()."                      );
          }
          $MySQLfirstRankQuery
            .= "$firstValueOfRankingVar\n";
          $MySQLfirstRankQuery
            .= ") as tab3\n";

          // execute query
          $qFirstRankResult = mysql_query($MySQLfirstRankQuery);

          // test result
          if (mysql_error() != '')
            MySQLerror("Problem in {$_SERVER['PHP_SELF']}::displayMySQLtable() (3).",
                       mysql_errno(), mysql_error()                                  );

          $firstRank = mysql_result($qFirstRankResult, 0, 0) + 1;
       }
    }

 echoDoctypeXHTMLstrictString();
?>
<html>
 <head>
  <link rel="StyleSheet" href="../../../common/css/style.css" type="text/css" />
  <style type="text/css">
   td {padding-left: 4px; padding-right: 4px;}
   th {padding-left: 4px; padding-right: 4px;}
   .padding0 {padding: 0;}
  </style>
  <title>Indoor Cricket Database (Data Table)</title>
 </head>
 <body>
  <table class="backh2"><?php /* NOTE: cellspacing="0" NOT required for outermost table. */ ?>
   <tr>
    <th class="h1 bordersTLRB" colspan="<?php echo $n_cols + 1; ?>">
<?php echo '     ', $_SESSION['tableHeading1'], "\n"; ?>
    </th>
   </tr>
   <tr>
    <td class="padding0">
     <table class="bordersTLRB" width="100%" cellspacing="0">
<?php /* NOTE: cellspacing="0" in table declaration above is required by IE. */ ?>
<?php
   if ($_SESSION['tableHeading2'] != '')
   {
      echo "      <tr>\n";
      echo '       <th class="h2 borders___B" colspan="', $n_cols + 1, "\">\n";
      echo '        ', $_SESSION['tableHeading2'], "\n";
      echo "       </th>\n";
      echo "      <tr>\n";
   }
?>
      <tr>
       <th class="h3 borders___B" colspan="<?php echo $n_cols + 1; ?>">
<?php echo '        ', $_SESSION['tableHeading3'], "\n"; ?>
       </th>
      </tr>
      <tr>
<?php
    if ($rank)
      echo "       <th class=\"h3l\">Rank</th>\n";

    for ($i = 0; $i < $n_cols; $i++)
    {
       echo '       <th class="h3';
       if ($i % 2 == 0) echo 'd">';
       else             echo 'l">';

       echo $_SESSION['tableColHeadingsArray'][$i], "</th>\n";
    }
?>
      </tr>
<?php
    // display table
    $n_rows = mysql_num_rows($qResult);
    $rowShade = 'l'; // ('d' = dark, 'l' = light.  Shade will toggle when rank changes.
    if ($rank)
    {
       $currRank = $firstRank - 1;
       $currValueOfRankingVar = NULL;
    }
    for ($i = 0; $i < $n_rows; $i++)
    {
       // get row
       if ($i != 0 || !$alreadyFetchedFirstRow)
         $dataRowArray = mysql_fetch_array($qResult, MYSQL_NUM);

       // deal with rank complications
       if ($rank)
       {
          // toggle rowshade if value of ranking variable has changed
          $rankChangedThisRow = false;
          if ($dataRowArray[$n_cols - 1] != $currValueOfRankingVar)
          {
             // this code should be run for first row so $rowShade='d' for first row
             $rowShade = ($rowShade == 'l')? 'd': 'l'; // toggle rowShade
             $currValueOfRankingVar = $dataRowArray[$n_cols - 1];
             $currRank++;
             $rankChangedThisRow = true;
          }
          else
            $currRankChar = '=';
       }
       else
         $rowShade = ($rowShade == 'l')? 'd': 'l'; // toggle rowShade

       // display row
       echo "      <tr>\n";
       if ($rank)
       {
          echo '       <th class="h3' . $rowShade . '">';
          if ($rankChangedThisRow) echo $currRank;
          else                     echo '=';
          echo "</th>\n";
       }
       for ($j = 0; $j < $n_cols; $j++)
       {
          echo '       <td class="' . $rowShade;
          if ($j % 2 == 0) echo 'd">';
          else             echo 'l">';

          $data = $dataRowArray[$j]; // Abbreviation.

          switch ($_SESSION['tableDataTypes'][$j])
          {
           case 'string'             : echo $data; break;
           case 'int'                : echo $data; break;
           case 'intORhalvesORthirds': echo convToIntOr3DigitDec($data); break;
           case 'intORfraction'      : echo convToIntOr3DigitDec($data); break;
           case 'float'              : printf('%.3f', $data); break;
           default:
             error(  "Unexpected type '{$_SESSION['tableDataTypes'][$j]}' encountered"
                   . "in {$_SERVER['PHP_SELF']}::displayMySQLtable()."                );
          }

          echo "</td>\n";
       }
       echo "      </tr>\n";
    }
?>
     </table>
    </td>
   </tr>
  </table>
 </body>
</html>
<?php
 }
?>
