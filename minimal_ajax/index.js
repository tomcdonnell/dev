/**************************************************************************************************\
*
* Filename: "index.js"
*
* Project: Minimal AJAX.
*
* Purpose: The starting point for the client-side code.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Globally executed code. /////////////////////////////////////////////////////////////////////////

window.addEventListener('load', onLoadWindow, false);

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function onLoadWindow(e)
{
   try
   {
      var f = 'onLoadWindow()';
      UTILS.checkArgs(f, arguments, [Event]);

      messageExchange.sendInitialMessageBatch([['test', 'test payload']]);
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*******************************************END*OF*FILE********************************************/

