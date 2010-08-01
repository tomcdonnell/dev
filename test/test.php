<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go -=b
*
* Filename: "test.php"
*
* Project: Tests.
*
* Purpose: The main file for the project.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Global variables. ///////////////////////////////////////////////////////////////////////////////

$filenamesJs = array
(
   '../../common/js/utils/utils.js',
   '../../common/js/utils/utilsDate.js',
   '../../common/js/utils/utilsDOM.js',
   '../../common/js/utils/utilsArray.js',
   '../../common/js/utils/utilsString.js',
   '../../common/js/utils/utilsObject.js',
   '../../common/js/utils/utilsValidator.js',
   '../../common/js/utils/utilsSelectionSortNodeList.js',
   '../../common/js/guiElements/selectors/SelectorDate.js',
   '../../common/js/guiElements/selectors/SelectorCalendar.js',
   '../../common/js/guiElements/selectors/SelectorDatePeriod.js',
   '../../common/js/guiElements/selectors/SelectorColor.js',
   '../../common/js/guiElements/selectors/SelectorColor2.js',
   '../../common/js/guiElements/BracketedTextFormatterGui.js',
   '../../common/js/generalObjects/BracketedTextParser.js',
   '../../common/js/generalObjects/BracketedTextFormatter.js',
   '../../common/js/3rdParty/utils/DomBuilder.js',
   'testingScripts/testDateSelectors.js',
   'testingScripts/testSelectionSortNodeList.js',
   'testingScripts/testSelectorCalendar.js',
   'testingScripts/testSelectorColor.js',
   'testingScripts/testSelectorColor2.js',
   'testingScripts/testBracketedTextFormatter.js',
   'test.js',
);

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
  <link rel='stylesheet' href="../../common/js/guiElements/selectors/SelectorCalendar.css" />
<?php
 $timestamp = time();
 foreach ($filenamesJs as $filename)
 {
?>
   <script src='<?php echo "$filename?$timestamp"; ?>'></script>
<?php
 }
?>
  <title>Test</title>
 </head>
 <body></body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
