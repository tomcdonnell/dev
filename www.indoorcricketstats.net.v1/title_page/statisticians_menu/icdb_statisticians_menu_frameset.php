<?php
 require '../../common/misc/icdb_functions.php';

 // Clear $_SESSION[] variables.
 $_SESSION = array();

 // Enable/disable debugging info.
 $_SESSION['debug'] = true;

 // Save $_POSTed variables to $_SESSION[].
 $_SESSION['teamID'  ] = $_POST['teamIdId'  ];
 $_SESSION['teamName'] = $_POST['teamNameId'];

 echoDoctypeXHTMLtransitionalString();
?>
<html>
 <head>
  <style type="text/css">
   body {margin: 0px;}
   img {border: 0px;}
   iframe {border: 0px;}
  </style>
  <title>IndoorCricketStats.net (Statisticians Menu)</title>
 </head>
 <body>
  <a href="../../index.htm"><img src="../../images/icdb_title_small.jpg" /></a>
<?php
 connectToMySQL_icdb();

 // Test $_POSTed password against password stored in database.
 if (testPassword($_SESSION['teamID'], $_POST['passwordId']))
 {
?>
  <iframe src="icdb_statisticians_menu.php" width="100%" height="85px">
  </iframe>
  <iframe width="100%" height="900px"
   id="icdb_statisticians_menu_result_id" name="icdb_statisticians_menu_result_id">
  </iframe>
<?php
 }
 else
 {
?>
  <p>The password you supplied was incorrect.</p>
<?php
 }
?>
 </body>
</html>
