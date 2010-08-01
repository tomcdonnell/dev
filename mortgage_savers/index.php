<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "index.php"
*
* Project: mortgagesavers.com.au.  Freelance for Marketcom.
*
* Purpose: This file is the starting point for all pages in the project.
*
* Author: Tom McDonnell 2010-02-28.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../common/php/utils/Utils_validator.php';
require_once dirname(__FILE__) . '/../../common/php/utils/Utils_htmlForm.php';
require_once dirname(__FILE__) . '/MortgageSaversCommon.php';

// Global variables. ///////////////////////////////////////////////////////////////////////////////

$filesCss = array
(
   'style.css'
);

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   Utils_validator::checkArray
   (
      $_GET, array(), array
      (
         'hasPartner'           => 'string',
         'combinedAnnualIncome' => 'string',
         'equityInHome'         => 'string'
      )
   );

   $validationMessage = null;

   switch (count($_GET))
   {
    case 0:
      // Do nothing.
      break;
    case 3:
      $validationMessage = validateGetArray();
      if ($validationMessage === null)
      {
         // Redirect to next page.
         header('Location: page_two.php?' . MortgageSaversCommon::createGetString());
      }
      break;
    default:
      throw new Exception('Unexpected count for $_GET.');
   }
}
catch (Exception $e)
{
   echo $e->getMessage();
   exit(0);
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function validateGetArray()
{
   if
   (
      $_GET['combinedAnnualIncome'] == '' ||
      $_GET['equityInHome'        ] == ''
   )
   {
      return 'Please answer all of the questions.';
   }

   if
   (
      !ctype_digit($_GET['combinedAnnualIncome']) ||
      !ctype_digit($_GET['equityInHome'        ])
   )
   {
      return 'Please enter digits only in the fields marked with dollar signs ($).';
   }

   if ($_GET['hasPartner'] == 'Select')
   {
      return 'Please indicate whether you have a partner.';
   }

   return null;
}

// HTML. ///////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
<?php
 $unixTime = time();
 foreach ($filesCss as $file) {echo "  <link rel='stylesheet' href='$file?$unixTime' />\n";}
?>
  <title>MortgageSavers</title>
 </head>
 <body>
  <div class='top'>
   <div class='center'>
    <h1>MortgageSavers</h1>
<?php
MortgageSaversCommon::echoSpielAsHtml('    ');
?>
   </div>
  </div>
  <div class='bottom'>
   <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
    <table class='questions'>
     <tbody>
<?php
if ($validationMessage !== null)
{
   echo "      <tr><th colspan='2' class='validationMessage'>$validationMessage</th></tr>\n";
}
?>
      <tr>
       <th>Do you have a partner?</th>
       <td>
<?php
Utils_htmlForm::echoSelectorHtml
(
   'hasPartner', array
   (
      'select' => 'Select',
      'yes'    => 'Yes'   ,
      'no'     => 'No'
   ),
   '        ', ((array_key_exists('hasPartner', $_GET))? $_GET['hasPartner']: null)
);
?>
       </td>
      </tr>
      <tr>
<?php
$value = (array_key_exists('combinedAnnualIncome', $_GET))? $_GET['combinedAnnualIncome']: '';
?>
       <th>What is your combined annual income?</th>
       <td>$<input type='text' value='<?php echo $value; ?>' name='combinedAnnualIncome' /></td>
      </tr>
      <tr>
<?php
$value = (array_key_exists('equityInHome', $_GET))? $_GET['equityInHome']: '';
?>
       <th>How much equity do you have in your home?</th>
       <td>$<input type='text' value='<?php echo $value; ?>' name='equityInHome' /></td>
      </tr>
      <tr><th colspan='2' class='submit'><input type='submit' value='Submit' /></th></tr>
     </tbody>
    </table>
   </form>
  </div>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
