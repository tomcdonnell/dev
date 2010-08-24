/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap go-=b
*
* Filename: "utilsDOM.js"
*
* Project: Utilities.
*
* Purpose: Utilities concerning the Document Object Model (DOM).
*
* Dependencies: jQuery.
*
* Author: Tom McDonnell 2010-07-30.
*
\**************************************************************************************************/

// Namespace 'UTILS' variables. ////////////////////////////////////////////////////////////////////

/**
 * Namespace for DOM utilities.
 */
UTILS.DOM = {};

// Namespace 'UTILS.array' functions. //////////////////////////////////////////////////////////////

/*
 * @param separator HTML DOM element.
 * @param elements  Array of HTML DOM elements.
 * @param container HTML DOM element.
 *
 * @return container with elements separated by clones of separator appended.
 */
UTILS.DOM.implode = function (separator, elements, container, boolWithDataAndEvents)
{
   var f = 'UTILS.DOM.implode()';
   UTILS.checkArgs(f, arguments, ['Defined', Array, 'Defined', Boolean]);

   for (var i = 0; i < elements.length - 1; ++i)
   {
      $(container).append(elements[i]);
      $(container).append($(separator).clone(boolWithDataAndEvents));
   }

   $(container).append(elements[elements.length - 1]);

   return container;
};

/*******************************************END*OF*FILE********************************************/
