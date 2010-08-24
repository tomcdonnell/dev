/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "main.js"
*
* Project: General.
*
* Purpose: Driver for testing the ExplorableTable object.
*
* Author: Tom McDonnell 2010-07-27.
*
\**************************************************************************************************/

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $(document).ready(onLoadDocument);
}
catch (e)
{
   UTILS.printExceptionToConsole('main.js', e);
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function onLoadDocument(e)
{
   try
   {
      var f = 'main.js onLoadDocument()';
      UTILS.checkArgs(f, arguments, [Function]);

      var tables          = $('#explorableTable');
      var divs            = $('#mainLeftDiv'    );
      var explorableTable = new ExplorableTable(tables[0], 'ajax-admin/browse');
      var navPanelManager = new NavPanelManager(divs[0]  , explorableTable    );
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*******************************************END*OF*FILE********************************************/
