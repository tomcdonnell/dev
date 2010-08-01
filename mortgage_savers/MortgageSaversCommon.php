<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "MortgageSaversCommon.php"
*
* Project: mortgagesavers.com.au.  Freelance for Marketcom.
*
* Purpose: Class containing functions common to multiple pages.
*
* Author: Tom McDonnell 2010-02-28.
*
\**************************************************************************************************/

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class MortgageSaversCommon
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct ()
   {
      throw new Exception('This class is not intended to be instantiated.');
   }

   /*
    *
    */
   public static function createGetString()
   {
      $strings = array();

      foreach ($_GET as $key => $value)
      {
         $strings[] = "$key=$value";
      }

      return implode('&', $strings);
   }

   /*
    *
    */
   public static function echoSpielAsHtml($indent)
   {
      $i = $indent; // Abbreviation.

      echo <<<STR
$i<p>We may be able to save you many thousands of dollars over the life of your mortgage.</p>
$i<p>
$i Our experience in the Australian mortgage industry tells us that most people are paying far more
$i for their home loans than they need to.  Our mission is to help.
$i</p>
$i<p>
$i Answer the three quick questions below and we will tell you how much people in your situation
$i typically overpay, and what we can do to help.
$i</p>
STR;
   }
}

/*******************************************END*OF*FILE********************************************/
?>
