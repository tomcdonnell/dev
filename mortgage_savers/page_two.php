<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "page_two.php"
*
* Project: mortgagesavers.com.au.  Freelance for Marketcom.
*
* Purpose: Page linked to from index page.
*
* Author: Tom McDonnell 2010-02-28.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL);

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../common/php/utils/Utils_validator.php';

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
      $_GET, array
      (
         'hasPartner'           => 'string',
         'combinedAnnualIncome' => 'string',
         'equityInHome'         => 'string'
      )
   );

   $validationMessage = validateGetArray();
}
catch (Exception $e)
{
   echo $e->getMessage();
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function validateGetArray()
{
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
   </div>
<?php
MortgageSaversCommon::echoSpielAsHtml('    ');
?>
  </div>
  <div class='bottom'>
   <form action='page_two.php' method='get'>
    <table class='questions'>
     <tbody>
      <tr>
       <th colspan='2'>
        <p>
         Based on our experience in the Australian mortgage industry, most people in your situation
         pay around $20,000 more than they need to over the lifetime of their mortgage.
        </p>
        <p>
         With our help you may be able to save $20,000.
        </p>
       </th>
      </tr>
<?php
if ($validationMessage !== null)
{
   echo "      <tr><th colspan='2' class='validationMessage'>$validationMessage</th></tr>\n";
}
?>
      <tr>
       <th>Do you have a partner?</th>
       <td><select><option>Select</option><option>Yes</option><option>No</option></select></td>
      </tr>
      <tr>
       <th>What is your combined annual income?</th>
       <td>$<input type='text' /></td>
      </tr>
      <tr>
       <th>How much equity do you have in your home?</th>
       <td>$<input type='text' /></td>
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
