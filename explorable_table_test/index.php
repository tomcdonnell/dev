<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap go-=b
*
* Filename: "template_start.php"
*
* Project: Templates.
*
* Purpose: Start page template.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <script src='../../lib/tom/js/3rdParty/jquery/1.4/jquery_minified.js'></script>
  <script src='../../lib/tom/js/3rdParty/utils/json.js'></script>
  <script src='../../lib/tom/js/3rdParty/utils/DomBuilder.js'></script>
  <script src='../../lib/tom/js/utils/utils.js'></script>
  <script src='../../lib/tom/js/utils/utilsValidator.js'></script>
  <script src='../../lib/tom/js/utils/utilsObject.js'></script>
  <script src='../../lib/tom/js/utils/utilsDOM.js'></script>
  <script src='ExplorableTable.js'></script>
  <script src='NavPanelManager.js'></script>
  <script src='InteractiveTableHeadingsRow.js'></script>
  <script src='main.js'></script>
  <link rel='stylesheet' type='text/css' href='style.css'></link>
  <link rel='stylesheet' type='text/css' href='explorable_table.css'></link>
  <title>Start Page Template</title>
 </head>
 <body>
  <h1>Administrator's View</h1>
  <div id='mainLeftDiv'>
   <h2>Browse Activities</h2>
   <ul>
    <li class='navPanelItem'>By Assignment</li>
    <li class='navPanelItem'>By Approval Status</li>
    <li class='navPanelItem'>By Client</li>
    <li class='navPanelItem'>By Due Date</li>
    <li class='navPanelItem'>By End Date</li>
    <li class='navPanelItem'>By KITB Output</li>
    <li class='navPanelItem'>By Manager</li>
   </ul>
  </div>
  <div id='mainRightDiv'>
   <div id='topButtonsBar'>
    <input type='button' value='New Activity'>
   </div>
  <table id='explorableTable'><thead></thead><tbody></tbody></table>
  </div>
  <table id='explorableTable'><thead></thead><tbody></tbody></table>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
