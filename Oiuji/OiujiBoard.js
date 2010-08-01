/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "OiujiBoard.js"
*
* Project: Oiuji.
*
* Purpose: Definition of the OiujiBoard object.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Global variables. ///////////////////////////////////////////////////////////////////////////////

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function OiujiBoard()
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   this.getBoardDiv = function () {return board;};
   this.getGlassDiv = function () {return glass;};

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   function onMouseDown(e)
   {
      try
      {
         var f = 'OiujiBoard.onMouseDown';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         glass.removeEventListener('mousedown', onMouseDown, false);
         glass.addEventListener('mousemove', onMouseMove , false);
         glass.addEventListener('mouseup'  , onMouseUpOut, false);
         glass.addEventListener('mouseout' , onMouseUpOut, false);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onMouseUpOut(e)
   {
      try
      {
         var f = 'OiujiBoard.onMouseUpOut';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         glass.removeEventListener('mousemove', onMouseMove , false);
         glass.removeEventListener('mouseup'  , onMouseUpOut, false);
         glass.removeEventListener('mouseout' , onMouseUpOut, false);
         glass.addEventListener('mousedown', onMouseDown, false);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onMouseMove(e)
   {
      try
      {
         var f = 'OiujiBoard.onMouseMove';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         var mx = e.clientX;
         var my = e.clientY;

         var gStyle = glass.style;
         var gl = UTILS.DOM.removePxSuffix(gStyle.left  );
         var gt = UTILS.DOM.removePxSuffix(gStyle.top   );
         var gw = UTILS.DOM.removePxSuffix(gStyle.width );
         var gh = UTILS.DOM.removePxSuffix(gStyle.height);

         var gx = gl + gw / 2;
         var gy = gt + gh / 2;

         var dx = mx - gx;
         var dy = my - gy;

         glass.style.left = gl + dx + 'px';
         glass.style.top  = gt + dy + 'px';
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   var board = IMG({src: 'images/board.jpg'});

   var glass = DIV({style: 'position: absolute; background: red; opacity: 0.5;'});

   // Initialisation code. //////////////////////////////////////////////////////////////////////

   glass.style.height = '50px';
   glass.style.width  = '50px';
   glass.style.top    = '50px';
   glass.style.left   = '50px';

   glass.addEventListener('mousedown', onMouseDown, false);
}

/*******************************************END*OF*FILE********************************************/

