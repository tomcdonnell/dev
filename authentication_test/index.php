<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "index.php"
*
* Project: Security.
*
* Purpose: Test 'restricted_access' files.
*
* Author: Tom McDonnell 2010-06-28.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

// NOTE: This file must be the first to be included.
require_once dirname(__FILE__) .
(
   '/../../common/php/pages/restrict_access/require_username_and_password.php'
);

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_STRICT);

//session_name('report_as_csv');
//session_id($_COOKIE['report_as_csv']); // TODO Will cause error if cookie does not yet exist.  Fix.
session_start();

$_SESSION['report_as_csv'] = '"session","test","success!"' . "\n";

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head><title>Top Secret</title></head>
 <body>
  <h1>Top Secret</h1>
  <p>The last digit of any postcode is meaningless!  Bwa ha!  Ha ha!</p>
  <p>
   $_SESSION test:<br/>
   <a target='_blank' href='download_in_csv_format.php'>Try to download a csv file</a><br/>
   The session id is '<?php echo session_id(); ?>'.<br/>
   The session name is '<?php echo session_name(); ?>'.<br/>
   The contents of the $_SESSION array are as follows.<br/>
<?php
var_dump($_SESSION);
?>
  </p>
  <p>
   $_GET test:<br/>
   The following parameters were passed in the $_GET string.<br/>
<?php
var_dump($_GET);
?>
  </p>
  <p>
   $_POST test:<br/>
   The following parameters were passed in the $_POST string.<br/>
<?php
var_dump($_POST);
?>
   <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='POST'/>
    <input type='text' name='testPostedVar'/>
    <input type='submit' value='Submit'/>
   </form>
  </p>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
