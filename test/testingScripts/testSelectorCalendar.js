/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "testSelectorCakendar.js"
*
* Project: Tests.
*
* Purpose: Tests for the SelectorCalendar object.
*
* Author: Tom McDonnell 2007-12-23.
*
\**************************************************************************************************/

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function testSelectorCalendar()
{
   var f = 'testSelectorCalendar()';
   UTILS.checkArgs(f, arguments, []);

   var selectorCalendar = new SelectorCalendar(new Date());

   var body = document.body;

   body.appendChild(selectorCalendar.getTable());
}

/*******************************************END*OF*FILE********************************************/
