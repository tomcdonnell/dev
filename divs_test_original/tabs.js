/**************************************************************************************************\
*
* Filename: "tabs.js"
*
* Purpose: Functions and variables for implementing movable dividers
*          on a web page without resorting to the use of frames.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Globals. ////////////////////////////////////////////////////////////////////////////////////////

var TABS = {}; // 'TABS' namespace.

// Namespace 'TABS' variables. /////////////////////////////////////////////////////////////////////

TABS.selectedTabNos = {};

// Namespace 'TABS' functions. /////////////////////////////////////////////////////////////////////

/*
 * PROBLEM: Must remove whitespace DOM nodes.
 */
TABS.init = function ()
{
   console.info('Initialising tabs.');

   var frameIds = ['topLeft', 'topRight', 'bottom'];

   // For each frame Id...
   for (var i = 0; i < frameIds.length; ++i)
   {
      // Get an array of the tab heading h2 elements for the corresponding frame.
      var tabHeadings = document.getElementById(frameIds[i]).firstChild.childNodes;

      // For each tab heading h2 element...
      for (var j = 0; j < tabHeadings.length; ++j)
      {
         // Attach a 'click' event listener.
         tabHeadings[j].attachEventListener('click', TABS.onClick, false);
      }
   }
};

/*
 *
 */
TABS.onClick = function (e)
{
   var f = 'TABS.onClick()';
   UTILS.DOM.checkArgs(f, arguments, [MouseEvent]);

   
};

/*******************************************END*OF*FILE********************************************/
