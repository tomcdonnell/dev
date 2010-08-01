/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "testSelectorColor.js"
*
* Project: Tests.
*
* Purpose: Tests for the SelectorColor object.
*
* Author: Tom McDonnell 2008-03-29.
*
\**************************************************************************************************/

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function testSelectorColor()
{
   var f = 'testSelectorColor()';
   UTILS.checkArgs(f, arguments, []);

   var selectorColor = new SelectorColor(405, 135);

   var body = document.body;

   body.appendChild(selectorColor.getDiv());
}

/*******************************************END*OF*FILE********************************************/
