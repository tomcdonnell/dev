/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "index.js"
*
* Project: Oiuji.
*
* Purpose: Starting point for the client-side code.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

window.addEventListener('load', onLoadWindow, false);

// Global variables. ///////////////////////////////////////////////////////////////////////////////

var oiujiBoard = new OiujiBoard();

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

      document.body.appendChild(H1('Oiuji'));
      document.body.appendChild(oiujiBoard.getBoardDiv());
      document.body.appendChild(oiujiBoard.getGlassDiv());
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*******************************************END*OF*FILE********************************************/

