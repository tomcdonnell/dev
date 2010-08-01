/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "testSelectorColor.js"
*
* Project: Tests.
*
* Purpose: Tests for the SelectorColor2 object.
*
* Author: Tom McDonnell 2008-04-06.
*
\**************************************************************************************************/

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function testSelectorColor2()
{
   var f = 'testSelectorColor()';
   UTILS.checkArgs(f, arguments, []);

   var selectorColor = new SelectorColor2(405, 135);

   var body = document.body;

   body.appendChild(selectorColor.getDiv());
}

/*******************************************END*OF*FILE********************************************/
