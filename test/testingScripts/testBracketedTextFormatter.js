/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "testBracketedTextFormatter.js"
*
* Project: Tests.
*
* Purpose: Tests for the BracketedTextFormatter object.
*
* Author: Tom McDonnell 2009-01-14.
*
\**************************************************************************************************/

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function testBracketedTextFormatter()
{
   var f = 'testBracketedTextFormatter()';
   UTILS.checkArgs(f, arguments, []);

   var btf    = new BracketedTextFormatter();
   var btfGui = new BracketedTextFormatterGui(btf)

   var body = document.body;

   body.appendChild(btfGui.getDiv());
}

/*******************************************END*OF*FILE********************************************/
