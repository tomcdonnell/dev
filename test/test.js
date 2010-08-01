/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "test.js"
*
* Project: Test.
*
* Purpose: Test.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

window.addEventListener('load', onLoadWindow, false);

// Functions. //////////////////////////////////////////////////////////////////////////////////////

function onLoadWindow(e)
{
   try
   {
      var f = 'onLoadWindow()';
console.debug(f, 'e');
      UTILS.checkArgs(f, arguments, [Event]);
      DomBuilder.apply(window);

      //testDateSelectors();
      testSelectorCalendar();
      //testSelectionSortNodeList();
      //testSelectorColor();
      //testSelectorColor2();
      //testBracketedTextFormatter();
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*******************************************END*OF*FILE********************************************/
