<?php
 require_once '../../common/misc/icdb_functions.php';
 require_once '../../common/date_and_time/date_functions.php';

 // Save teamID and teamName before clearing $_SESSION[] variables.
 $teamID   = $_SESSION['teamID'  ];
 $teamName = $_SESSION['teamName'];

 // Clear $_SESSION[] variables.
 $_SESSION = array();

 // Reinstate saved $_SESSION[] variables.
 $_SESSION['teamID'  ] = $teamID;
 $_SESSION['teamName'] = $teamName;

 // Enable/disable debugging info.
 $_SESSION['debug'] = true;

 connectToMySQL_icdb();

 echoDoctypeXHTMLtransitionalString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../../common/css/style.css" type="text/css" />
  <script type="text/javascript" src="icdb_statisticians_menu.js"></script>
  <title>IndoorCricketStats.net (Statisticians Menu)</title>
 </head>
 <body>
  <form method="post" id="statisticiansMenuFormId" name="statisticiansMenuFormId"
   target="icdb_statisticians_menu_result_id" action="">
   <table class="backh2" width="100%">
    <tr>
     <th class="h1 bordersTLRB" colspan="3">
      Team '<?php echo $_SESSION['teamName']; ?>' Statisticians Menu
     </th>
    </tr>
    <tr>
     <td>
      <table class="bordersTLRB" width="100%">
       <tr>
        <th width="25%" class="borders__R_">
         <input type="submit" value="Add Match Record" onclick="onClickAddMatchRecord()">
        </th>
        <th width="25%" class="borders__R_">
         <input type="submit" value="Modify Match Record" onclick="onClickModifyMatchRecord()">
        </th>
        <th width="25%" class="borders__R_">
         <input type="submit" value="Delete Match Record" onclick="onClickDeleteMatchRecord()">
        </th>
        <th width="25%">
         <input type="submit" value="Modify Player Record" onclick="onClickModifyPlayerRecord()">
        </th>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </form>
 </body>
</html>
