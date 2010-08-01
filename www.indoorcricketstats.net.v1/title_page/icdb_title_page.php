<?php
 require_once '../common/misc/icdb_functions.php';

 // Clear $_SESSION[] variables.
 $_SESSION = array();

 $_SESSION['debug'] = true;

 echoDoctypeXHTMLstrictString();
?>
<html>
 <head>
  <link rel="stylesheet" href="../common/css/style.css" type="text/css" />
  <style type="text/css">
   body {margin: 0px;}
   h1 {color: #ffffff; position: relative; top: -530px; z-index: 999;}
   p {margin: 0px;}
   table.selectteam {position: absolute; right: 265px; top: 267px; background: transparent;}
   table.addteam {position: absolute; right: 339px; top: 534px; background: transparent;}
   td {padding: 2px; text-align: left; background: transparent;}
   select {text-align: left;}
  </style>
  <script type="text/javascript" src="./icdb_title_page.js"></script>
  <script type="text/javascript" src="../common/misc/misc_functions.js"></script>
  <title>IndoorCricketStats.net (Title Page)</title>
 </head>
 <body onload="init()">
  <p><img src="../images/icdb_title_page_picture.jpg" alt="IndoorCricketStats.net" /></p>
  <h1>SITE UNDER CONSTRUCTION</h1>
  <form method="post"
   id="titlePageFormId" name="titlePageFormId"
   action="data_retrieval_form/icdb_data_retrieval_form_frameset.php">
   <p>
    <input type="hidden" value="Two Dogs" id="teamNameId" name="teamNameId" />
    <input type="hidden" value="1" id="teamIdId" name="teamIdId" />
    <input type="hidden" value="8" id="n_playersPerTeamId" name="n_playersPerTeamId" />
   </p>
   <table class="selectteam">
    <tbody>
     <tr>
      <td colspan="2" id="countrySelectorTd">
       <select><option>Loading...</option></select>
      </td>
     </tr>
     <tr>
      <td colspan="2" id="stateSelectorTd">
       <select><option>Loading...</option></select>
      </td>
     </tr>
     <tr>
      <td colspan="2" id="centreSelectorTd">
       <select><option>Loading...</option></select>
      </td>
     </tr>
     <tr>
      <td colspan="2" id="teamSelectorTd">
       <select><option>Loading...</option></select>
      </td>
     </tr>
     <tr>
      <td>
       <input type="submit" id="guestButtonId" name="guestButtonId" value="Enter as Guest" />
      </td>
      <td id="statisticianButtonTd">
       <input type="button" id="statisticianButtonId" name="statisticianButtonId"
        value="Enter as Statistician" onclick="onClickEnterAsStatistician()" />
      </td>
     </tr><td></td><td id="passwordTd"></td></tr>
    </tbody>
   </table>
   <table class="addteam">
    <tbody>
     <tr><td><input type="submit" value="Tour the Site" disabled="disabled" /></td></tr>
     <tr><td><input type="submit" value="Add Your Team" disabled="disabled" /></td></tr>
    </tbody>
   </table>
  </form>
  <table>
   <tr>
    <td id="codedData">
     <!--
<?php
 // START coded data for client side scripting //////////////////////////////////////////////////

 connectToMySQL_icdb();
 readCountryNamesAndIDs();
 readStateDetails();
 readCentreDetails();
 readTeamDetails();

 echo $_SESSION['n_countries'], ' ', $_SESSION['n_states'], ' ',
      $_SESSION['n_centres'  ], ' ', $_SESSION['n_teams' ], "\n";

 foreach ($_SESSION['countriesArray'] as $country)
   echo $country['countryID'  ], ' ',
        $country['countryName'], "\n";

 foreach ($_SESSION['statesArray'] as $state)
   echo $state['countryID'], ' ',
        $state['stateID'  ], ' ',
        $state['stateName'], "\n";

 foreach ($_SESSION['centresArray'] as $centre)
   echo $centre['countryID' ], ' ',
        $centre['stateID'   ], ' ',
        $centre['centreID'  ], ' ',
        $centre['centreName'], "\n";

 foreach ($_SESSION['teamsArray'] as $team)
   echo $team['centreID' ], ' ',
        $team['teamID'   ], ' ',
        $team['n_matches'], ' ',
        $team['teamName' ], "\n";

 // FINISH coded data for client-side scripting. ////////////////////////////////////////////////
?>
     -->
    </td>
   </tr>
  </table>
 </body>
</html>
