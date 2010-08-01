<?php
 require '../../common/misc/misc_functions.php';

 // Clear $_SESSION[] variables.
 $_SESSION = array();

 // Enable/disable debugging info.
 $_SESSION['debug'] = false;

 // These variables should be read from the database on team selection from the main page.
 $_SESSION['teamID'  ] = $_POST['teamIdId'  ];
 $_SESSION['teamName'] = $_POST['teamNameId'];

 echoDoctypeXHTMLtransitionalString();
?>
<html>
 <head>
  <style type="text/css">
   body {margin: 0;}
   img {border: 0px;}
   iframe {border: 0;}
  </style>
  <title>IndoorCricketStats.net (Data Retrieval Form)</title>
 </head>
 <body>
  <a href="../../index.htm"><img src="../../images/icdb_title_small.jpg" /></a>
  <iframe src="icdb_data_retrieval_form.php" width="100%" height="348px"></iframe>
  <iframe id="icdb_data_retrieval_form_result_id"
   name="icdb_data_retrieval_form_result_id" width="100%" height="900px">
  </iframe>
 </body>
</html>
